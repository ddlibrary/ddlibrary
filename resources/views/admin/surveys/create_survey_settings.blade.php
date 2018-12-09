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
          <i class="fa fa-table"></i> Create Pop Up Time
        </div>

        <div class="card-body">
          @if (session('status'))
              <div class="alert alert-success">
                  {{ session('status') }}
              </div>
          @endif
          <form method="POST" action="{{ route('store_survey_modal_time') }}">
            @csrf
            <div class="form-group row">
              <label for="name" class="col-sm-2 col-form-label">Pop Up Time</label>
              <div class="col-sm-10">
                <input type="number" class="form-control" id="time" name="time" required="true" placeholder="Time in seconds">
              </div>
            </div>
            <button type="submit" class="btn btn-primary pull-right">Create</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- /.container-fluid-->
  <!-- /.content-wrapper-->
  @endsection
