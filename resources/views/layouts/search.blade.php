<section class="banner">
    <header>
        <h1>@lang('Free and open educational resources for Afghanistan')</h1>
    </header>
    <form method="POST" action="{{ route('resourceList') }}">
        @csrf
        <input type="search" name="search" class="form-search form-control" value="{{ session('search') }}" placeholder="@lang('Search our library')" autofocus>
        <input type="submit" class="search-button" value="@lang('Go')">
    </form>
</section>