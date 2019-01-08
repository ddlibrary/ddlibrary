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
        <li class="breadcrumb-item active">Create</li>
      </ol>

      <!-- Surveys Answers DataTables -->
      <div class="card mb-3">

        <div class="card-header">
          <i class="fa fa-plus"></i> Create Survey
        </div>

        <div class="card-body">
          @if (session('status'))
              <div class="alert alert-success">
                  {{ session('status') }}
              </div>
          @endif
          <form method="POST" action="{{ route('create_survey')}}">
            @csrf

            <div class="row">
              <div class="col-sm-6 offset-sm-3">

                <div class="form-group row">
                  <label for="name" class="col-sm-3 col-form-label">Survey Name</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="name" name="name" required="true" placeholder="Type Survey Name">
                  </div>
                </div>

                <div class="form-group row">
                  <label for="name" class="col-sm-3 col-form-label">Survey Status</label>
                  <div class="col-sm-9">
                      <input type="radio" id="status" name="state" value="published" checked>
                      <label for="status" class="badge badge-success">Published</label>
                      
                      <input type="radio" id="status" name="state" value="draft">
                      <label for="status" class="badge badge-warning">Draft</label>
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
