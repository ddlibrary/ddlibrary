<section class="banner">
    <header>
        <h3>@lang('Free and open educational resources for Afghanistan')</h3>
    </header>
    <form class="form-inline" method="GET" action="{{ route('resourceList') }}" id="search-form">
        @csrf
        <div class="form-group mx-auto mb-2">
            <input type="text" name="search" class="form-control col-md-10" style="margin-right: 10px;" value="{{ session('search') }}" placeholder="@lang('SEARCH OUR GROWING LIBRARY!')" autofocus>
            <button type="submit" class="btn btn-primary">@lang('Go')</button>
        </div>
    </form>
</section>
