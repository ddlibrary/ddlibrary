@extends('admin.layout')
@section('admin.content')
<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ URL::to('admin') }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">DDL Reports</li>
        </ol>
        <div class="row">
            <div class="col-lg-12">
                <!-- Example Bar Chart Card-->
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fa fa-list"></i> DDL Analytics
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <form method="POST" action="{{ route('analytics') }}">
                            @csrf
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <tr>
                                    <td>Source</td>
                                    <td>
                                        <select class="form-control" name="source">
                                            <option value="">- Choose -</option>
                                            <option value="ddl">DDL</option>
                                            <option value="ddl">Google Analytics</option>
                                            <option value="ddl">Facebook</option>
                                            <option value="ddl">Twitter</option>
                                        </select>
                                    </td>
                                    <td>From</td>
                                    <td>
                                        <input class="form-control" type="date" name="date">
                                    </td>
                                    <td>To</td>
                                    <td>
                                        <input class="form-control" type="date" name="date">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Type</td>
                                    <td>
                                        <select class="form-control" name="type">
                                            <option value="">- Choose -</option>
                                            <option value="ddl">Gender</option>
                                            <option value="ddl">Top Downloaded Resources</option>
                                            <option value="ddl">Top Viewed Resources</option>
                                            <option value="ddl">Total Users by Roles</option>
                                            <option value="ddl">Total Users by Country</option>
                                            <option value="ddl">Total Resources by Language</option>
                                            <option value="ddl">Total Resources by Subject Area</option>
                                            <option value="ddl">Total Resources by Level</option>
                                            <option value="ddl">Total Resources by Material Type</option>
                                            <option value="ddl">Total Resources by Format</option>
                                        </select>
                                    </td>

                                    <td>Language</td>
                                    <td>
                                        <select class="form-control" name="language">
                                            <option value="">- Choose -</option>
                                            @foreach(LaravelLocalization::getSupportedLocales() as $localcode => $properties)
                                            <option value="{{ $localcode }}" {{ (isset($filters['language']) && $filters['language'] == $localcode)?"selected":"" }}>{{ $properties['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td colspan="2">
                                        <input class="btn btn-primary float-right" type="submit" value="Generate">
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid-->
<!-- /.content-wrapper-->
@endsection