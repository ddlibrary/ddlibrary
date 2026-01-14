@extends('admin.layout')
@section('admin.content')
<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="{{ URL::to('admin/menu') }}">Menu</a>
      </li>
      <li class="breadcrumb-item active">Translation</li>
    </ol>
    <!-- Success/Error Messages -->
    @include('layouts.messages')
    <!-- Example DataTables Card-->
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i> Menu Translations
        @if($tnid)
        <form method="POST" action="{{ route('delete_menu', $id) }}" style="display: inline-block; float: right;" onsubmit="return confirm('Are you sure you want to delete all translations of this menu? This action cannot be undone.');">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">
            <i class="fa fa-trash"></i> Delete All
          </button>
        </form>
        @endif
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
              <tbody>
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
              </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>
  <!-- /.container-fluid-->
</div>
<!-- /.content-wrapper-->
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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

