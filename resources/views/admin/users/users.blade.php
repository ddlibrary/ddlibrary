@extends('admin.layout')
@section('admin.content')
<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="{{ URL::to('admin') }}">Dashboard</a>
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
                      <option value="{{ $role->id}}" {{ (isset($filters['role']) && $filters['role'] == $role->id)?"selected":"" }}>{{ $role->name }}</option>
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
          <span>Total: <strong>{{ $users->total() }}</strong></span>
          <button type="button" class="btn btn-link float-right"><a href="{{ URL::to('admin/user/export') }}">Exporting Users</a></button>
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
                <td>{{ (($users->currentPage() - 1) * $users->perPage())+$indexkey + 1 }}</td>
                <td>
                  {{-- <a href="{{URL::to('user/'.$user->id) }}">{{ $user->username }}</a> We currently don't have user profiles resulting in a 404.--}}
                  <span style="color: #4e73df;">{{ $user->username }}</span><br>
                  @if ($user->email)
                    {{ $user->email }}
                  @else
                    {{ __('No email in file.') }}
                  @endif
                </td>
                <td>{{ ($user->status==0?"Not Active":"Active") }}</td>
                <td>{{ $user->all_roles }}</td>
                <td>{{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}</td>
                <td>{{ \Carbon\Carbon::parse($user->accessed_at)->diffForHumans() }}</td>
                <td>
                  <a href="user/edit/{{$user->id}}">Edit</a> | 
                  <a href="user/delete/{{$user->id}}" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                </td>
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
