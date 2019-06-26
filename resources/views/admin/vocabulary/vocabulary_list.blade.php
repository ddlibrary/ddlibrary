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
        <i class="fa fa-table"></i> Taxonomy Vocabulary List
        <a href="{{ URL::to('admin/vocabulary/create') }}" style="float:right"><button class="btn btn-primary">+</button></a>
      </div>
      <div class="card-body">
        <!-- Search bar will come here -->
        
        <div class="table-responsive">
          <table class="table table-bordered" width="100%" cellspacing="0" id="taxonomy_vocabulary-table">
            <thead>
              <tr>
                <th>NO</th>
                <th>NAME</th>
                <th>WEIGHT</th>
                <th>LANGUAGE</th>
                <th>ACTION</th>
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
      $('#taxonomy_vocabulary-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('getvocabularies') !!}',
            columns: [
                { data: 'vid', name: 'vid' },
                { data: 'name', name: 'name' },
                { data: 'weight', name: 'weight' },
                { data: 'language', name: 'language' },
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });
    });
    </script>
  @endpush
  