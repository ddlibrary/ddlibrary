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
                        <i class="fa fa-list"></i> Update user details for <strong>{{ $user->username }}</strong>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <tbody>
                                <tr>
                                    <td><strong>Username</strong></td>
                                    <td><input type="text" name="name" class="form-control" value="{{ $user->username }}"></td>
                                </tr>
                                <tr>
                                    <td><strong>Email</strong></td>
                                    <td><input type="text" name="name" class="form-control" value="{{ $user->email }}"></a></td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td>
                                        <select name="status">
                                            <option value="0" {{ ($user->status==1?"selected":"") }}>Active</option>
                                            <option value="1" {{ ($user->status==0?"selected":"") }}>Not Active</option>
                                        </select>
                                    </td>
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
                            <input class="btn btn-outline-dark" type="button" onclick="location.href='{{ URL::to('admin/users/update/'.$user->id) }}'" value="Update">
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