<section class="banner">
    <header>
        <h1>Free and open educational resources for Afghanistan</h1>
    </header>
    <form method="POST" action="{{ route('resourceList') }}">
        @csrf
        <input type="search" name="search" class="form-search" value="{{ session('search') }}" placeholder="Search our library">
        <input type="submit" class="search-button" value="Go">
    </form>

    <div class="top-pages">
    <input type="button" class="normal-button" value="About DD Library" onclick="location.href='{{ URL::to('pages/view/15') }}'"> 
            <input type="button" class="normal-button" value="How to use the Library" onclick="location.href='{{ URL::to('pages/view/16') }}'"> 
            <input type="button" class="normal-button" value="Support the Library" onclick="location.href='{{ URL::to('pages/view/21') }}'"> 
            <input type="button" class="normal-button" value="Help" onclick="location.href='{{ URL::to('pages/view/20') }}'"> 
    </div>
</section>