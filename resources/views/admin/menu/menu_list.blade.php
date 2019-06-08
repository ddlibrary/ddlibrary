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
        <!-- The search bar -->
        {!! $searchBar !!}

          <div class="clear-fix dd">
            <ol class="dd-list">
              @foreach ($menuRecords as $indexkey => $menu) 
              @if($menu->parent == 0)
              <li class="dd-item dd-item-alt" data-id="{{ $menu->id }}">
                  <div class="dd-handle"></div>
                  <div class="dd-content"> {{ $menu->title }} - {{ $menu->location }}
                      <a style="float:right;" href="menu/edit/{{$menu->id}}"><i class="fa fa-edit"></i> edit</a> 
                  </div>

                  <ol class="dd-list">
                    @foreach ($menuRecords as $indexkey => $sub) 
                    @if($sub->parent > 0 && $sub->parent == $menu->id)
                      <li class="dd-item dd-item-alt" data-id="{{ $sub->id }}">
                          <div class="dd-handle"></div>
                          <div class="dd-content"> {{ $sub->title }} 
                            <a style="float:right;" href="menu/edit/{{$sub->id}}"><i class="fa fa-edit"></i> edit</a> 
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