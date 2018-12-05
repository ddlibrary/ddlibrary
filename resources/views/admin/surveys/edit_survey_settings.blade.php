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
          <i class="fa fa-table"></i> Edit Pop Up Time
        </div>

        <div class="card-body">
          <form method="POST" action="{{ route('update_survey_modal_time', ['id' => $survey_modal_time->id]) }}">
            @csrf
            <div style="display: flex;">
              <div style="margin-right: 40px;">
                <label class="control-label">@lang('Pop Up Time:')</label>
              </div>
              <div>
                <input type="number" class="form-control" id="time" name="time" value="{{$survey_modal_time->time}}" required>
              </div>
            </div>
            <button class="btn btn-success btn-sm" type="submit" style="margin-left: 265px;margin-top: 10px;"> @lang('Update')</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- /.container-fluid-->
  <!-- /.content-wrapper-->
  @endsection
