<style>
    .user-nav {
        display: flex;
        background: lightseagreen;
        margin-bottom: 10px;
    }

    .user-nav a {
        padding: 20px;
        color: #fff;
    }

    .user-nav .active {
        background: #000;
    }

    .user-nav a:not(.active):hover {
        background: #318984;
    }

    @media only screen and (max-width: 600px) {
        .user-nav {
            flex-wrap: wrap;
        }

        .user-nav a {
            flex-grow: 1;
        }
    }
</style>

<nav class="user-nav" style="">
    <a href="{{ URL::to('user/profile') }}" title="@lang('Personal Information')"
        class="{{ $page == 'profile' ? 'active' : '' }}">@lang('Personal Information')</a>
    <a href="{{ URL::to('user/favorites') }}" title="@lang('Favorites')"
        class="{{ $page == 'favorites' ? 'active' : '' }}">@lang('Favorites')</a>
    <a href="{{ URL::to('user/uploaded-resources') }}" title="@lang('Uploaded Resources')"
        class="{{ $page == 'uploaded-resources' ? 'active' : '' }}">@lang('Uploaded Resources')</a>
</nav>
