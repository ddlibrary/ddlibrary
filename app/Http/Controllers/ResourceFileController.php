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
use Imagine\Gd\Imagine;
use Imagine\Image\Box;

class ResourceFileController extends Controller
{
    public function uploadImage(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'image' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:3072', File::image()->dimensions(Rule::dimensions()->ratio(1.0))],
                'image_name' => 'nullable|string|max:255',
                'taxonomy_term_data_id' => 'nullable|exists:taxonomy_term_data,id',
            ],
            [
                'image.dimensions' => 'The resource image must be square in shape.',
            ],
        );

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

        // Create a thumbnail using imagine/imagine
        $imagine = new Imagine();
        $image = $imagine->open($file->getRealPath());
        $thumbnailPath = 'resources/thumbnails/' . $fileName;

        // Ensure the temp directory exists
        $tempDirectory = storage_path('app/temp/resources/thumbnails');
        if (!file_exists($tempDirectory)) {
            mkdir($tempDirectory, 0755, true); // Create the directory if it doesn't exist
        }

        // Resize and save the thumbnail to the temporary local storage
        $image
            ->resize(new Box(250, 250)) // Resize to 150x150 pixels
            ->save($tempDirectory . '/' . $fileName); // Save to the temporary local storage

        // Store the thumbnail in S3
        Storage::disk('s3')->put($thumbnailPath, file_get_contents($tempDirectory . '/' . $fileName));

        $thumbnailFullPath = Storage::disk('s3')->url($thumbnailPath);

        $fullPath = Storage::disk('s3')->url($path);

        $resourceFile = ResourceFile::create([
            'uuid' => Str::uuid(),
            'name' => $request->image_name,
            'language' => $request->language,
            'taxonomy_term_data_id' => $request->taxonomy_term_data_id,
            'path' => $fullPath,
            'thumbnail_path' => $thumbnailFullPath,
        ]);

        return response()->json([
            'success' => true,
            'imageUuid' => $resourceFile->uuid,
            'imageUrl' => $fullPath,
            'thumbnailUrl' => $thumbnailFullPath,
            'imageName' => $request->image_name,
            'message' => 'Image uploaded successfully',
        ]);
    }

    public function searchImages(ResourceFileRequest $request)
    {
        $query = ResourceFile::query()
            ->select('uuid', 'name', 'thumbnail_path')
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
            })
            ->where('language', $request->language);

        $count = $query->count();
        $files = $query
            ->orderByDesc('created_at')
            ->paginate(16)
            ->appends($request->except(['page']));

        return view('resources.partial.file-list', compact('count', 'files'));
    }
}