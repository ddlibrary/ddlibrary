@extends('admin.layout')
@section('admin.content')
    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Breadcrumbs-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ URL::to('admin') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Taxonomy</li>
            </ol>
            <!-- Success/Error Messages -->
            @include('layouts.messages')
            <!-- Example DataTables Card-->
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fa fa-table"></i> All Terms
                    </div>
                    <a href="{{ URL::to('admin/taxonomy/create') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i> Create New
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search bar -->
                    <div class="table-responsive">
                        <form method="GET" action="{{ route('gettaxonomylist') }}">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <tr>
                                    <td>Term</td>
                                    <td>
                                        <input class="form-control" type="text" name="term"
                                            value="{{ request()->term }}" placeholder="Search term name...">
                                    </td>
                                    <td>Vocabulary <span class="text-danger">*</span></td>
                                    <td>
                                        <select class="form-control" name="taxonomy_vocabulary_id" >
                                            <option value="">-- Select Vocabulary --</option>
                                            @foreach ($taxonomyVocabularies as $taxonomyVocabulary)
                                                <option value="{{ $taxonomyVocabulary->vid }}" @selected(request()->taxonomy_vocabulary_id == $taxonomyVocabulary->vid)>
                                                    {{ $taxonomyVocabulary->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control" name="language" required>
                                            @foreach ($languages as $localeCode => $localeProperties)
                                                <option value="{{ $localeCode }}" @selected(request()->language == $localeCode)>{{ $localeProperties['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td colspan="2">
                                        <input class="btn btn-primary float-right" type="submit" value="Filter">
                                        @if (request()->vocabulary || request()->term)
                                            <a href="{{ route('gettaxonomylist') }}"
                                                class="btn btn-secondary float-right mr-2">Clear</a>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>

                    @if (!$taxonomyVocabularyId)
                        <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                            <i class="fa fa-exclamation-triangle"></i> Please search a taxonomy term or select a vocabulary to view taxonomy terms.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @elseif ($terms)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <span class="badge badge-secondary">Total:
                                    <strong>{{ count($terms) }}</strong></span>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center" style="width: 50px;">#</th>
                                        <th>Vocabulary</th>
                                        <th>English</th>
                                        <th>Persian</th>
                                        <th>Pashto</th>
                                        <th>Uzbeki</th>
                                        <th>Munji</th>
                                        <th>Nooristani</th>
                                        <th>Savji</th>
                                        <th>Sheghnani</th>
                                        <th>Pashai</th>
                                        <th class="text-center" style="width: 100px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($terms as $term)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td><strong>{{ $term->vocabulary->name }}</strong></td>
                                            @php
                                                $translationsByLanguage = $term->translationsByLanguage();
                                            @endphp

                                            @foreach ($languages as $localeCode => $localeProperties)
                                                <td>
                                                    @if($translationsByLanguage->count())
                                                        {{ $translationsByLanguage->has($localeCode) ? $translationsByLanguage[$localeCode]->name : '' }}
                                                    @else
                                                        <!-- Display the term's own name if no translation exists -->
                                                        {{ $term->language == $localeCode ? $term->name :'' }}
                                                    @endif
                                                </td>
                                            @endforeach
                                            <td class="text-center">
                                                <a href="{{ url('admin/taxonomy/edit/' . $term->vid . '/' . $term->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fa fa-info-circle"></i> No taxonomy terms found.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endsection
