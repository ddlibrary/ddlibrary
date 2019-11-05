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
        <li class="breadcrumb-item active">Edit</li>
      </ol>

      <!-- Surveys Answers DataTables -->
      <div class="card mb-3">

        <div class="card-header">
          <i class="fa fa-pencil-square-o"></i> Edit Survey
        </div>

        <div class="card-body">
          @if (session('status'))
              <div class="alert alert-success">
                  {{ session('status') }}
              </div>
          @endif
          <form method="POST" action="{{ route('update_survey', ['id' => $survey->id]) }}">
            @csrf

            <div class="row">
              <div class="col-sm-6 offset-sm-3">

                <div class="form-group row">
                  <label for="name" class="col-sm-3 col-form-label">Survey Name</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="name" name="name" value="{{$survey->name}}" required="true" placeholder="Name">
                  </div>
                </div>

                <div class="form-group row">
                  <label for="name" class="col-sm-3 col-form-label">Language</label>
                  <div class="col-sm-9">
                    <select class="form-control{{ $errors->has('language') ? ' is-invalid' : '' }}" name="language" id="language" required>
                        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                          @if ( $survey->language == $localeCode)
                            <option value="{{ $localeCode }}" selected>{{ $properties['native'] }}</option>
                          @else
                            <option value="{{ $localeCode }}">{{ $properties['native'] }}</option>
                          @endif
                        @endforeach
                    </select>
                  </div>
                </div>

                <div class="form-group row">
                  <label for="name" class="col-sm-3 col-form-label">Survey Status</label>
                  <div class="col-sm-7">
                      @if ($survey->state == 'published')
                        <input type="radio" id="status" name="state" value="published" checked>
                      @else
                        <input type="radio" id="status" name="state" value="published">
                      @endif
                      <label for="status" class="badge badge-success">Published</label>
                      
                      @if ($survey->state == 'draft')
                        <input type="radio" id="status" name="state" value="draft" checked>
                      @else
                        <input type="radio" id="status" name="state" value="draft">
                      @endif
                      <label for="status" class="badge badge-warning">Draft</label>
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
