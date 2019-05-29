@extends('admin.analytics.analytics_main')
@section('analytics.content')
<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-list"></i> Total Resources by Subject Area
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                            <th>Name</th>
                            <th>Period</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($fbRecords as $fb)
                        <tr>
                            <td>{{ $fb['name'] }}</td>
                            <td>{{ $fb['period'] }}</td>
                            <td>{{ $fb['title'] }}</td>
                            <td>{{ $fb['description'] }}</td>
                            <td><strong>{{ $fb['values'][0]['value'] }}</strong></td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection