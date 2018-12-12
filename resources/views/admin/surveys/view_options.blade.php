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
          <a href="/admin/survey/questions/{{$survey->id}}">Question</a>
        </li> 

        <li class="breadcrumb-item active">{{$question->text}}</li>
      </ol>

      <!-- Surveys Answers DataTables -->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-list"></i> Options
        </div>

        <div class="card-body">
            <a href="/admin/survey/{{$survey->id}}/question/{{$question->id}}/option/create" class="btn btn-success pull-right" style="margin-bottom: 10px">
              <span class="fa fa-plus"></span> Add New
            </a>
            <span>Total: <strong>{{count($questin_options)}}</strong></span>
            <table class="table table-bordered" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Text</th>
                  <th>OPERATIONS</th>
                </tr>
              </thead>

              <tbody>
                @foreach ($questin_options as $indexkey => $questin_option)
                  <tr>
                    <td>{{ $questin_option-> text }}</td>
                    <td style="display: flex;">
                      <a href="survey/edit/{{$questin_option->id}}" class="badge badge-primary" style="margin-right: 5px;">Edit</a>
                      <a href="survey/delete/{{$questin_option->id}}" class="badge badge-danger">Delete</a>
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
