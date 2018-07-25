@extends('admin.layout')
@section('admin.content')
<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="{{ URL::to('admin') }}">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">Resources</li>
    </ol>
    <!-- Example DataTables Card-->
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i> All Resources</div>
      <div class="card-body">
      <div class="table-responsive">
          <form method="POST" action="{{ route('resources') }}">
          @csrf
          <table class="table table-bordered" width="100%" cellspacing="0">
            <tr>
              <td>Title</td>
              <td>
                <input class="form-control" type="text" name="title" value="{{ isset($filters['title'])?$filters['title']:"" }}">
              </td>
              <td>Published</td>
              <td>
                <select class="form-control" name="status">
                  <option value="">Any</option>
                  <option value="1" {{ (isset($filters['status']) && $filters['status'] == 1)?"selected":"" }}>Yes</option>
                  <option value="0" {{ (isset($filters['status']) && $filters['status'] == 0)?"selected":"" }}>No</option>
                </select>
              </td>
              <td>Language</td>
              <td>
                <select class="form-control" name="language">
                  <option value="">Any</option>
                  <option value="en" {{ (isset($filters['status']) && $filters['status'] == "en")?"selected":"" }}>English</option>
                  <option value="fa" {{ (isset($filters['status']) && $filters['status'] == "fa")?"selected":"" }}>Farsi/Dari</option>
                  <option value="ps" {{ (isset($filters['status']) && $filters['status'] == "ps")?"selected":"" }}>Pashto</option>
                </select>
              </td>
              <td colspan="2">
                  <input class="btn btn-primary float-right" type="submit" value="Filter">
              </td>
            </tr>
          </table>
          </form>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>NO</th>
                <th>TITLE</th>
                <th>ADDED BY</th>
                <th>PUBLISHED</th>
                <th>UPDATED</th>
                <th>LANGUAGE</th>
                <th>OPERATIONS</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>NO</th>
                <th>TITLE</th>
                <th>ADDED BY</th>
                <th>PUBLISHED</th>
                <th>UPDATED</th>
                <th>LANGUAGE</th>
                <th>OPERATIONS</th>
              </tr>
            </tfoot>
            <tbody>
            @foreach ($resources as $indexkey => $resource)
              <tr>
                <td>{{ (($resources->currentPage() - 1) * $resources->perPage())+$indexkey + 1 }}</td>
                <td><a href="{{URL::to('resource/'.$resource->id) }}">{{ $resource->title }}</a></td>
                <td><a href="{{ URL::to('users/view/'.$resource->user_id) }}">{{ $resource->addedby }}</a></td>
                <td>{{ ($resource->status==0?"Not Published":"Published") }}</td>
                <td>{{ $resource->updated_at }}</td>
                <td>{{ fixLanguage($resource->language) }}</td>
                <td><a href="{{ URL::to('resources/edit/step1/'.$resource->id) }}">Edit</a></td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        {{ $resources->appends(request()->input())->links() }}
      </div>
    </div>
  </div>
  <!-- /.container-fluid-->
  <!-- /.content-wrapper-->
  @endsection
