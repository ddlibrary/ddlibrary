@extends('admin.layout')
@section('admin.content')
 <!-- Begin Page Content -->
 <div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
      <a href="{{ URL::to('admin') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> My Dashboard</a>
    </div>

    <!-- Content Row -->
    <div class="row">

      <!-- Earnings (Monthly) Card Example -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Users</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-user fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Resources</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalResources }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Pages</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPages }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total News</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalNews }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
    <!-- End Statistics -->
    <!-- Start latest resources and users section -->
    <div class="row">
      <div class="col-lg-6">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
          <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Latest Resources</h6>
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
            <h6 class="m-0 font-weight-bold text-primary">Latest Users</h6>
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
            <h6 class="m-0 font-weight-bold text-primary">Latest Pages</h6>
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
            <h6 class="m-0 font-weight-bold text-primary">Latest News</h6>
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
  <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
@endsection