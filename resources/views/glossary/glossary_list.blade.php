@extends('layouts.main')
@section('title')
DDL Glossary
@endsection
@section('description')
DDL Glossary
@endsection
@section('page_image')
{{ asset('storage/files/logo-dd.png') }}
@endsection
@section('content')
<section class="general-content">
<form method="POST" action="{{ route('glossary') }}" id="gform">
    @csrf
    <table class="glossary">
        <tr>
            <td style="width: 5%">@lang('Text'):</td>
            <td style="width:5%">
                <input class="form-control" type="text" name="text" id="text" value="{{ isset($filters['text'])?$filters['text']:"" }}">
            </td>
            <td style="width:5%">@lang('Subject'):</td>
            <td style="width:5%">
                <select name="subject" class="form-control">
                    <option value="">@lang('All')</option>
                    @foreach($glossary_subjects as $id => $subject)
                        <option value="{{ $id }}" {{ (isset($filters['subject']) && $filters['subject'] ==  $id)?"selected":"" }}>{{ $subject }}</option>
                    @endforeach
                </select>
            </td>
            @if (isLibraryManager() or isAdmin())
                <td style="width: 15%;">@lang('Flagged for review'):</td>
                <td style="width: 5%;">
                    <select name="flagged" class="form-control">
                        <option value="show" {{ (isset($filters['flagged']) && $filters['flagged'] == "show")?"selected":"" }}>@lang('Show')</option>
                        <option value="hide" {{ (isset($filters['flagged']) && $filters['flagged'] == "hide")?"selected":"" }}>@lang('Hide')</option>
                    </select>
                </td>
            @endif
            <td>
                <input class="form-control" type="submit" value="@lang('Filter')">
            </td>
            <td style="text-align: right; width: 5%; "><a href="{{ route('glossary_create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> @lang('Add')</a></td>
        </tr>
    </table>
    </form>
    <div id="success_msg" style="
    margin-top: 11px;
    text-align: center;
    color: #5cb85c;
    font-size: 22px;
    display: none;"></div>

    @if (session('status'))
        <br>
        <div id="add_success" class="alert alert-success">
            {{ (session('status')) }}
        </div>
    @endif
    @if (isLibraryManager() or isAdmin())
        <div id="update" style="color: #777; font-size: 14px; text-align: right;">@lang('Click and edit the text you would like to change (plain text only) and click \'Enter\' (return) once done, or click \'Escape\' to discard the changes. ')</div>
    @endif
    @if ($glossary_flagged)
        <h3>@lang('Flagged for review')</h3>
        @include('glossary.table', ['glossary' => $glossary_flagged, 'glossary_subjects' => $glossary_subjects, 'flagged_queue' => true])
        <br>
        <div class="resource-pagination">
            {{ $glossary_flagged->appends(request()->input())->links() }}
        </div>
        <br>
    @endif
    @include('glossary.table', ['glossary' => $glossary, 'glossary_subjects' => $glossary_subjects, 'flagged_queue' => false])
    <br>
    <div class="resource-pagination">
        {{ $glossary->appends(request()->input())->links() }}
    </div>
</section>
@endsection
@push('scripts')
    <script src="{{ asset('js/ddl.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("td[contenteditable=\"true\"]").keydown(function(event) {
                let escape = event.which === 27,  // 27 - Escape key
                    enter = event.which === 13,  // 13 - Return key
                    source = event.target;

                if (escape) {
                    document.execCommand('undo');
                    source.blur();
                } else if (enter) {
                    let id = source.getAttribute('data-id')
                    let type = source.getAttribute('data-type')
                    let locale = source.getAttribute('data-language')
                    // grabbed from https://stackoverflow.com/a/42312538
                    let string = source.innerHTML.split('\n')  // split by new line
                        .map(function(v) {  // iterate and trim out white space from both ends
                            return v.trim()
                        })
                        .filter(function(v) {  // filter out non-empty strings
                            return v !== '';
                        }).join('\n')  // join using newline char
                    let data = [id, type, locale, string]

                    $.ajax({
                        type: 'POST',
                        url: 'glossary/update',
                        data: {
                            _token: '{{ csrf_token() }}',
                            data: data,
                        },
                        success: function() {
                            let div = $('#success_msg');
                            div.html("Updated successfully!").fadeIn('slow');
                            div.delay(5000).fadeOut('slow');
                        },
                        error: function() {
                            console.log("Request to update glossary item failed. File a bug request.")
                        }
                    });

                    source.blur();
                    event.preventDefault();
                }
            });
            $(".glossary_delete").click(function (event){
                if(confirm("Are you sure you would like to delete the glossary item?")) {
                    let source = event.currentTarget;
                    let id = parseInt(source.getAttribute('data-id'));
                    $.ajax({
                        type: 'POST',
                        url: 'glossary/delete/' + id,
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function() {
                            let div = $('#success_msg');
                            div.html("Item deleted successfully! Page will reload now.").fadeIn('slow');
                            div.delay(5000).fadeOut('slow');
                            location.reload();
                        },
                        error: function() {
                            console.log('Request to delete glossary item failed. File a bug request.')
                        }
                    });
                }
            });
            $(".glossary_approve").click(function (event){
                let source = event.currentTarget;
                let id = parseInt(source.getAttribute('data-id'));
                $.ajax({
                    type: 'POST',
                    url: 'glossary/approve/' + id,
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function() {
                        let div = $('#success_msg');
                        div.html("Item approved! Page will reload now.").fadeIn('slow');
                        div.delay(5000).fadeOut('slow');
                        location.reload();
                    },
                    error: function() {
                        console.log("Request to approve glossary item failed. File a bug request.");
                    }
                });
            });
            $(function() {
                let div = $('#add_success');
                if (div) {
                    div.delay(5000).fadeOut('slow');
                }
            });
        });
    </script>
@endpush
