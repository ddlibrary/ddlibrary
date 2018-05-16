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
        <i class="fa fa-table"></i> All Users</div>
      <div class="card-body">
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
                <td><a href="users/view/{{$user->id}}">{{ $user->username }}</a><br>{{ $user->email }}</td>
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
        {{ $users->links() }}
      </div>
    </div>
  </div>
  <!-- /.container-fluid-->
  <!-- /.content-wrapper-->
  @endsection
