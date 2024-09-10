@extends('layouts.main')
@section('content')
<section class="general-content">
    <header>
        <h1>{{ __('Users Details for') }} <strong>{{ $user->username }}</strong></h1>
    </header>
    <article>
        @include('users.user_nav')
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('Title') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                <?php $c = 0;?>
                @foreach ($resources as $resource)
                    <tr>
                        <td>{{ ++$c }}</td>
                        <td>{{ $resource->title }}</td>
                        <td><a href="{{ URL::to('resource/' . $resource->id) }}">{{ __('View') }}</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </article>
</section>
<!-- /.container-fluid-->
<!-- /.content-wrapper-->
@endsection
