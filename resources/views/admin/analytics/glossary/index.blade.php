@extends('admin.layout')
@section('admin.content')
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="pb-4">
                <form method="get" action="{{ url('admin/analytics/glossary') }}">
                    @csrf
                    <div class="row">

                        {{-- From Date --}}
                        <div class="col-md-2 mb-4">
                            <label for="from-date">From </label>
                            <input type="date" id="from-date" value="{{ request()->date_from }}" class="form-control"
                                name="date_from">
                        </div>

                        {{-- To Date --}}
                        <div class="col-md-2 mb-4">
                            <label for="to-date">To </label>
                            <input type="date" id="to-date" value="{{ request()->date_to }}" class="form-control"
                                name="date_to">
                        </div>

                        {{-- Gender --}}
                        <div class="col-md-2 mb-4">
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
                        <div class="col-md-2 mb-4">
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
                        <div class="col-md-2 mb-4">
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
                        <div class="col-md-2 mb-4">
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
                        <div class="col-md-2 mb-4">
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
                        <div class="col-md-2 mb-4">
                            <label for="glossary-subject">Subject </label>
                            <select class="form-control" name="glossary_subject_id" id="glossary-subject">
                                <option value="">...</option>
                                @foreach ($glossarySubjects as $glossarySubject)
                                    <option value="{{ $glossarySubject->id }}" @selected($glossarySubject->id == request()->glossary_subject_id)>
                                        {{ $glossarySubject->en }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Is bot --}}
                        <div class="col-md-2 mb-4">
                            <label for="is-bot">Is bot </label>
                            <select class="form-control" name="is_bot" id="is-bot">
                                <option value="">...</option>
                                <option value="1" @selected(1 == request()->is_bot)>Yes</option>
                                <option value="2" @selected(2 == request()->is_bot)>No</option>
                            </select>
                        </div>

                        {{-- Status --}}
                        <div class="col-md-2 mb-4">
                            <label for="bot">Status </label>
                            <select class="form-control" name="status" id="status">
                                <option value="1" @selected(1 == request()->status)>View</option>
                                <option value="2" @selected(2 == request()->status)>Created</option>
                            </select>
                        </div>

                        <div class="col-md-2 mb-4" style="align-self: flex-end">
                            <input class="btn btn-primary" type="submit" value="Filter">
                        </div>
                    </div>
                </form>
            </div>
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-table"></i> Glossary Analytics
                </div>
                <div class="card-body">
                    <div class="row">
                        {{-- Total view by languages --}}
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-secondary mb-3">
                                <div class="card-header d-flex justify-content-between">
                                    <div>
                                        {{ $status }} by languages
                                    </div>
                                </div>
                                <div class="card-body text-secondary p-2">

                                    @forelse ($totalViewBasedOnLanguage as $view)
                                        <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                            <div class="p-1">
                                                {{ $loop->iteration }}.
                                                {{ $view->language ?: '<no language>' }}
                                            </div>
                                            <div class="p-1">
                                                <span class="badge badge-info">
                                                    {{ number_format($view->view_count) }}
                                                </span>
                                            </div>
                                        </div>
                                    @empty
                                        <h2 class="alert alert-danger">N/A</h2>
                                    @endforelse

                                    <div class="d-flex justify-content-between">
                                        <div>
                                            Total {{ $status }}
                                        </div>
                                        <span class="badge badge-info">
                                            {{ number_format($totalViewBasedOnLanguage->sum('count')) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Total views base on user types  --}}
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-secondary mb-3">
                                <div class="card-header">{{ $status }} by user types </div>
                                <div class="card-body text-secondary p-2">
                                    <div class="card-text">
                                        <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                            <div class="p-1 text-capitalize">
                                                1. Total views
                                            </div>
                                            <div class="p-1">
                                                <span class="badge badge-info">
                                                    {{ number_format($totalViews) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                            <div class="p-1 text-capitalize">
                                                2. Unregistered
                                            </div>
                                            <div class="p-1">
                                                <span class="badge badge-info">
                                                    {{ number_format($totalGuestViews) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                            <div class="p-1 text-capitalize">
                                                3. Registered
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

                        {{-- View based on platform --}}
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-secondary mb-3">
                                <div class="card-header d-flex justify-content-between">
                                    <div>
                                        {{ $status }} by platforms
                                    </div>
                                </div>
                                <div class="card-body text-secondary p-2">

                                    @forelse ($platformCounts as $platform)
                                        <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                            <div class="p-1">
                                                {{ $loop->iteration }}.
                                                {{ $platform->name ?: '<no platform>' }}
                                            </div>
                                            <div class="p-1">
                                                <span class="badge badge-info">
                                                    {{ number_format($platform->glossary_page_views_count) }}
                                                </span>
                                            </div>
                                        </div>
                                    @empty
                                        <h2 class="alert alert-danger">N/A</h2>
                                    @endforelse

                                </div>
                            </div>
                        </div>

                        {{-- View based on browser --}}
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-secondary mb-3">
                                <div class="card-header d-flex justify-content-between">
                                    <div>
                                        {{ $status }} by browsers
                                    </div>
                                </div>
                                <div class="card-body text-secondary p-2">

                                    @forelse ($browserCounts as $browser)
                                        <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                            <div class="p-1">
                                                {{ $loop->iteration }}.
                                                {{ $browser->name ?: '<no browser>' }}
                                            </div>
                                            <div class="p-1">
                                                <span class="badge badge-info">
                                                    {{ number_format($browser->glossary_page_views_count) }}
                                                </span>
                                            </div>
                                        </div>
                                    @empty
                                        <h2 class="alert alert-danger">N/A</h2>
                                    @endforelse

                                </div>
                            </div>
                        </div>

                        {{-- View based on page type --}}
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-secondary mb-3">
                                <div class="card-header d-flex justify-content-between">
                                    <div>
                                        {{ $status }} by glossary subjects
                                    </div>
                                </div>
                                <div class="card-body text-secondary p-2">

                                    @forelse ($glossarySubjectCounts->where('glossary_page_views_count', '<>', 0) as $glossarySubjectCount)
                                        <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                            <div class="p-1">
                                                {{ $loop->iteration }}.
                                                {{ $glossarySubjectCount->en ?: '<no page type>' }}
                                            </div>
                                            <div class="p-1">
                                                <span class="badge badge-info">
                                                    {{ number_format($glossarySubjectCount->glossary_page_views_count) }}
                                                </span>
                                            </div>
                                        </div>
                                    @empty
                                        <h2 class="alert alert-danger">N/A</h2>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
