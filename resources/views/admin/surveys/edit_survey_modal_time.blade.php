@extends('admin.layout')

@section('admin.content')
  <div class="content-wrapper">
    <div class="container-fluid">

      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{ URL::to('admin') }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Edit Survey Pop Up Time</li>
      </ol>

      <!-- Surveys Answers DataTables -->
      <div class="card mb-3">

        <div class="card-header">
          <i class="fa fa-table"></i> Edit
        </div>

        <div class="card-body">
          <form method="POST" style="width: 20%" action="{{ route('update_survey_modal_time', ['id' => $survey_modal_time->id]) }}">
            @csrf
            <div class="form-group">
                <label class="control-label">@lang('Time in seconds')</label>
                <input type="number" class="form-control" id="time" name="time" value="{{$survey_modal_time->time}}" required>
            </div>
            <button class="btn btn-success bottom_buttons btn-sm" type="submit"> @lang('Update')</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- /.container-fluid-->
  <!-- /.content-wrapper-->
  @endsection
