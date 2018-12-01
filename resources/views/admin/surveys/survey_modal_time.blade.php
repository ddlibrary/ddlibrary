@extends('admin.layout')

@section('admin.content')
  <div class="content-wrapper">
    <div class="container-fluid">

      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{ URL::to('admin') }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Survey Pop Up Time</li>
      </ol>

      <!-- Surveys Answers DataTables -->
      <div class="card mb-3">

        <div class="card-header">
          <i class="fa fa-table"></i> Pop Up Time
        </div>

        @if(isset($survey_modal_time))
          <a href="{{ URL::to('page/create') }}"><button class="btn btn-success pull-right" style="margin: 10px;">Update</button></a>
        @else
          <a href="{{ URL::to('page/create') }}"><button class="btn btn-success pull-right" style="margin: 10px;">Create One</button></a>
        @endif

        <div class="card-body">
          @if(isset($survey_modal_time))
            <p>Time in seconds: <span class="badge badge-primary">10</span></p> 
          @else
            <p class="badge badge-warning">Not defined yet!</p> 
          @endif

          
        </div>

      </div>
    </div>
  </div>
  <!-- /.container-fluid-->
  <!-- /.content-wrapper-->
  @endsection
