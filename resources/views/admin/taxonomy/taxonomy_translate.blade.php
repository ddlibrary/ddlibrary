@extends('admin.layout')
@section('admin.content')
<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="{{ URL::to('admin') }}">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">Taxonomy</li>
    </ol>
    <!-- Example DataTables Card-->
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i> Taxonomy Translations
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" width="100%" cellspacing="0">
            @if(count($translations))
              @foreach($supportedLocals as $locale)
              <?php 
              $terms = $translations->where('language', $locale);
              $terms = array_values($terms->toArray());
              ?>
              @if(isset($terms[0]['language']))
              <tr>
                <td>{{ $terms[0]['name'] }}</td>
                <td>{{ fixLanguage($terms[0]['language']) }}</td>
                <td><a href="{{ route('taxonomyedit', ['vid' => $terms[0]['vid'], 'id' => $terms[0]['id']]) }}">Edit</a></td>
              </tr>
              @else
              <tr>
                <td>Not translated</td>
                <td>{{ fixLanguage($locale) }}</td>
                <td><a href="{{ route('taxonomytranslatecreate', ['tid' => $tid, 'tnid' => $tnid, 'lang' => $locale]) }}">Add</a></td>
              </tr>
              @endif
              @endforeach
            @else
            Please edit the item first, and then click translate.
            @endif
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- /.container-fluid-->
</div>
<!-- /.content-wrapper-->
@endsection
