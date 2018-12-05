@extends('admin.layout')

@section('admin.content')
  <div class="content-wrapper">
    <div class="container-fluid">

      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{ URL::to('admin') }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Survey Settings</li>
      </ol>

      <!-- Surveys Answers DataTables -->
      <div class="card mb-3">

        <div class="card-header">
          <i class="fa fa-table"></i> Pop Up Time
        </div>

        <div class="card-body">
          @if(isset($survey_modal_time))
            <p>Pop Up Time: <span class="badge badge-primary">{{ $survey_modal_time->time }} Seconds</span></p> 
          @else
            <p class="badge badge-warning">Not defined yet!</p> 
          @endif
        </div>

        @if(isset($survey_modal_time))
          <a href="{{ URL::to('admin/edit_survey_modal_time') }}"><button class="btn btn-success btn-sm" style="margin: 10px;">Edit</button></a>
        @else
          <a href="{{ URL::to('admin/create_survey_modal_time') }}"><button class="btn btn-success btn-sm" style="margin: 10px;">Create</button></a>
        @endif

      </div>
    </div>
  </div>
  <!-- /.container-fluid-->
  <!-- /.content-wrapper-->
  @endsection
