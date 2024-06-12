@extends('admin.layout')
@section('admin.content')
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="pb-4">
                <form method="get" action="{{ url('admin/user-analytics') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-2">
                            <label>From <span class="fa fa-calendar"></span></label>
                            <input type="date" value="{{ request()->date_from }}" class="form-control" name="date_from">
                        </div>
                        <div class="col-md-2">
                            <label>To <span class="fa fa-calendar"></span></label>
                            <input type="date" value="{{ request()->date_to }}" class="form-control" name="date_to">
                        </div>

                        <div class="col-md-2" style="align-self: flex-end">
                            <input class="btn btn-primary" type="submit" value="Filter">
                        </div>
                    </div>

                </form>
            </div>
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-table"></i> User Analytics
                </div>
                <div class="card-body">
                    <div class="row">

                        {{-- Total registered users  --}}
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-secondary mb-3">
                                <div class="card-header">Total registered users </div>
                                <div class="card-body text-secondary p-2">
                                    <div class="card-text">
                                        <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                            <div class="p-1 text-capitalize">
                                                Total Users
                                            </div>
                                            <div class="p-1">
                                                <span class="badge badge-info">
                                                    {{ number_format($totalRegisteredUsers) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card border-secondary mb-3">
                                <div class="card-header">Total users base on registration source </div>
                                <div class="card-body text-secondary p-2">
                                    <div class="card-text">
                                        <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                            <div class="p-1 text-capitalize">
                                                1. Manual Users
                                            </div>
                                            <div class="p-1">
                                                <span class="badge badge-info">
                                                    {{ number_format($totalUsers) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                            <div class="p-1 text-capitalize">
                                                2. Google
                                            </div>
                                            <div class="p-1">
                                                <span class="badge badge-info">
                                                    {{ number_format($totalGoogleUsers) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                            <div class="p-1 text-capitalize">
                                                3. Facebook
                                            </div>
                                            <div class="p-1">
                                                <span class="badge badge-info">
                                                    {{ number_format($totalFacebookUsers) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                            <div class="p-1 text-capitalize">
                                                Total Users
                                            </div>
                                            <div class="p-1">
                                                <span class="badge badge-info">
                                                    {{ number_format($totalFacebookUsers + $totalGoogleUsers + $totalUsers) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Top 10 active users  --}}
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-secondary mb-3">
                                <div class="card-header">Top 10 active users </div>
                                <div class="card-body text-secondary p-2">
                                    <div class="card-text">
                                        @forelse ($top10ActiveUsers as $user)
                                            <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                                <div class="p-1 text-capitalize">
                                                    {{ $loop->iteration }}.
                                                    {{ $user->first_name }} {{ $user->last_name }}
                                                </div>
                                                <div class="p-1">
                                                    <span class="badge badge-info">
                                                        {{ number_format($user->activity_count) }}
                                                    </span>
                                                </div>
                                            </div>
                                        @empty
                                            <h2 class="alert alert-danger">not available</h2>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- List the total admins/managers/translators.  --}}
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-secondary mb-3">
                                <div class="card-header">List the total admins/managers/translators. </div>
                                <div class="card-body text-secondary p-2">
                                    <div class="card-text">
                                        @foreach ($roles as $role)
                                            <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                                <div class="p-1 text-capitalize">
                                                    {{ $loop->iteration }}.
                                                    {{ $role->name }}
                                                </div>
                                                <div class="p-1">
                                                    <span class="badge badge-info">
                                                        {{ number_format($role->users_count) }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Total users base on gender  --}}
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-secondary mb-3">
                                <div class="card-header">Total users base on gender </div>
                                <div class="card-body text-secondary p-2">
                                    <div class="card-text">
                                        @forelse ($totalUsersBaseOnGenders as $totalUsersBaseOnGender)
                                            <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                                <div class="p-1 text-capitalize">
                                                    {{ $loop->iteration }}.
                                                    {{ $totalUsersBaseOnGender->gender }}
                                                </div>
                                                <div class="p-1">
                                                    <span class="badge badge-info">
                                                        {{ number_format($totalUsersBaseOnGender->users_count) }}
                                                    </span>
                                                </div>
                                            </div>
                                        @empty
                                            <h2 class="alert alert-danger">not available</h2>
                                        @endforelse
                                        <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                            <div class="p-1 text-capitalize">
                                                Total Users
                                            </div>
                                            <div class="p-1">
                                                <span class="badge badge-info">
                                                    {{ number_format($totalUsersBaseOnGenders->sum('users_count')) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
