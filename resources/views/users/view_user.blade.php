@extends('layouts.main')
@section('content')
<section class="general-content">
        <header>
            <h1>@lang('Users Details for') <strong>{{ $user->username }}</h1>
        </header>
        <article>
            <div>
                <span><strong>@lang('First Name'):</strong></span>
                <span>{{ $user->first_name }}</a></span>
            </div>
            <div>
                <span><strong>@lang('Last Name'):</strong></span>
                <span>{{ $user->last_name }}</a></span>
            </div>
            <div>
                <span><strong>@lang('Username'):</strong></span>
                <span>{{ $user->username }}</a></span>
            </div>
            <div>
                <span><strong>@lang('Email'):</strong></span>
                @if(Auth::id() == $user->id || isAdmin())
                <span>{{ $user->email }}</a>(@lang('Only visible to you'))</span>
                @else
                <span><i>(@lang('hidden'))</i></span>
                @endif
            </div>
            <div>
                <span><strong>@lang('Status'):</strong></span>
                <span>{{ ($user->status==0?"Not Active":"Active") }}</a></span>
            </div>
            <div>
                <span><strong>@lang('Created'):</strong></span>
                <span>{{ $user->created_at }}</a></span>
            </div>
            <div>
                <span><strong>@lang('Access'):</strong></span>
                <span>{{ $user->accessed_at }}</a></span>
            </div>
        </article>
</section>
<!-- /.container-fluid-->
<!-- /.content-wrapper-->
@endsection