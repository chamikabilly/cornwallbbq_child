document.addEventListener('DOMContentLoaded', function () {
    if (typeof Swiper === 'undefined') return;

    var thumbsContainer = document.querySelector('.thumbs-swiper');
    var mainImageSelector = '.woocommerce-product-gallery__image img';

    var thumbsSwiperInstance = null;

    if (thumbsContainer) {
        var thumbSlideCount = thumbsContainer.querySelectorAll('.swiper-slide').length;
        var shouldCenterThumbs = thumbSlideCount <= 1;

        thumbsSwiperInstance = new Swiper(thumbsContainer, {
            slidesPerView: 3,
            spaceBetween: 10,
            centeredSlides: shouldCenterThumbs,
            direction: 'horizontal',
            grabCursor: true,
            watchSlidesProgress: true,
            navigation: {
                nextEl: '.thumbs-swiper .thumbs-swiper-button-next',
                prevEl: '.thumbs-swiper .thumbs-swiper-button-prev',
            },
        });

        // Add click behavior to thumbnail images
        thumbsContainer.querySelectorAll('.woocommerce-thumb-swiper').forEach(function (img) {
            img.setAttribute('role', 'button');
            img.setAttribute('tabindex', '0');

            function activateThumbnail(targetImg) {
                var largeImageSrc = targetImg.getAttribute('data-large-image');
                var mainImage = document.querySelector(mainImageSelector);

                if (mainImage && largeImageSrc) {
                    mainImage.src = largeImageSrc;
                    mainImage.dataset.large_image = largeImageSrc;
                }

                // toggle active state on slides
                thumbsContainer.querySelectorAll('.swiper-slide').forEach(function (slide) {
                    slide.classList.remove('active');
                });
                var parentSlide = targetImg.closest('.swiper-slide');
                if (parentSlide) parentSlide.classList.add('active');
            }

            img.addEventListener('click', function () {
                activateThumbnail(img);
            });
            img.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    activateThumbnail(img);
                }
            });
        });
    }
    /* Bootstrap modal gallery (replace PhotoSwipe) */
    var bootstrapModal = document.getElementById('photoswipe-bootstrap-modal');
    var modalInner = bootstrapModal ? bootstrapModal.querySelector('.photoswipe-modal-inner') : null;
    var modalPrev = bootstrapModal ? bootstrapModal.querySelector('#photoswipe-prev') : null;
    var modalNext = bootstrapModal ? bootstrapModal.querySelector('#photoswipe-next') : null;

    function gatherGalleryImages() {
        var images = [];
        var mainImage = document.querySelector(mainImageSelector);
        if (mainImage && mainImage.src) images.push({ src: mainImage.src, alt: mainImage.alt || '' });
        var thumbs = document.querySelectorAll('.woocommerce-thumb-swiper');
        thumbs.forEach(function (t) {
            var src = t.getAttribute('data-large-image') || t.src;
            if (src && images.findIndex(function (i) { return i.src === src; }) === -1) {
                images.push({ src: src, alt: t.alt || '' });
            }
        });
        return images;
    }

    function openBootstrapGallery(startIndex) {
        if (!bootstrapModal || !modalInner) return;
        var images = gatherGalleryImages();
        modalInner.innerHTML = '';
        images.forEach(function (img, idx) {
            var el = document.createElement('img');
            el.src = img.src;
            el.alt = img.alt;
            el.style.maxWidth = '90%';
            el.style.display = (idx === startIndex) ? 'block' : 'none';
            el.dataset.index = idx;
            modalInner.appendChild(el);
        });

        var current = startIndex || 0;

        function showIndex(i) {
            current = i < 0 ? images.length - 1 : (i >= images.length ? 0 : i);
            modalInner.querySelectorAll('img').forEach(function (el) {
                el.style.display = (parseInt(el.dataset.index, 10) === current) ? 'block' : 'none';
            });
        }

        if (modalPrev) modalPrev.onclick = function () { showIndex(current - 1); };
        if (modalNext) modalNext.onclick = function () { showIndex(current + 1); };

        // Show Bootstrap modal (supports Bootstrap 5 and 4)
        if (window.bootstrap && bootstrap.Modal) {
            var bsModal = new bootstrap.Modal(bootstrapModal);
            bsModal.show();
            // store instance so we can hide later if needed
            bootstrapModal._bsModal = bsModal;
        } else if (window.jQuery && jQuery(bootstrapModal).modal) {
            jQuery(bootstrapModal).modal('show');
        }
    }

    // Add custom zoom overlay button and remove theme's default trigger/search icon
    var mainImageWrapper = document.querySelector('.woocommerce-product-gallery__image');
    if (mainImageWrapper) {
        // hide any existing gallery trigger/search icon added by theme
        var themeTriggers = mainImageWrapper.querySelectorAll('.woocommerce-product-gallery__trigger, .zoom, .product-search-icon');
        themeTriggers.forEach(function (t) { t.style.display = 'none'; });

        // ensure wrapper is positioned for overlay
        mainImageWrapper.style.position = mainImageWrapper.style.position || 'relative';

        // create zoom button overlay
        var zoomBtn = document.createElement('button');
        zoomBtn.type = 'button';
        zoomBtn.className = 'product-zoom-button';
        zoomBtn.setAttribute('aria-label', 'Open gallery');
        zoomBtn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21 21l-4.35-4.35" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M11 19a8 8 0 1 0 0-16 8 8 0 0 0 0 16z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
        zoomBtn.style.position = 'absolute';
        zoomBtn.style.right = '12px';
        zoomBtn.style.top = '12px';
        zoomBtn.style.zIndex = '30';
        zoomBtn.style.width = '44px';
        zoomBtn.style.height = '44px';
        zoomBtn.style.borderRadius = '50%';
        zoomBtn.style.background = 'rgba(255,255,255,0.95)';
        zoomBtn.style.border = '0';
        zoomBtn.style.display = 'flex';
        zoomBtn.style.alignItems = 'center';
        zoomBtn.style.justifyContent = 'center';
        zoomBtn.style.boxShadow = '0 8px 20px rgba(7,10,15,0.08)';
        zoomBtn.style.cursor = 'pointer';
        zoomBtn.style.opacity = '0';
        zoomBtn.style.transition = 'all .12s ease';

        mainImageWrapper.appendChild(zoomBtn);

        // show on hover/focus
        mainImageWrapper.addEventListener('mouseenter', function () { zoomBtn.style.opacity = '1'; });
        mainImageWrapper.addEventListener('mouseleave', function () { zoomBtn.style.opacity = '0'; });
        zoomBtn.addEventListener('focus', function () { zoomBtn.style.opacity = '1'; });

        // open modal when zoom button clicked
        zoomBtn.addEventListener('click', function (e) {
            e.preventDefault();
            openBootstrapGallery(0);
        });
    }

});
