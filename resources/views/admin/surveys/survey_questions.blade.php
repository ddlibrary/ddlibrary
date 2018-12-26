@extends('admin.layout')

@section('admin.content')
  <div class="content-wrapper">
    <div class="container-fluid">

      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{ URL::to('admin') }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Surveys Results</li>
      </ol>

      <!-- Surveys Answers DataTables -->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-list"></i> Survey Questions
        </div>

        <div class="card-body">
            <span>Total: <strong>{{count($survey_questions)}}</strong></span>
            <table class="table table-bordered" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Question</th>
                  <th>Survey Name</th>
                  <th>OPERATIONS</th>
                </tr>
              </thead>

              <tfoot>
                <tr>
                  <th>Question</th>
                  <th>Survey</th>
                  <th>OPERATIONS</th>
                </tr>
              </tfoot>

              <tbody>
                @foreach ($survey_questions as $indexkey => $survey_question)
                  <tr>
                    <td>{{ $survey_question-> text }}</td>
                    <td>{{\App\Survey::find($survey_question->survey_id)->name }}</td>
                    <td><a href="{{ URL::to('admin/survey_question/answers/'.$survey_question->id) }}" class="badge badge-success">View Answers</a></td>
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
