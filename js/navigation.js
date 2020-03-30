/**
 * navigation.js
 *
 * Handles toggling the navigation menu for small screens and enables tab
 * support for dropdown menus.
 */
(function () {
	var body = document.querySelector('body');
	var openBtn = document.getElementById('menu-toggle');
	var overlay = document.createElement('div');
	var menuWrap = document.getElementById('primary-menu-wrap');
	var menuContainer = menuWrap.querySelector('#primary-menu > .content-width'); // Menu items container
	var menuParentItems = menuContainer.querySelectorAll(":scope > .menu-item");
	menuContainer.insertAdjacentHTML('beforeend', '<div id="menu-close">╳</div>'); // Insert Close btn
	var closeBtn = document.getElementById('menu-close');

	// Open/close mobile menu
	openBtn.addEventListener('click', () => {
		createOverlay();
		openNav();
	});
	
	closeBtn.addEventListener('click', () => {
		destroyOverlay();
		closeNav();
	});
	
	// Open sub-menu if .menu-item-has-children
	menuParentItems.forEach( parentMenuItem => {
		if ( parentMenuItem.classList.contains('menu-item-has-children') ) {
			let parentItemLink = parentMenuItem.querySelector('a');
			parentItemLink.addEventListener('click', function(event) {
				event.preventDefault();
				let subMenuWrap = parentMenuItem.querySelector('.sub-menu');
				subMenuWrap.classList.toggle('active-submenu');
			});
		}
	});
	
	if (window.screen.width > 1024) {
		destroyOverlay();
	}

	if (window.screen.width < 1024) {
		const height = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
		menuWrap.style['min-height'] = height + 'px';
	}
	
	function openNav() {
		body.classList.add('menu--active');
		openBtn.setAttribute('aria-expanded', 'true');
		menuWrap.setAttribute('aria-expanded', 'true');
		return;
	}
	
	function closeNav() {
		body.classList.remove('menu--active');
		openBtn.setAttribute('aria-expanded', 'false');
		menuWrap.setAttribute('aria-expanded', 'false');
		return;
	}
	
	function createOverlay() {
		overlay.classList.add('mobile-nav-overlay');
		menuWrap.insertAdjacentElement('afterend', overlay);
		overlay.addEventListener('click', () => {
			destroyOverlay();
			closeNav();
		});
	}
	
	function destroyOverlay() {
		overlay.remove();
	}

	// Get all the link elements within the menu.
	var subMenus = menuWrap.getElementsByTagName('ul')

	// Set menu items with submenus to aria-haspopup="true".
	for (var i = 0, len = subMenus.length; i < len; i++) {
		subMenus[i].parentNode.setAttribute('aria-haspopup', 'true')
	}
	
})();