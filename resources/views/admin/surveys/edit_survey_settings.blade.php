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
          <i class="fa fa-table"></i> Edit Popup Time
        </div>

        <div class="card-body">
          @include('layouts.messages')
          <form method="POST" action="{{ route('update_survey_modal_time', ['id' => $survey_modal_time->id]) }}">
            @csrf

            <div class="row">
                <div class="col-sm-6 offset-sm-3">

                  <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Popup Time</label>
                    <div class="col-sm-9">
                      <input type="number" class="form-control" id="time" value="{{$survey_modal_time->time}}" name="time" required="true" placeholder="Numbers Only">
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary pull-right btn-sm">@lang('Update')</button>
                  
              </div>
            </div>

          </form>

        </div>
      </div>
    </div>
  </div>
  <!-- /.container-fluid-->
  <!-- /.content-wrapper-->
  @endsection
