@extends('admin.layout')
@section('admin.content')
<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="#">Users</a>
        </li>
        <li class="breadcrumb-item active">Users Details</li>
        </ol>
        <div class="row">
            <div class="col-lg-12">
                <!-- Example Bar Chart Card-->
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fa fa-list"></i> User details for <strong>{{ $user->name }}</strong>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <tbody>
                                    <tr>
                                        
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <tbody>
                                <tr>
                                    <td><strong>Username</strong></td>
                                    <td>{{ $user->name }}</a></td>
                                </tr>
                                <tr>
                                    <td><strong>Email</strong></td>
                                    <td>{{ $user->email }}</a></td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td>{{ ($user->status==0?"Not Active":"Active") }}</a></td>
                                </tr>
                                <tr>
                                    <td><strong>Created</strong></td>
                                    <td>{{ Carbon\Carbon::createFromTimestamp($user->created)->diffForHumans() }}</a></td>
                                </tr>
                                <tr>
                                    <td><strong>Access</strong></td>
                                    <td>{{ Carbon\Carbon::createFromTimestamp($user->access)->diffForHumans() }}</a></td>
                                </tr>
                                </tbody>
                            </table>
                            <input class="btn btn-outline-dark" type="button" onclick="location.href='{{ URL::to('admin/users/update/'.$user->userid) }}'" value="Update">
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