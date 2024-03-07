<section class="banner">
    <div class="main-section search-section">
        @if (session()->has('alert'))
            <x-alert :message="Session::get('alert.message')" :level="Session::get('alert.level')" />
        @endif
        <header>
            <h1>@lang('Free and open educational resources for Afghanistan')</h1>
        </header>
        <form method="GET" action="{{ route('resourceList') }}" id="search-form">
            @csrf

            <div class="relative-div">
                <input type="text" name="search" class="form-control search-input" value="{{ session('search') }}"
                    placeholder="@lang('SEARCH OUR GROWING LIBRARY!')" autofocus>
                <i class="fa fa-search fa-2x search-icon {{ Lang::locale() == 'en' ? 'search-icon-right' : 'search-icon-left' }}"
                    onclick="submitForm()"></i>
            </div>
        </form>
    </div>
    @push('scripts')
        <script>
            function submitForm() {
                document.getElementById('search-form').submit();
            }
        </script>
    @endpush
</section>
