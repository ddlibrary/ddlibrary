
<!-- Modal content -->
<div id="surveyModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close" id="survey-close">&times;</span>
            <h3>@lang('DDL Survey')</h3>
        </div>
        <div class="modal-body">
            
            <div class="survey_content">
                <div class="progress">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="1" aria-valuemin="1" aria-valuemax="5" style="width: 10%;">
                    Survey 1 of 5
                    </div>
                </div>
    
                <div class="navbar" style="display:none;">
                    <div class="navbar-inner">
                        <ul class="nav nav-pills">
                            <li class="active"><a href="#step1" data-toggle="tab" data-step="1">Survey 1</a></li>
                            <li><a href="#step2" data-toggle="tab" data-step="2">Survey 2</a></li>
                            <li><a href="#step3" data-toggle="tab" data-step="3">Survey 3</a></li>
                            <li><a href="#step4" data-toggle="tab" data-step="4">Survey 4</a></li>
                            <li><a href="#step5" data-toggle="tab" data-step="5">Survey 5</a></li>
                        </ul>
                    </div>
                </div>
        
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="step1">
                        <div class="well">
                            <h4>Step 3</h4>
                        </div>
                        <a class="btn btn-primary next" href="#">Next</a>
                    </div>
    
                    <div class="tab-pane fade" id="step2">
                        <div class="well">
                            <h4>Step 2</h4>
                        </div>
                        <a class="btn btn-primary next" href="#">Next</a>
                    </div>
    
                    <div class="tab-pane fade" id="step3">
                        <div class="well"> 
                            <h4>Step 3</h4>
                        </div>
                        <a class="btn btn-primary next" href="#">Next</a>
                    </div>
    
                    <div class="tab-pane fade" id="step4">
                        <div class="well"> 
                            <h4>Step 4</h4>
                        </div>
                        <a class="btn btn-primary next" href="#">Next</a>
                    </div>
    
                    <div class="tab-pane fade" id="step5">
                        <div class="well"> 
                            <h4>Step 5</h4>Thank you!
                        </div>
                        <a class="btn btn-success first" href="#">Start over</a>
                        <a class="btn btn-success first" href="#">Submit</a>
                    </div>
                </div>  
        
                {{-- <div class="modal-body" id="modal-body">
                    <form method="POST" id="surveyform">
                        @csrf
                        @foreach(\App\Survey::where('state', 'published')->get() as $survey)
                            <h3>Survey Name: {{ $survey->name }}</h3>
                            @foreach($survey->questions as $question)
                                <h5 style="padding-bottom: 5px;padding-top: 5px;">Question:{{ $question->text }}</h5>
                                @foreach($question->options as $option)
                                    @if ($question->type == "single_choice")
                                        <input type="radio" value="{{$option->id}}" name="single_choice[{{$question->id}}]" class="form-control" style="display: inline;">{{ $option->text }}<br>
                                    @else
                                        <input type="checkbox" value="{{$question->id}}" name="multi_choice[{{$option->id}}]" class="form-control" style="display: inline;">{{ $option->text }}<br>    
                                    @endif
                                @endforeach
                                @if ($question->type == "descriptive")
                                    <input type="text" style="width: 80%;" name="descriptive[{{$question->id}}]" class="form-control" style="display: inline;"><br>
                                @endif
                                <hr>
                            @endforeach
                        @endforeach
                        <br><input class="form-control normalButton" type="submit" value="Submit"><br>
                    </form>
                </div> --}}
            </div>
            
        </div>
    </div>
</div>

<script type="text/javascript">
	$('.next').click(function(){
		var nextId = $(this).parents('.tab-pane').next().attr("id");
		$('[href*=\\#'+nextId+']').tab('show');
		return false;
	})

	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
	  //update progress
	  var step = $(e.target).data('step');
	  var percent = (parseInt(step) / 5) * 100;
	  
	  $('.progress-bar').css({width: percent + '%'});
	  $('.progress-bar').text("Step " + step + " of 5");
	  //e.relatedTarget // previous tab  
	})

	$('.first').click(function(){
		$('.nav-pills a:first').tab('show')
	})
</script>

{{-- Survey pop up time/start  --}}
<span class="pop_up_time" id="{{ \App\SurveySettings::first() }}"></span>

<script>
    var pop_up_time = document.querySelector('.pop_up_time').id

    if (!Boolean(pop_up_time)){
        pop_up_time = 2000;
    } else{
        pop_up_time  = JSON.parse(pop_up_time)['time'];
    }

    setTimeout(function () {            
        console.log('Pop up time', pop_up_time);

        var cookieValue = Cookies.get('ddl');
        if(cookieValue !== "survey"){
            $('#surveyModal').show();

            // Cookies.set('ddl', 'survey', { expires: 30, path: '/' });
        }
    }, pop_up_time);
</script>
{{-- Survey pop up time/end  --}}

<script src="{{ asset('js/js.cookie.min.js') }}"></script>

<script>
    if(window.jQuery){
        $(function () {
            $('#surveyform').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    type: 'post',
                    url: "{{ route('survey') }}",
                    data: $('form').serialize(),
                    success: function (data) {
                        if(data){
                            console.log("success!");
                            $("#modal-body").html("Thank you for completing the survey!");
                        }else{
                            console.log("failure!");
                        }
                    }
                });
            });
        });
    }
</script>
