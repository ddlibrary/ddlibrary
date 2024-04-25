@extends('admin.layout')
@section('admin.content')
    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Breadcrumbs-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ URL::to('admin') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Downloads</li>
            </ol>
            <!-- Example DataTables Card-->

            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-table"></i> All Downloads
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="pb-4">
                            <form method="POST" action="{{ route('downloads') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>From Date</label>
                                        <input type="date"
                                            value="{{ isset($filters['date_from']) ? $filters['date_from'] : '' }}"
                                            class="form-control" name="date_from">
                                    </div>
                                    <div class="col-md-2">
                                        <label>To Date</label>
                                        <input type="date"
                                            value="{{ isset($filters['date_to']) ? $filters['date_to'] : '' }}"
                                            class="form-control" name="date_to">
                                    </div>

                                    <div class="col-md-2">
                                        <label>Gender</label>
                                        <select class="form-control" name="gender">
                                            <option value="">...</option>
                                            @foreach ($genders as $gender)
                                            <option @selected($filters['gender'] == $gender) value="{{ $gender }}">
                                              {{ $gender }}</option>
                                            @endforeach
                                            <option @selected($filters['gender'] == 'guest') value="guest">Guest User</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Language</label>
                                        <select class="form-control" name="language">
                                            <option value="">...</option>
                                            @foreach ($languages as $key => $value)
                                                <option @selected($filters['language'] == $key) value="{{ $key }}">
                                                    {{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2" style="align-self: flex-end">
                                        <input class="btn btn-primary" type="submit" value="Filter">
                                    </div>

                                </div>

                            </form>
                        </div>

                        <span class="pull-left">Total: <strong>{{ $records->total() }}</strong></span>
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>RESOURCE</th>
                                    <th>FILE</th>
                                    <th>USERID</th>
                                    <th>IP ADDRESS</th>
                                    <th>VISITED</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>NO</th>
                                    <th>RESOURCE</th>
                                    <th>FILE</th>
                                    <th>USERID</th>
                                    <th>IP ADDRESS</th>
                                    <th>VISITED</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($records as $indexkey => $record)
                                    <tr>
                                        <td>{{ ($records->currentPage() - 1) * $records->perPage() + $indexkey + 1 }}</td>
                                        <td><a
                                                href="{{ URL::to($record->resource->language . '/' . 'resource/' . $record->resource->id) }}">{{ $record->resource->title }}</a>
                                        </td>
                                        <td><a
                                                href="{{ URL::to($record->resource->language . '/' . 'resource/' . $record->resource->id) }}">{{ $record->file->file_name ?? '-' }}</a>
                                        </td>
                                        @if ($record->user)
                                            <td><a
                                                    href="{{ URL::to('user/' . $record->user->id) }}">{{ $record->user->username }}</a>
                                            </td>
                                        @else
                                            <td>{{ $record->user_id }}</td>
                                        @endif
                                        <td>{{ $record->ip_address }}</td>
                                        <td>{{ $record->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pt-4">
                      {{ $records->links() }}
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid-->
        <!-- /.content-wrapper-->
    @endsection
