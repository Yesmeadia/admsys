@if (!app()->runningUnitTests())
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        // CSRF token configuration for JavaScript
        window.CSRF_TOKEN = '{{ csrf_token() }}';
        window.APP_URL = '{{ url("/") }}';

        // Intercept all AJAX requests and add CSRF token
        (function () {
            const originalFetch = window.fetch;
            window.fetch = function (...args) {
                const [resource, config = {}] = args;

                // Add CSRF token to headers if not present
                config.headers = {
                    ...config.headers,
                    'X-CSRF-TOKEN': window.CSRF_TOKEN,
                    'X-Requested-With': 'XMLHttpRequest'
                };

                return originalFetch(resource, config);
            };

            // Also patch XMLHttpRequest
            const originalOpen = XMLHttpRequest.prototype.open;
            XMLHttpRequest.prototype.open = function (method, url, async, user, password) {
                const result = originalOpen.apply(this, arguments);

                // Only set headers for internal requests or if needed
                try {
                    this.setRequestHeader('X-CSRF-TOKEN', window.CSRF_TOKEN);
                    this.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                } catch (e) {
                    // Ignore errors if the state changed unexpectedly
                }

                return result;
            };
        })();
    </script>
@endif