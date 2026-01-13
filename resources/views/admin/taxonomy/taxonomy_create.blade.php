@extends('admin.layout')
@section('admin.content')
<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="{{ URL::to('admin') }}">Dashboard</a>
      </li>
      <li class="breadcrumb-item">
        <a href="{{ URL::to('admin/taxonomy') . (request('vocabulary') ? '?vocabulary=' . request('vocabulary') : '') }}">Taxonomy</a>
      </li>
      <li class="breadcrumb-item active">Create</li>
    </ol>
    @include('layouts.messages')
    <!-- Example DataTables Card-->
    <div class="card mb-3">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div>
          <i class="fa fa-plus-circle"></i> <strong>Create Taxonomy</strong>
        </div>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('taxonomystore') }}">
          @csrf
          @if(request('vocabulary'))
              <input type="hidden" name="vocabulary" value="{{ request('vocabulary') }}">
          @endif
          
          <div class="row mb-3">
            <div class="col-md-6">
              <div class="form-group">
                <label for="vocabulary_select"><strong>Vocabulary</strong> <span class="text-danger">*</span></label>
                <select class="form-control" name="vid" id="vocabulary_select" required>
                    <option value="">-- Select Vocabulary --</option>
                    @foreach($vocabulary as $vb)
                    <option value="{{ $vb->vid }}" {{ (old('vid', isset($selectedVocabulary) ? $selectedVocabulary : '') == $vb->vid) ? 'selected' : '' }}>{{ $vb->name }}</option>
                    @endforeach
                </select>
                @error('vid')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="weight"><strong>Weight</strong> <span class="text-danger">*</span></label>
                <input class="form-control" type="number" id="weight" value="{{ old('weight', 0) }}" name="weight" required>
                <small class="form-text text-muted">Shared weight for all translations</small>
                @error('weight')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>
            </div>
          </div>

          <hr class="my-4">
          <h5 class="mb-3"><i class="fa fa-language"></i> Translations</h5>
          
          @foreach($supportedLocales as $localeCode => $localeProperties)
          <div class="card mb-3" style="border-left: 4px solid #007bff;">
            <div class="card-body">
              <h6 class="card-title mb-3">
                <i class="fa fa-globe"></i> <strong>{{ $localeProperties['name'] }}</strong>
              </h6>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="name_{{ $localeCode }}"><strong>Term Name</strong></label>
                    <input class="form-control" type="text" 
                        id="name_{{ $localeCode }}"
                        name="names[{{ $localeCode }}]" 
                        value="{{ old('names.' . $localeCode) }}"
                        placeholder="Enter {{ $localeProperties['name'] }} name">
                    @error('names.' . $localeCode)
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="parent_{{ $localeCode }}"><strong>Parent Term</strong></label>
                    <select class="form-control parent-select" id="parent_{{ $localeCode }}" name="parents[{{ $localeCode }}]" data-lang="{{ $localeCode }}">
                        <option value="0">-- None (Top Level) --</option>
                    </select>
                    <small class="form-text text-muted">Select a parent term for this translation (optional)</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endforeach

          <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary">
              <i class="fa fa-save"></i> Save
            </button>
            <a href="{{ URL::to('admin/taxonomy') . (request('vocabulary') ? '?vocabulary=' . request('vocabulary') : '') }}" class="btn btn-secondary">
              <i class="fa fa-times"></i> Cancel
            </a>
          </div>
        </form>
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
    const vocabularySelect = document.getElementById('vocabulary_select');
    const parentSelects = document.querySelectorAll('.parent-select');
    
    function updateAllParentOptions() {
        const selectedVid = vocabularySelect.value;
        
        if (!selectedVid) {
            parentSelects.forEach(function(select) {
                select.innerHTML = '<option value="0">-- None --</option>';
            });
            return;
        }
        
        // Show loading state for all selects
        parentSelects.forEach(function(select) {
            select.disabled = true;
            select.innerHTML = '<option value="0">Loading...</option>';
        });
        
        // Single AJAX request to get all parents for all languages
        fetch('{{ url("admin/taxonomy/ajax/parents") }}?vid=' + selectedVid)
            .then(response => response.json())
            .then(data => {
                // Populate each language's dropdown from the single response
                parentSelects.forEach(function(select) {
                    const lang = select.getAttribute('data-lang');
                    const parentsForLang = data[lang] || [];
                    
                    select.innerHTML = '<option value="0">-- None --</option>';
                    
                    parentsForLang.forEach(function(parent) {
                        const option = document.createElement('option');
                        option.value = parent.id;
                        option.textContent = parent.name;
                        select.appendChild(option);
                    });
                    
                    select.disabled = false;
                });
            })
            .catch(error => {
                console.error('Error loading parents:', error);
                parentSelects.forEach(function(select) {
                    select.innerHTML = '<option value="0">-- None --</option>';
                    select.disabled = false;
                });
            });
    }
    
    vocabularySelect.addEventListener('change', updateAllParentOptions);
    
    // Load parents if vocabulary is pre-selected (from query parameter or old input)
    if (vocabularySelect.value) {
        updateAllParentOptions();
    }
});

// Show success message using toastr if available
@if(session('success'))
    if (typeof toastr !== 'undefined') {
        setTimeout(function() {
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "5000",
            };
            toastr.success({!! json_encode(session('success')) !!}, 'Success');
        }, 100);
    }
@endif

@if(session('error'))
    if (typeof toastr !== 'undefined') {
        setTimeout(function() {
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "5000",
            };
            toastr.error({!! json_encode(session('error')) !!}, 'Error');
        }, 100);
    }
@endif
</script>
@endpush
