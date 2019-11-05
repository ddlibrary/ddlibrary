@extends('layouts.main')

@section('content')
<section class="ddl-forms">
    <header>
        <h1>@lang('Add User') | <a href="{{ url('/admin/users') }}">View Users List</a></h1> 
    </header>
    <script>
        $(document).on('click', '#save', function() {
            $.ajax({
                url:'/api/store_user',
                data:new FormData($("#submit_user")[0]),
                dataType:'json',
                async:false,
                type:'post',
                processData: false,
                contentType: false,
                success:function(response){
                    $('#result').append('the user saved successfull');
                },
            });
            $('#username').val('');
            $('#email').val('');
            $('#password').val('');
            
            return false;
        });
    </script>
    <div class="content-body">
        @include('layouts.messages')
        <form method="POST" enctype="multipart/form-data" id="submit_user">
        @csrf
        <div>
            <label for="" class="lable label-success"><strong id="result"></strong></label>
        </div>
        <div class="form-item">
            <label for="username"> 
                <strong>@lang('UserName')</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <input class="form-control{{ $errors->has('UserName') ? ' is-invalid' : '' }}" id="username" name="UserName" size="40" type="text" value="{{ old('UserName') }}" required autofocus>
            @if ($errors->has('UserName'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('UserName') }}</strong>
                </span><br>
            @endif
        </div>
        <div class="form-item">
            <label for="email"> 
                <strong>@lang('Email')</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <input class="form-control{{ $errors->has('Email') ? ' is-invalid' : '' }}" id="email" name="Email" size="40" type="email" value="{{ old('Email') }}" required autofocus>
            @if ($errors->has('Email'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('Email') }}</strong>
                </span><br>
            @endif
        </div>
        <div class="form-item">
            <label for="password"> 
                <strong>@lang('Password')</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <input class="form-control{{ $errors->has('Password') ? ' is-invalid' : '' }}" id="password" name="Password" size="40" type="password" value="{{ old('Password') }}" required autofocus>
            @if ($errors->has('Password'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('Password') }}</strong>
                </span><br>
            @endif
        </div>
        <div class="form-item">
            <label for="status"> 
                <strong>@lang('Status')</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <select class="form-control{{ $errors->has('Status') ? ' is-invalid' : '' }}" name="Status" id="status" required>
                <option value="">- @lang('Choose Status') -</option>
                
                <option value="1" {{ old('Status') == '1' ? "selected" : "" }}>Active</option>
                <option value="0" {{ old('Status') == '0' ? "selected" : "" }}>Deactive</option>

            </select>
        </div>
        <div class="form-item">
            <label for="role"> 
                <strong>@lang('Role')</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <select class="form-control{{ $errors->has('Role') ? ' is-invalid' : '' }}" name="Role" id="role" required>
                <option value="">- @lang('Choose role') -</option>
               <?php $db_roles=DB::table('roles')->get();
                foreach($db_roles as $item){ ?>
                <option value="{{ $item->id }}" {{ old('Role') == $item->name ? "selected" : "" }}>{{ $item->name }}</option>
                <?php } ?>
            </select>
        </div>
        <div class="personal-information">
            <div class="right-side">
                <div class="form-item">
                    <label for="first_name"> 
                        <strong>First Name</strong>
                        <span class="form-required" title="This field is required.">*</span>
                    </label>
                    <input class="form-control" id="first_name" name="first_name" value="" size="40" type="text" required="">
                </div>
            </div>
            <div class="left-side">
                <div class="form-item">
                    <label for="last_name"> 
                        <strong>Last Name</strong>
                        <span class="form-required" title="This field is required.">*</span>
                    </label>
                    <input class="form-control" id="last_name" name="last_name" value="" size="40" type="text" required="">
                </div>
            </div>
            <div class="right-side">
                <div class="form-item">
                    <label for="phone"> 
                        <strong>Telephone Number</strong>
                    </label>
                    <input class="form-control" id="phone" name="phone" value="" size="40" type="tel" style="width: 327px;" required="">
                                    </div>
            </div>
            <div class="left-side">
                <div class="form-item">
                    <label for="gender"> 
                        <strong>Gender</strong>
                        <span class="form-required" title="This field is required.">*</span>
                    </label>
                    <select class="form-control" name="gender" id="gender" required="">
                        <option value="">- None -</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
            </div>
            <div class="right-side">
                <div class="form-item">
                    <label for="country"> 
                        <strong>@lang('Country')</strong>
                        <span class="form-required" title="This field is required.">*</span>
                    </label>
                    <select class="form-control{{ $errors->has('country') ? ' is-invalid' : '' }}" name="country" id="country" onchange="javascript:populate(this,'city', {{ json_encode($provinces) }})" required>
                        <option value="">- @lang('None') -</option>
                        @foreach($countries AS $cn)
                        <option value="{{ $cn->tnid }}" {{ old('country') == $cn->tnid ? "selected" : "" }}>{{ $cn->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="left-side">
                <div class="form-item">
                    <label for="city"> 
                        <strong>@lang('City')</strong>
                    </label>
                    <select class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" id="city">
                        <option value="">- @lang('None') -</option>
                    </select>
                    <input type="text" class="form-control" name="city_other" id="js-text-city" size="40" style="display:none;">
                </div>
            </div>

        </div>
        <div class="left-side">
            <input class="form-control normalButton" id="save" type="button" value="@lang('Submit')">
        </div>
        </form>
    </div>
</section>

@endsection
