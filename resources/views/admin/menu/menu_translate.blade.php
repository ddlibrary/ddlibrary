@extends('admin.layout')
@section('admin.content')
<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="{{ URL::to('admin') }}">Menu</a>
      </li>
      <li class="breadcrumb-item active">Translation</li>
    </ol>
    <!-- Example DataTables Card-->
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i> Menu Translations
      </div>
      <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
              <thead>
                <tr>
                    <th> Menu Title </th>
                    <th> Language </th>
                    <th> Action </th>
                </tr>
              </thead>
              @if($translations)
                @foreach($locals as $key=>$value)
                <?php
                  if (isset($translations) && isset($key)) {
                    $terms = $translations->where('language', $key);
                  }
                $terms = array_values($terms->toArray());
                ?>
                @if(isset($terms[0]['language']))
                <tr>
                  <td>{{ $terms[0]['title'] }}</td>
                  <td>{{ $value['name'] }}</td>
                  <td><a href="/admin/menu/edit/{{$terms[0]['id']}}"><i class="fa fa-edit"></i> Edit</a></td>
                </tr>
                @else
                <tr>
                  <td>Not translated</td>
                  <td>{{ $value['name'] }}</td>
                  <td><a href="/admin/menu/add/{{ $id }}?lang={{ $key }}"><i class="fa fa-edit"></i> Add</a></td>
                </tr>
                @endif
                @endforeach
              @else
              @foreach($locals as $key=>$value)
              <tr>
                <td>Not translated</td>
                <td>{{ $value['name'] }}</td>
                <td><a href="/admin/menu/add/{{ $id }}?lang={{ $key }}"><i class="fa fa-edit"></i> Add</a></td>
              </tr>
              @endforeach
              @endif
              </tr>
            </table>
        </div>
      </div>
    </div>
  </div>
  <!-- /.container-fluid-->
</div>
<!-- /.content-wrapper-->
@endsection

