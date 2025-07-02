<footer id="main-footer" class="py-4 px-3">
    <div class="container-fluid">
        <div class="row align-items-center">
            @if($menu)
                <div class="col-md-6 col-lg-8 mb-3 mb-md-0">
                    <ul class="footer-nav">
                        @foreach ($menu->where('location', 'footer-menu')->where('language', app()->getLocale()) as $fmenu)
                            <li>
                                <a href="{{ URL::to($fmenu->path) }}" title="{{ $fmenu->title }}">{{ $fmenu->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="col-md-6 col-lg-4 text-md-end">
                <div class="footer-right-content">
                    <div class="app-badges mb-3 mb-md-0">
                        <a href="https://play.google.com/store/apps/details?id=com.ddacademi.library" target="_blank" title="@lang('Get it on Google Play')">
                            <img src="{{ (Lang::locale() != 'en') ?  asset('storage/files/google-play-badge-fa.png') : asset('storage/files/google-play-badge-en.png') }}" alt="@lang('Google Play')" class="app-badge">
                        </a>
                        <a href="https://apps.apple.com/us/app/darakht-e-danesh-library/id6745165605" target="_blank" title="@lang('Download on the App Store')">
                            <img src="{{ asset('storage/files/app-store-badge-en.svg') }}" alt="@lang('App Store')" class="app-badge">
                        </a>
                    </div>

                    <div class="social-icons">
                        <a href="https://www.instagram.com/darakhtedanesh" target="_blank" title="DDL Instagram" class="social-icon instagram">
                            <i class="fab fa-instagram-square"></i>
                        </a>
                        <a href="https://www.facebook.com/darakhtedanesh/" target="_blank" title="DDL Facebook" class="social-icon facebook">
                            <i class="fab fa-facebook-square"></i>
                        </a>
                        <a href="https://www.youtube.com/c/DarakhteDaneshLibrary" target="_blank" title="DDL Youtube" class="social-icon youtube">
                            <i class="fab fa-youtube-square"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
        {{-- Copyright --}}
        <div class="row mt-3">
            <div class="col text-center text-muted small">
                &copy; {{ date('Y') }} @lang('Darakht-e Danesh Library. All rights reserved.')
            </div>
        </div>
    </div>
</footer>