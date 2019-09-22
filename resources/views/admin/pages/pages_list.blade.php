@extends('admin.layout')
@section('admin.content')
<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="{{ URL::to('admin') }}">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">Pages</li>
    </ol>
    <!-- Example DataTables Card-->
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i> All Pages
        <a href="{{ URL::to('page/create') }}" style="float:right"><button class="btn btn-primary">+</button></a>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" width="100%" cellspacing="0" id="pages-table">
            <thead>
              <tr>
                <th>NO</th>
                <th>TITLE</th>
                <th>LANGUAGE</th>
                <th>CREATED</th>
                <th>UPDATED</th>
                <th>OPERATIONS</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- /.container-fluid-->
  <!-- /.content-wrapper-->
  @endsection

  @push('scripts')
  <script>
    $(document).ready(function(){
      $('#pages-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('getpages') !!}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'title', name: 'title' },
                { data: 'language', name: 'language' },
                { data: 'created_at', name: 'created', searchable: false },
                { data: 'updated_at', name: 'update', searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });
    });
    </script>
  @endpush