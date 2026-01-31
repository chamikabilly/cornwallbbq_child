/**
 * Global Toast Notification System
 * Theme-styled toast notifications for WooCommerce
 */

(function($) {
    'use strict';

    // Global toast function
    window.showMiheliToast = function(message, type = 'info') {
        const toastId = 'miheli-toast-' + Date.now();
        
        // Map types to colors and icons
        const typeConfig = {
            success: {
                bgClass: 'toast-success',
                icon: 'fa-check-circle',
                title: 'Success'
            },
            error: {
                bgClass: 'toast-error',
                icon: 'fa-exclamation-circle',
                title: 'Error'
            },
            info: {
                bgClass: 'toast-info',
                icon: 'fa-info-circle',
                title: 'Notice'
            }
        };
        
        const config = typeConfig[type] || typeConfig.info;
        
        const toastHtml = `
            <div id="${toastId}" class="toast miheli-toast ${config.bgClass}" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <i class="fas ${config.icon} me-2"></i>
                    <strong class="me-auto">${config.title}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        `;
        
        let $container = $('.toast-container');
        if (!$container.length) {
            $('body').append('<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11000;"></div>');
            $container = $('.toast-container');
        }
        
        const $toast = $(toastHtml);
        $container.append($toast);
        
        const bsToast = new bootstrap.Toast($toast[0], {
            autohide: true,
            delay: type === 'error' ? 5000 : 3000
        });
        bsToast.show();
        
        $toast.on('hidden.bs.toast', function() {
            $(this).remove();
        });
    };

    // Alias for cart page compatibility
    window.showCartToast = window.showMiheliToast;

})(jQuery);
