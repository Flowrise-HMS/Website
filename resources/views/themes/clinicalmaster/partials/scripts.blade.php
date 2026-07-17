@php
    $themes = app(\Modules\Website\Classes\Support\ThemeManager::class);
@endphp
{{-- GSAP animation pack is incomplete for this HMS port (ScrollSmoother needs page wrappers).
     Skip animation.js to avoid console errors and layout breakage. --}}
<script src="{{ $themes->assetUrl('vendor/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ $themes->assetUrl('js/dz.carousel.js') }}"></script>
<script src="{{ $themes->assetUrl('js/custom.js') }}"></script>
<script>
    (function () {
        const header = document.getElementById('cmSiteHeader');
        const toggle = document.getElementById('cmMenuToggle');
        const mobileNav = document.getElementById('cmMobileNav');
        const scrollTop = document.getElementById('cmScrollTop');

        if (header) {
            const onScroll = () => {
                const y = window.scrollY || 0;
                header.classList.toggle('is-fixed', y > 80);
                if (scrollTop) {
                    scrollTop.classList.toggle('is-visible', y > 320);
                }
            };

            onScroll();
            window.addEventListener('scroll', onScroll, { passive: true });

            if (toggle && mobileNav) {
                toggle.addEventListener('click', () => {
                    const open = !header.classList.contains('is-open');
                    header.classList.toggle('is-open', open);
                    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                    mobileNav.hidden = !open;
                });

                mobileNav.querySelectorAll('a').forEach((link) => {
                    link.addEventListener('click', () => {
                        header.classList.remove('is-open');
                        toggle.setAttribute('aria-expanded', 'false');
                        mobileNav.hidden = true;
                    });
                });
            }
        }

        if (scrollTop) {
            scrollTop.addEventListener('click', (event) => {
                event.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        // Soften Swiper loop warnings when a slider has too few slides.
        document.querySelectorAll('.swiper').forEach((el) => {
            if (el.swiper && el.querySelectorAll('.swiper-slide').length < 4 && el.swiper.params?.loop) {
                el.swiper.params.loop = false;
                el.swiper.update();
            }
        });
    })();
</script>
