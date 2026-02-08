/**
 * Cart Page - AJAX Handlers & Interactions - Production Ready
 * 
 * Handles:
 * - Quantity +/- buttons with validation
 * - Individual cart item updates via AJAX
 * - Coupon application with visual feedback
 * - Remove item functionality
 * - Cart totals dynamic updates
 * - Toast notifications integration
 * 
 * Dependencies: jQuery, Bootstrap 5 Toast, WooCommerce AJAX
 * 
 * @package Miheli_Solutions_Child
 * @version 1.0.0
 */

jQuery(function($) {
    'use strict';

    // Handle +/- buttons
    $(document).on('click', '.qty-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $btn = $(this);
        const $quantityWrapper = $btn.closest('.quantity');
        const $input = $quantityWrapper.find('input.qty');
        
        if (!$input.length) return;
        
        let currentVal = parseInt($input.val()) || 1;
        const minVal = parseInt($input.attr('min')) || 1;
        const maxAttr = $input.attr('max');
        const maxVal = maxAttr ? parseInt(maxAttr) : 999999;
        
        if ($btn.hasClass('minus')) {
            if (currentVal > minVal) currentVal--;
        } else if ($btn.hasClass('plus')) {
            if (currentVal < maxVal) currentVal++;
        }
        
        $input.val(currentVal).attr('value', currentVal).prop('value', currentVal);
        
        // Visual feedback
        $input.addClass('changed');
        setTimeout(function() {
            $input.removeClass('changed');
        }, 300);

        scheduleRowUpdate($btn.closest('tr'));
    });

    // Handle direct input validation
    $(document).on('input', 'input.qty', function() {
        const $input = $(this);
        let val = parseInt($input.val()) || 1;
        const minVal = parseInt($input.attr('min')) || 1;
        const maxAttr = $input.attr('max');
        const maxVal = maxAttr ? parseInt(maxAttr) : 999999;
        
        // Validate and correct value
        if (val < minVal) val = minVal;
        if (val > maxVal) val = maxVal;
        
        $input.val(val).attr('value', val).prop('value', val);

        scheduleRowUpdate($input.closest('tr'));
    });

    function updateCartTotalsHtml(totalsHtml) {
        if (!totalsHtml) return;

        const $tempDiv = $('<div>').html(totalsHtml);
        const $newTotals = $tempDiv.find('.cart_totals');
        const $currentTotals = $('.cart-totals-modern .cart_totals');

        if ($newTotals.length && $currentTotals.length) {
            $currentTotals.replaceWith($newTotals);
        } else if ($newTotals.length) {
            $('.cart-totals-modern').html($newTotals);
        }
    }

    function scheduleRowUpdate($row) {
        if (!$row || !$row.length) return;
        if (!$row.find('.btn-update-item').length) return;

        const existingTimer = $row.data('updateTimer');
        if (existingTimer) clearTimeout(existingTimer);

        const timer = setTimeout(function() {
            $row.find('.btn-update-item').trigger('click');
        }, 350);

        $row.data('updateTimer', timer);
    }

    // Handle Update button click for individual items
    $(document).on('click', '.btn-update-item', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $btn = $(this);
        const $row = $btn.closest('tr');
        const cartKey = $btn.data('cart-key');
        const $qtyInput = $row.find('input.qty');
        const newQty = parseInt($qtyInput.val()) || 1;
        
        // Disable button during update
        $btn.prop('disabled', true);
        $row.css('opacity', '0.6');
        
        // Get nonce from form (same nonce used by WooCommerce mini cart)
        const nonce = $('input[name="woocommerce-cart-nonce"]').val();
        
        // Prepare data for AJAX update
        const updateData = {
            action: 'woocommerce_update_cart_item',
            cart_key: cartKey,
            quantity: newQty,
            nonce: nonce
        };
        
        $.ajax({
            type: 'POST',
            url: cartAjax.ajaxurl,
            data: updateData,
            success: function(response) {
                if (response.success) {
                    // Update the subtotal for this item
                    const subtotalHtml = response.data.subtotal;
                    $row.find('.col-subtotal').html(subtotalHtml);
                    
                    // Update cart totals
                    updateCartTotalsHtml(response.data.cart_totals);
                    
                    // Visual feedback - show success
                    $row.addClass('row-updated');
                    setTimeout(function() {
                        $row.removeClass('row-updated');
                    }, 500);
                    
                    // Trigger WooCommerce fragment refresh for mini cart
                    $(document.body).trigger('wc_fragment_refresh');
                    
                    // Show toast notification
                    showCartToast('Item updated successfully', 'success');
                } else {
                    console.error('Update failed:', response.data);
                    showCartToast('Error updating item: ' + response.data.message, 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Update error:', error);
                console.error('XHR Response:', xhr.responseText);
                showCartToast('Error updating cart item. Please try again.', 'error');
            },
            complete: function() {
                $btn.prop('disabled', false);
                $row.css('opacity', '1');
            }
        });
        
        return false;
    });

    // Handle coupon application
    $(document).on('click', '.btn-apply-coupon', function(e) {
        e.preventDefault();
        
        const $btn = $(this);
        const $couponInput = $('#coupon_code');
        const couponCode = $couponInput.val().trim();
        
        if (!couponCode) {
            showCartToast('Please enter a coupon code', 'error');
            $couponInput.addClass('is-invalid');
            setTimeout(function() {
                $couponInput.removeClass('is-invalid');
            }, 3000);
            return false;
        }
        
        // Get nonce from form
        const nonce = $('input[name="woocommerce-cart-nonce"]').val();
        
        // Disable button during processing
        $btn.prop('disabled', true);
        $btn.html('<i class="fas fa-spinner fa-spin"></i> Applying...');
        $couponInput.prop('disabled', true);
        
        $.ajax({
            type: 'POST',
            url: cartAjax.ajaxurl,
            data: {
                action: 'apply_coupon',
                coupon_code: couponCode,
                nonce: nonce
            },
            success: function(response) {
                if (response.success) {
                    // Coupon applied successfully
                    $couponInput.val('').removeClass('is-invalid').addClass('is-valid');
                    showCartToast(response.data.message || 'Coupon applied successfully!', 'success');
                    
                    // Update cart totals content only
                    updateCartTotalsHtml(response.data.cart_totals);
                    
                    // Clear the valid class after 3 seconds
                    setTimeout(function() {
                        $couponInput.removeClass('is-valid');
                    }, 3000);
                    
                    // Trigger WooCommerce fragment refresh
                    $(document.body).trigger('wc_fragment_refresh');
                } else {
                    // Coupon validation failed
                    $couponInput.addClass('is-invalid');
                    showCartToast(response.data.message || 'Invalid coupon code', 'error');
                    
                    // Remove invalid class after 3 seconds
                    setTimeout(function() {
                        $couponInput.removeClass('is-invalid');
                    }, 3000);
                }
            },
            error: function(xhr, status, error) {
                console.error('Coupon error:', error);
                $couponInput.addClass('is-invalid');
                showCartToast('Error applying coupon. Please try again.', 'error');
            },
            complete: function() {
                $btn.prop('disabled', false);
                $btn.html('Apply');
                $couponInput.prop('disabled', false);
            }
        });
        
        return false;
    });

    // Handle main Update Cart button
    $(document).on('submit', 'form.woocommerce-cart-form', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        
        $.ajax({
            type: 'POST',
            url: $form.attr('action'),
            data: $form.serialize(),
            beforeSend: function() {
                $form.css('opacity', '0.6');
            },
            success: function(response) {
                const $newCart = $(response).find('form.woocommerce-cart-form');
                if ($newCart.length) {
                    $form.replaceWith($newCart);
                }
                
                // Update cart totals
                const $newTotals = $(response).find('.cart-totals-wrapper');
                if ($newTotals.length) {
                    $('.cart-totals-wrapper').replaceWith($newTotals);
                }
                
                // Trigger WooCommerce fragment refresh
                $(document.body).trigger('wc_fragment_refresh');
            },
            error: function() {
                alert('Error updating cart. Please try again.');
            },
            complete: function() {
                $form.css('opacity', '1');
            }
        });
        
        return false;
    });

    // Handle remove item
    $(document).on('click', '.btn-remove-item', function(e) {
        e.preventDefault();
        
        const $btn = $(this);
        const removeUrl = $btn.attr('href');
        const $row = $btn.closest('tr');
        
        $row.css('opacity', '0.5');
        
        $.ajax({
            type: 'GET',
            url: removeUrl,
            success: function(response) {
                const $newCart = $(response).find('form.woocommerce-cart-form');
                const $currentForm = $('form.woocommerce-cart-form');
                
                if ($newCart.length) {
                    $currentForm.replaceWith($newCart);
                }
                
                // Update cart totals
                const $newTotals = $(response).find('.cart-totals-wrapper');
                if ($newTotals.length) {
                    $('.cart-totals-wrapper').replaceWith($newTotals);
                }
                
                // Trigger WooCommerce fragment refresh
                $(document.body).trigger('wc_fragment_refresh');
            },
            error: function() {
                $row.css('opacity', '1');
                alert('Error removing item. Please try again.');
            }
        });
        
        return false;
    });
});