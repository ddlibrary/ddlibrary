<?php

namespace App\Http\Controllers;

use App\Models\DownloadCount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Imagine\Image\ImagineInterface;
use Imagine\Image\Box;

class FileController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function __invoke($resource_id, $file_id, $file_path): BinaryFileResponse
    {
        $this->fileDownloadCounter($resource_id, $file_id);
        if (! Storage::disk('private')->exists($file_path)) {
            abort(404);
        }

        $local_path = Storage::disk('private')->url($file_path);

        return response()->file($local_path);
    }

    public function fileDownloadCounter($resource_id, $file_id)
    {

        if (is_numeric($resource_id) && is_numeric($file_id)) {
            $fileDownload = new DownloadCount();

            $userAgentParser = parse_user_agent(request());

            $fileDownload->resource_id = $resource_id;
            $fileDownload->file_id = $file_id;
            $fileDownload->user_id = (Auth::id()) ? Auth::id() : 0;
            $fileDownload->ip_address = request()->ip();
            $fileDownload->save();
        }
    }

    /**
     * Upload image for TinyMCE editor
     */
    public function uploadtImageFromEditor(Request $request)
    {
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,jpg,png,gif,bmp|max:10240', // 10MB max
        ]);

        $file = $request->file('upload');
        $uniqueId = uniqid();
        $fileName = auth()->user()->id . '_' . $uniqueId . '_' . time() . '.' . $file->getClientOriginalExtension();
        
        $diskType = 's3';
        if(config('app.env') != 'production'){
            $diskType = 'public';
        }

        $path = ($diskType === 's3') ? 'files/thumbnails/' . $fileName : "thumbnails/$fileName";

        $imagine = $this->getImagineInstance();

        $image = $imagine->open($file->getRealPath());

        $originalSize = $image->getSize();
        $maxWidth = 1920; // Maximum width for compression
        $maxHeight = 1920; // Maximum height for compression

        // Resize if image is too large (maintain aspect ratio)
        if ($originalSize->getWidth() > $maxWidth || $originalSize->getHeight() > $maxHeight) {
            $ratio = min($maxWidth / $originalSize->getWidth(), $maxHeight / $originalSize->getHeight());
            $newWidth = (int) ($originalSize->getWidth() * $ratio);
            $newHeight = (int) ($originalSize->getHeight() * $ratio);
            $image = $image->resize(new Box($newWidth, $newHeight));
        }

        // Create temporary file for compressed image
        $tempDirectory = sys_get_temp_dir() . '/tinymce_uploads';
        if (!file_exists($tempDirectory)) {
            mkdir($tempDirectory, 0755, true);
        }
        $tempFilePath = $tempDirectory . '/' . $fileName;

        // Save with compression
        $extension = strtolower($file->getClientOriginalExtension());
        $options = [];

        if (in_array($extension, ['jpg', 'jpeg'])) {
            $options['quality'] = 85;
        } elseif ($extension === 'png') {
            $options['png_compression_level'] = 6;
        }

        $image->save($tempFilePath, $options);

        // Store the compressed image
        Storage::disk($diskType)->put($path, file_get_contents($tempFilePath));

        // Cleanup temporary file
        if (file_exists($tempFilePath)) {
            @unlink($tempFilePath);
        }

        $imageUrl = getFile("thumbnails/$fileName");

        return response()->json([
            'url' => $imageUrl,
            'location' => $imageUrl,
        ]);
    }

    /**
     * Get Imagine instance, preferring Imagick if available
     */
    private function getImagineInstance(): ImagineInterface
    {
        // Try Imagick first if extension is available
        if (extension_loaded('imagick')) {
            try {
                return new \Imagine\Imagick\Imagine();
            } catch (\Exception $e) {
                // Fall back to Gd if Imagick fails
            }
        }
        
        // Fall back to Gd
        return new \Imagine\Gd\Imagine();
    }
}
