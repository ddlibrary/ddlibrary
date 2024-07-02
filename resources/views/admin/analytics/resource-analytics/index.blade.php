@extends('admin.layout')
@section('admin.content')
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="pb-4">
                <form method="get" action="{{ url('admin/analytics/resources') }}">
                    @csrf
                    <div class="row">

                        {{-- From  --}}
                        <div class="col-md-2">
                            <label>From <span class="fa fa-calendar"></span></label>
                            <input type="date" value="{{ request()->date_from }}" class="form-control" name="date_from">
                        </div>

                        {{-- To --}}
                        <div class="col-md-2">
                            <label>To <span class="fa fa-calendar"></span></label>
                            <input type="date" value="{{ request()->date_to }}" class="form-control" name="date_to">
                        </div>

                        {{-- Gender --}}
                        <div class="col-md-2">
                            <label>Gender <span class="fa fa-female"></span></label>
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
                            <label>Language <span class="fa fa-language"></span></label>
                            <select class="form-control" name="language">
                                <option value="">...</option>
                                @foreach ($languages as $key => $value)
                                    <option @selected(request()->language == $key) value="{{ $key }}">
                                        {{ $value['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filter button --}}
                        <div class="col-md-2" style="align-self: flex-end">
                            <input class="btn btn-primary" type="submit" value="Filter">
                        </div>
                    </div>
                </form>
            </div>
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-table"></i> Resource Analytics
                </div>
                <div class="card-body">
                    <div class="row">

                        {{-- Total resources by languages --}}
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-secondary mb-3">
                                <div class="card-header d-flex justify-content-between">
                                    <div>
                                        Total resources by languages
                                    </div>
                                    <div class="display-inline-block text-right">
                                        <span class="fa fa-calendar"></span>
                                        <span class="fa fa-female"></span>
                                        <span class="fa fa-language"></span>
                                    </div>
                                </div>
                                <div class="card-body text-secondary p-2">

                                    @forelse ($totalResources as $resource)
                                        <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                            <div class="p-1">
                                                {{ $loop->iteration }}.
                                                {{ $resource->language ?  : '<no language>' }}
                                            </div>
                                            <div class="p-1">
                                                <span class="badge badge-info">
                                                    {{ number_format($resource->count) }}
                                                </span>
                                            </div>
                                        </div>
                                    @empty
                                        <h2 class="alert alert-danger">N/A</h2>
                                    @endforelse

                                    <div class="d-flex justify-content-between">
                                        <div>
                                            Total Resources
                                        </div>
                                        <span class="badge badge-info">
                                            {{ number_format($totalResources->sum('count')) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Top 10 downloaded resources --}}
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-secondary mb-3">
                                <div class="card-header d-flex justify-content-between">
                                    <div>
                                        Top 10 downloaded resources
                                    </div>
                                    <div class="display-inline-block text-right">
                                        <span class="fa fa-calendar"></span>
                                        <span class="fa fa-language"></span>
                                    </div>
                                </div>
                                <div class="card-body text-secondary p-2">

                                    @forelse ($top10DownloadedResources as $resource)
                                        <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                            <div class="p-1">
                                                {{ $loop->iteration }}.
                                                <a href="{{ URL::to('resource/' . $resource->id) }}"
                                                   target="_blank">{{ $resource->title }}</a>
                                            </div>
                                            <div class="p-1">
                                                <span class="badge badge-info">
                                                    {{ number_format($resource->downloads_count) }}
                                                </span>
                                            </div>
                                        </div>
                                    @empty
                                        <h2 class="alert alert-danger">N/A</h2>
                                    @endforelse

                                </div>
                            </div>
                        </div>

                        {{-- Top 10 favorited resources --}}
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-secondary mb-3">
                                <div class="card-header d-flex justify-content-between">
                                    <div>
                                        Top 10 favorited resources
                                    </div>
                                    <div class="display-inline-block text-right">
                                        <span class="fa fa-calendar"></span>
                                        <span class="fa fa-language"></span>
                                    </div>
                                </div>
                                <div class="card-body text-secondary p-2">

                                    @forelse ($top10FavoriteResources as $resource)
                                        <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                            <div class="p-1">
                                                {{ $loop->iteration }}.
                                                <a href="{{ URL::to('resource/' . $resource->id) }}"
                                                   target="_blank">{{ $resource->title }}</a>
                                            </div>
                                            <div class="p-1">
                                                <span class="badge badge-info">
                                                    {{ number_format($resource->resource_favorites_count) }}
                                                </span>
                                            </div>
                                        </div>
                                    @empty
                                        <h2 class="alert alert-danger">N/A</h2>
                                    @endforelse

                                </div>
                            </div>
                        </div>

                        {{-- Top 10 downloaded resources by file size --}}
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-secondary mb-3">
                                <div class="card-header d-flex justify-content-between">
                                    <div>
                                        Top 10 downloaded resources by file size
                                    </div>
                                    <div class="display-inline-block text-right">
                                        <span class="fa fa-calendar"></span>
                                        <span class="fa fa-female"></span>
                                        <span class="fa fa-language"></span>
                                    </div>
                                </div>
                                <div class="card-body text-secondary p-2">

                                    @forelse ($top10DownloadedResourcesByFileSizes as $resource)
                                        <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                            <div class="p-1">
                                                {{ $loop->iteration }}.
                                                <a href="{{ URL::to('resource/' . $resource->id) }}"
                                                   target="_blank">{{ $resource->title }}</a>
                                            </div>
                                            <div class="p-1">
                                                <span class="badge badge-info">
                                                    {{ number_format($resource->file_size / (1024 * 1024), 2) }}
                                                    MB
                                                </span>
                                            </div>
                                        </div>
                                    @empty
                                        <h2 class="alert alert-danger">N/A</h2>
                                    @endforelse

                                </div>
                            </div>
                        </div>

                        {{-- Sum of all individual Total data downloaded --}}
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-secondary mb-3">
                                <div class="card-header">Total data downloaded</div>
                                <div class="card-body text-secondary p-2">

                                    <div class="card-text">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                Sum of all individual downloaded file sizes
                                            </div>
                                            <div>
                                                <span class="badge badge-info">
                                                    {{ number_format($sumOfAllIndividualDownloadedFileSizes / (1024 * 1024 * 1024), 2) }}
                                                    GB
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Top 10 Authors --}}
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-secondary mb-3">
                                <div class="card-header d-flex justify-content-between">
                                    <div>
                                        Top 10 authors
                                    </div>
                                    <div class="display-inline-block text-right">
                                        <span class="fa fa-calendar"></span>
                                        <span class="fa fa-language"></span>
                                    </div>
                                </div>
                                <div class="card-body text-secondary p-2">

                                    @forelse ($top10Authors as $author)
                                        <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                            <div class="p-1">
                                                {{ $loop->iteration }}.
                                                {{ $author->name ?  : '<no author>' }}
                                            </div>
                                            <div class="p-1">
                                                <span class="badge badge-info">
                                                    {{ number_format($author->resource_count) }}
                                                </span>
                                            </div>
                                        </div>
                                    @empty
                                        <h2 class="alert alert-danger">N/A</h2>
                                    @endforelse

                                </div>
                            </div>
                        </div>

                        {{-- Top 10 Publishers --}}
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-secondary mb-3">
                                <div class="card-header d-flex justify-content-between">
                                    <div>
                                        Top 10 publishers
                                    </div>
                                    <div class="display-inline-block text-right">
                                        <span class="fa fa-calendar"></span>
                                        <span class="fa fa-language"></span>
                                    </div>
                                </div>
                                <div class="card-body text-secondary p-2">

                                    @forelse ($top10Publishers as $publisher)
                                        <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                            <div class="p-1">
                                                {{ $loop->iteration }}.
                                                @if ($publisher->name)
                                                    <a href="{{ URL::route('resourceList', ['publisher' => $publisher->id]) }}"
                                                       target="_blank">{{ $publisher->name }}</a>
                                                @else
                                                    <no publisher>
                                                @endif
                                            </div>
                                            <div class="p-1">
                                                <span class="badge badge-info">
                                                    {{ number_format($publisher->resource_count) }}
                                                </span>
                                            </div>
                                        </div>
                                    @empty
                                        <h2 class="alert alert-danger">N/A</h2>
                                    @endforelse

                                </div>
                            </div>
                        </div>

                        {{-- Subject Areas --}}
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-secondary mb-3">
                                <div class="card-header d-flex justify-content-between">
                                    <div>
                                        Subject Area
                                    </div>
                                </div>
                                <div class="card-body text-secondary p-2">

                                    @forelse ($subjectAreas as $subjectArea)
                                        <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                            <div class="p-1">
                                                {{ $loop->iteration }}.
                                                @if ($subjectArea->name)
                                                    <a href="{{ URL::route('resourceList', ['subjectArea' => $subjectArea->id]) }}"
                                                       target="_blank">{{ $subjectArea->name }}</a>
                                                @else
                                                    <no subjectArea>
                                                @endif
                                            </div>
                                            <div class="p-1">
                                                <span class="badge badge-info">
                                                    {{ number_format($subjectArea->resources_count) }}
                                                </span>
                                            </div>
                                        </div>
                                    @empty
                                        <h2 class="alert alert-danger">N/A</h2>
                                    @endforelse
                                    <div class="card-text">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                Total
                                            </div>
                                            <div>
                                                <span class="badge badge-info">
                                                    {{ number_format($subjectAreas->sum('resources_count')) }}
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
