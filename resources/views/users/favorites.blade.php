@extends('layouts.main')
@section('content')
<section class="general-content">
    <header>
        <h1>@lang('Users Details for') <strong>{{ $user->username }}</strong></h1>
    </header>
    <article>
        @include('users.user_nav')
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('Title')</th>
                    <th>@lang('Actions')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($resources as $resource)
                    <tr>
                        <td>{{ ++$c }}</td>
                        <td>{{ $resource->title }}</td>
                        <td><a href="{{ URL::to('resource/' . $resource->id) }}">@lang('View')</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </article>
</section>
<!-- /.container-fluid-->
<!-- /.content-wrapper-->
@endsection