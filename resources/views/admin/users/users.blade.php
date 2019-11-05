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
        <a href="http://localhost:8000/user/create" style="float:right"><button class="btn btn-primary">+</button></a>
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
             {{-- <script>
              $(document).ready(function(){
                $.ajax({
                  url:'/api/show_users',
                  type:'get',
                  success:function(response){
                      
                        for(var i=0;i<response.data.length;i++){
                          if(response.data[i].role_id=='5'){
                          $('#showtblData').append('<tr>','<td>'+ i +'</td>','<td>'+response.data[i].username+'<br>'+response.data[i].email+'</td>','<td>'+response.data[i].status+'</td>','<td>Admin</td>','<td>'+new Date(response.data[i].created_at)+'</td>','<td>'+response.data[i].accessed_at+'</td>'
                          ,'<td><a href="user/edit/'+response.data[i].id+'">Edit</a> | <a id="deleted" href="#">Delete</a></td>','</tr>');
                          //alert(response.data[i].id)
                        }else{
                          $('#showtblData').append('<tr>','<td>'+ i +'</td>','<td>'+response.data[i].username+'<br>'+response.data[i].email+'</td>','<td>'+response.data[i].status+'</td>','<td>User</td>','<td>'+response.data[i].created_at+'</td>','<td>'+response.data[i].accessed_at+'</td>'
                            ,'<td><a href="user/edit/'+response.data[i].id+'">Edit</a> | <a onClick(del('+response.data[i].id+')) href="#">Delete</a></td>','</tr>');
                          
                        }
                        }
                      
                  },
              });
              
            })
        
             
             
            </script>  --}}
            <tbody id="showtblData">
            @foreach ($users as $indexkey => $user)
              <tr>
                <td id="id">{{ (($users->currentPage() - 1) * $users->perPage())+$indexkey + 1 }}</td>
                <td><a href="{{URL::to('user/'.$user->id) }}">{{ $user->username }}</a><br>{{ $user->email }}</td>
                <td>{{ ($user->status==0?"Not Active":"Active") }}</td>
                <td>{{ $user->all_roles }}</td>
                <td>{{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}</td>
                <td>{{ \Carbon\Carbon::parse($user->accessed_at)->diffForHumans() }}</td>
                <td>
                  <a href="user/edit/{{$user->id}}">Edit</a> | 
                  <a id="delButton" href="#" onclick="return del({{ $user->id }})">Delete</a>
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
  <script>
  function del(id){
    $.ajax({
      url:'/api/delete_user/'+id,
      type:'get',
      success:function(response){
          alert('the selected user deleted successfully');
          window.location.replace('http://localhost:8000/en/admin/users');          
      },
  });
  }
</script>
  @endsection
  