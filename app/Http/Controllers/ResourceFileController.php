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
                'image.dimensions' => __('Upload an image that is the same width and height (a square).'),
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
        $filelabel = auth()->user()->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = 'resources/' . $filelabel;

        $fileSystemDisk = config('filesystems.default', 'local');

        Storage::disk($fileSystemDisk)->put('public/' . $path, file_get_contents($file));

        $imagine = new Imagine();
        $image = $imagine->open($file->getRealPath());
        $thumbnailPath = 'resources/thumbnails/' . $filelabel;

        $tempDirectory = sys_get_temp_dir() . '/resources/thumbnails';

        if (!file_exists($tempDirectory)) {
            mkdir($tempDirectory, 0755, true);
        }

        $image->resize(new Box(250, 250))->save($tempDirectory . '/' . $filelabel);

        Storage::disk($fileSystemDisk)->put('public/' . $thumbnailPath, file_get_contents($tempDirectory . '/' . $filelabel));

        $thumbnailFullPath = Storage::disk($fileSystemDisk)->url($thumbnailPath);
        $thumbnailFullPath = str_replace('/storage', '', $thumbnailFullPath);

        $fullPath = Storage::disk($fileSystemDisk)->url($path);
        $fullPath = str_replace('/storage', '', $fullPath);

        $resourceFile = ResourceFile::create([
            'label' => $request->image_name,
            'language' => $request->language,
            'taxonomy_term_data_id' => $request->taxonomy_term_data_id,
            'name' => $fullPath,
        ]);

        return response()->json([
            'success' => true,
            'resource_file_id' => $resourceFile->id,
            'imageUrl' => $fullPath,
            'thumbnailUrl' => $thumbnailFullPath,
            'imageName' => $request->image_name,
            'message' => __('Image uploaded successfully'),
        ]);
    }

    public function searchImages(ResourceFileRequest $request)
    {
        $query = ResourceFile::query()
            ->select('id', 'label', 'name')
            ->where(function ($query) use ($request) {
                if ($request->subject_area_id) {
                    $resourceFileIds = DB::table('resource_subject_areas')->join('resources', 'resource_subject_areas.resource_id', '=', 'resources.id')->where('tid', $request->subject_area_id)->pluck('resource_file_id');
                    $query->whereIn('id', $resourceFileIds);
                }
                if ($request->search) {
                    $query->where('label', 'like', "%{$request->search}%");
                }
            })
            ->where('language', $request->language);

        $count = $query->count();
        $files = $query->orderByDesc('created_at')->paginate(16)->appends($request->except('page'));


        return view('resources.partial.file-list', compact('count', 'files'));
    }
}
