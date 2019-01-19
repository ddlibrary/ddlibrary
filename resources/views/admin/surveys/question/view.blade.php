@extends('admin.layout')

@section('admin.content')
  <div class="content-wrapper">
    <div class="container-fluid">

      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{ URL::to('admin') }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item">
          <a href="{{ URL::to('admin/surveys') }}">Survey</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ URL::to('admin/survey/questions/'.$survey->id) }}">Survey's Questions</a>
        </li>
        <li class="breadcrumb-item active">Translations</li>
      </ol>

      <!-- Surveys Answers DataTables -->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-list"></i> Available Translations
        </div>

        <div class="card-body">
            <table class="table table-bordered" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Question</th>
                  <th>Language</th>
                  <th>OPERATIONS</th>
                </tr>
              </thead>

              <tbody>
                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                  <?php $item = $questions->firstWhere('language',$localeCode);?>
                  @if(count($item))
                    <tr>
                        <td>{{ $item->text }}</td>
                        <td>{{ fixLanguage($item->language) }}</td>
                        <td><span class="badge badge-success">Existed</span></td>
                    </tr>
                  @else
                    <tr>
                      <td><span class="badge badge-warning">Empty</span></td>
                      <td>{{ $properties['name'] }}</td>
                      <td><a href="{{ URL::to('admin/survey/question/add/translate/'.$question_self->tnid.'/'.$localeCode) }}" class="badge badge-primary">Add</a></td>
                    </tr>
                  @endif
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
@endsection
