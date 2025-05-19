<footer id="footer">
    @if($menu)
        <ul class="list-inline" style="display: inline">
            @foreach ($menu->where('location', 'footer-menu')->where('language', app()->getLocale()) as $fmenu)
                <li class="list-inline-item">
                    <a href="{{ URL::to($fmenu->path) }}" title="{{ $fmenu->title }}">{{ $fmenu->title }}</a>
                </li>
            @endforeach
        </ul>
    @endif
    <span style="float: right; display:inline-block">
        <a href="https://twitter.com/AfghanOERs" target="_blank"><i class="fab fa-twitter-square fa-2x" title="DDL Twitter" style="color: #1da1f2;"></i></a>
        <a href="https://www.facebook.com/AfghanOERs/" target="_blank"><i class="fab fa-facebook-square fa-2x" title="DDL Facebook" style="color: #4267b2;"></i></a>
        <a href="https://www.youtube.com/c/DarakhteDaneshLibrary" target="_blank"><i class="fab fa-youtube-square fa-2x" title="DDL Youtube" style="color: #ff0000;"></i></a>
    </span>
</footer>
