(function ($) {
  $(function () {
    // Init category titles slider (no autoplay)
    var catSwiper = new Swiper(".shop-cats-swiper", {
      direction: "horizontal",
      slidesPerView: "auto",
      spaceBetween: 10,
      freeMode: true,
      watchOverflow: true,
      navigation: {
        nextEl: ".shop-cats-swiper .swiper-button-next",
        prevEl: ".shop-cats-swiper .swiper-button-prev",
      },
    });

    var currentCategory = "";
    var currentPage = 1;

    // Initialize active slide from data attribute on page load
    function initializeActiveSlide() {
      var categorySlug = $(".shop-archive").data("current-category") || "";
      currentCategory = categorySlug;

      // Remove active class from all slides
      $(".shop-cats-swiper .swiper-slide").removeClass("active");

      // Add active class to matching slide
      if (categorySlug) {
        $(
          '.shop-cats-swiper .swiper-slide[data-slug="' + categorySlug + '"]',
        ).addClass("active");
      } else {
        // If no category, activate the "All" slide
        $('.shop-cats-swiper .swiper-slide[data-slug=""]').addClass("active");
      }
    }

    // Initialize active slide on page load
    initializeActiveSlide();
    // console.log('Initialized active slide for category:', currentCategory);

    function applyFilters(page) {
      var $form = $("#shop-price-filter");
      var min = parseFloat($form.find('input[name="min_price"]').val()) || 0;
      var max = parseFloat($form.find('input[name="max_price"]').val()) || 0;
      $.ajax({
        type: "POST",
        url: miheliShop.ajax_url,
        data: {
          action: "miheli_filter_products",
          nonce: miheliShop.nonce,
          min_price: min,
          max_price: max,
          category: currentCategory,
          paged: page || 1,
        },
        beforeSend: function () {
          $("#shop-products").addClass("loading");
        },
        success: function (resp) {
          if (resp && resp.success) {
            $("#shop-products")
              .html(resp.data.products_html)
              .removeClass("loading");
            // Update bottom pagination (template renders #shop-pagination-bottom)
            $("#shop-pagination-bottom").html(resp.data.pagination_html || "");
          } else {
            // Fallback: if AJAX response isn't successful, do not block navigation
            $("#shop-products").removeClass("loading");
          }
        },
        error: function () {
          $("#shop-products").removeClass("loading");
        },
      });
    }

    // Category navigation handled via anchor links within slides

    // Price filter submit
    $("#shop-price-filter").on("submit", function (e) {
      e.preventDefault();
      currentPage = 1;
      applyFilters(currentPage);
    });

    // Bottom pagination: convert to AJAX with graceful fallback
    $(document).on("click", "#shop-pagination-bottom a", function (e) {
      var href = $(this).attr("href") || "";

      // Extract page from common WooCommerce permalink patterns
      // Supports ?paged=2 and /page/2/ forms
      var page = 1;
      var queryMatch = href.match(/[?&]paged=(\d+)/);
      if (queryMatch) {
        page = parseInt(queryMatch[1], 10) || 1;
      } else {
        var pathMatch = href.match(/\/page\/(\d+)/);
        if (pathMatch) {
          page = parseInt(pathMatch[1], 10) || 1;
        }
      }

      // If AJAX context is available, try AJAX; else allow normal navigation
      if (typeof miheliShop !== "undefined" && miheliShop.ajax_url) {
        e.preventDefault();
        currentPage = page;
        var didFail = false;

        // Wrap applyFilters to allow redirect on failure
        $.ajax({
          type: "POST",
          url: miheliShop.ajax_url,
          data: {
            action: "miheli_filter_products",
            nonce: miheliShop.nonce,
            min_price:
              parseFloat(
                $("#shop-price-filter").find('input[name="min_price"]').val(),
              ) || 0,
            max_price:
              parseFloat(
                $("#shop-price-filter").find('input[name="max_price"]').val(),
              ) || 0,
            category: currentCategory,
            paged: currentPage || 1,
          },
          beforeSend: function () {
            $("#shop-products").addClass("loading");
          },
          success: function (resp) {
            if (resp && resp.success) {
              $("#shop-products")
                .html(resp.data.products_html)
                .removeClass("loading");
              $("#shop-pagination-bottom").html(
                resp.data.pagination_html || "",
              );
            } else {
              didFail = true;
            }
          },
          error: function () {
            didFail = true;
          },
          complete: function () {
            $("#shop-products").removeClass("loading");
            if (didFail && href) {
              // Graceful fallback to normal navigation
              window.location.href = href;
            }
          },
        });
      } else {
        // No AJAX context; allow default navigation
        // Do not preventDefault so the browser follows the link
      }
    });
  });
})(jQuery);
