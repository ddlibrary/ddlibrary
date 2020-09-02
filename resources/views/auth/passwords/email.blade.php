@extends('layouts.main')
@section('title')
    @lang('Reset your password') - @lang('Darakht-e Danesh Library')
@endsection
@section('content')
<section class="ddl-forms">
    <header>
        <h3>@lang('Reset your password')</h3>
    </header>
    <div class="content-body">
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-item">
                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Your email address') }}</label>

                    <div class="col-md-8">
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" style="width: 377px;" name="email" value="{{ old('email') }}" required>

                        @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-item">
                    <input type="submit" class="form-control" value="{{ __('Send password reset link') }}" onclick="this.style.display='none';document.getElementById('wait').style.display='block'" ondblclick="this.style.display='display';document.getElementById('wait').style.display='block'">

                    <input type="button" class="form-control" id="wait" value="@lang('Please wait..')" style="color:red;display:none" disabled>
                </div>
            </form>
            <span style="font-size: 0.9rem;">@lang('If you registered using a phone number, please <a href="'.URL::to('contact-us').'">contact us</a>.')</span>
        </div>
    </div>
</section>
@endsection
