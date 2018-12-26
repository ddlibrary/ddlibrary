@extends('admin.layout')

@section('admin.content')
  <div class="content-wrapper">
    <div class="container-fluid">

      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{ URL::to('admin') }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item">
          <a href="{{ URL::to('admin/surveys') }}">Surveys</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ URL::to('admin/survey/questions/'.$survey->id) }}">Survey's Questions</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ URL::to('admin/survey/'.$survey->id.'/question/'.$question->id.'/view_options') }}">Question's Options</a>
        </li>

        <li class="breadcrumb-item active">Create Option</li>
      </ol>

      <!-- Surveys Answers DataTables -->
      <div class="card mb-3">

        <div class="card-header">
          <i class="fa fa-plus"></i> Create Option
        </div>

        <div class="card-body">
          @include('layouts.messages')
          <form method="POST" action="{{ route('create_option')}}">
            @csrf

            <div class="row">
              <div class="col-sm-6 offset-sm-3">

                <div class="form-group row">
                  <label for="name" class="col-sm-2 col-form-label">Option</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="text" name="text" required="true" placeholder="Text">
                    <input type="integer" name="question_id" value="{{$question->id}}" hidden>
                  </div>
                </div>
                <button type="submit" class="btn btn-primary pull-right"> Create</button>
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
