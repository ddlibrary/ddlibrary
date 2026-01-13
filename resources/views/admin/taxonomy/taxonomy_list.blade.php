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
                    <div class="card mb-3">
                        <div class="card-body">
                            <form method="GET" action="{{ route('gettaxonomylist') }}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="term">Term</label>
                                            <input class="form-control" type="text" id="term" name="term"
                                                value="{{ request('term') }}" placeholder="Search by term name">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="vocabulary">Vocabulary <span class="text-danger">*</span></label>
                                            <select class="form-control" id="vocabulary" name="vocabulary" required>
                                                <option value="">-- Select Vocabulary --</option>
                                                @foreach ($vocabulary as $vb)
                                                    <option value="{{ $vb->val }}"
                                                        {{ request('vocabulary') == $vb->val ? 'selected' : '' }}>
                                                        {{ $vb->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <div>
                                                <button type="submit" class="btn btn-primary btn-block">
                                                    <i class="fa fa-filter"></i> Filter
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if (isset($groupedTerms) && count($groupedTerms) > 0)
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
                                        <th class="text-center" style="width: 80px;">Weight</th>
                                        @foreach ($supportedLocales as $localeCode => $localeProperties)
                                            <th>{{ $localeProperties['name'] }}</th>
                                        @endforeach
                                        <th class="text-center" style="width: 100px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($groupedTerms as $index => $translations)
                                        <?php
                                        $firstTerm = $translations->first();
                                        $vocabName = $firstTerm->vocabulary->name ?? 'N/A';
                                        $weight = $firstTerm->weight ?? 0;
                                        $tnid = $firstTerm->tnid ?? $firstTerm->id;
                                        $vid = $firstTerm->vid;
                                        
                                        // Create array of translations by language code
                                        $translationsByLang = [];
                                        $invalidLangTerm = null;
                                        foreach ($translations as $trans) {
                                            $lang = $trans->language;
                                            if (!empty($lang) && $lang !== 'und' && isset($supportedLocales[$lang])) {
                                                $translationsByLang[$lang] = $trans;
                                            } else {
                                                // Store first invalid language term (NULL, empty, or 'und')
                                                if (!$invalidLangTerm) {
                                                    $invalidLangTerm = $trans;
                                                }
                                            }
                                        }
                                        ?>
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td><strong>{{ $vocabName }}</strong></td>
                                            <td class="text-center">
                                                <span class="badge badge-info">{{ $weight }}</span>
                                            </td>
                                            @foreach ($supportedLocales as $localeCode => $localeProperties)
                                                <td>
                                                    @if (isset($translationsByLang[$localeCode]))
                                                        <span
                                                            class="text-dark">{{ $translationsByLang[$localeCode]->name }}</span>
                                                    @elseif($invalidLangTerm && $loop->first)
                                                        {{-- Show invalid language term only in first language column --}}
                                                        <span class="badge badge-warning"
                                                            title="Language: {{ $invalidLangTerm->language ?: 'und' }}">
                                                            {{ $invalidLangTerm->name }}
                                                            <small>({{ $invalidLangTerm->language ?: 'und' }})</small>
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                            <td class="text-center">
                                                <a href="{{ url('admin/taxonomy/edit/' . $vid . '/' . $firstTerm->id) . (request('vocabulary') ? '?vocabulary=' . request('vocabulary') : '') }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @elseif(request('vocabulary'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fa fa-info-circle"></i> No taxonomy terms found for the selected vocabulary.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- /.container-fluid-->
        <!-- /.content-wrapper-->
    @endsection

    @push('scripts')
        <script>
            
        </script>
    @endpush
