document.addEventListener('DOMContentLoaded', function () {
    var accordion = document.querySelector('.product-tabs-accordion');
    if (!accordion) return;

    // Fallback accordion behavior if Bootstrap JS is not active
    accordion.querySelectorAll('.accordion-button').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            var targetId = btn.getAttribute('data-bs-target') || btn.getAttribute('data-target');
            if (!targetId) return;

            var target = document.querySelector(targetId);
            if (!target) return;

            var isOpen = target.classList.contains('show');

            // Close all panels
            accordion.querySelectorAll('.accordion-collapse').forEach(function (panel) {
                panel.classList.remove('show');
                panel.style.height = '';
                panel.setAttribute('aria-hidden', 'true');
            });
            accordion.querySelectorAll('.accordion-button').forEach(function (button) {
                button.classList.add('collapsed');
                button.setAttribute('aria-expanded', 'false');
            });

            if (!isOpen) {
                target.classList.add('show');
                target.setAttribute('aria-hidden', 'false');
                btn.classList.remove('collapsed');
                btn.setAttribute('aria-expanded', 'true');
            }
        });
    });
});
