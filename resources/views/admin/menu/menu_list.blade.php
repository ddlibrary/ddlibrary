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
    <!-- Success/Error Messages -->
    @include('layouts.messages')
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
            <?php
              // Check if this menu has sub-menus
              $hasSubMenus = $menuRecords->where('parent', $menu->id)->count() > 0;
            ?>
            <li class="dd-item dd-item-alt" data-id="{{ $menu->id }}">
                <div class="dd-handle"></div>
                <div class="dd-content"> {{ $menu->title }} - {{ $menu->location }}
                  <span class="badge badge-{{ $menu->status ? 'success' : 'secondary' }} ml-2">
                    {{ $menu->status ? 'Active' : 'Inactive' }}
                  </span>
                  <span style="float:right;">
                    <a href="menu/edit/{{$menu->id}}"><i class="fa fa-edit"></i> Edit</a> &nbsp; &nbsp;
                    <a href="menu/translate/{{$menu->id}}"><i class="fa fa-language"></i> Translate</a>
                    @if(!$hasSubMenus)
                    &nbsp; &nbsp;
                    <form method="POST" action="{{ route('delete_menu', $menu->id) }}" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this menu and all its translations? This action cannot be undone.');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-link text-danger p-0" style="border: none; background: none; cursor: pointer;">
                        <i class="fa fa-trash"></i> Delete
                      </button>
                    </form>
                    @endif
                  </span>
                </div>

                <ol class="dd-list">
                  @foreach ($menuRecords as $indexkey => $sub)
                  @if($sub->parent > 0 && $sub->parent == $menu->id)
                    <li class="dd-item dd-item-alt" data-id="{{ $sub->id }}">
                        <div class="dd-handle"></div>
                        <div class="dd-content"> {{ $sub->title }}
                          <span class="badge badge-{{ $sub->status ? 'success' : 'secondary' }} ml-2">
                            {{ $sub->status ? 'Active' : 'Inactive' }}
                          </span>
                          <span style="float:right;">
                            <a href="menu/edit/{{$sub->id}}"><i class="fa fa-edit"></i> Edit</a> &nbsp; &nbsp;
                            <a href="menu/translate/{{$sub->id}}"><i class="fa fa-language"></i> Translate</a> &nbsp; &nbsp;
                            <form method="POST" action="{{ route('delete_menu', $sub->id) }}" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this menu and all its translations? This action cannot be undone.');">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-link text-danger p-0" style="border: none; background: none; cursor: pointer;">
                                <i class="fa fa-trash"></i> Delete
                              </button>
                            </form>
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

        $('#sort_btn').on('click', function(){
          var order = $('.dd').nestable('serialize');
          $.get('{{ URL('admin/menu/sort') }}', {data:order}, function(data){
            if(data) toastr.success('Menu Sorted Successfully!', 'Success');
          });
        });

        // Show success message using toastr if available
        @if(session('success'))
            toastr.success('{{ session('success') }}', 'Success');
        @endif

        @if(session('error'))
            toastr.error('{{ session('error') }}', 'Error');
        @endif
      });
    </script>
  @endpush 
