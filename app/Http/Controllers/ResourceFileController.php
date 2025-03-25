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
                'image.dimensions' => __("Upload an image that is the same width and height (a square)."),
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

        $fileSystemDisk = env('FILESYSTEM_DISK', 'local');

        Storage::disk($fileSystemDisk)->put($path, file_get_contents($file));

        $imagine = new Imagine();
        $image = $imagine->open($file->getRealPath());
        $thumbnailPath = 'resources/thumbnails/' . $fileName;

        $tempDirectory = sys_get_temp_dir() . '/resources/thumbnails';

        if (!file_exists($tempDirectory)) {
            mkdir($tempDirectory, 0755, true);
        }

        $image
            ->resize(new Box(250, 250))
            ->save($tempDirectory . '/' . $fileName);

        Storage::disk($fileSystemDisk)->put($thumbnailPath, file_get_contents($tempDirectory . '/' . $fileName));

        $thumbnailFullPath = Storage::disk($fileSystemDisk)->url($thumbnailPath);

        $fullPath = Storage::disk($fileSystemDisk)->url($path);

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
            'message' => __('Image uploaded successfully'),
        ]);
    }

    public function searchImages(ResourceFileRequest $request)
    {
        $query = ResourceFile::query()
            ->select('uuid', 'name', 'thumbnail_path','path')
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
