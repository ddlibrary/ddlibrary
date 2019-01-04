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
        <li class="breadcrumb-item">
            <a href="{{ URL::to('admin/survey/questions/'.$survey->id) }}">Survey's Questions</a>
        </li>

        <li class="breadcrumb-item active">Create Question</li>
      </ol>

      <!-- Surveys Answers DataTables -->
      <div class="card mb-3">

        <div class="card-header">
          <i class="fa fa-plus"></i> Create Question
        </div>

        <div class="card-body">
          
          @if (session('status'))
              <div class="alert alert-success">
                  {{ session('status') }}
              </div>
          @endif

          <form method="POST" action="{{ route('create_question')}}">
            @csrf

            <div class="row">
              <div class="col-sm-6 offset-sm-3">

                <div class="form-group row">
                  <label for="name" class="col-sm-3 col-form-label">Question Text</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="question" name="text" required="true" placeholder="Type question text">
                    <input type="integer" name="survey_id" value="{{$survey->id}}" hidden>
                  </div>
                </div>

                <div class="form-group row">
                  <label for="name" class="col-sm-3 col-form-label">Question Type</label>
                  <div class="col-sm-9">
                    <select class="form-control" id="option_type" required onchange="showOption()" name="type">
                      <option value="descriptive">Descriptive</option>
                      <option value="single_choice">Single Choice</option>
                      <option value="multi_choice">Multi Choice</option>
                    </select>
                  </div>
                </div>
                
                <div class="form-group row" style="display: none" id="options"> 
                  <label for="name" class="col-sm-3 col-form-label">Option Text</label>
              
                  <div class="col-sm-9 d-flex">
                    <input type="text" name="options[]" placeholder="Type option text" class="form-control name_lists"/>
                    <button type="button" name="add" id="add" class="btn btn-success btn-sm">Add More</button>
                  </div>
                </div>

                <div class="row" style="display: none" id="dynamic_options">
                  <div class="col-sm-3"></div>
                  <div class="col-sm-9">
                      <table id="dynamic_field"></table> 
                  </div>
                </div>
               
                <button type="submit" class="btn btn-primary pull-right btn-sm"> Add Question</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- /.container-fluid-->
  <!-- /.content-wrapper-->

  <script src="{{ URL::to('vendor/jquery/jquery.min.js') }}"></script>

  <script type="text/javascript">

    function showOption() {
      var optionText = document.getElementById("option_type");
      var selected_option = optionText.options[optionText.selectedIndex].value;
      console.log(selected_option);
      
      if (selected_option != 'descriptive'){
        document.getElementById("options").style.display = "flex";
        document.getElementById("dynamic_options").style.display = "flex";
      }else{
        document.getElementById("options").style.display = "none";
        document.getElementById("dynamic_options").style.display = "none";
      }
    }


    $(document).ready(function(){      
      var i=1;  

      $('#add').click(function(){  
        i++;  
        $('#dynamic_field').append(
          '<tr id="row'+i+'" class="dynamic-added"><td><input type="text" name="options[]" placeholder="Type option text" class="form-control name_list" required /></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove btn-sm">Remove</button></td></tr>'
        );  
      });

      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      });  

    }); 

</script>

@endsection
