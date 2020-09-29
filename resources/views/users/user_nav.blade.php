<nav class="user-nav">
<a href="{{ URL::to('user/profile') }}" title="@lang('Personal Information')" class="{{ ($page == 'profile') ? 'active' : '' }}">@lang('Personal Information')</a>
    <a href="{{ URL::to('user/favorites') }}" title="@lang('Favorites')" class="{{ ($page == 'favorites') ? 'active' : '' }}">@lang('Favorites')</a>
    <a href="{{ URL::to('user/uploaded-resources') }}" title="@lang('Uploaded Resources')" class="{{ ($page == 'uploaded-resources') ? 'active' : '' }}">@lang('Uploaded Resources')</a>

<hr class="hr-class">
</nav>