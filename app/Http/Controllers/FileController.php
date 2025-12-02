<?php

namespace App\Http\Controllers;

use App\Models\DownloadCount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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
     * Upload image for TinyMCE editor (abstract field)
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

        Storage::disk($diskType)->put($fileName, file_get_contents($file));

        if ($diskType === 's3') {
            $imageUrl = Storage::disk('s3')->url($fileName);
        } else {
            $imageUrl = Storage::disk('public')->url($fileName);
        }

        return response()->json([
            'url' => $imageUrl,
            'location' => $imageUrl,
        ]);
    }
}
