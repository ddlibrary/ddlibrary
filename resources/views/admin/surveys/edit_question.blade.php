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
          <a href="{{ URL::to('admin/surveys') }}">Survey</a>
        </li>
        <li class="breadcrumb-item">
          <a href="{{ URL::to('admin/survey/questions/'.$survey->id) }}">Questions</a>
        </li>
        <li class="breadcrumb-item active">Edit</li>
      </ol>

      <!-- Surveys Answers DataTables -->
      <div class="card mb-3">

        <div class="card-header">
          <i class="fa fa-pencil-square-o"></i> Edit Question
        </div>

        <div class="card-body">
          @if (session('status'))
              <div class="alert alert-success">
                  {{ session('status') }}
              </div>
          @endif
          <form method="POST" action="{{ route('update_question', ['id' => $question->id]) }}">
            @csrf

            <div class="row">
              <div class="col-sm-6 offset-sm-3">

                <div class="form-group row">
                  <label for="name" class="col-sm-2 col-form-label">Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="name" name="text" value="{{$question->text}}" required="true" placeholder="Name">
                  </div>
                </div>
                <button type="submit" class="btn btn-primary pull-right"><span class="fa fa-download"></span> Update</button>
                
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
