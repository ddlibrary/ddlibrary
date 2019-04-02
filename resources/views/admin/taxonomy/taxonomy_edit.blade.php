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
        <i class="fa fa-table"></i> Edit Taxonomy
      </div>
      <div class="card-body">
        <div class="table-responsive">
        <a href="{{ URL::to('admin/taxonomy/translate/'.$term->id) }}"><button class="btn btn-primary pull-right">Translate</button></a>
        <form method="POST" action="{{ route('taxonomyedit', ['vid' => $term->vid, 'id' => $term->id]) }}">
        @csrf
          <table class="table table-bordered" width="100%" cellspacing="0">
              <tr>
                <td>Vocabulary</td>
                <td>
                    <select class="form-control" name="vid" required>
                        @foreach($vocabulary as $vb)
                        <option value="{{ $vb->vid }}" {{ $term->vid == $vb->vid?"selected":"" }}>{{ $vb->name }}</option>
                        @endforeach
                    </select>
                </td>
              </tr>
              <tr>
                <td>Name</td>
                <td>
                    <input class="form-control" type="text" value="{{ $term->name }}" name="name" required>
                </td>
              </tr>
              <tr>
                  <td>Parent</td>
                  <td>
                      <select class="form-control" name="parent" required>
                          <option value="0" {{ $theParent == 0 ? "selected":"" }}>-- None --</option>
                          @foreach($parents as $p)
                              <option value="{{ $p->id }}" {{ $p->id == $theParent ? "selected":"" }}>{{ $p->name }}</option>
                          @endforeach
                      </select>
                  </td>
              </tr>
              <tr>
                <td>Weight</td>
                <td>
                    <input class="form-control" type="text" value="{{ $term->weight }}" name="weight" required>
                </td>
              </tr>
              <tr>
                <td>Language</td>
                <td>
                    <select class="form-control" name="language" required>
                        <option value="">Any</option>
                        @foreach(LaravelLocalization::getSupportedLocales() as $localcode => $properties)
                        <option value="{{ $localcode }}" {{ $term->language == $localcode?"selected":"" }}>{{ $properties['name'] }}</option>
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
