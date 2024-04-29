<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserAnalyticsController extends Controller
{
    public function index(Request $request): View
    {
        $roles = $this->getTotalUsersBaseOnRole($request);
        $totalRegisteredUsers = $this->getTotalRegisteredUsers($request);
        $totalUsersBaseOnGenders = $this->getTotalUsersBaseOnGender($request);

        return view('admin.analytics.users.index', compact(['roles', 'totalUsersBaseOnGenders', 'totalRegisteredUsers']));
    }

    private function getTotalUsersBaseOnRole($request): Collection
    {
        return Role::withCount([
            'users' => function ($query) use ($request) {
                if ($request->date_from && $request->date_to) {
                    $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
                }
            },
        ])
            //->where('id', '>', 2)
            ->get();
    }

    private function getTotalUsersBaseOnGender($request): Collection
    {
        return User::select(['user_profiles.gender', DB::raw('COUNT(users.id) as users_count')])
            ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->where(function ($query) use ($request) {
                if ($request->date_from && $request->date_to) {
                    $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
                }
            })
            ->groupBy('user_profiles.gender')
            ->get();
    }

    private function getTotalRegisteredUsers($request)
    {
        return User::where(function ($query) use ($request) {
            if ($request->date_from && $request->date_to) {
                $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
            }
        })->count();
    }
}
