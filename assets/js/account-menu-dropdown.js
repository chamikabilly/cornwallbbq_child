document.addEventListener('DOMContentLoaded', function () {
  var toggles = document.querySelectorAll('.account-actions-dropdown .dropdown-toggle');

  toggles.forEach(function (toggle) {
    toggle.addEventListener('click', function (event) {
      event.preventDefault();

      if (window.bootstrap && window.bootstrap.Dropdown) {
        var instance = window.bootstrap.Dropdown.getOrCreateInstance(toggle);
        instance.toggle();
        return;
      }

      var menu = toggle.parentElement.querySelector('.dropdown-menu');
      if (!menu) {
        return;
      }

      var isShown = menu.classList.contains('show');
      menu.classList.toggle('show', !isShown);
      toggle.setAttribute('aria-expanded', (!isShown).toString());
    });
  });

  document.addEventListener('click', function (event) {
    if (event.target.closest('.account-actions-dropdown')) {
      return;
    }

    document.querySelectorAll('.account-actions-dropdown .dropdown-menu.show').forEach(function (menu) {
      menu.classList.remove('show');
      var button = menu.parentElement.querySelector('.dropdown-toggle');
      if (button) {
        button.setAttribute('aria-expanded', 'false');
      }
    });
  });
});
