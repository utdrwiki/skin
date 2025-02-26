/**
 * Creates a separate navigation bar for mobile, adds interactivity to it and
 * enables the mobile navigation toggle button.
 */
function init() {
	const mobileNavs = document.getElementsByClassName('mobile-navigation');
	for (const node of document.querySelectorAll('.mw-header .vector-header-start .vector-menu')) {
		for (const mobileNav of mobileNavs) {
			const menu = node.cloneNode(true);
			menu.addEventListener('click', event => event.currentTarget.classList.toggle('shown'));
			mobileNav.appendChild(menu);
		}
	}
	for (const node of document.getElementsByClassName('mobile-navigation-show-toggle')) {
		node.addEventListener('click', () => {
			for (const mobileNav of mobileNavs) {
				mobileNav.classList.toggle('shown');
			}
		});
	}
}

module.exports = init;
