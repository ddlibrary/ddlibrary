@extends('admin.layout')

@section('admin.content')
  <div class="content-wrapper">
    <div class="container-fluid">

      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{ URL::to('admin') }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Survey</li>
      </ol>

      <!-- Surveys Answers DataTables -->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-list"></i> Surveys
        </div>

        <div class="card-body">
            <a href="{{ URL::to('admin/survey/create') }}" class="btn btn-success pull-right" style="margin-bottom: 10px">
              <span class="fa fa-plus"></span> Add New
            </a>
            <span>Total: <strong>{{count($surveys)}}</strong></span>
            <table class="table table-bordered" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Created At</th>
                  <th>OPERATIONS</th>
                </tr>
              </thead>

              <tbody>
                @foreach ($surveys as $indexkey => $survey)
                  <tr>
                    <td>{{ $survey-> name }}</td>
                    <td>{{ $survey-> created_at }}</td>
                    <td style="display: flex;">
                      <a href="survey/questions/{{$survey->id}}" class="badge badge-primary" style="margin-right: 5px;">Questions</a>
                      <a href="survey/edit/{{$survey->id}}" class="badge badge-primary" style="margin-right: 5px;">Edit</a>
                      <a href="survey/delete/{{$survey->id}}" class="badge badge-danger">Delete</a>
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
