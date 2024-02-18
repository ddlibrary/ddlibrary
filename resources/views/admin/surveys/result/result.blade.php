@extends('admin.layout')

@section('admin.content')
  <div class="content-wrapper">
    <div class="container-fluid">

      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{ URL::to('admin') }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ URL::to('admin/survey_questions') }}">Survey Results</a></li>
        <li class="breadcrumb-item active">Survey Question</li>
      </ol>

      <!-- Surveys Answers DataTables -->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-list"></i> Question Answers Summary
            <button class="btn btn-sm btn-primary float-right" onclick="to_xls()">Export</button>
        </div>

        <div class="card-body">
          <h3 class="badge badge-primary">Question: {{ $question->text }}</h3>

          <table class="table table-bordered" width="100%" cellspacing="0" id="dvData">
              <thead>
                <tr>
                  @if ($question->type == "descriptive")
                    <th>Answers</th>

                  @else
                    <th>Options</th>
                    <th>Language</th>
                    <th>Count</th>
                  @endif
                </tr>
              </thead>

              <tbody>
                @if ($question->type != "descriptive")
                  @foreach (\App\Models\SurveyAnswer::getAnswersByOption($question->id) as $option)
                    <tr>
                      <td>{{ $option->text }}</td>
                      <td>{{ fixLanguage($option->language) }}</td>
                      <td>{{ $option->total }}</td>
                    </tr>
                  @endforeach
                @else
                  @foreach ($descriptive_answers as $answer)
                  @if(!empty ($answer->description))
                    <tr>
                      <td>{{ $answer->description }}</td>
                    </tr>
                  @endif
                  @endforeach
                @endif

              </tbody>
            </table>
        </div>

      </div>
    </div>
  </div>
  <!-- /.container-fluid-->
  <!-- /.content-wrapper-->
  @endsection

  @push('scripts')
  <script>
    
	function to_xls()
	{
		//is_valid('csv');
		var dt = new Date();
        var day = dt.getDate();
        var month = dt.getMonth() + 1;
        var year = dt.getFullYear();
        var hour = dt.getHours();
        var mins = dt.getMinutes();
        var postfix = day + "." + month + "." + year + "_" + hour + "." + mins;

		var htmls = "";
        var uri = 'data:application/vnd.ms-excel;base64,';
        var template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'; 
        var base64 = function(s) {
            return window.btoa(unescape(encodeURIComponent(s)))
        };

        var format = function(s, c) {
            return s.replace(/{(\w+)}/g, function(m, p) {
                return c[p];
            })
        };

        htmls = $('#dvData').html();

        var ctx = {
            worksheet : 'Worksheet',
            table : htmls
        }


        var link = document.createElement("a");
        link.download = 'Survey_Result_' + postfix + '.xls';
        link.href = uri + base64(format(template, ctx));
        link.click();
	}
  </script>
  @endpush