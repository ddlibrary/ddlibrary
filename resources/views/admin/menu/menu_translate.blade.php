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
      </div>
      <div class="card-body">
        @if($tnid && $translations && $translations->count() > 0)
        <form id="deleteForm" method="POST" action="{{ route('delete_menu', $id) }}" onsubmit="return confirm('Are you sure you want to delete the selected menu translations? This action cannot be undone.');">
          @csrf
          @method('DELETE')
          <input type="hidden" name="selected_ids" id="selected_ids" value="">
          <button type="submit" class="btn btn-danger mb-3" id="deleteSelectedBtn" disabled>
            <i class="fa fa-trash"></i> Delete Selected
          </button>
        </form>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
              <thead>
                <tr>
                    @if($tnid && $translations && $translations->count() > 0)
                    <th width="50">
                      <input type="checkbox" id="selectAll" title="Select All">
                    </th>
                    @endif
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
                  @if($tnid)
                  <td>
                    @if($terms[0]['language'] != 'en')
                    <input type="checkbox" class="menu-checkbox" name="menu_ids[]" value="{{ $terms[0]['id'] }}">
                    @endif
                  </td>
                  @endif
                  <td>{{ $terms[0]['title'] }}</td>
                  <td>{{ $value['name'] }}</td>
                  <td><a href="/admin/menu/edit/{{$terms[0]['id']}}"><i class="fa fa-edit"></i> Edit</a></td>
                </tr>
                @else
                <tr>
                  @if($tnid)
                  <td></td>
                  @endif
                  <td>Not translated</td>
                  <td>{{ $value['name'] }}</td>
                  <td><a href="/admin/menu/add/{{ $id }}?lang={{ $key }}"><i class="fa fa-edit"></i> Add</a></td>
                </tr>
                @endif
                @endforeach
              @else
              @foreach($locals as $key=>$value)
              <tr>
                @if($tnid)
                <td></td>
                @endif
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
    const selectAllCheckbox = document.getElementById('selectAll');
    const menuCheckboxes = document.querySelectorAll('.menu-checkbox');
    const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
    const deleteForm = document.getElementById('deleteForm');
    const selectedIdsInput = document.getElementById('selected_ids');

    // Select All functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            menuCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateDeleteButton();
        });
    }

    // Individual checkbox change
    menuCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectAll();
            updateDeleteButton();
        });
    });

    // Update Select All checkbox state
    function updateSelectAll() {
        if (selectAllCheckbox && menuCheckboxes.length > 0) {
            const allChecked = Array.from(menuCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(menuCheckboxes).some(cb => cb.checked);
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = someChecked && !allChecked;
        }
    }

    // Update Delete Selected button state
    function updateDeleteButton() {
        if (deleteSelectedBtn) {
            const checkedBoxes = Array.from(menuCheckboxes).filter(cb => cb.checked);
            deleteSelectedBtn.disabled = checkedBoxes.length === 0;
        }
    }

    // Handle form submission
    if (deleteForm) {
        deleteForm.addEventListener('submit', function(e) {
            const checkedBoxes = Array.from(menuCheckboxes).filter(cb => cb.checked);
            const selectedIds = checkedBoxes.map(cb => cb.value).join(',');
            selectedIdsInput.value = selectedIds;
        });
    }

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

