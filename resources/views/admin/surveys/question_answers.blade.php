@extends('admin.layout')

@section('admin.content')
  <div class="content-wrapper">
    <div class="container-fluid">

      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{ URL::to('admin') }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ URL::to('admin/survey_questions') }}">Survey Result</a></li>
        <li class="breadcrumb-item active">Survey Question</li>
      </ol>

      <!-- Surveys Answers DataTables -->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Question Answers Summary
        </div>

        <div class="card-body">
          <h5>{{ $question->text }}</h5>

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
