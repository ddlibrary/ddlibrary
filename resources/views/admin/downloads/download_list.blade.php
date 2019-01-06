@extends('admin.layout')
@section('admin.content')
<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="{{ URL::to('admin') }}">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">Downloads</li>
    </ol>
    <!-- Example DataTables Card-->
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i> All Downloads</div>
      <div class="card-body">
        <div class="table-responsive">
        <form method="POST" action="{{ route('downloads') }}">
        @csrf
        <table class="table table-bordered" width="100%" cellspacing="0">
            <tr>
            <td>from</td>
            <td>
                <input class="form-control" type="date" name="date_from" value="{{ isset($filters['date_from'])?$filters['date_from']:"" }}">
            </td>
            <td>to</td>
            <td>
                <input class="form-control" type="date" name="date_to" value="{{ isset($filters['date_to'])?$filters['date_to']:"" }}">
            </td>
            <td colspan="2">
                <input class="btn btn-primary float-right" type="submit" value="Filter">
            </td>
            </tr>
        </table>
        </form>
          <span class="pull-left">Total: <strong>{{ $records->total() }}</strong></span>
          <table class="table table-bordered" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>NO</th>
                <th>RESOURCE</th>
                <th>FILE</th>
                <th>USERID</th>
                <th>IP ADDRESS</th>
                <th>VISITED</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>NO</th>
                <th>RESOURCE</th>
                <th>FILE</th>
                <th>USERID</th>
                <th>IP ADDRESS</th>
                <th>VISITED</th>
              </tr>
            </tfoot>
            <tbody>
            @foreach ($records as $indexkey => $record)
              <tr>
                <td>{{ (($records->currentPage() - 1) * $records->perPage())+$indexkey + 1 }}</td>
                <td><a href="{{ URL::to($record->resource->language.'/'.'resource/'.$record->resource->id) }}">{{ $record->resource->title }}</a></td>
                <td><a href="{{ URL::to($record->resource->language.'/'.'resource/'.$record->resource->id) }}">{{ $record->file->file_name ?? "-" }}</a></td>
                @if(count($record->user))
                <td><a href="{{ URL::to('user/'.$record->user->id) }}">{{ $record->user->username }}</a></td>
                @else
                <td>{{ $record->user_id }}</td>
                @endif
                <td>{{ $record->ip_address }}</td>
                <td>{{ $record->created_at->diffForHumans() }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        {{ $records->links() }}
      </div>
    </div>
  </div>
  <!-- /.container-fluid-->
  <!-- /.content-wrapper-->
  @endsection