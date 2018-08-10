@extends('layouts.main')

@section('content')
<section class="ddl-forms">
    <header>
        <h1>@lang('Reset Password')</h1>
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
                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                        @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-item">
                    <input type="submit" class="form-control" value="{{ __('Send Password Reset Link') }}" onclick="this.style.display='none';document.getElementById('wait').style.display='block'" ondblclick="this.style.display='display';document.getElementById('wait').style.display='block'">

                    <input type="button" class="form-control" id="wait" value="@lang('Please wait..')" style="color:red;display:none" disabled>
                </div>
            </form>
        </div>
</section>
@endsection
