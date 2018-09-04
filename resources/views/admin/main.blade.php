@extends('admin.layout')
@section('admin.content')
<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="{{ URL::to('admin') }}">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">My Dashboard</li>
    </ol>
    @if(env('DDL_LITE')=='no')
    <!-- Icon Cards-->
    <div class="row">
      <div class="col-xl-3 col-sm-6 mb-3">
        <div class="card text-white bg-primary o-hidden h-100">
          <div class="card-body">
            <div class="card-body-icon">
              <i class="fa fa-fw fa-users"></i>
            </div>
            <div class="mr-5">{{ $totalUsers }} Users!</div>
          </div>
          <a class="card-footer text-white clearfix small z-1" href="{{ URL::to('admin/users') }}">
            <span class="float-left">View Details</span>
            <span class="float-right">
              <i class="fa fa-angle-right"></i>
            </span>
          </a>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 mb-3">
        <div class="card text-white bg-success o-hidden h-100">
          <div class="card-body">
            <div class="card-body-icon">
              <i class="fa fa-fw fa-list"></i>
            </div>
            <div class="mr-5">{{ $totalResources }} Resources!</div>
          </div>
          <a class="card-footer text-white clearfix small z-1" href="{{ URL::to('admin/resources') }}">
            <span class="float-left">View Details</span>
            <span class="float-right">
              <i class="fa fa-angle-right"></i>
            </span>
          </a>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 mb-3">
        <div class="card text-white bg-info o-hidden h-100">
          <div class="card-body">
            <div class="card-body-icon">
              <i class="fa fa-fw fa-newspaper-o"></i>
            </div>
            <div class="mr-5">{{ $totalPages }} Pages!</div>
          </div>
          <a class="card-footer text-white clearfix small z-1" href="{{ URL::to('admin/pages') }}">
            <span class="float-left">View Details</span>
            <span class="float-right">
              <i class="fa fa-angle-right"></i>
            </span>
          </a>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 mb-3">
        <div class="card text-white bg-danger o-hidden h-100">
          <div class="card-body">
            <div class="card-body-icon">
              <i class="fa fa-fw fa-globe"></i>
            </div>
            <div class="mr-5">{{ $totalNews }} News!</div>
          </div>
          <a class="card-footer text-white clearfix small z-1" href="{{ URL::to('admin/news') }}">
            <span class="float-left">View Details</span>
            <span class="float-right">
              <i class="fa fa-angle-right"></i>
            </span>
          </a>
        </div>
      </div>
    </div>
    @endif
    <!-- End Statistics -->
    <!-- Start latest resources and users section -->
    <div class="row">
      <div class="col-lg-6">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fa fa-list"></i> Latest Resources
          </div>
          <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>TITLE</th>
                      <th>ADDED BY</th>
                      <th>STATUS</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach ($latestResources as $indexkey => $resource)
                  <tr>
                      <td><a href="{{ URL::to($resource->language.'/'.'resource/'.$resource->id) }}">{{ $resource->title }}</a></td>
                      <td><a href="{{ URL::to('user/'.$resource->user_id) }}">{{ $resource->user->username }}</a></td>
                      <td>{{ ($resource->status==0?"Not Published":"Published") }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <!-- Example Pie Chart Card-->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fa fa-users"></i> Latest Users
          </div>
          <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>NAME</th>
                      <th>ACTIVE</th>
                      <th>ROLES</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach ($latestUsers as $indexkey => $user)
                    <tr>
                      <td><a href="{{ URL::to('user/'.$user->id) }}">{{ $user->username }}</a></td>
                      <td>{{ ($user->status==0?"Not Active":"Active") }}</td>
                      <td>
                        @if(count($user->role))
                        {{ $user->role->role->name }}
                        @endif
                      </td>
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
      <div class="col-lg-6">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fa fa-list"></i> Latest Pages</div>
            <div class="card-body">
              <div class="table-responsive">
                  <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>TITLE</th>
                        <th>CREATED</th>
                        <th>UPDATED</th>
                      </tr>
                    </thead>
                    <tbody>
                    @foreach ($latestPages as $indexkey => $page)
                      <tr>
                        <td><a href="{{ URL::to($page->language.'/'.'page/'.$page->id) }}">{{ $page->title }}</a></td>
                        <td>{{ $page->created_at->diffForHumans() }}</td>
                        <td>{{ $page->updated_at->diffForHumans() }}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
            </div>
        </div>
      </div>
      <div class="col-lg-6">
        <!-- Example Pie Chart Card-->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fa fa-users"></i> Latest News
          </div>
            <div class="card-body">
              <div class="table-responsive">
                  <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>TITLE</th>
                        <th>CREATED</th>
                        <th>UPDATED</th>
                      </tr>
                    </thead>
                    <tbody>
                    @foreach ($latestNews as $indexkey => $news)
                      <tr>
                        <td><a href="{{ URL::to($news->language.'/'.'news/'.$news->id) }}">{{ $news->title }}</a></td>
                        <td>{{ $news->created_at->diffForHumans() }}</td>
                        <td>{{ $news->updated_at->diffForHumans() }}</td>
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
</div>
<!-- /.container-fluid-->
<!-- /.content-wrapper-->
@endsection