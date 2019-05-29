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
                  @if ($question->type == "descriptive")
                    <th>Answers</th>

                  @else
                    <th>Options</th>
                    <th>Language</th>
                    <th>Count</th>
                  @endif
                </tr>
              </thead>

              <tbody>
                @if ($question->type != "descriptive")
                  @foreach (\App\SurveyAnswer::getAnswersByOption($question->id) as $option)
                    <tr>
                      <td>{{ $option->text }}</td>
                      <td>{{ fixLanguage($option->language) }}</td>
                      <td>{{ $option->total }}</td>
                    </tr>
                  @endforeach
                @else
                  @foreach ($descriptive_answers as $answer)
                  @if(!empty ($answer->description))
                    <tr>
                      <td>{{ $answer->description }}</td>
                    </tr>
                  @endif
                  @endforeach
                @endif

              </tbody>
            </table>
        </div>

      </div>
    </div>
  </div>
  <!-- /.container-fluid-->
  <!-- /.content-wrapper-->
  @endsection
