(function($){
    $(function(){
        // Init category titles slider (no autoplay)
        var catSwiper = new Swiper('.shop-cats-swiper', {
            direction: 'horizontal',
            effect: 'slide',
            slidesPerView: 3,
            spaceBetween: 10,
            navigation: {
                nextEl: '.shop-cats-swiper .swiper-button-next',
                prevEl: '.shop-cats-swiper .swiper-button-prev'
            },
            breakpoints: {
                // When window width is >= 320px
                320: {
                    slidesPerView: 2,
                    spaceBetween: 5
                },
                // When window width is >= 600px
                600: {
                    slidesPerView: 3,
                    spaceBetween: 8
                },
                // When window width is >= 768px
                768: {
                    slidesPerView: 4,
                    spaceBetween: 10
                },
                // When window width is >= 991px
                991: {
                    slidesPerView: 6,
                    spaceBetween: 10
                },
                // When window width is >= 1200px
                1200: {
                    slidesPerView: 8,
                    spaceBetween: 10
                },
                // When window width is >= 1400px
                1400: {
                    slidesPerView: 3,
                    spaceBetween: 10
                }
            }
        });

        var currentCategory = '';
        var currentPage = 1;

        // Initialize active slide from data attribute on page load
        function initializeActiveSlide() {
            var categorySlug = $('.shop-archive').data('current-category') || '';
            currentCategory = categorySlug;
            
            // Remove active class from all slides
            $('.shop-cats-swiper .swiper-slide').removeClass('active');
            
            // Add active class to matching slide
            if (categorySlug) {
                $('.shop-cats-swiper .swiper-slide[data-slug="' + categorySlug + '"]').addClass('active');
            } else {
                // If no category, activate the "All" slide
                $('.shop-cats-swiper .swiper-slide[data-slug=""]').addClass('active');
            }
        }

        // Initialize active slide on page load
        initializeActiveSlide();
        console.log('Initialized active slide for category:', currentCategory);

        function applyFilters(page){
            var $form = $('#shop-price-filter');
            var min = parseFloat($form.find('input[name="min_price"]').val()) || 0;
            var max = parseFloat($form.find('input[name="max_price"]').val()) || 0;
            $.ajax({
                type: 'POST',
                url: miheliShop.ajax_url,
                data: {
                    action: 'miheli_filter_products',
                    nonce: miheliShop.nonce,
                    min_price: min,
                    max_price: max,
                    category: currentCategory,
                    paged: page || 1
                },
                beforeSend: function(){
                    $('#shop-products').addClass('loading');
                },
                success: function(resp){
                    if(resp && resp.success){
                        $('#shop-products').html(resp.data.products_html).removeClass('loading');
                        $('#shop-pagination-top').html(resp.data.pagination_html || '');
                    } else {
                        $('#shop-products').html('<div class="woocommerce-info">No products found.</div>').removeClass('loading');
                    }
                },
                error: function(){
                    $('#shop-products').removeClass('loading');
                }
            });
        }

        // Category click to filter
        $(document).on('click', '.shop-cats-swiper .swiper-slide', function(){
            currentCategory = $(this).data('slug') || '';
            $('.shop-cats-swiper .swiper-slide').removeClass('active');
            $(this).addClass('active');
            currentPage = 1;
            applyFilters(currentPage);
        });

        // Price filter submit
        $('#shop-price-filter').on('submit', function(e){
            e.preventDefault();
            currentPage = 1;
            applyFilters(currentPage);
        });

        // Top pagination: convert to AJAX
        $(document).on('click', '#shop-pagination-bottom a', function(e){
            e.preventDefault();
            var href = $(this).attr('href');
            var match = href.match(/[?&]paged=(\d+)/);
            var page = match ? parseInt(match[1], 10) : 1;
            currentPage = page;
            applyFilters(currentPage);
        });

        
    });
})(jQuery);
