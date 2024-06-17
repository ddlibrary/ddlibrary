@extends('admin.layout')
@section('admin.content')
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="pb-4">
                <form method="get" action="{{ url('admin/reports/sitewide') }}">
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
                        <div class="col-md-2">
                            <label>Language</label>
                            <select class="form-control" name="language">
                                <option value="">...</option>
                                @foreach ($languages as $locale => $properties)
                                    <option value="{{ $locale }}" @selected($locale == request()->language)>
                                        {{ $properties['name'] }}</option>
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
                    <i class="fa fa-table"></i> Resource View
                </div>
                <div class="card-body">
                    <div class="row">

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">NO</th>
                                    <th class="text-left">Title</th>
                                    <th class="text-center">Language</th>
                                    <th class="text-center">IP</th>
                                    <th class="text-center">Browser</th>
                                    <th class="text-center">Platform</th>
                                    <th class="text-center">User</th>
                                    <th class="text-center">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($resourceViews as $resourceView)
                                    <tr>
                                        <td class="text-center">
                                            {{ ($resourceViews->currentPage() - 1) * $resourceViews->perPage() + $loop->iteration }}
                                        </td>
                                        <td class="text-left">
                                            <a
                                                href="{{ URL::to($resourceView->resource->language . '/' . 'resource/' . $resourceView->resource->id) }}">
                                                {{ $resourceView->resource->title }}
                                        </td>
                                        </a>
                                        <td class="text-center">{{ $resourceView->resource->language }}</td>
                                        <td class="text-center">{{ $resourceView->ip }}</td>
                                        <td class="text-center">{{ $resourceView->browser_name }}
                                            {{ $resourceView->browser_version }}</td>
                                        <td class="text-center">{{ $resourceView->platform }}</td>
                                        <td class="text-center">{{ $resourceView->user?->profile?->first_name }}
                                            {{ $resourceView->user?->profile?->last_name }}</td>
                                        <td class="text-center">{{ $resourceView->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $resourceViews->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
