@extends('admin.layout')

@push('scripts')
  <!-- Nestable CSS file for menues -->
  <link href="{{ URL::to('vendor/nestable/nestable.min.css') }}" rel="stylesheet">
@endpush 

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
        <i class="fa fa-table"></i> All Menus
        <a href="{{ URL::to('admin/menu/add/new') }}" class="btn btn-primary float-right">Add a new menu</a>
      </div>
      <div class="card-body">
        <!-- The search bar -->
        {!! $searchBar !!}
        <div class="clear-fix dd">
          <ol class="dd-list">
            @foreach ($menuRecords as $indexkey => $menu)
            @if($menu->parent == 0)
            <li class="dd-item dd-item-alt" data-id="{{ $menu->id }}">
                <div class="dd-handle"></div>
                <div class="dd-content"> {{ $menu->title }} - {{ $menu->location }}
                  <span style="float:right;">
                    <a href="menu/edit/{{$menu->id}}"><i class="fa fa-edit"></i> Edit</a> &nbsp; &nbsp;
                    <a href="menu/translate/{{$menu->id}}"><i class="fa fa-language"></i> Translate</a>
                  </span>
                </div>

                <ol class="dd-list">
                  @foreach ($menuRecords as $indexkey => $sub)
                  @if($sub->parent > 0 && $sub->parent == $menu->id)
                    <li class="dd-item dd-item-alt" data-id="{{ $sub->id }}">
                        <div class="dd-handle"></div>
                        <div class="dd-content"> {{ $sub->title }}
                          <span style="float:right;">
                            <a href="menu/edit/{{$sub->id}}"><i class="fa fa-edit"></i> Edit</a> &nbsp; &nbsp;
                            <a href="menu/translate/{{$sub->id}}"><i class="fa fa-language"></i> Translate</a>
                          </span>
                        </div>
                    </li>
                  @endif
                  @endforeach
                </ol>
            </li>
            @endif
            @endforeach
          </ol>
        </div>

        <button class="btn btn-primary" id="sort_btn">Sort</button>

      </div>
    </div>
  </div>
  <!-- /.container-fluid-->
  <!-- /.content-wrapper-->
  @endsection

  @push('scripts')
    <!-- Nestable plugin JavaScript-->
    <script src="{{ URL::to('vendor/nestable/nestable.min.js') }}"></script>
    <script>
      $(document).ready(function(){
        $('.dd').nestable({
          'maxDepth' : 2,
          'handleClass' : 'dd-handle'
        });
      });
      $('#sort_btn').on('click', function(){
        var order = $('.dd').nestable('serialize');
        $.get('{{ URL('admin/menu/sort') }}', {data:order}, function(data){
          if(data) toastr.success('Success', 'Menu Sorted Successfully!');
        });
      });
    </script>
  @endpush 
