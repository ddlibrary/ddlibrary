@extends('admin.layout')
@section('admin.content')
<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="#">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">Settings</li>
    </ol>
    <!-- Example DataTables Card-->
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i> All Settings</div>
      <div class="card-body">
          @include('layouts.messages')
          <form method="POST" action="{{ route('settings') }}">
          @csrf
          <div class="form-group">
              <label for="website_name"> 
                  <strong>Website Name</strong>
                  <span class="text-danger" title="This field is required.">*</span>
              </label>
              <input class="form-control" id="website_name" name="website_name"  value="{{ $setting->website_name?$setting->website_name:old('website_name') }}" type="text" required>
          </div>

          <div class="form-group">
              <label for="website_slogan"> 
                  <strong>Website Slogan</strong>
                  <span class="text-danger" title="This field is required.">*</span>
              </label>
              <input class="form-control" id="website_slogan" name="website_slogan"  value="{{ $setting->website_slogan?$setting->website_slogan:old('website_slogan') }}" type="text" required>
          </div>
          
          <div class="form-group">
              <label for="website_email"> 
                  <strong>Website Email</strong>
                  <span class="text-danger" title="This field is required.">*</span>
              </label>
              <input class="form-control" id="website_email" name="website_email"  value="{{ $setting->website_email?$setting->website_email:old('website_email') }}" type="email" required>
          </div>
          
          <input class="btn btn-primary" type="submit" value="Save">
          </form>
      </div>
    </div>
  </div>
  <!-- /.container-fluid-->
  <!-- /.content-wrapper-->
  @endsection