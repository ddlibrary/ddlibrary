@extends('admin.layout')
@section('admin.content')
<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="#">Users</a>
        </li>
        <li class="breadcrumb-item active">Resource Details</li>
        </ol>
        <div class="row">
            <div class="col-lg-12">
                <!-- Example Bar Chart Card-->
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fa fa-list"></i> Total Users by Gender
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <tbody>
                                <tr>
                                    <td><strong>Language</strong></td>
                                    <td>{{ fixLanguage($resource->language) }}</a></td>
                                    <td><strong>Title</strong></td>
                                    <td>{{ $resource->title }}</a></td>
                                </tr>
                                <tr>
                                    <td><strong>Resource Level</strong></td>
                                    <td> {{ unpackResourceObject($resourceLevels, 'resource_level') }}</td>
                                    <td><strong>Resource Level</strong></td>
                                    <td> {{ unpackResourceObject($resourceSubjectAreas,'subject_area') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Learning Resource Type</strong></td>
                                    <td> {{ unpackResourceObject($resourceLearningResourceTypes,'learning_resource_type') }}</td>
                                    <td><strong>Publishers</strong></td>
                                    <td> {{ unpackResourceObject($resourcePublishers,'publisher_name') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Licence</strong></td>
                                    <td> {{ giveMeCC($resource->creative_commons) }}</td>
                                    <td><strong>Author</strong></td>
                                    <td>{{ $resource->author }}</a></td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td colspan="3">{{ ($resource->status==0?"Not Published":"Published") }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Abstract</strong></td>
                                    <td colspan="3">{!! checkAbstract($resource->abstract) !!}</td>
                                </tr>
                                </tbody>
                            </table>
                            <input class="btn btn-outline-dark" type="button" value="Edit">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid-->
<!-- /.content-wrapper-->
@endsection