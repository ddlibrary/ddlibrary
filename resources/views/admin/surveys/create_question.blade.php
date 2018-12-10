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
            <a href="/admin/survey/questions/{{$survey->id}}">Survey's Questions</a>
        </li>

        <li class="breadcrumb-item active">Create Question</li>
      </ol>

      <!-- Surveys Answers DataTables -->
      <div class="card mb-3">

        <div class="card-header">
          <i class="fa fa-plus"></i> Create Question
        </div>

        <div class="card-body">
          @if (session('status'))
              <div class="alert alert-success">
                  {{ session('status') }}
              </div>
          @endif
          <form method="POST" action="{{ route('create_question')}}">
            @csrf

            <div class="row">
              <div class="col-sm-6 offset-sm-3">

                <div class="form-group row">
                  <label for="name" class="col-sm-2 col-form-label">Question</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="question" name="question" required="true" placeholder="Text">
                    <input type="integer" name="survey_id" value="{{$survey->id}}" hidden>
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
