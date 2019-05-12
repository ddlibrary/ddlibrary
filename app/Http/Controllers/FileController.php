<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\DownloadCount;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function __invoke($resource_id, $file_id, $file_path)
    {
        $this->fileDownloadCounter($resource_id, $file_id);
        if (!Storage::disk('private')->exists($file_path)) {
            abort(404);
        }

        $local_path = Storage::disk('private')->url($file_path);
        return response()->file($local_path);
    }

    public function fileDownloadCounter($resource_id, $file_id)
    {
        if(is_int($resource_id) && is_int($file_id)) {
            $fileDownload = new DownloadCount();

            $userAgentParser = parse_user_agent(request());

            $fileDownload->resource_id = $resource_id;
            $fileDownload->file_id = $file_id;
            $fileDownload->user_id = (Auth::id())?Auth::id():0;
            $fileDownload->ip_address = request()->ip();
            $fileDownload->save();
        }
    }
}
