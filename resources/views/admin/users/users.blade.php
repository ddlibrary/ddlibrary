@extends('admin.layout')
@section('admin.content')
<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="#">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">Users</li>
    </ol>
    <!-- Example DataTables Card-->
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i> All Users
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <form method="POST" action="{{ route('user') }}">
          @csrf
          <table class="table table-bordered" width="100%" cellspacing="0">
            <tr>
              <td>Username</td>
              <td>
                <input class="form-control" type="text" name="username" value="{{ isset($filters['username'])?$filters['username']:"" }}">
              </td>
              <td>Email</td>
              <td>
                <input class="form-control" type="text" name="email" value="{{ isset($filters['email'])?$filters['email']:"" }}">
              </td>

                <td>Active</td>
                <td>
                  <select class="form-control" name="status">
                    <option value="">Any</option>
                    <option value="1" {{ (isset($filters['status']) && $filters['status'] == 1)?"selected":"" }}>Yes</option>
                    <option value="0" {{ (isset($filters['status']) && $filters['status'] == 0)?"selected":"" }}>No</option>
                  </select>
                </td>
                <td>Role</td>
                <td>
                  <select class="form-control" name="role">
                    <option value="">Any</option>
                    @foreach($roles as $role)
                      <option value="{{ $role->roleid}}" {{ (isset($filters['role']) && $filters['role'] == $role->roleid)?"selected":"" }}>{{ $role->name }}</option>
                    @endforeach
                  </select>
                </td>
                <td colspan="2">
                    <input class="btn btn-primary float-right" type="submit" value="Filter">
                </td>
            </tr>
          </table>
          </form>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>NO</th>
                <th>NAME</th>
                <th>ACTIVE</th>
                <th>ROLES</th>
                <th>MEMBER FOR</th>
                <th>LAST ACCESS</th>
                <th>OPERATIONS</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>NO</th>
                <th>NAME</th>
                <th>ACTIVE</th>
                <th>ROLES</th>
                <th>MEMBER FOR</th>
                <th>LAST ACCESS</th>
                <th>OPERATIONS</th>
              </tr>
            </tfoot>
            <tbody>
            @foreach ($users as $indexkey => $user)
              <tr>
                <td>{{ (($users->currentPage() - 1) * 50)+$indexkey + 1 }}</td>
                <td><a href="{{URL::to('users/view/'.$user->id) }}">{{ $user->username }}</a><br>{{ $user->email }}</td>
                <td>{{ ($user->status==0?"Not Active":"Active") }}</td>
                <td>{{ $user->all_roles }}</td>
                <td>{{ Carbon\Carbon::createFromTimestamp($user->created)->diffForHumans() }}</td>
                <td>{{ Carbon\Carbon::createFromTimestamp($user->access)->diffForHumans() }}</td>
                <td><a href="users/edit/{{$user->id}}">Edit</a></td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        {{ $users->appends(request()->input())->links() }}
      </div>
    </div>
  </div>
  <!-- /.container-fluid-->
  <!-- /.content-wrapper-->
  @endsection
