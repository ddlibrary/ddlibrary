
<!-- Modal content -->
<div id="surveyModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close" id="survey-close">&times;</span>
            <h3>@lang('DDL Survey')</h3>
        </div>
        <div class="modal-body">
            <input name="questions_count" id="questions_count" style="display: none;" value="{{\App\SurveyQuestion::getPublishedQuestions()->count()}}">

            <div class="survey_content">
                <div class="progress">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="1" aria-valuemin="1" aria-valuemax="5" style="width: 15%;">
                        Question 1 of {{\App\SurveyQuestion::getPublishedQuestions()->count()}}
                    </div>
                </div>

                <div class="navbar" style="display: none;">
                    <div class="navbar-inner">
                        <ul class="nav nav-pills">
                            <?php $i = 1; ?>
                            @foreach(\App\SurveyQuestion::getPublishedQuestions() as $question)
                                @if ($i == 1)
                                    <li class="active"><a href="#{{$question->id}}" data-toggle="tab" data-step="{{$i}}">{{$question->name}}</a></li>
                                @else
                                    <li><a href="#{{$question->id}}" data-toggle="tab" data-step="{{$i}}">{{$question->name}}</a></li>
                                @endif
                                <?php $i++; ?>
                            @endforeach
                            <li><a href="#finish" class="finish" data-toggle="tab" data-step="{{\App\SurveyQuestion::getPublishedQuestions()->count()}}"></a></li>
                        </ul>
                    </div>
                </div>
                <form method="POST" id="surveyform">
                    @csrf
                    <div class="tab-content">
                        <?php  
                            $a = 1; 
                        ?>
                        @foreach(\App\SurveyQuestion::getPublishedQuestions() as $question)
                            @if ($a == 1)
                                <div class="tab-pane fade in active" id="{{$question->id}}">
                                    <div class="well">
                                        <h4>{{ $question->text }}</h4>
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
                                    </div>
                                    @if (\App\SurveyQuestion::getPublishedQuestions()->count() ==  $a)
                                        <button type="submit" class="btn btn-success">Submit</button>
                                    @else
                                        <a class="btn btn-primary next" href="#">Next</a>
                                    @endif
                                </div>
                            @else
                                <div class="tab-pane fade" id="{{$question->id}}">
                                    <div class="well">
                                        <h4>{{ $question->text }}</h4>
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
                                    </div>
                                    @if (\App\SurveyQuestion::getPublishedQuestions()->count() == $a)
                                        <a class="btn btn-success first" href="#">Start over</a>
                                        <button type="submit" class="btn btn-success">Submit</button>
                                    @else
                                        <a class="btn btn-primary next" href="#">Next</a>
                                    @endif
                                </div>
                            @endif
                            <?php 
                                $a++; 
                            ?>
                        @endforeach
                        <div class="tab-pane" id="finish">
                            <div class="well"> 
                                <h4 style="text-align: center;">Thank you for completing the survey!</h4>
                            </div>
                        </div>
                    </div>  
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Survey progress bar/start  --}}
<script type="text/javascript">
    var questions_count = $('#questions_count').val();
	$('.next').click(function(){         
		var nextId = $(this).parents('.tab-pane').next().attr("id");
		$('[href*=\\#'+nextId+']').tab('show');
		return false;
	})

	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
	  //update progress
	  var step = $(e.target).data('step');
	  var percent = (parseInt(step) / questions_count) * 100;
	  
	  $('.progress-bar').css({width: percent + '%'});
	  $('.progress-bar').text("Question " + step + " of " + questions_count);
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
        pop_up_time = 7000;
    } else{
        pop_up_time  = JSON.parse(pop_up_time)['time'];
    }

    setTimeout(function () {            
        console.log('Pop up time', pop_up_time);

        var cookieValue = Cookies.get('ddl');
        if(cookieValue !== "survey"){
            $('#surveyModal').show();
            Cookies.set('ddl', 'survey', { expires: 30, path: '/' });
        }
    }, pop_up_time);
</script>


{{-- Survey submission --}}
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
                            $('.finish').tab('show')
                        }else{
                            console.log("failure!");
                        }
                    }
                });
            });
        });
    }
</script>
