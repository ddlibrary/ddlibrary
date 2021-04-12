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
            <li class="breadcrumb-item active">Edit</li>
        </ol>
        <div class="row">
            <div class="col-lg-12">
                <!-- Example Bar Chart Card-->
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fa fa-list"></i> <strong>Edit a glossary subject</strong>
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
                                                <input type="text" name="english" class="form-control" value="{{ $glossary_subject->en }}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Farsi</strong>
                                            </td>
                                            <td>
                                                <input type="text" name="farsi" class="form-control" value="{{ $glossary_subject->fa }}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Pashto</strong>
                                            </td>
                                            <td>
                                                <input type="text" name="pashto" class="form-control" value="{{ $glossary_subject->ps }}">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <input type="hidden" name="id" value="{{ $glossary_subject->id }}">
                                <input class="btn btn-outline-dark" type="submit" value="Update">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

