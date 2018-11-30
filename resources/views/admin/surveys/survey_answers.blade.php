@extends('admin.layout')

@section('admin.content')
  <div class="content-wrapper">
    <div class="container-fluid">

      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{ URL::to('admin') }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Surveys</li>
      </ol>

      <!-- Surveys Answers DataTables -->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> All Survey Answers
        </div>

        <div class="card-body">
          <div class="table-responsive">
              <form method="POST">
                @csrf
                <table class="table table-bordered" width="100%" cellspacing="0">
                  <tr>
                    <td>Title</td>
                    <td>
                      <input class="form-control" type="text" name="title">
                    </td>
            
                    <td>
                      <select class="form-control" name="status">
                        <option value="">Any</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                      </select>
                    </td>
                    <td>Answer</td>
                    <td>
                      <select class="form-control" name="language">
                        <option value="">Any</option>
                      </select>
                    </td>
                    <td colspan="2">
                        <input class="btn btn-primary float-right" type="submit" value="Filter">
                    </td>
                  </tr>
                </table>
              </form>
            </div>
            <span>Total: <strong>{{count($survey_answers)}}</strong></span>

            <table class="table table-bordered" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Question</th>
                  <th>Answer</th>
                  <th>Created</th>
                  <th>OPERATIONS</th>
                </tr>
              </thead>

              <tfoot>
                <tr>
                  <th>Question</th>
                  <th>Answer</th>
                  <th>Created</th>
                  <th>OPERATIONS</th>
                </tr>
              </tfoot>

              <tbody>
                @foreach ($survey_answers as $indexkey => $survey_answer)
                  <tr>
                    <td>{{\App\SurveyQuestion::find($survey_answer->question_id)->text}}</td>
                    <td>{{\App\SurveyQuestionOption::find($survey_answer->answer)->text}}</td>
                    <td>{{ \Carbon\Carbon::parse($survey_answer->created_at)->diffForHumans() }}</td>
                    <td><a href="survey_answer/edit/{{$survey_answer->id}}">Edit</a></td>
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
