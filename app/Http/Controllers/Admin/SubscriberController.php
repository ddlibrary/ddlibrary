<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SubscriberController extends Controller
{
    public function index(Request $request): View{
        $query = Subscriber::query();

        if($request->search){
            $query->whereAny([
                'name',
                'email',
            ], 'LIKE', "%$request->search%");
        }

        $subscribers = $query->latest()->paginate();
        $totalSubscribers = DB::table('subscribers')->count();

        return view('admin.subscriber.index', compact('subscribers', 'totalSubscribers'));
    }

    public function destroy(Subscriber $subscriber): RedirectResponse{
        $subscriber->delete();

        return back();
    }
}
