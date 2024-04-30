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
        $top10ActiveUsers = $this->getTop10ActiveUsers($request);
        $totalRegisteredUsers = $this->getTotalRegisteredUsers($request);
        $totalUsersBaseOnGenders = $this->getTotalUsersBaseOnGender($request);
        $totalUsers = $this->getTotalUsersBaseOnRegistrationSource($request);
        $totalGoogleUsers = $this->getTotalUsersBaseOnRegistrationSource($request, 'google');
        $totalFacebookUsers = $this->getTotalUsersBaseOnRegistrationSource($request, 'facebook');

        return view('admin.analytics.users.index', compact(['roles', 'totalUsersBaseOnGenders', 'totalRegisteredUsers', 'totalUsers', 'totalGoogleUsers', 'totalFacebookUsers', 'top10ActiveUsers']));
    }

    private function getTotalUsersBaseOnRole($request): Collection
    {
        return Role::withCount([
            'users' => function ($query) use ($request) {
                if ($request->date_from && $request->date_to) {
                    $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
                }
            },
        ])->get();
    }

    private function getTotalUsersBaseOnGender($request): Collection
    {
        return User::select(['user_profiles.gender', DB::raw('COUNT(users.id) as users_count')])
            ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->when($request->date_from && $request->date_to, function ($query) use ($request) {
                $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
            })
            ->groupBy('user_profiles.gender')
            ->get();
    }

    private function getTotalRegisteredUsers($request): float
    {
        return User::when($request->date_from && $request->date_to, function ($query) use ($request) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        })->count();
    }

    private function getTotalUsersBaseOnRegistrationSource($request, $providerName = null): float
    {
        $query = User::query()
            ->when($providerName, function ($query, $providerName) {
                return $query->whereProviderName($providerName);
            })
            ->unless($providerName, function ($query) {
                return $query->whereNull('provider_name');
            })
            ->when($request->date_from && $request->date_to, function ($query) use ($request) {
                return $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
            });

        return $query->count();
    }

    private function getTop10ActiveUsers($request)
    {
        $query = DB::table('activity_log')->select('user_profiles.first_name', 'user_profiles.last_name', DB::raw('count(*) as activity_count'))->join('users', 'users.id', '=', 'activity_log.causer_id')->join('user_profiles', 'user_profiles.user_id', '=', 'users.id')->groupBy('users.id', 'user_profiles.last_name', 'user_profiles.first_name')->orderByDesc('activity_count')->limit(10);

        if ($request->date_from && $request->date_to) {
            $query->whereBetween('activity_log.created_at', [$request->date_from, $request->date_to]);
        }

        return $query->get();
    }
}
