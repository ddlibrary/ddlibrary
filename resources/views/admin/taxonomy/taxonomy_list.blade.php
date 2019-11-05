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
        <i class="fa fa-table"></i> All Terms
      </div>
      <div class="card-body">
        <!-- Search bar will come here -->
        {!! $searchBar !!}
        <div class="table-responsive">
          Total: <strong>{{ $terms->total() }}</strong>
          <a href="{{ URL::to('admin/taxonomy/create') }}"><button class="btn btn-primary pull-right">Create New</button></a>
          <table class="table table-bordered" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>NO</th>
                <th>VID</th>
                <th>NAME</th>
                <th>WEIGHT</th>
                <th>LANGUAGE</th>
                <th>OPERATIONS</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>NO</th>
                <th>VID</th>
                <th>NAME</th>
                <th>WEIGHT</th>
                <th>LANGUAGE</th>
                <th>OPERATIONS</th>
              </tr>
            </tfoot>
            <tbody>
            @foreach ($terms as $indexkey => $term)
              <tr>
                <td>{{ (($terms->currentPage() - 1) * $terms->perPage())+$indexkey + 1 }}</td>
                <td>{{ $term->vocabulary->name }}</td>
                <td>{{ $term->name }}</td>
                <td>{{ $term->weight }}</td>
                <td>{{ fixLanguage($term->language) }}</td>
                <td><a href="taxonomy/edit/{{$term->vid}}/{{$term->id}}">Edit</a></td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        {{ $terms->appends(request()->input())->links() }}
      </div>
    </div>
  </div>
  <!-- /.container-fluid-->
  <!-- /.content-wrapper-->
  @endsection
