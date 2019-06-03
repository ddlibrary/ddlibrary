@extends('admin.layout')
@section('admin.content')
<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="{{ URL::to('admin') }}">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">Taxonomy Vocabulary</li>
    </ol>
    <!-- Example DataTables Card-->
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i> Create Vocabulary
      </div>
      <div class="card-body">
        <div class="table-responsive">
        <form method="POST" action="{{ route('vocabularystore') }}">
        @csrf
          <table class="table table-bordered" width="100%" cellspacing="0">
              <tr>
                <td>Name</td>
                <td>
                    <input class="form-control" type="text" value="{{ old('name') }}" name="name" required>
                </td>
              </tr>
              <tr>
                <td>Weight</td>
                <td>
                    <input class="form-control" type="text" value="{{ old('weight') }}" name="weight" required>
                </td>
              </tr>
              <tr>
                <td>Language</td>
                <td>
                    <select class="form-control" name="language" required>
                        <option value="">Any</option>
                        @foreach(LaravelLocalization::getSupportedLocales() as $localcode => $properties)
                        <option value="{{ $localcode }}" {{ (old('language') == $localcode)?"selected":"" }}>{{ $properties['name'] }}</option>
                        @endforeach
                    </select>
                </td>
              </tr>
              <tr>
                  <td colspan="2">
                    <input class="btn btn-primary" type="submit" value="Save">
                  </td>
              </tr>
          </table>
         </form>
        </div>
      </div>
    </div>
  </div>
  <!-- /.container-fluid-->
</div>
<!-- /.content-wrapper-->
@endsection
