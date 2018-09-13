<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DownloadCount;

class DownloadController extends Controller
{
    public function index(Request $request)
    {
        $this->middleware('admin');
        if($request->has('date_from') && $request->has('date_to')){
            $from = request('date_from');
            $to = request('date_to');
            $records = DownloadCount::orderBy('id','desc')->whereBetween('created_at', [$from, $to])->paginate(10);
            $records->appends(request()->except(['page','_token']));
        }else{
            $records = DownloadCount::orderBy('id','desc')->paginate(10);
        }
        $filters = $request;
        return view('admin.downloads.download_list', compact('records','filters'));
    }
}
