/**
 * Product Add-ons JavaScript
 * Handles add-on selection and price calculation
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        
        // Handle addon checkbox changes
        $('.addon-checkbox').on('change', function() {
            updateTotalPrice();
        });

        // Click on addon item to toggle checkbox
        $('.addon-item').on('click', function(e) {
            if (!$(e.target).is('input[type="checkbox"]')) {
                const $checkbox = $(this).find('.addon-checkbox');
                $checkbox.prop('checked', !$checkbox.prop('checked')).trigger('change');
            }
        });

        /**
         * Update total price display
         */
        function updateTotalPrice() {
            let totalAddonPrice = 0;
            
            $('.addon-checkbox:checked').each(function() {
                const addonValue = $(this).val();
                const parts = addonValue.split('|');
                if (parts.length === 2) {
                    totalAddonPrice += parseFloat(parts[1]);
                }
            });

            // Get base price from the product
            const $priceElement = $('.single-product-summary .price .woocommerce-Price-amount').first();
            
            if ($priceElement.length) {
                const priceText = $priceElement.text().replace(/[^0-9.]/g, '');
                const basePrice = parseFloat(priceText);
                
                if (!isNaN(basePrice) && totalAddonPrice > 0) {
                    const newTotal = basePrice + totalAddonPrice;
                    
                    // Display addon total
                    if ($('.addon-total-display').length === 0) {
                        $('.single-product-summary .price').parent().append(
                            '<div class=\"addon-total-display\">' +
                            '<span class=\"addon-label\">Add-ons: </span>' +
                            '<span class=\"addon-amount\">$' + totalAddonPrice.toFixed(2) + '</span>' +
                            '</div>' +
                            '<div class=\"final-total-display\">' +
                            '<span class=\"total-label\">Total: </span>' +
                            '<span class=\"total-amount\">$' + newTotal.toFixed(2) + '</span>' +
                            '</div>'
                        );
                    } else {
                        $('.addon-amount').text('$' + totalAddonPrice.toFixed(2));
                        $('.total-amount').text('$' + newTotal.toFixed(2));
                    }
                } else {
                    $('.addon-total-display, .final-total-display').remove();
                }
            }
        }

        /**
         * Add animation to add to cart button
         */
        $('.single_add_to_cart_button').on('click', function() {
            $(this).addClass('loading');
        });

        // Remove loading class after adding to cart
        $(document.body).on('added_to_cart', function() {
            $('.single_add_to_cart_button').removeClass('loading');
        });

    });

})(jQuery);
