<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function __invoke($file_path)
    {
        if (!Storage::disk('private')->exists($file_path)) {
            abort(404);
        }

        $local_path = Storage::disk('private')->url($file_path);
        return response()->file($local_path);
    }
}
