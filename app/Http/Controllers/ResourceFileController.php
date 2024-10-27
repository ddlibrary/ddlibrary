<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResourceFileRequest;
use App\Models\ResourceFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class ResourceFileController extends Controller
{
    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => ['required', 'file','mimes:jpg,jpeg,png','max:3072',File::image()->dimensions(
                Rule::dimensions()->ratio(1.0)
            )],
            'image_name' => 'nullable|string|max:255',
            'license' => 'nullable|string|max:255',
        ], [
            'image.dimensions' => 'The resource image must be square in shape.', // Custom message
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'errors' => $validator->errors(),
                ],
                422,
            );
        }

        $file = $request->file('image');
        $fileName = auth()->user()->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = 'resources/' . $fileName;

        // Store the file in S3
        Storage::disk('s3')->put($path, file_get_contents($file));

        // Get the full S3 URL
        $fullPath = Storage::disk('s3')->url($path);

        $resourceFile = ResourceFile::create([
            'uuid' => Str::uuid(),
            'name' => $request->image_name,
            'license' => $request->license,
            'path' => $fullPath,
        ]);

        return response()->json([
            'success' => true,
            'imageUuid' => $resourceFile->uuid,
            'imageUrl' => $fullPath,
            'imageName' => $request->image_name,
            'message' => 'Image uploaded successfully',
        ]);
    }

    public function searchImages(ResourceFileRequest $request)
    {
        $query = ResourceFile::query()->select('uuid', 'name', 'path')
            ->where(function ($query) use ($request) {
                if ($request->subject_area_id) {
                    $resourceFileIds = DB::table('resource_subject_areas')
                    ->join('resources', 'resource_subject_areas.resource_id', '=', 'resources.id')
                        ->where('tid', $request->subject_area_id)
                        ->pluck('resource_file_id');
                    $query->whereIn('id', $resourceFileIds);
                }
                if ($request->search) {
                    $query->where('name', 'like', "%{$request->search}%");
                }
            });
        $count = $query->count();
        $files = $query->paginate(16)->appends($request->except(['page']));
        
        return view('resources.partial.file-list', compact('count', 'files'));
    }
}
