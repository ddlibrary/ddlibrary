@extends('admin.layout')
@section('admin.content')

    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Breadcrumbs-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ URL::to('admin') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Impact Report</li>
            </ol>
            <div class="pb-4">
                @include('layouts.messages')
                <form method="GET" action="{{ route('impact-report') }}">
                    <div class="row">

                        {{-- From  --}}
                        <div class="col-md-2">
                            <label>From </label>
                            <input type="date" value="{{ request()->from }}" class="form-control" name="from">
                        </div>

                        {{-- To --}}
                        <div class="col-md-2">
                            <label>To </label>
                            <input type="date" value="{{ request()->to }}" class="form-control" name="to">
                        </div>

                        {{-- Filter button --}}
                        <div class="col-md-2" style="align-self: flex-end">
                            <input class="btn btn-primary" type="submit" value="Filter">
                        </div>
                    </div>
                </form>
            </div>

            <!-- Example DataTables Card-->
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-table"></i> Report for impact database
                </div>
                <div class="card-body">
                    <ul>
                        <li>Uploaded resources: {{ $resources_count }}</li>
                        <li>
                            Registered users: {{ $registered_users_count->total_users_count }}
                            <ul>
                                <li>Male: {{ $registered_users_count->male_count }}</li>
                                <li>Female: {{ $registered_users_count->female_count }}</li>
                                <li>Undisclosed: {{ $registered_users_count->undisclosed_count }}</li>
                                <li>Unknown: {{ $registered_users_count->unknown_count }}</li>
                            </ul>
                        </li>
                        <li>Resource downloads: {{ $resources_download_count }}</li>
                        </ul>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /.container-fluid-->
        <!-- /.content-wrapper-->
@endsection
