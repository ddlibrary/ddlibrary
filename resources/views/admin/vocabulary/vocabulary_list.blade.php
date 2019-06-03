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
          <span>Total: <strong>{{count($vocabularies)}}</strong></span>
          <table class="table table-bordered" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>NO</th>
                <th>NAME</th>
                <th>WEIGHT</th>
                <th>LANGUAGE</th>
                <th>OPERATIONS</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>NO</th>
                <th>NAME</th>
                <th>WEIGHT</th>
                <th>LANGUAGE</th>
                <th>OPERATIONS</th>
              </tr>
            </tfoot>
            <tbody>
            @foreach ($vocabularies as $indexkey => $vocabulary)
              <tr>
                <td>{{ $vocabulary->vid }}</td>
                <td>{{ $vocabulary->name }}</td>
                <td>{{ $vocabulary->weight }}</td>
                <td>{{ fixLanguage($vocabulary->language) }}</td>
                <td><a href="vocabulary/edit/{{$vocabulary->vid}}">Edit</a></td>
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
