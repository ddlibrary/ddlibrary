@extends('admin.layout')
@section('admin.content')
<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ URL::to('admin') }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">GA Reports</li>
        </ol>
        <div class="row">
            <div class="col-lg-12">
                <!-- Example Bar Chart Card-->
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fa fa-list"></i> Total Visitors and Pageviews for Last 30 Days
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                    <th>DATE</th>
                                    <th>VISITORS</th>
                                    <th>PAGE VIEWS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($totalVisitorsAndPageViews as $indexkey => $resource)
                                <tr>
                                    <td>{{ $resource['date'] }}</td>
                                    <td><strong>{{ $resource['visitors'] }}</strong></td>
                                    <td><strong>{{ $resource['pageViews'] }}</strong></td>
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
                        <i class="fa fa-list"></i> Most Visited Pages for Last 30 Days
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                    <th>URL</th>
                                    <th>PAGE TITLE</th>
                                    <th>PAGE VIEWS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($mostVisitedPages as $indexkey => $resource)
                                <tr>
                                    <td>{{ $resource['url'] }}</td>
                                    <td><strong>{{ $resource['pageTitle'] }}</strong></td>
                                    <td><strong>{{ $resource['pageViews'] }}</strong></td>
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
                        <i class="fa fa-list"></i> Top referrers for Last 30 Days
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                    <th>URL</th>
                                    <th>PAGE VIEWS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($topReferrers as $indexkey => $resource)
                                <tr>
                                    <td>{{ $resource['url'] }}</td>
                                    <td><strong>{{ $resource['pageViews'] }}</strong></td>
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
                        <i class="fa fa-list"></i> Users (type) for Last 30 Days
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                    <th>TYPE</th>
                                    <th>SESSIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($userTypes as $indexkey => $resource)
                                <tr>
                                    <td>{{ $resource['type'] }}</td>
                                    <td><strong>{{ $resource['sessions'] }}</strong></td>
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
                        <i class="fa fa-list"></i> Top Browsers for Last 30 Days
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                    <th>BROWSER</th>
                                    <th>SESSIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($topBrowsers as $indexkey => $resource)
                                <tr>
                                    <td>{{ $resource['browser'] }}</td>
                                    <td><strong>{{ $resource['sessions'] }}</strong></td>
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
</div>
<!-- /.container-fluid-->
<!-- /.content-wrapper-->
@endsection