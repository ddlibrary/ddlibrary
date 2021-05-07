@extends('admin.layout')
@section('admin.content')
    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Breadcrumbs-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ URL::to('admin') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">Glossary</li>
                <li class="breadcrumb-item">
                    <a href="{{ URL::to('admin/glossary_subjects') }}">Subjects</a>
                </li>
                <li class="breadcrumb-item active">@if($glossary_subject)Edit @else Create @endif</li>
            </ol>
            @include('layouts.messages')
            <div class="row">
                <div class="col-lg-12">
                    <!-- Example Bar Chart Card-->
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fa fa-list"></i> <strong>@if($glossary_subject) Edit @else Create @endif a glossary subject</strong>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <form method="POST" action="{{ route('glossary_subjects_update') }}">
                                    @csrf
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <strong>English</strong>
                                                </td>
                                                <td>
                                                    <input type="text" name="english" class="form-control" value="@if($glossary_subject){{ $glossary_subject->en }}@endif">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>Farsi</strong>
                                                </td>
                                                <td>
                                                    <input type="text" name="farsi" class="form-control" value="@if($glossary_subject){{ $glossary_subject->fa }}@endif">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>Pashto</strong>
                                                </td>
                                                <td>
                                                    <input type="text" name="pashto" class="form-control" value="@if($glossary_subject){{ $glossary_subject->ps }}@endif">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>Munji</strong>
                                                </td>
                                                <td>
                                                    <input type="text" name="munji" class="form-control" value="@if($glossary_subject){{ $glossary_subject->mj }}@endif">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>Nuristani</strong>
                                                </td>
                                                <td>
                                                    <input type="text" name="nuristani" class="form-control" value="@if($glossary_subject){{ $glossary_subject->no }}@endif">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>Pashayi</strong>
                                                </td>
                                                <td>
                                                    <input type="text" name="pashayi" class="form-control" value="@if($glossary_subject){{ $glossary_subject->pa }}@endif">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>Shughni</strong>
                                                </td>
                                                <td>
                                                    <input type="text" name="shughni" class="form-control" value="@if($glossary_subject){{ $glossary_subject->sh }}@endif">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>Swahili</strong>
                                                </td>
                                                <td>
                                                    <input type="text" name="swahili" class="form-control" value="@if($glossary_subject){{ $glossary_subject->sw }}@endif">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>Uzbek</strong>
                                                </td>
                                                <td>
                                                    <input type="text" name="uzbek" class="form-control" value="@if($glossary_subject){{ $glossary_subject->uz }}@endif">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <input type="hidden" name="id" value="@if($glossary_subject){{ $glossary_subject->id }}@else new @endif">
                                    <span style="display: block; padding-bottom: 10px;">* All of the fields are required. If you do not know the
                                        translation of a subject word in a particular language, fill in the English version as a stopgap.</span>
                                    <input class="btn btn-outline-dark" type="submit" value="@if($glossary_subject)Update @else Create @endif">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

