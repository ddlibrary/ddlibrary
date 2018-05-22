<section class="banner">
    <header>
        <h1>Free and open educational resources for Afghanistan</h1>
    </header>
    <form method="POST" action="{{ route('resourceList') }}">
        @csrf
        <input type="search" name="search" class="formSearch" value="{{ session('search') }}" placeholder="Search our library">
        <input type="submit" class="searchButton" value="Go">
    </form>

    <div class="ddlButtons">
    <input type="button" class="normalButton" value="About DD Library" onclick="location.href='{{ URL::to('pages/view/15') }}'"> 
            <input type="button" class="normalButton" value="How to use the Library" onclick="location.href='{{ URL::to('pages/view/16') }}'"> 
            <input type="button" class="normalButton" value="Support the Library" onclick="location.href='{{ URL::to('pages/view/21') }}'"> 
            <input type="button" class="normalButton" value="Help" onclick="location.href='{{ URL::to('pages/view/20') }}'"> 
    </div>
</section>
<section class="ddlTopNews">
    <div>
        <p>Want to support Open Library? Until April 30, We'll double your donation!</p>
    </div>
</section>