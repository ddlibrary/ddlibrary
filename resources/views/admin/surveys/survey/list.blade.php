@extends('admin.layout')

@section('admin.content')
  <div class="content-wrapper">
    <div class="container-fluid">

      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{ URL::to('admin') }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Survey</li>
      </ol>

      <!-- Surveys Answers DataTables -->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-list"></i> Surveys
        </div>

        <div class="card-body">
            @if (session('status'))
              <div class="alert alert-success">
                  {{ session('status') }}
              </div>
            @endif
            <a href="{{ URL::to('admin/survey/create') }}" class="btn btn-success pull-right" style="margin-bottom: 10px">
              <span class="fa fa-plus"></span> Add New
            </a>
            <span>Total: <strong>{{count($surveys)}}</strong></span>
            <table class="table table-bordered" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Language</th>
                  <th>Status</th>
                  <th>Created At</th>
                  <th>Updated At</th>
                  <th>OPERATIONS</th>
                </tr>
              </thead>

              <tbody>
                @foreach ($surveys as $indexkey => $survey)
                  <tr>
                    <td>{{ $survey-> name }}</td>
                    <td>{{ fixLanguage($survey->language) }}</td>
                    <td>
                      @if ($survey->state == 'published')
                        <span class="badge badge-success">Published</span>
                      @else
                        <span class="badge badge-warning">Draft</span>
                      @endif
                    </td>
                    <td>{{ $survey->created_at->diffForHumans() }}</td>
                    <td>{{ $survey->updated_at->diffForHumans() }}</td>
                    <td style="display: flex;">
                      <a href="{{ URL::to('admin/survey/questions/'.$survey->id) }}" class="badge badge-primary" style="margin-right: 5px;">Questions</a>
                      <a href="{{ URL::to('admin/survey/edit/'.$survey->id) }}" class="badge badge-primary" style="margin-right: 5px;">Edit</a>
                      <a href="{{ URL::to('admin/survey/view/'.$survey->id.'/'.$survey->tnid) }}" class="badge badge-primary" style="margin-right: 5px;">Translations</a>
                      <a href="javascript:void(0)" id="{{$survey->id}}" onclick="confirm(this.id);" class="badge badge-danger">Delete</a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->

    <!-- Modal for confirmation -->
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Are you sure want to delete this survey?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success btn-sm" data-dismiss="modal">No</button>
            <a class="delete"><button type="button" class="btn btn-danger btn-sm confirm">Yes</button></a>
          </div>
        </div>
      </div>
    </div>

    <!--javascript function for deleting a survey/starts-->
    <script>
        function confirm(id){
            $("#confirmModal").modal('show');
            $('.confirm').click(function(){
                var url='/admin/survey/delete/'+id;
                $('a.delete').attr('href',url);
            });
        }
    </script>
    <!--end-->
@endsection
