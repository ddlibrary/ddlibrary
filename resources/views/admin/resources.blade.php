@extends('admin.layout')
@section('admin.content')
<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="#">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">Resources</li>
    </ol>
    <!-- Example DataTables Card-->
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i> All Resources</div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>NO</th>
                <th>TITLE</th>
                <th>ADDED BY</th>
                <th>PUBLISHED</th>
                <th>UPDATED</th>
                <th>LANGUAGE</th>
                <th>OPERATIONS</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>NO</th>
                <th>TITLE</th>
                <th>ADDED BY</th>
                <th>PUBLISHED</th>
                <th>UPDATED</th>
                <th>LANGUAGE</th>
                <th>OPERATIONS</th>
              </tr>
            </tfoot>
            <tbody>
            @foreach ($resources as $indexkey => $resource)
              <tr>
                <td>{{ (($resources->currentPage() - 1) * 50)+$indexkey + 1 }}</td>
                <td><a href="{{URL::to('resources/view/'.$resource->resourceid) }}">{{ $resource->title }}</a></td>
                <td><a href="{{ URL::to('users/view/'.$resource->userid) }}">{{ $resource->author }}</a></td>
                <td>{{ ($resource->status==0?"Not Published":"Published") }}</td>
                <td>{{ Carbon\Carbon::createFromTimestamp($resource->updated) }}</td>
                <td>{{ fixLanguage($resource->language) }}</td>
                <td><a href="resources/edit/{{$resource->resourceid}}">Edit</a></td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        {{ $resources->links() }}
      </div>
    </div>
  </div>
  <!-- /.container-fluid-->
  <!-- /.content-wrapper-->
  @endsection
