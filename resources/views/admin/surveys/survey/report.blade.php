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
            <a href="{{ URL::to('admin/surveys') }}">Surveys</a>
          </li>
        <li class="breadcrumb-item active">Report {{ $survey->name}}</li>
      </ol>

      <!-- Surveys Answers DataTables -->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-list"></i> Report
          <button class="btn btn-sm btn-primary float-right" onclick="doit(&#39;xlsx&#39;);">Export</button>
        </div>

        <div class="card-body" style="width:100%; overflow:scroll;">
            @if (session('status'))
              <div class="alert alert-success">
                  {{ session('status') }}
              </div>
            @endif
            
            <table class="table table-bordered table-condensed" width="100%" cellspacing="0" id="dvData">
              <thead style="text-align:center;">
                <tr>
                  <th rowspan="2">Question</th>
                  <th rowspan="2">Type</th>
                  <th rowspan="2">Options</th>
                  <th colspan="3">Language</th>
                </tr>
                <tr>
                  <th>English</th>
                  <th>Dari</th>
                  <th>Pashto</th>
                </tr>
              </thead>

              <tbody>
                @foreach ($survey_questions as $indexkey => $survey_question)
                  <tr>
                    <td>{{ $survey_question-> text }}</td>
                    <td>
                      @if ($survey_question->type == 'single_choice')
                        <span>Single Choice</span>
                      @elseif ($survey_question->type == 'multi_choice')
                        <span>Multiple Choice</span>
                      @else
                        <span>Descriptive</span>
                      @endif
                    </td>
                    <td>
                        @foreach(\App\SurveyAnswer::getQuestionOptions($survey_question->id) as $qoption)
                        <i class="badge badge-primary">{{ ($survey_question->type != 'descriptive') ? $qoption->text : '' }}</i> <br>
                        @endforeach
                    </td>
                    @foreach(['en', 'fa', 'ps'] as $lang)
                    <td>                            
                        @if($survey_question->type != 'descriptive')

                            @foreach(\App\SurveyAnswer::getQuestionOptions($survey_question->id) as $qoption)
                              <?php $data = (\App\SurveyAnswer::getAnswerAmount($survey_question->id, $qoption->id, $lang)); ?>
                              {{ ($data) ? $data->total : 0 }}<br>
                            @endforeach

                        @else
                            <?php $data = (\App\SurveyAnswer::getDescriptiveAnswers($survey_question->id, $lang)); ?>
                            {{ ($data) ? $data->total : 0 }}<br>
                        @endif
                    </td>
                    @endforeach
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
            <p>Are you sure want to delete this question?</p>
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
                var url='/admin/survey/question/delete/'+id;
                $('a.delete').attr('href',url);
            });
        }
    </script>
    <!--end-->

  @endsection

  @push('scripts')
  <script>
      function doit(type, fn, dl) {
        var elt = document.getElementById("dvData");
        var wb = XLSX.utils.table_to_book(elt, { sheet: "Sheet JS" });
        return dl
          ? XLSX.write(wb, { bookType: type, bookSST: true, type: "base64" })
          : XLSX.writeFile(wb, fn || '{{ $survey->name}}_Survey_Report_' + '{{ date("d-M-Y h:i:s") }}.' + (type || "xlsx"));
      }
    </script>
  @endpush