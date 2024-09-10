<?php

namespace App\Http\Controllers;

use App\Models\DownloadCount;
use App\Traits\GenderTrait;
use App\Traits\LanguageTrait;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DownloadController extends Controller
{
    use GenderTrait, LanguageTrait;

    public function index(Request $request): View
    {
        $genders = $this->genders();
        $languages = $this->getLanguages();

        $query = DownloadCount::query()->with(['user:id,username', 'resource:id,title,language', 'file:id,file_name']);

        // Date
        if ($request->date_from && $request->date_to) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }

        // Gender
        if ($request->gender) {
            if ($request->gender == 'guest') {
                $query->where('user_id', 0);
            } else {
                $query->whereHas('user.profile', function ($query) use ($request) {
                    $query->where('gender', $request->gender);
                });
            }
        }

        // Language
        if ($request->language) {
            $query->whereHas('resource', function ($query) use ($request) {
                $query->where('language', $request->language);
            });
        }

        $records = $query->orderByDesc('id')->paginate()->appends(request()->except(['page', '_token']));

        $filters = $request;

        return view('admin.downloads.download_list', compact('records', 'filters', 'genders', 'languages'));
    }
}
