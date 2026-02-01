/**
 * Logout confirmation dialog for My Account links
 */
(function($) {
    'use strict';

    const message = (window.miheliLogoutConfirm && window.miheliLogoutConfirm.message)
        ? window.miheliLogoutConfirm.message
        : 'Are you sure you want to log out?';

    $(document).on('click', 'a.js-confirm-logout', function(event) {
        const confirmed = window.confirm(message);
        if (!confirmed) {
            event.preventDefault();
        }
    });
})(jQuery);
