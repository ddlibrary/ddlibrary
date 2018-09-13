@extends('admin.layout')
@section('admin.content')
<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="{{ URL::to('admin') }}">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">Contacts</li>
    </ol>
    <!-- Example DataTables Card-->
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i> All Contacts</div>
      <div class="card-body">
        @include('layouts.messages')
        <div class="table-responsive">
          <span class="pull-left">Total: <strong>{{ $records->total() }}</strong></span>
          <table class="table table-bordered" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>NO</th>
                <th>NAME</th>
                <th>EMAIL</th>
                <th>SUBJECT</th>
                <th>MESSAGE</th>
                <th>CREATED</th>
                <th>ACTION</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>NO</th>
                <th>NAME</th>
                <th>EMAIL</th>
                <th>SUBJECT</th>
                <th>MESSAGE</th>
                <th>CREATED</th>
                <th>ACTION</th>
              </tr>
            </tfoot>
            <tbody>
            @foreach ($records as $indexkey => $record)
              <tr>
                <td>{{ (($records->currentPage() - 1) * $records->perPage())+$indexkey + 1 }}</td>
                @if($record->read)
                <td>{{ $record->name }}</td>
                @else
                <td><strong>{{ $record->name }}</strong></td>
                @endif
                <td><a href="mailto:{{ $record->email }}"> {{ $record->email }}</a></td>
                <td>{{ $record->subject }}</td>
                <td>{{ $record->message }}</td>
                <td>{{ $record->created_at->diffForHumans() }}</td>
                <td><a href="{{ URL::to('admin/contacts/read/'.$record->id) }}">Read</a> | <a href="{{ URL::to('admin/contacts/delete/'.$record->id) }}">Delete</a></td>
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