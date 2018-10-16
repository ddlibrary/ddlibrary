@extends('admin.layout')
@section('admin.content')
<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="{{ URL::to('admin') }}">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">Taxonomy</li>
    </ol>
    <!-- Example DataTables Card-->
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i> Create Taxonomy
      </div>
      <div class="card-body">
        <div class="table-responsive">
        <form method="POST" action="{{ route('taxonomytranslatestore', ['tnid' => $tnid]) }}">
        @csrf
          <table class="table table-bordered" width="100%" cellspacing="0">
              <tr>
                <td>Vocabulary</td>
                <td>
                    <select class="form-control" name="vid" required disabled>
                        <option value=""> - None - </option>
                        @foreach($vocabulary as $vb)
                        <option value="{{ $vb->vid }}" {{ (old('vid') || $vid == $vb->vid)?"selected":"" }}>{{ $vb->name }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="vid" value="{{ $vid }}">
                </td>
              </tr>
              <tr>
                <td>Name</td>
                <td>
                    <input class="form-control" type="text" value="{{ old('name') }}" name="name" required>
                </td>
              </tr>
              <tr>
                <td>Weight</td>
                <td>
                    <input class="form-control" type="text" value="{{ $weight }}" name="weight" required>
                </td>
              </tr>
              <tr>
                <td>Language</td>
                <td>
                    <select class="form-control" name="language" required disabled>
                        <option value="">Any</option>
                        @foreach(LaravelLocalization::getSupportedLocales() as $localcode => $properties)
                        <option value="{{ $localcode }}" {{ (old('language') == $localcode || $lang == $localcode)?"selected":"" }}>{{ $properties['name'] }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="language" value="{{ $lang }}">
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
