@extends('admin.layout')
@section('admin.content')
@include('admin.notifications.toast')
    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Breadcrumbs-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ URL::to('admin') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Resources without publisher</li>
            </ol>
            
            <div class="pb-4">
                <form method="get" action="{{ route('resource-without-publishers') }}">
                    @csrf
                    <div class="row">

                        {{-- Title  --}}
                        <div class="col-md-2">
                            <label>Title </label>
                            <input type="text" value="{{ isset($filters['title']) ? $filters['title'] : '' }}"
                                placeholder="Please search..." class="form-control" name="title">
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

                        {{-- Without publisher --}}
                        <div class="col-md-2">
                            <label>Without publisher</label>
                            <select class="form-control" name="without_publisher">
                                <option value="">...</option>
                                <option value="1"
                                    {{ isset($filters['without_publisher']) && $filters['without_publisher'] == 1 ? 'selected' : '' }}>
                                    Yes</option>
                              
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
                                    <th>Title</th>
                                    <th>Publisher</th>
                                    <th>Added By</th>
                                    <th>Published</th>
                                    <th>Updated</th>
                                    <th>Language</th>
                                    <th>Operations</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>NO</th>
                                    <th>Title</th>
                                    <th>Publisher</th>
                                    <th>Added By</th>
                                    <th>Published</th>
                                    <th>Updated</th>
                                    <th>Language</th>
                                    <th>Operations</th>
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
                                        <td>
                                            <div>
                                                <div class="row" style="width: 100%">
                                                    <div class="col-9">
                                                        <input type="text" value="{{ $resource->publishers->first()?->name}}" class="form-control make-disable item-{{$resource->id}}"  placeholder="Please add publisher">
                                                    </div>
                                                    <div class="col-3">
                                                        <button class="btn btn-success make-disable" onclick="addPublisher({{ $resource->id }})">Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ URL::to('users/view/' . $resource->user_id) }}">
                                                {{ $resource->user?->username }}
                                            </a>
                                        </td>
                                        <td><a
                                                href="{{ URL::to('admin/resource/published/' . $resource->id) }}">{{ $resource->status == 0 ? 'Not Published' : 'Published' }}</a>
                                        </td>
                                        <td>{{ $resource->updated_at }}</td>
                                        <td>{{ fixLanguage($resource->language) }}</td>
                                        <td>
                                            <a href="{{ URL::to('resources/edit/step1/' . $resource->id) }}">Edit</a> 
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

        <script>
            function addPublisher(resourceId) {
            const name = $(`.item-${resourceId}`).val();
            if(confirm('Are you sure to change the publisher?')){
                if (name) {
                    $('.make-disable').attr('disabled', true)
                    $.ajax({
                        url: "{{ url('resources/add-publisher') }}",
                        method: 'POST',
                        data: {
                            name: name,
                            resource_id: resourceId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            showToast(response.message, 'success');
                        },
                        error: function(xhr) {
                            const errorMessage = xhr.responseJSON?.message || 'Failed to add publisher.';
                            showToast(errorMessage, 'error');
                        },
                        complete: function() {
                            $('.make-disable').attr('disabled', false);
                        }
                    });
                } else {
                    showToast('Please enter a publisher name.', 'error');
                }
            }
        }
        </script>
    @endsection
