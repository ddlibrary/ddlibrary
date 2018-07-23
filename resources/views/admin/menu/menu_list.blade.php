@extends('admin.layout')
@section('admin.content')
<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="{{ URL::to('admin') }}">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">Menus</li>
    </ol>
    <!-- Example DataTables Card-->
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i> All Menus</div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>NO</th>
                <th>PARENT</th>
                <th>LOCATION</th>
                <th>TITLE</th>
                <th>WEIGHT</th>
                <th>LANGUAGE</th>
                <th>OPERATIONS</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>NO</th>
                <th>PARENT</th>
                <th>LOCATION</th>
                <th>TITLE</th>
                <th>WEIGHT</th>
                <th>LANGUAGE</th>
                <th>OPERATIONS</th>
              </tr>
            </tfoot>
            <tbody>
            @foreach ($menuRecords as $indexkey => $menu)
              <tr>
                <td>{{ (($menuRecords->currentPage() - 1) * $menuRecords->perPage())+$indexkey + 1 }}</td>
                <td>{{ $menu->parent }}</td>
                <td>{{ $menu->location }}</td>
                <td><a href="menu/view/{{$menu->id}}">{{ $menu->title }}</a></td>
                <td>{{ $menu->weight }}</td>
                <td>{{ fixLanguage($menu->language) }}</td>
                <td><a href="menu/edit/{{$menu->id}}">Edit</a></td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        {{ $menuRecords->links() }}
      </div>
    </div>
  </div>
  <!-- /.container-fluid-->
  <!-- /.content-wrapper-->
  @endsection