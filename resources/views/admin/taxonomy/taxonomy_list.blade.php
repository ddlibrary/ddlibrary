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
                                        <select class="form-control" name="vocabulary" required>
                                            <option value="">-- Select Vocabulary --</option>
                                            @foreach ($vocabulary as $vb)
                                                <option value="{{ $vb->val }}" @selected(request()->vocabulary == $vb->val)>
                                                    {{ $vb->name }}</option>
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

                    @if (!$vocabularyId)
                        <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                            <i class="fa fa-exclamation-triangle"></i> Please search a taxonomy term or select a vocabulary to view taxonomy terms.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @elseif (isset($groupedTerms) && count($groupedTerms) > 0)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <span class="badge badge-secondary">Total:
                                    <strong>{{ count($groupedTerms) }}</strong></span>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center" style="width: 50px;">#</th>
                                        <th>Vocabulary</th>
                                        @foreach ($laguages as $localeCode => $localeProperties)
                                            <th>{{ $localeProperties['name'] }}</th>
                                        @endforeach
                                        <th class="text-center" style="width: 100px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($groupedTerms as $termGroup)
                                        @php
                                            $translations = $termGroup['translations'];
                                            $firstTerm = $termGroup['first_term'];
                                            $vocabName = $firstTerm->vocabulary->name ?? '';
                                            $weight = $firstTerm->weight ?? 0;
                                            $vid = $firstTerm->vid;

                                            $translationsByLang = [];
                                            $invalidLangTerm = null;
                                            foreach ($translations as $trans) {
                                                $lang = $trans->language;
                                                if (!empty($lang) && $lang !== 'und' && isset($laguages[$lang])) {
                                                    $translationsByLang[$lang] = $trans;
                                                } else {
                                                    if (!$invalidLangTerm) {
                                                        $invalidLangTerm = $trans;
                                                    }
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td><strong>{{ $vocabName }}</strong></td>
                                            @foreach ($laguages as $localeCode => $localeProperties)
                                                <td>
                                                    @if (isset($translationsByLang[$localeCode]))
                                                        <span
                                                            class="text-dark">{{ $translationsByLang[$localeCode]->name }}</span>
                                                    @elseif($invalidLangTerm && $loop->first)
                                                        <span class="badge badge-warning"
                                                            title="Language: {{ $invalidLangTerm->language }}">
                                                            {{ $invalidLangTerm->name }}
                                                            <small>({{ $invalidLangTerm->language ?: 'und' }})</small>
                                                        </span>
                                                    @endif
                                                </td>
                                            @endforeach
                                            <td class="text-center">
                                                <a href="{{ url('admin/taxonomy/edit/' . $vid . '/' . $firstTerm->id) }}"
                                                    class="btn btn-sm btn-outline-primary">
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
