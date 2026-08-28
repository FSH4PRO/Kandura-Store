@php
$containerFooter = !empty($containerNav) ? $containerNav : 'container-fluid';
@endphp

<!-- Footer-->
<footer class="content-footer footer bg-footer-theme">
    <div class="{{ $containerFooter }}">
        <div class="footer-container d-flex flex-column flex-md-row align-items-center justify-content-between py-2 text-center text-md-start">
            <div class="mb-2 mb-md-0">
                ©
                <script>
                    document.write(new Date().getFullYear());
                </script>
                , made with ❤️ by
                <a href="https://www.tamkeen-kw.com" target="_blank" class="footer-link fw-semibold">Firas Shaikha</a>
            </div>
            <div>
                <a href="https://kandura-store-docs.tamkeen-kw.com/" class="footer-link me-4" target="_blank">Documentation</a>
                <a href="https://www.tamkeen-kw.com/contact-us/" class="footer-link me-4" target="_blank">Support</a>
                <a href="https://www.tamkeen-kw.com/privacy-policy/" class="footer-link me-4" target="_blank">Privacy Policy</a>
                <a href="https://www.tamkeen-kw.com/terms-of-service/" class="footer-link" target="_blank">Terms of Service</a>
            </div>
           
        </div>
    </div>
</footer>
<!--/ Footer-->