@extends('admin.layout')
@section('admin.content')
<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="#">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">Sync</li>
    </ol>
    <!-- Example DataTables Card-->
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i> Sync Status</div>
      <div class="card-body">
          @include('layouts.messages')
          <div class="form-group">
            <strong>New or updated Resources: </strong>{{ $countResourceRecords }}
          </div>
          <div class="form-group">
            <strong>New or updated Images: </strong>{{ $countDdlFileRecords }}
          </div>
          <div class="form-group">
            <strong>New or updated News: </strong>{{ $countNewsRecords }}
          </div>
          <div class="form-group">
            <strong>New or updated Pages: </strong>{{ $countPageRecords }}
          </div>
          <div class="form-group">
            <strong>New or updated Taxonomy: </strong>{{ $countTaxonomyTermRecords }}
          </div>
        <input class="btn btn-primary" type="button" value="Check Sync Status" onclick="location.href='{{ URL::to('admin/sync') }}'">
        <input class="btn btn-primary" type="button" value="Run Sync" onclick="location.href='{{ URL::to('admin/run_sync') }}'">
      </div>
    </div>
  </div>
  <!-- /.container-fluid-->
  <!-- /.content-wrapper-->
  @endsection