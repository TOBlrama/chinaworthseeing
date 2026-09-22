(() => {
  'use strict';

  const navigation = document.querySelector('.main-navigation');

  if (!navigation) {
    return;
  }

  const desktopQuery = window.matchMedia('(min-width: 783px)');
  const mainMenu = navigation.querySelector('.main-menu');
  const themeToggle = navigation.querySelector('#toggle');
  const themeToggleLabel = navigation.querySelector('#toggle-menu');
  const menuContainer = navigation.querySelector('.main-menu-container');

  if (!mainMenu) {
    return;
  }

  mainMenu.setAttribute('aria-label', 'Primary navigation');

  if (themeToggle && themeToggleLabel && menuContainer) {
    menuContainer.id ||= 'cws-primary-menu-container';
    themeToggle.setAttribute('aria-controls', menuContainer.id);

    const visibleTextNode = Array.from(themeToggleLabel.childNodes).find(
      (node) => node.nodeType === Node.TEXT_NODE && node.textContent.trim()
    );

    if (visibleTextNode) {
      visibleTextNode.textContent = 'Menu ';
    }

    const syncThemeToggle = () => {
      const expanded = themeToggle.checked;
      themeToggle.setAttribute('aria-expanded', String(expanded));
      themeToggle.setAttribute('aria-label', expanded ? 'Close primary menu' : 'Open primary menu');
    };

    syncThemeToggle();
    themeToggle.addEventListener('change', syncThemeToggle);
  }

  const menuItems = Array.from(mainMenu.querySelectorAll('.menu-item-has-children'));

  const directChild = (item, selector) =>
    Array.from(item.children).find((child) => child.matches(selector));

  const setOpen = (item, open) => {
    const button = directChild(item, '.cws-submenu-toggle');

    item.classList.toggle('is-open', open);

    if (button) {
      button.setAttribute('aria-expanded', String(open));
      const label = button.dataset.label || 'submenu';
      button.setAttribute('aria-label', `${open ? 'Close' : 'Open'} ${label} submenu`);
    }

    if (!open) {
      Array.from(item.querySelectorAll('.menu-item-has-children.is-open')).forEach((child) => {
        if (child !== item) {
          setOpen(child, false);
        }
      });
    }
  };

  const closeSiblings = (item) => {
    const parent = item.parentElement;

    if (!parent) {
      return;
    }

    Array.from(parent.children).forEach((sibling) => {
      if (sibling !== item && sibling.classList.contains('is-open')) {
        setOpen(sibling, false);
      }
    });
  };

	menuItems.forEach((item, index) => {
		const labelElement = directChild(item, 'a, .cws-nav-label');
		const submenu = directChild(item, '.sub-menu');

		if (!labelElement || !submenu) {
			return;
		}

		submenu.id ||= `cws-submenu-${index + 1}`;
		labelElement.removeAttribute('aria-haspopup');
		labelElement.removeAttribute('aria-expanded');

		const button = document.createElement('button');
		const label = labelElement.textContent.trim();

    button.type = 'button';
    button.className = 'cws-submenu-toggle';
    button.dataset.label = label;
    button.setAttribute('aria-controls', submenu.id);
    button.setAttribute('aria-expanded', 'false');
    button.setAttribute('aria-label', `Open ${label} submenu`);
		labelElement.insertAdjacentElement('afterend', button);

    button.addEventListener('click', (event) => {
      // A desktop pointer has already opened the mega menu on hover. Keep that
      // visible on click; keyboard and touch activation retain normal toggle
      // behavior and can close the disclosure with a second activation.
      const open = desktopQuery.matches && event.detail > 0
        ? true
        : !item.classList.contains('is-open');

      if (open) {
        closeSiblings(item);
      }

      setOpen(item, open);
    });

    if (item.classList.contains('cws-mega-menu')) {
      item.addEventListener('mouseenter', () => {
        if (desktopQuery.matches) {
          setOpen(item, true);
        }
      });

      item.addEventListener('mouseleave', () => {
        if (desktopQuery.matches && !item.contains(document.activeElement)) {
          setOpen(item, false);
        }
      });

      item.addEventListener('focusout', () => {
        window.setTimeout(() => {
          if (desktopQuery.matches && !item.contains(document.activeElement)) {
            setOpen(item, false);
          }
        }, 0);
      });
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') {
      return;
    }

    const openItem = navigation.querySelector('.menu-item.is-open');

    if (openItem) {
      const button = directChild(openItem, '.cws-submenu-toggle');
      setOpen(openItem, false);
      button?.focus();
      return;
    }

    if (themeToggle?.checked) {
      themeToggle.checked = false;
      themeToggle.dispatchEvent(new Event('change'));
      themeToggleLabel?.focus();
    }
  });

  document.addEventListener('pointerdown', (event) => {
    if (!navigation.contains(event.target)) {
      menuItems.forEach((item) => setOpen(item, false));
    }
  });

  desktopQuery.addEventListener('change', () => {
    menuItems.forEach((item) => setOpen(item, false));
  });
})();
