/**
 * Main
 */

'use strict';

let menu, animate;

(function () {
  // Initialize menu
  //-----------------

  let layoutMenuEl = document.querySelectorAll('#layout-menu');
  layoutMenuEl.forEach(function (element) {
    menu = new Menu(element, {
      orientation: 'vertical',
      closeChildren: false
    });
    // Change parameter to true if you want scroll animation
    window.Helpers.scrollToActive((animate = false));
    window.Helpers.mainMenu = menu;
  });

  // Initialize menu togglers and bind click on each
  let menuToggler = document.querySelectorAll('.layout-menu-toggle');
  menuToggler.forEach(item => {
    item.addEventListener('click', event => {
      event.preventDefault();
      window.Helpers.toggleCollapsed();
    });
  });

  // Display menu toggle (layout-menu-toggle) on hover with delay
  let delay = function (elem, callback) {
    let timeout = null;
    elem.onmouseenter = function () {
      // Set timeout to be a timer which will invoke callback after 300ms (not for small screen)
      if (!Helpers.isSmallScreen()) {
        timeout = setTimeout(callback, 300);
      } else {
        timeout = setTimeout(callback, 0);
      }
    };

    elem.onmouseleave = function () {
      // Clear any timers set to timeout
      document.querySelector('.layout-menu-toggle').classList.remove('d-block');
      clearTimeout(timeout);
    };
  };
  if (document.getElementById('layout-menu')) {
    delay(document.getElementById('layout-menu'), function () {
      // not for small screen
      if (!Helpers.isSmallScreen()) {
        document.querySelector('.layout-menu-toggle').classList.add('d-block');
      }
    });
  }

  // Display in main menu when menu scrolls
  let menuInnerContainer = document.getElementsByClassName('menu-inner'),
    menuInnerShadow = document.getElementsByClassName('menu-inner-shadow')[0];
  if (menuInnerContainer.length > 0 && menuInnerShadow) {
    menuInnerContainer[0].addEventListener('ps-scroll-y', function () {
      if (this.querySelector('.ps__thumb-y').offsetTop) {
        menuInnerShadow.style.display = 'block';
      } else {
        menuInnerShadow.style.display = 'none';
      }
    });
  }

  // Init helpers & misc
  // --------------------

  // Init BS Tooltip
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Accordion active class
  const accordionActiveFunction = function (e) {
    if (e.type == 'show.bs.collapse' || e.type == 'show.bs.collapse') {
      e.target.closest('.accordion-item').classList.add('active');
    } else {
      e.target.closest('.accordion-item').classList.remove('active');
    }
  };

  const accordionTriggerList = [].slice.call(document.querySelectorAll('.accordion'));
  const accordionList = accordionTriggerList.map(function (accordionTriggerEl) {
    accordionTriggerEl.addEventListener('show.bs.collapse', accordionActiveFunction);
    accordionTriggerEl.addEventListener('hide.bs.collapse', accordionActiveFunction);
  });

  // Toggle Password Visibility
  function initPasswordToggle() {
    const containers = document.querySelectorAll('.form-password-toggle');
    containers.forEach(function (container) {
      if (container.dataset.passwordToggleBound) return;
      container.dataset.passwordToggleBound = 'true';

      const input = container.querySelector('input');
      const trigger = container.querySelector('.cursor-pointer, .input-group-text:last-child');
      const icon = container.querySelector('i.bx-hide, i.bx-show') || (trigger ? trigger.querySelector('i') : null);

      if (input && trigger) {
        trigger.style.cursor = 'pointer';
        trigger.setAttribute('role', 'button');
        trigger.setAttribute('tabindex', '0');
        trigger.setAttribute('title', 'Lihat kata sandi');
        trigger.setAttribute('aria-label', 'Lihat kata sandi');

        const toggleVisibility = function (e) {
          if (e) e.preventDefault();
          const isPassword = input.type === 'password' || input.getAttribute('type') === 'password';
          if (isPassword) {
            input.setAttribute('type', 'text');
            input.type = 'text';
            if (icon) {
              icon.classList.remove('bx-hide');
              icon.classList.add('bx-show');
            }
            trigger.setAttribute('title', 'Sembunyikan kata sandi');
            trigger.setAttribute('aria-label', 'Sembunyikan kata sandi');
          } else {
            input.setAttribute('type', 'password');
            input.type = 'password';
            if (icon) {
              icon.classList.remove('bx-show');
              icon.classList.add('bx-hide');
            }
            trigger.setAttribute('title', 'Lihat kata sandi');
            trigger.setAttribute('aria-label', 'Lihat kata sandi');
          }
        };

        trigger.addEventListener('click', toggleVisibility);
        trigger.addEventListener('keydown', function (e) {
          if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            toggleVisibility(e);
          }
        });
      }
    });
  }

  // Register on Helpers and initialize
  if (typeof window.Helpers !== 'undefined' && window.Helpers) {
    window.Helpers.initPasswordToggle = initPasswordToggle;
  }
  initPasswordToggle();

  // Helper-dependent initializations (safely checked)
  if (typeof window.Helpers !== 'undefined' && window.Helpers) {
    if (typeof window.Helpers.setAutoUpdate === 'function') {
      window.Helpers.setAutoUpdate(true);
    }
    if (typeof window.Helpers.initSpeechToText === 'function') {
      window.Helpers.initSpeechToText();
    }
    if (typeof window.Helpers.isSmallScreen === 'function' && window.Helpers.isSmallScreen()) {
      return;
    }
    if (typeof window.Helpers.setCollapsed === 'function') {
      window.Helpers.setCollapsed(true, false);
    }
  }
})();
