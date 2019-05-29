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
        <i class="fa fa-table"></i> All Pages</div>
      <div class="card-body">
        <div class="table-responsive">
          <a href="{{ URL::to('page/create') }}"><button class="btn btn-primary pull-right">Create New</button></a>
          <span class="pull-left">Total: <strong>{{ $pages->total() }}</strong></span>
          <table class="table table-bordered" width="100%" cellspacing="0">
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
            <tfoot>
              <tr>
                <th>NO</th>
                <th>TITLE</th>
                <th>LANGUAGE</th>
                <th>CREATED</th>
                <th>UPDATED</th>
                <th>OPERATIONS</th>
              </tr>
            </tfoot>
            <tbody>
            @foreach ($pages as $indexkey => $page)
              <tr>
                <td>{{ (($pages->currentPage() - 1) * $pages->perPage())+$indexkey + 1 }}</td>
                <td><a href="{{ URL::to($page->language.'/'.'page/'.$page->id) }}">{{ $page->title }}</a></td>
                <td>{{ fixLanguage($page->language) }}</td>
                <td>{{ $page->created_at->diffForHumans() }}</td>
                <td>{{ $page->updated_at->diffForHumans() }}</td>
                <td><a href="{{ URL::to($page->language.'/'.'page/edit'.'/'.$page->id) }}">Edit</a></td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        {{ $pages->links() }}
      </div>
    </div>
  </div>
  <!-- /.container-fluid-->
  <!-- /.content-wrapper-->
  @endsection