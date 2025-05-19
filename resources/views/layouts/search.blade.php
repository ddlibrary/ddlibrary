<div class="container text-center mb-4">

    <h2 class="my-3">@lang('Free and open educational resources for Afghanistan')</h2>

    <form class="justify-content-center row" method="GET" action="{{ Request::fullUrl() }}">
        <div class="form-group col-md-6 col-12 my-2">
            <label for="search" class="sr-only">@lang('Search')</label>
            <input type="text" id="search" name="search" class="form-control" placeholder="@lang('Search our growing library!')">
        </div>
        <input type="submit" class="btn btn-primary col-md-1 col-2 my-2" value="@lang('Go')">
        <a href="{{ route('resourceFilter') }}" class="btn btn-outline-secondary col-md-1 col-2 ms-1 my-2">@lang('Filter')</a>
    </form>
</div>