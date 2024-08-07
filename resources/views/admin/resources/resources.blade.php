@extends('admin.layout')
@section('admin.content')
    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Breadcrumbs-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ URL::to('admin') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Resources</li>
            </ol>
            
            <div class="pb-4">
                <form method="POST" action="{{ route('resources') }}">
                    @csrf
                    <div class="row">

                        {{-- Title  --}}
                        <div class="col-md-2">
                            <label>Title </label>
                            <input type="text" value="{{ isset($filters['title']) ? $filters['title'] : '' }}"
                                placeholder="Please search..." class="form-control" name="title">
                        </div>

                        {{-- From  --}}
                        <div class="col-md-2">
                            <label>From </label>
                            <input type="date" value="{{ request()->date_from }}" class="form-control" name="date_from">
                        </div>

                        {{-- To --}}
                        <div class="col-md-2">
                            <label>To </label>
                            <input type="date" value="{{ request()->date_to }}" class="form-control" name="date_to">
                        </div>

                        {{-- Is Published --}}
                        <div class="col-md-2">
                            <label>Is Published</label>
                            <select class="form-control" name="status">
                                <option value="">...</option>
                                <option value="1"
                                    {{ isset($filters['status']) && $filters['status'] == 1 ? 'selected' : '' }}>
                                    Yes</option>
                                <option value="2"
                                    {{ isset($filters['status']) && $filters['status'] == 2 ? 'selected' : '' }}>
                                    No</option>
                            </select>
                        </div>


                        {{-- Language --}}
                        <div class="col-md-2">
                            <label>Language </label>
                            <select class="form-control" name="language">
                                <option value="">...</option>
                                @foreach ($languages as $key => $value)
                                    <option @selected(request()->language == $key) value="{{ $key }}">
                                        {{ $value['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filter button --}}
                        <div class="col-md-2" style="align-self: flex-end">
                            <input class="btn btn-primary" type="submit" value="Filter">
                        </div>
                    </div>
                </form>
            </div>

            <!-- Example DataTables Card-->
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-table"></i> All Resources
                </div>
                <div class="card-body">

                    <span>Total: <strong>{{ $resources->total() }}</strong></span>
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>TITLE</th>
                                    <th>ADDED BY</th>
                                    <th>PUBLISHED</th>
                                    <th>UPDATED</th>
                                    <th>LANGUAGE</th>
                                    <th>OPERATIONS</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>NO</th>
                                    <th>TITLE</th>
                                    <th>ADDED BY</th>
                                    <th>PUBLISHED</th>
                                    <th>UPDATED</th>
                                    <th>LANGUAGE</th>
                                    <th>OPERATIONS</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($resources as $indexkey => $resource)
                                    <tr>
                                        <td>{{ ($resources->currentPage() - 1) * $resources->perPage() + $indexkey + 1 }}
                                        </td>
                                        <td><a
                                                href="{{ URL::to($resource->language . '/' . 'resource/' . $resource->id) }}">{{ $resource->title }}</a>
                                        </td>
                                        <td><a
                                                href="{{ URL::to('users/view/' . $resource->user_id) }}">{{ $resource->addedby }}</a>
                                        </td>
                                        <td><a
                                                href="{{ URL::to('admin/resource/published/' . $resource->id) }}">{{ $resource->status == 0 ? 'Not Published' : 'Published' }}</a>
                                        </td>
                                        <td>{{ $resource->updated_at }}</td>
                                        <td>{{ fixLanguage($resource->language) }}</td>
                                        <td>
                                            <a href="{{ URL::to('resources/edit/step1/' . $resource->id) }}">Edit</a> |
                                            <a href="resource/delete/{{ $resource->id }}"
                                                onclick="return confirm('Are you sure you want to delete this resource?');">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $resources->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
        <!-- /.container-fluid-->
        <!-- /.content-wrapper-->
    @endsection
