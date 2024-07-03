@extends('admin.layout')
@section('admin.content')
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="pb-4">
                <form method="get" action="{{ url('admin/analytics/reports/sitewide') }}">
                    @csrf
                    <div class="row">

                        {{-- From Date --}}
                        <div class="col-md-2">
                            <label for="from-date">From </label>
                            <input type="date" id="from-date" value="{{ request()->date_from }}" class="form-control"
                                name="date_from">
                        </div>

                        {{-- To Date --}}
                        <div class="col-md-2">
                            <label for="to-date">To </label>
                            <input type="date" id="to-date" value="{{ request()->date_to }}" class="form-control"
                                name="date_to">
                        </div>

                        {{-- Gender --}}
                        <div class="col-md-2">
                            <label>Gender </label>
                            <select class="form-control" name="gender">
                                <option value="">...</option>
                                @foreach ($genders as $gender)
                                    <option @selected(request()->gender == $gender) value="{{ $gender }}">
                                        {{ $gender }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Language --}}
                        <div class="col-md-2">
                            <label for="language">Language </label>
                            <select class="form-control" name="language" id="language">
                                <option value="">...</option>
                                @foreach ($languages as $locale => $properties)
                                    <option value="{{ $locale }}" @selected($locale == request()->language)>
                                        {{ $properties['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Device --}}
                        <div class="col-md-2">
                            <label for="device">Device </label>
                            <select class="form-control" name="device_id" id="device">
                                <option value="">...</option>
                                @foreach ($devices as $device)
                                    <option value="{{ $device->id }}" @selected($device->id == request()->device_id)>
                                        {{ $device->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Platform --}}
                        <div class="col-md-2">
                            <label for="platform">Platform </label>
                            <select class="form-control" name="platform_id" id="platform">
                                <option value="">...</option>
                                @foreach ($platforms as $platform)
                                    <option value="{{ $platform->id }}" @selected($platform->id == request()->platform_id)>
                                        {{ $platform->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Browser --}}
                        <div class="col-md-2">
                            <label for="browser">Browser </label>
                            <select class="form-control" name="browser_id" id="browser">
                                <option value="">...</option>
                                @foreach ($browsers as $browser)
                                    <option value="{{ $browser->id }}" @selected($browser->id == request()->browser_id)>
                                        {{ $browser->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Page Type --}}
                        <div class="col-md-2">
                            <label for="page-type">Page Type </label>
                            <select class="form-control" name="page_type_id" id="page-type">
                                <option value="">...</option>
                                @foreach ($pageTypes as $pageType)
                                    <option value="{{ $pageType->id }}" @selected($pageType->id == request()->page_type_id)>
                                        {{ $pageType->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Is Robot --}}
                        <div class="col-md-2">
                            <label for="page-type">Is Robt </label>
                            <select class="form-control" name="is_bot" id="is-robot">
                                <option value="">...</option>
                                <option value="1" @selected(1 == request()->is_bot)>Yes</option>
                                <option value="2" @selected(2 == request()->is_bot)>No</option>
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
                    <i class="fa fa-table"></i> Page View
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
                                    <th class="text-center">Device</th>
                                    <th class="text-center">Is Bot</th>
                                    <th class="text-center">User</th>
                                    <th class="text-center">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($views as $view)
                                    <tr>
                                        <td class="text-center">
                                            {{ ($views->currentPage() - 1) * $views->perPage() + $loop->iteration }}
                                        </td>
                                        <td class="text-left">
                                            <a
                                                href="{{ $view->page_url }}">
                                                {{ $view->title }}
                                        </td>
                                        </a>
                                        <td class="text-center">{{ $view->language }}</tdDevice>
                                        <td class="text-center">{{ $view->ip_address }}</td>
                                        <td class="text-center">{{ $view->browser }}</td>
                                        <td class="text-center">{{ $view->platform->name }}</td>
                                        <td class="text-center">{{ $view->device->name }}</td>
                                        <td class="text-center">
                                            {!!  $view->is_bot ? "<span class='badge badge-danger'>Yes</span>": "<span class='badge badge-success'>No</span>" !!}
                                        </td>
                                        <td class="text-center">{{ $view->user?->profile?->first_name }}
                                            {{ $view->user?->profile?->last_name }}</td>
                                        <td class="text-center">{{ $view->created_at ? $view->created_at->diffForHumans() : ''  }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $views->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
