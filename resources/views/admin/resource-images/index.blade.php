@extends('admin.layout')
@section('admin.content')
    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Breadcrumbs-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ URL::to('admin') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Resource images</li>
            </ol>

            <div class="pb-4">
                <form method="get" action="{{ url('en/admin/resource-images?') }}">
                    @csrf
                    <div class="row">

                        {{-- Search  --}}
                        <div class="col-md-2">
                            <label>Search </label>
                            <input type="text" value="{{ request()->search }}" placeholder="Please search..."
                                class="form-control" name="search">
                        </div>

                        {{-- Subject Area --}}
                        <div class="col-md-2">
                            <label>Subject Area</label>
                            <select
                                class="form-control w-100 box-sizing {{ $errors->has('subject_area_id') ? ' is-invalid' : '' }}"
                                id="subject_area_id" name="subject_area_id">
                                <option value="">...</option>
                                @foreach ($subjects as $item)
                                    @if ($item->parent == 0)
                                        <optgroup label="{{ $item->name }}">
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            <?php if (isset($subjects) && isset($item)) {
                                                $parentItems = $subjects->where('parent', $item->id);
                                            } ?>
                                            @foreach ($parentItems as $pitem)
                                                <option @selected($pitem->id == request()->subject_area_id) value="{{ $pitem->id }}">
                                                    {{ $pitem->name . termEn($pitem->id) }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        {{-- Language --}}
                        <div class="col-md-2">
                            <label>Language </label>
                            <select class="form-control" name="language">
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
                    <i class="fa fa-table"></i> All resource images
                </div>
                <div class="card-body">

                    <span>Total resource images: <strong>{{ $totalResourceImages }}</strong></span>
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>License</th>
                                    <th>Resources</th>
                                    <th>Updated</th>
                                    <th>Edit</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($images as $indexkey => $image)
                                    <tr>
                                        <td>
                                            {{ ($images->currentPage() - 1) * $images->perPage() + $indexkey + 1 }}
                                        </td>
                                        <td><img src="{{ $image->thumbnail_path }}" style="width:150px;"></td>
                                        <td>{{ $image->name }}</td>
                                        <td>{{ $image->license }}</td>
                                        <td>
                                            <ol>
                                                @foreach ($image->resources as $resource)
                                                    <li>
                                                        <a href="{{ url("$resource->language/resource/$resource->id") }}"
                                                            target="_blanket">
                                                            {{ $resource->title }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ol>
                                        </td>
                                        <td>{{ $image->updated_at }}</td>
                                        <td>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $images->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
        <!-- /.container-fluid-->
        <!-- /.content-wrapper-->
    @endsection
