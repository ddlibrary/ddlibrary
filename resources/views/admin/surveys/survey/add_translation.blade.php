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
          <i class="fa fa-plus"></i> Create Survey: <span class="badge badge-success">{{ fixLanguage($lang) }} </span>
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
                    <input type="text" class="form-control" id="name" name="name" required="true" placeholder="Type survey name in {{ fixLanguage($lang) }}">
                  </div>
                </div>

                <div class="form-group row">
                  <label for="name" class="col-sm-3 col-form-label">Language</label>
                  <div class="col-sm-9">
                    <select readonly class="form-control{{ $errors->has('language') ? ' is-invalid' : '' }}" name="language" id="language" required>
                      @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        @if ( $lang == $localeCode)
                          <option value="{{ $localeCode }}">{{ $properties['native'] }}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                </div>
                
                <input type="integer" name="tnid" style="display: none;" value="{{$tnid}}">

                <div class="form-group row">
                  <label for="name" class="col-sm-3 col-form-label">Published?</label>
                  <div class="col-sm-9">
                      <input type="radio" id="status" name="state" value="published" checked>
                      <label for="status" class="badge badge-success">Yes</label>
                      
                      <input type="radio" id="status" name="state" value="draft">
                      <label for="status" class="badge badge-warning">No</label>
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
