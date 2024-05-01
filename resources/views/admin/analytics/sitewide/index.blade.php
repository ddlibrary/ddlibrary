@extends('admin.layout')
@section('admin.content')
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="pb-4">
                <form method="get" action="{{ url('admin/sitewide-analytics') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-2">
                            <label>From Date</label>
                            <input type="date" value="{{ request()->date_from }}" class="form-control" name="date_from">
                        </div>
                        <div class="col-md-2">
                            <label>To Date</label>
                            <input type="date" value="{{ request()->date_to }}" class="form-control" name="date_to">
                        </div>
                        <div class="col-md-2">
                            <label>Language</label>
                            <select class="form-control" name="language">
                                <option value="">...</option>
                                @foreach($languages as $locale => $properties)
                                    <option value="{{ $locale }}" @selected($locale==request()->language)>{{ $properties['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2" style="align-self: flex-end">
                            <input class="btn btn-primary" type="submit" value="Filter">
                        </div>
                    </div>
                </form>
            </div>
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-table"></i> Sitewide Analytics
                </div>
                <div class="card-body">
                    <div class="row">

                        {{-- Total registered users  --}}
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-secondary mb-3">
                                <div class="card-header">Total users base on registration source </div>
                                <div class="card-body text-secondary p-2">
                                    <div class="card-text">
                                        @foreach ($top10ViewedResources as $top10ViewedResource)
                                            <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                                <div class="p-1 text-capitalize">
                                                    {{ $top10ViewedResource->title}}
                                                </div>
                                                <div class="p-1">
                                                    <span class="badge badge-info">
                                                        {{ number_format($top10ViewedResource->views_count) }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                            <div class="p-1 text-capitalize">
                                                Total top 10 viewed resources
                                            </div>
                                            <div class="p-1">
                                                <span class="badge badge-info">
                                                    {{ number_format($top10ViewedResources->sum('views_count')) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Total views base on user types  --}}
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-secondary mb-3">
                                <div class="card-header">Total views base on user types </div>
                                <div class="card-body text-secondary p-2">
                                    <div class="card-text">
                                        <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                            <div class="p-1 text-capitalize">
                                                1. Total View
                                            </div>
                                            <div class="p-1">
                                                <span class="badge badge-info">
                                                    {{ number_format($totalViews) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                            <div class="p-1 text-capitalize">
                                                2. Unregistered users views
                                            </div>
                                            <div class="p-1">
                                                <span class="badge badge-info">
                                                    {{ number_format($totalGuestViews) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                            <div class="p-1 text-capitalize">
                                                3. Registered users views
                                            </div>
                                            <div class="p-1">
                                                <span class="badge badge-info">
                                                    {{ number_format($totalRegisteredUsersViews) }}
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
