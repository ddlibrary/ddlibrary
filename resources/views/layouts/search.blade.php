<section class="banner">
    <header>
        <h1>Free and open educational resources for Afghanistan</h1>
    </header>
    <form method="POST" action="{{ route('resourceList') }}">
        @csrf
        <input type="search" name="search" class="form-search form-control" value="{{ session('search') }}" placeholder="Search our library" autofocus>
        <input type="submit" class="search-button" value="Go">
    </form>
</section>