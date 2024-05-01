@extends('admin.layout')
@section('admin.content')
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="pb-4">
                <form method="get" action="{{ url('admin/resource-analytics') }}">
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
                            <label>Gender</label>
                            <select class="form-control" name="gender">
                                <option value="">...</option>
                                @foreach ($genders as $gender)
                                    <option @selected(request()->gender == $gender) value="{{ $gender }}">
                                        {{ $gender }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Language</label>
                            <select class="form-control" name="language">
                                <option value="">...</option>
                                @foreach ($languages as $key => $value)
                                    <option @selected(request()->language == $key) value="{{ $key }}">
                                        {{ $value['name'] }}</option>
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
                    <i class="fa fa-table"></i> Resource Analytics
                </div>
                <div class="card-body">
                    <div class="row">

                        {{-- Resouces base on Language --}}
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-secondary mb-3">
                                <div class="card-header">Resouces base on Language</div>
                                <div class="card-body text-secondary p-2">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="badge badge-info">Date <span class="fa fa-check"></span> </span>
                                        <span class="badge badge-info">Gender <span class="fa fa-check"></span> </span>
                                        <span class="badge badge-info">Language <span class="fa fa-check"></span> </span>
                                    </div>

                                    @foreach ($totalResources as $totalResource)
                                        <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                            <div class="p-1">
                                                {{ $loop->iteration }}.
                                                {{ $totalResource->language }}
                                            </div>
                                            <div class="p-1">
                                                <span class="badge badge-info">
                                                    {{ number_format($totalResource->count) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach

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
                                <div class="card-header">Top 10 downloaded resources</div>
                                <div class="card-body text-secondary p-2">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="badge badge-info">Date <span class="fa fa-check"></span> </span>
                                        <span class="badge badge-info">Gender <span class="fa fa-check"></span> </span>
                                        <span class="badge badge-info">Language <span class="fa fa-check"></span> </span>
                                    </div>

                                    @foreach ($top10DownloadedResources as $top10DownloadedResource)
                                        <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                            <div class="p-1">
                                                {{ $loop->iteration }}.
                                                {{ $top10DownloadedResource->title }}
                                            </div>
                                            <div class="p-1">
                                                <span class="badge badge-info">
                                                    {{ number_format($top10DownloadedResource->downloads_count) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>

                        {{-- Top 10 downloaded resources by file size --}}
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-secondary mb-3">
                                <div class="card-header">Top 10 downloaded resources by file size</div>
                                <div class="card-body text-secondary p-2">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="badge badge-info">Date <span class="fa fa-check"></span> </span>
                                        <span class="badge badge-info">Gender <span class="fa fa-check"></span> </span>
                                        <span class="badge badge-info">Language <span class="fa fa-check"></span> </span>
                                    </div>

                                    @foreach ($top10DownloadedResourcesByFileSizes as $top10DownloadedResourcesByFileSize)
                                        <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                            <div class="p-1">
                                                {{ $loop->iteration }}.
                                                {{ $top10DownloadedResourcesByFileSize->title }}
                                            </div>
                                            <div class="p-1">
                                                <span class="badge badge-info">
                                                    {{ number_format($top10DownloadedResourcesByFileSize->file_size / (1024 * 1024 * 1024), 2) }}
                                                    GB
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>

                        {{-- Sum of all individual downloaded file sizes --}}
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-secondary mb-3">
                                <div class="card-header">Downloaded File Sizes</div>
                                <div class="card-body text-secondary p-2">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="badge badge-danger">Date <span class="fa fa-times"></span> </span>
                                        <span class="badge badge-danger">Gender <span class="fa fa-times"></span> </span>
                                        <span class="badge badge-danger">Language <span class="fa fa-times"></span>
                                        </span>
                                    </div>
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
                                <div class="card-header">Top 10 Authors</div>
                                <div class="card-body text-secondary p-2">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="badge badge-info">Date <span class="fa fa-check"></span> </span>
                                        <span class="badge badge-danger">Gender <span class="fa fa-times"></span> </span>
                                        <span class="badge badge-info">Language <span class="fa fa-check"></span> </span>
                                    </div>

                                    @foreach ($authors as $author)
                                        <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                            <div class="p-1">
                                                {{ $loop->iteration }}.
                                                {{ $author->name }}
                                            </div>
                                            <div class="p-1">
                                                <span class="badge badge-info">
                                                    {{ number_format($author->resource_count) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>

                        {{-- Top 10 Publishers --}}
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-secondary mb-3">
                                <div class="card-header">Top 10 Publisher</div>
                                <div class="card-body text-secondary p-2">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="badge badge-info">Date <span class="fa fa-check"></span> </span>
                                        <span class="badge badge-danger">Gender <span class="fa fa-times"></span> </span>
                                        <span class="badge badge-info">Language <span class="fa fa-check"></span> </span>
                                    </div>

                                    @foreach ($publishers as $publisher)
                                        <div class="d-flex justify-content-between mb-2 rounded bg-light text-dark">
                                            <div class="p-1">
                                                {{ $loop->iteration }}.
                                                {{ $publisher->name }}
                                            </div>
                                            <div class="p-1">

                                                <span class="badge badge-info">

                                                    {{ number_format($publisher->resource_count) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
