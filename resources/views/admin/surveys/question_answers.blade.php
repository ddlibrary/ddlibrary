@extends('admin.layout')

@section('admin.content')
  <div class="content-wrapper">
    <div class="container-fluid">

      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{ URL::to('admin') }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ URL::to('admin/survey_questions') }}">Survey Results</a></li>
        <li class="breadcrumb-item active">Survey Question</li>
      </ol>

      <!-- Surveys Answers DataTables -->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-list"></i> Question Answers Summary
        </div>

        <div class="card-body">
          <h3 class="badge badge-primary">Question: {{ $question->text }}</h3>

          <table class="table table-bordered" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Answer Option</th>
                  <th>Answers Count</th>
                </tr>
              </thead>

              <tbody>
                @foreach ($survey_question_options as $indexkey => $survey_question_option)
                  <tr>
                    <td>{{ $survey_question_option-> text }}</td>
                    <td>{{ count(\App\SurveyAnswer::where(['question_id'=> $survey_question_option -> question_id, 'answer' => $survey_question_option->id])->get()) }} </td>
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
