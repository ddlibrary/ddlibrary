@extends('admin.layout')
@section('admin.content')
<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="{{ URL::to('admin') }}" title="Dashboard">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">Comments</li>
    </ol>
    @include('layouts.messages')
    <!-- Example DataTables Card-->
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i> All Comments</div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>NO</th>
                <th>Title</th>
                <th>User</th>
                <th>Comment</th>
                <th>Status</th>
                <th>Created</th>
                <th>Operations</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>NO</th>
                <th>Title</th>
                <th>User</th>
                <th>Comment</th>
                <th>Status</th>
                <th>Created</th>
                <th>Operations</th>
              </tr>
            </tfoot>
            <tbody>
            @foreach ($comments as $indexkey => $comment)
              <tr>
                <td>{{ (($comments->currentPage() - 1) * 20)+$indexkey + 1 }}</td>
                <td><a href="{{ URL::to('resource/'.$comment->resource_id) }}" title="Resource Title">{{ $comment->resource->title }}</a></td>
                <td><a href="{{ URL::to('user/'.$comment->user_id) }}" title="User">{{ $comment->user->username }}</a></td>
                <td>{{ $comment->comment }}</td>
                <td><a href="{{ URL::to('admin/comments/published/'.$comment->id) }}" title="Status">{{ ($comment->status==0?"Not Published":"Published") }}</a></td>
                <td>{{ $comment->created_at->diffForHumans() }}</td>
                <td><a href="{{ URL::to('admin/comments/delete/'.$comment->id) }}">Delete</a></td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        {{ $comments->links() }}
      </div>
    </div>
  </div>
  <!-- /.container-fluid-->
  <!-- /.content-wrapper-->
  @endsection