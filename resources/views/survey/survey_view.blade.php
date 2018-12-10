<!-- Modal content -->
<div id="surveyModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close" id="survey-close">&times;</span>
            <h2>@lang('DDL Survey')</h2>
        </div>
        <div class="modal-body">
            <div class="modal-body" id="modal-body">
                <h2>{{ \App\SurveyQuestion::where('survey_id', 1)->first()->text }} </h2>
                <form method="POST" id="surveyform">
                @foreach(\App\SurveyQuestionOption::where('question_id', 1)->get() as $item)
                    <input type="radio" value="{{ $item->id }}" name="useful" class="form-control" style="display: inline;"> {{ $item->text }} <br>
                @endforeach
                    <br><input class="form-control normalButton" type="submit" value="Submit"><br>
                </form>
            </div>
        </div>
    </div>
</div>

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
{{-- Survey pop up time/end  --}}


<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/js.cookie.min.js') }}"></script>

<script>
if(window.jQuery){
    $(function () {
        $('#surveyform').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                type: 'post',
                url: "{{ route('survey') }}",
                data: {"mydata": $('form').serialize(), "_token": "{{ csrf_token() }}"},
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