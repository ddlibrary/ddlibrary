@extends('admin.layout')
@section('admin.content')
    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Breadcrumbs-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ URL::to('admin') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Subscribers</li>
            </ol>
            <!-- Example DataTables Card-->
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-table"></i> All Subscribers
                </div>
                <div class="card-body">
                    @include('layouts.messages')
                    <div class="table-responsive">
                        <span class="pull-left">Total: <strong>{{ $totalSubscribers }}</strong></span>
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Created At</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subscribers as $subscriber)
                                    <tr>
                                        <td>{{ ($subscribers->currentPage() - 1) * $subscribers->perPage() + $loop->iteration }}
                                        </td>
                                        <td>
                                            <a href="{{ url('admin/user/edit/' . $subscriber->user_id) }}">
                                                {{ $subscriber->name }}
                                            </a>
                                        </td>
                                        <td>{{ $subscriber->email }}</td>
                                        <td>{{ $subscriber->created_at ? $subscriber->created_at->diffForHumans() : 'Not Added' }}</td>
                                        <td class="text-center">
                                            <form action="{{ url('admin/subscribers/'.$subscriber->id) }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <button onclick="return confirm('Are you sure to delete this item?')" class="btn btn-danger" type="submit">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $subscribers->appends(['search' => request()->query('search')])->links() }}
                </div>
            </div>
        </div>
        <!-- /.container-fluid-->
        <!-- /.content-wrapper-->
    @endsection
