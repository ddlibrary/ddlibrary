@extends('admin.layout')
@section('admin.content')
<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="#">Users</a>
        </li>
        <li class="breadcrumb-item active">Profile</li>
        </ol>
        <div class="row">
            <div class="col-lg-12">
                <!-- Example Bar Chart Card-->
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fa fa-list"></i> Update user profile for <strong>{{ ucwords($user->username) }}</strong>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            @include('layouts.messages')
                            <form method="POST" action="{{ route('update_profile') }}">
                            @csrf
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <tbody>
                                <tr>
                                    <td>
                                        <strong>Username</strong>
                                    </td>
                                    <td>
                                        <input type="text" name="username" class="form-control" value="{{ $user->username }}" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Password</strong>
                                    </td>
                                    <td>
                                        <input type="text" name="password" class="form-control" placeholder="Only fill this if you want to change the user's password" value="">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Email</strong>
                                    </td>
                                    <td>
                                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>First Name</strong>
                                    </td>
                                    <td>
                                        <input type="text" name="first_name" class="form-control" value="{{ $user->profile->first_name }}" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Last Name</strong>
                                    </td>
                                    <td>
                                        <input type="text" name="last_name" class="form-control" value="{{ $user->profile->last_name }}" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Gender</strong></td>
                                    <td>
                                        <select name="gender" required>
                                            <option value=""></option>
                                            <option value="Male" {{ ($user->profile->gender=="Male"?"selected":"") }}>Male</option>
                                            <option value="Female" {{ ($user->profile->gender=="Female"?"selected":"") }}>Female</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Phone</strong></td>
                                    <td><input type="text" name="phone" class="form-control" value="{{ $user->profile->phone }}"></a></td>
                                </tr>
                                <tr>
                                    <td><strong>Country</strong></td>
                                    <td>
                                        <select name="country" id="country" onchange="javascript:populate(this,'city', {{ json_encode($provinces) }})" required>
                                            @foreach($countries AS $cn)
                                            <option value="{{ $cn->tnid }}" {{ ($user->profile->country==$cn->tnid?"selected":"") }}>{{ $cn->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>City</strong></td>
                                    <td>
                                        <select name="city" id="city">
                                            <option value=""> - None -</option>
                                            @foreach($provinces AS $pn)
                                            <option value="{{ $pn->tnid }}" {{ ($user->profile->city==$pn->tnid?"selected":"") }}>{{ $pn->name }}</option>
                                            @endforeach
                                        </select>
                                    <input type="text" class="form-control" name="city_other" id="js-text-city" size="40" value="{{ $user->profile->city }}" style="display:none;">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <input class="btn btn-outline-dark" type="submit" value="Update">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid-->
<!-- /.content-wrapper-->
@push('scripts')
    <script src="{{ asset('js/ddl.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('#country').trigger('change');
        });
    </script> 
@endpush
@endsection 