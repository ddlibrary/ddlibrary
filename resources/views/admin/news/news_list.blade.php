@extends('admin.layout')
@section('admin.content')
<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="{{ URL::to('admin') }}">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">News</li>
    </ol>
    <!-- Example DataTables Card-->
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i> All News</div>
      <div class="card-body">
        <div class="table-responsive">
          <a href="{{ URL::to('news/create') }}"><button class="btn btn-primary pull-right">Create New</button></a>
          <span class="pull-left">Total: <strong>{{ $newsRecords->total() }}</strong></span>
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
            @foreach ($newsRecords as $indexkey => $news)
              <tr>
                <td>{{ (($newsRecords->currentPage() - 1) * $newsRecords->perPage())+$indexkey + 1 }}</td>
                <td><a href="{{ URL::to('/news/'.$news->id) }}">{{ $news->title }}</a></td>
                <td>{{ fixLanguage($news->language) }}</td>
                <td>{{ $news->created_at->diffForHumans() }}</td>
                <td>{{ $news->updated_at->diffForHumans() }}</td>
                <td><a href="{{ URL::to('news/edit/'.$news->id) }}">Edit</a></td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        {{ $newsRecords->links() }}
      </div>
    </div>
  </div>
  <!-- /.container-fluid-->
  <!-- /.content-wrapper-->
  @endsection