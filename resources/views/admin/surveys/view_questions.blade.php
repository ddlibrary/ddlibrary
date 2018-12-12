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
        <li class="breadcrumb-item active">{{ $survey->name}}</li>
      </ol>

      <!-- Surveys Answers DataTables -->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-list"></i> Questions
        </div>

        <div class="card-body">
            <a href="/admin/survey/question/add/{{ $survey->id }}" class="btn btn-success pull-right" style="margin-bottom: 10px">
              <span class="fa fa-plus"></span> Add New
            </a>
            <span>Total: <strong>{{count($survey_questions)}}</strong></span>
            <table class="table table-bordered" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Text</th>
                  <th>OPERATIONS</th>
                </tr>
              </thead>

              <tbody>
                @foreach ($survey_questions as $indexkey => $survey_question)
                  <tr>
                    <td>{{ $survey_question-> text }}</td>
                    <td style="display: flex;">
                        <a href="/admin/survey/{{$survey->id}}/question/{{$survey_question->id}}/view_options" class="badge badge-primary" style="margin-right:5px;">Options</a>
                        <a href="survey/delete/{{$survey_question->id}}" class="badge badge-danger">Delete</a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
  @endsection
