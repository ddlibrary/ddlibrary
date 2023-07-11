<hr class="hr-class">
<footer>
    <nav>
        @if($menu)
            @foreach ($menu->where('location', 'footer-menu')->where('language', app()->getLocale()) as $fmenu)
                <a href="{{ URL::to($fmenu->path) }}" title="{{ $fmenu->title }}">{{ $fmenu->title }}</a>
            @endforeach
        @endif
    </nav>
    <div>
        <i class="fab fa-twitter fa-2x" title="DDL Twitter Account" onclick="window.location.href='https://twitter.com/AfghanOERs'"></i>
        <i class="fab fa-facebook fa-2x" title="DDL Facebook Account" onclick="window.location.href='https://www.facebook.com/AfghanOERs/'"></i>
        <i class="fab fa-youtube fa-2x" title="DDL Youtube Account" onclick="window.location.href='https://www.youtube.com/channel/UCVmc4QsedamLXMeXbutW-iw/videos'"></i>
    </div>
</footer>