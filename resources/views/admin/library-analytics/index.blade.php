@extends('admin.layout')
@section('admin.content')
    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Breadcrumbs-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ URL::to('admin') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Downloads</li>
            </ol>
            <!-- Example DataTables Card-->
            <div class="pb-4">
                <form method="get" action="{{ route('downloads') }}">
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
                        <div class="col-md-2">
                            <label>Report Type</label>
                            <select class="form-control" name="report_type">
                                <option value="">...</option>

                                <option value="top-10-downloaded-resources" @selected(request()->report_type == 'top-10-downloaded-resources')>Top 10
                                    downloaded resources</option>
                                <option value="top-10-downloaded-resource-by-file-size" @selected(request()->report_type == 'top-10-downloaded-resource-by-file-size')>Top 10
                                    downloaded resources by file size</option>
                                <option value="top-10-authors-and-publishers" @selected(request()->report_type == 'top-10-authors-and-publishers')>Top 10
                                    authors and publishers</option>
                                <option value="sum-of-all-individual-downloaded-file-sizes" @selected(request()->report_type == 'sum-of-all-individual-downloaded-file-sizes')>Sum
                                    of all individual downloaded file sizes
                                </option>

                                <option value="top-10-viewed-resources" @selected(request()->report_type == 'top-10-viewed-resources')>Top 10
                                    Viewed resources</option>
                                <option value="resource-view" @selected(request()->report_type == 'resource-view')>Resource View
                                </option>
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
                    <i class="fa fa-table"></i> Library Analytics
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2">
                            <div class="card border-secondary mb-3" style="max-width: 18rem;">
                                <div class="card-header">Resouces base on Language</div>
                                <div class="card-body text-secondary">
                                    <div class="card-text">
                                        @foreach ($totalResources as $totalResource)
                                            
                                        <span class="badge badge-info">{{ $totalResource->language }}: {{ $totalResource->count}}</span>
                                        @endforeach
                                        <span class="badge badge-info">Total Resources: {{ $totalResources->sum('count')}}</span>

                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid-->
        <!-- /.content-wrapper-->
    @endsection
