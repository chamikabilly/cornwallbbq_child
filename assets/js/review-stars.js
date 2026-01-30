/**
 * Replace default WooCommerce star ratings with custom SVG stars in reviews
 */
(function($) {
    'use strict';

    function replaceStarRatings() {
        // Replace star ratings in the reviews list (comments)
        $('.product-tabs-accordion #reviews .star-rating').each(function() {
            const $starRating = $(this);
            
            // Skip if already converted
            if ($starRating.hasClass('svg-stars-converted')) {
                return;
            }

            // Get the rating from the width percentage or data attribute
            let rating = 0;
            const widthAttr = $starRating.attr('style');
            if (widthAttr && widthAttr.includes('width:')) {
                const widthMatch = widthAttr.match(/width:\s*(\d+(?:\.\d+)?)/);
                if (widthMatch) {
                    rating = (parseFloat(widthMatch[1]) / 100) * 5;
                }
            }

            // If no width found, try to extract from title/aria-label
            if (rating === 0) {
                const ratingText = $starRating.find('strong').text() || $starRating.text();
                const ratingMatch = ratingText.match(/(\d+(?:\.\d+)?)/);
                if (ratingMatch) {
                    rating = parseFloat(ratingMatch[1]);
                }
            }

            // Generate SVG stars
            const svgStarsHTML = generateSVGStars(rating);
            
            // Replace content with SVG stars
            $starRating.html(svgStarsHTML).addClass('svg-stars-converted');
        });
    }

    function generateSVGStars(rating) {
        const fullStars = Math.floor(rating);
        const partialStar = rating - fullStars;
        const emptyStars = 5 - Math.ceil(rating);
        
        let html = '<span class="star-ratings">';
        
        // Full stars
        for (let i = 0; i < fullStars; i++) {
            html += `<svg class="star" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#D4A331" width="16" height="16">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>`;
        }
        
        // Partial star
        if (partialStar > 0) {
            const clipId = 'star-clip-' + Math.random().toString(36).substr(2, 9);
            const starFill = (partialStar * 100).toFixed(1);
            
            html += `<svg class="star" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16">
                <defs>
                    <clipPath id="${clipId}">
                        <rect x="0" y="0" width="${starFill}%" height="100%" />
                    </clipPath>
                </defs>
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" fill="rgba(212, 163, 49, 0.2)"/>
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" fill="#D4A331" clip-path="url(#${clipId})"/>
            </svg>`;
        }
        
        // Empty stars
        for (let i = 0; i < emptyStars; i++) {
            html += `<svg class="star" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="rgba(212, 163, 49, 0.2)" width="16" height="16">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>`;
        }
        
        html += '</span>';
        return html;
    }

    // Run on page load
    $(document).ready(function() {
        replaceStarRatings();
    });

    // Run after AJAX events (for dynamic content loading)
    $(document).on('ajaxComplete', function() {
        setTimeout(replaceStarRatings, 100);
    });

    // Watch for DOM changes in the reviews section
    if (window.MutationObserver) {
        const observer = new MutationObserver(function(mutations) {
            replaceStarRatings();
        });

        const reviewsSection = document.querySelector('.product-tabs-accordion #reviews');
        if (reviewsSection) {
            observer.observe(reviewsSection, {
                childList: true,
                subtree: true
            });
        }
    }

})(jQuery);
