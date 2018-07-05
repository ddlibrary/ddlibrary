@extends('admin.layout')
@section('admin.content')
<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="#">Reports</a>
        </li>
        <li class="breadcrumb-item active">DDL Reports</li>
        </ol>
        <div class="row">
            <div class="col-lg-12">
                <!-- Example Bar Chart Card-->
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fa fa-list"></i> Total Users by Gender
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                    <th>Gender</th>
                                    <th>TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($totalUsersByGender as $indexkey => $resource)
                                <tr>
                                    <td><strong>{{ $resource->gender }}</strong></td>
                                    <td><a href="{{ URL::to('admin/users?gender='.$resource->gender) }}">{{ $resource->total }}</a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
                <div class="col-lg-12">
                    <!-- Example Bar Chart Card-->
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fa fa-list"></i> Total Users by Roles
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                        <th>Role</th>
                                        <th>TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($totalResourcesByRoles as $indexkey => $resource)
                                    <tr>
                                        <td><strong>{{ $resource->name }}</strong></td>
                                        <td><a href="{{ URL::to('admin/users?role='.$resource->roleid) }}">{{ $resource->total }}</a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <div class="row">
            <div class="col-lg-12">
                <!-- Example Bar Chart Card-->
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fa fa-list"></i> Total Users by Country
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                    <th>Country</th>
                                    <th>TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($totalUsersByCountry as $indexkey => $resource)
                                <tr>
                                    <td><strong>{{ $resource->country }}</strong></td>
                                    <td><a href="{{ URL::to('admin/users?country='.$resource->country) }}">{{ $resource->total }}</a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Start latest resources and users section -->
        <div class="row">
            <div class="col-lg-12">
                <!-- Example Bar Chart Card-->
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fa fa-list"></i> Total Resources by Language
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                    <th>LANGUAGE</th>
                                    <th>TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($totalResources as $indexkey => $resource)
                                <tr>
                                    <td><strong>{{ fixLanguage($resource->language) }}</strong></td>
                                    <td><a href="{{ URL::to('admin/resources/list/language/'.$resource->language) }}">{{ $resource->total }}</a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End latest resources and users section-->

        <!-- Start latest resources and users section -->
        <div class="row">
            <div class="col-lg-12">
                <!-- Example Bar Chart Card-->
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fa fa-list"></i> Total Resources by Subject Area
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                    <th>Subject Area</th>
                                    <th>Language</th>
                                    <th>TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($totalResourcesBySubject as $indexkey => $resource)
                                <tr>
                                    <td><a href="resource/view/?subject_area={{$resource->tid}}">{{ $resource->name }}</a></td>
                                    <td>{{ fixLanguage($resource->language) }}</td>
                                    <td><a href="{{ URL::to('admin/resources/list/subject_area/'.str_slug($resource->name)) }}">{{ $resource->total }}</a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <!-- Example Bar Chart Card-->
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fa fa-list"></i> Total Resources by Level
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                    <th>Level</th>
                                    <th>Language</th>
                                    <th>TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($totalResourcesByLevel as $indexkey => $resource)
                                <tr>
                                    <td><a href="resource/view/?level={{$resource->tid}}">{{ $resource->name }}</a></td>
                                    <td>{{ fixLanguage($resource->language) }}</td>
                                    <td><a href="{{ URL::to('admin/resources/list/level/'.str_slug($resource->name)) }}">{{ $resource->total }}</a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End latest resources and users section-->
    </div>

    <!-- Start latest resources and users section -->
    <div class="row">
        <div class="col-lg-12">
            <!-- Example Bar Chart Card-->
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-list"></i> Total Resources by Material Type
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                <th>Material Type</th>
                                <th>Language</th>
                                <th>TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($totalResourcesByType as $indexkey => $resource)
                            <tr>
                                <td><a href="resource/view/type={{$resource->name}}">{{ $resource->name }}</a></td>
                                <td>{{ fixLanguage($resource->language) }}</td>
                                <td><a href="{{ URL::to('admin/resources/list/type/'.str_slug($resource->name)) }}">{{ $resource->total }}</a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Start latest resources and users section -->
    <div class="row">
            <div class="col-lg-12">
                <!-- Example Bar Chart Card-->
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fa fa-list"></i> Total Resources by Format
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                    <th>Format</th>
                                    <th>Language</th>
                                    <th>TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($totalResourcesByFormat as $indexkey => $resource)
                                <tr>
                                    <td><a href="resource/view/{{ $resource->file_mime }}">{{ giveMeFileFormat($resource->file_mime) }}</a></td>
                                    <td>{{ fixLanguage($resource->language) }}</td>
                                    <td><a href="{{ URL::to('admin/resources/list/format/'.str_slug($resource->file_mime)) }}">{{ $resource->total }}</a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
<!-- /.container-fluid-->
<!-- /.content-wrapper-->
@endsection