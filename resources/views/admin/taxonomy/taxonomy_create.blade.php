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
        <a href="{{ route('gettaxonomylist') }}">Taxonomy</a>
      </li>
      <li class="breadcrumb-item active">Create</li>
    </ol>
    @include('layouts.messages')
    
    <h3 class="mb-2">Create Taxonomy</h3>
    
    <form method="POST" action="{{ route('taxonomystore') }}">
      @csrf
      <div class="row mb-2">
        <div class="col-md-6">
          <div class="form-group row mb-2">
            <label for="vocabulary_select" class="col-sm-4 col-form-label">Vocabulary <span class="text-danger">*</span></label>
            <div class="col-sm-8">
              <select class="form-control form-control-sm" onchange="window.location = this.value" required>
                  <option value="">...Select Vocabulary...</option>
                  @foreach($taxonomyVocabularies as $taxonomyVocabulary)
                      <option value="{{ url('admin/taxonomy/create?vid='.$taxonomyVocabulary->vid) }}" 
                              {{ old('vid', request('vid', $selectedVocabulary ?? '')) == $taxonomyVocabulary->vid ? 'selected' : '' }}>
                          {{ $taxonomyVocabulary->name }}
                      </option>
                  @endforeach
              </select>
              <input type="hidden" name="vid" value="{{ $selectedVocabulary }}">
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group row mb-2">
            <label for="weight" class="col-sm-4 col-form-label">Weight <span class="text-danger">*</span></label>
            <div class="col-sm-8">
              <input class="form-control form-control-sm" type="number" id="weight" value="{{ old('weight', 0) }}" name="weight" required>
              <small class="form-text text-muted" style="font-size: 0.75rem;">Shared weight for all translations</small>
              @error('weight')
                  <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>
          </div>
        </div>
      </div>

      <hr class="my-2">
      <h5 class="mb-2">Translations</h5>
      
      @foreach($supportedLocales as $localeCode => $localeProperties)
        <div class="mb-2 pb-2 border-bottom">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row mb-1">
                <label for="name_{{ $localeCode }}" class="col-sm-4 col-form-label">{{ $localeProperties['name'] }}</label>
                <div class="col-sm-8">
                  <input class="form-control form-control-sm" type="text" 
                      id="name_{{ $localeCode }}"
                      name="names[{{ $localeCode }}]" 
                      value="{{ old('names.' . $localeCode) }}"
                      placeholder="Enter {{ $localeProperties['name'] }} name">
                  @error('names.' . $localeCode)
                      <small class="text-danger">{{ $message }}</small>
                  @enderror
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row mb-1">
                <label for="parent_{{ $localeCode }}" class="col-sm-4 col-form-label">Parent Term</label>
                <div class="col-sm-8">
                  <select class="form-control form-control-sm parent-select" id="parent_{{ $localeCode }}" name="parents[{{ $localeCode }}]" data-lang="{{ $localeCode }}">
                      <option value="0">...</option>
                      @foreach ($parents?->where('language', $localeCode) as $parent)
                      <option value="{{ $parent->id }}">{{ $parent->name}}</option>
                      @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach

      <div class="form-group mt-2">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('gettaxonomylist') }}" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
