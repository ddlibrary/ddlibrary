@push('scripts')
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-QZK5S8JQJN"></script>
    <script>
        // Source - https://stackoverflow.com/a
        // Posted by XTOTHEL, modified by community. See post 'Timeline' for change history
        // Retrieved 2025-11-12, License - CC BY-SA 4.0

        // Disable tracking if the opt-out cookie exists.
        var disableStr = 'ga-disable-G-QZK5S8JQJN';
        if (document.cookie.indexOf(disableStr + '=true') > -1) {
            window['ga-disable-G-QZK5S8JQJN'] = true; //this is what disables the tracking, note the measurement ID
        }
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-QZK5S8JQJN');

        // Opt-out function (this function is unchanged)
        function gaOptout() {
            document.cookie = disableStr + '=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/';
            window[disableStr] = true;

            // Update status message
            var statusEl = document.getElementById('ga-optout-status');
            if (statusEl) {
                statusEl.textContent = '{{ __('You have successfully opted out of Google Analytics tracking.') }}';
                statusEl.className = 'mt-3 small mb-0 text-success';
            }

            alert('{{ __('You have successfully opted out of Google Analytics tracking.') }}');
        }

        // Check and display current opt-out status on page load
        document.addEventListener('DOMContentLoaded', function() {
            var statusEl = document.getElementById('ga-optout-status');
            if (document.cookie.indexOf(disableStr + '=true') > -1) {
                if (statusEl) {
                    statusEl.textContent = '{{ __('You are currently opted out of Google Analytics tracking.') }}';
                    statusEl.className = 'mt-3 small mb-0 text-success';
                }
            } else {
                if (statusEl) {
                    statusEl.textContent = '{{ __('You are currently opted in to Google Analytics tracking.') }}';
                    statusEl.className = 'mt-3 small mb-0 text-muted';
                }
            }
        });
    </script>

    <!-- Matomo Opt-Out -->
    <script
        src="https://analytics.darakhtdanesh.org/index.php?module=CoreAdminHome&action=optOutJS&divId=matomo-opt-out&language=auto&showIntro=1">
    </script>
@endpush
