<!-- Modal content -->
<div class="modal-content">
    <div class="modal-header">
        <span class="close" id="survey-close">&times;</span>
        <h2>@lang('DDL Survey')</h2>
    </div>
    <div class="modal-body">
        <div class="modal-body">
            <h2>{{ $surveyQuestions->text }} </h2>
            <form method="POST">
            @foreach($surveyQuestionOptions as $item)
                <input type="radio" value="{{ $item->id }}" name="useful" class="form-control" style="display: inline;"> {{ $item->text }} <br>
            @endforeach
                <br><input class="form-control normalButton" type="submit" value="Submit"><br>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/js.cookie.min.js') }}"></script>

<script>
if(window.jQuery){
    $(function () {
        $('form').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                type: 'post',
                url: "{{ route('survey') }}",
                data: {"mydata": $('form').serialize(), "_token": "{{ csrf_token() }}"},
                success: function (data) {
                    if(data){
                        console.log("success!");
                    }else{
                        console.log("failure!");
                    }
                }
            });
        });
    });
}
</script>