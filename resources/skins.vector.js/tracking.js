function trackAll(selector, category, action, name) {
	document.querySelectorAll(selector).forEach(link => link
		.addEventListener('click', event => {
			if (!window._paq) {
				return;
			}
			const nameOrValue = event.currentTarget.href;
			if (name) {
				window._paq.push(['trackEvent', category, action, name, nameOrValue]);
			} else {
				window._paq.push(['trackEvent', category, action, nameOrValue]);
			}
		})
	);
}

function initSiteNoticeTracking() {
	trackAll('#siteNotice a[href]', 'Site Notice', 'Click');
}

function initNavigationTracking() {
	const headerSelectors = [
		[
			'.vector-header-start',
			'Header',
			'Desktop Click',
		],
		[
			'.vector-header-container:not(.vector-sticky-header-container) > .mobile-navigation',
			'Header',
			'Mobile Click',
		],
		[
			'#vector-sticky-header',
			'Sticky Header',
			'Desktop Click',
		],
		[
			'.vector-sticky-header-container .mobile-navigation',
			'Sticky Header',
			'Mobile Click',
		],
	];
	const headerLevelSelectors = [
		'.vector-menu > .vector-menu-heading > a',
		'.vector-menu-content:not(.utw-navbar-subitem-collapsable) > .utw-navbar-menu > .utw-navbar-subitem > .utw-navbar-subitem-link',
		'.vector-menu-content.utw-navbar-subitem-collapsable > .utw-navbar-menu > .utw-navbar-subitem > .utw-navbar-subitem-link',
	];
	for (const [headerSelector, category, action] of headerSelectors) {
		for (const [level, levelSelector] of headerLevelSelectors.entries()) {
			trackAll(
				`${headerSelector} ${levelSelector}`,
				category,
				action,
				`Level ${level + 1}`
			);
		}
	}
}

function initHeaderButtonTracking() {
	const headerButtonAreas = [
		[
			'.vector-header-end',
			'Header',
		],
		[
			'.vector-sticky-header-end',
			'Sticky Header',
		]
	];
	const headerButtons = [
		[
			'.discussions-button',
			'Discussions Button Click',
		],
		[
			'#vector-appearance-dropdown-checkbox',
			'Appearance Button Click',
		],
		[
			'#vector-user-links-dropdown-checkbox',
			'User Links Dropdown Click',
		]
	];
	for (const [headerButtonArea, category] of headerButtonAreas) {
		for (const [headerButton, action] of headerButtons) {
			trackAll(
				`${headerButtonArea} ${headerButton}`,
				category,
				action
			);
		}
	}
}

function initTocButtonTracking() {
	trackAll(
		'#vector-page-titlebar-toc-checkbox',
		'Table of Contents',
		'Title Click'
	);
	trackAll(
		'#vector-sticky-header-toc-checkbox',
		'Table of Contents',
		'Sticky Header Click',
	);
}

function initSidebarToggleTracking() {
	trackAll(
		'.sidebar-toggle',
		'Sidebar Toggle',
		'Click'
	);
}

function init() {
	initSiteNoticeTracking();
	initNavigationTracking();
	initHeaderButtonTracking();
	initTocButtonTracking();
	initSidebarToggleTracking();
}

module.exports = init;
