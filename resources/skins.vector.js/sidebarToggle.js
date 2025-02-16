let /** @type {Element | null} */ toggle = null;

const features = require( './features.js' );

/**
 * Toggles the icon to left/right on the sidebar toggle, and updates the label.
 */
function toggleIconAndLabel() {
	const icon = toggle?.querySelector( '.vector-icon' );
	const wasShown = icon?.classList.contains( 'mw-ui-icon-next' );
	const newMsgCode = `utw-sidebar-${wasShown ? 'show' : 'hide'}`;
	const newMsg = mw.msg( newMsgCode );
	icon?.setAttribute( 'title', newMsg );
	icon?.setAttribute( 'aria-label', newMsg );

	icon?.classList.toggle( 'mw-ui-icon-next' );
	icon?.classList.toggle( 'mw-ui-icon-previous' );
	icon?.classList.toggle( 'mw-ui-icon-wikimedia-next' );
	icon?.classList.toggle( 'mw-ui-icon-wikimedia-previous' );
}

/**
 * Handles clicking on the sidebar toggle.
 */
function click() {
	// Change user's preference for showing or hiding the sidebar.
	features.toggle( 'page-tools-pinned' );
	// Change the toggle button's icon.
	toggleIconAndLabel();
}

/**
 * Sets a click handler on the sidebar toggle.
 */
function init() {
	toggle = document.querySelector( '.vector-column-end .sidebar-toggle' );
	toggle?.addEventListener( 'click', click );
	if ( mw.user.isAnon() && mw.user.clientPrefs.get('vector-feature-page-tools-pinned') === '0' ) {
		// MediaWiki does not set the correct icon classes for anonymous users.
		// See: FIXME in VectorComponentTableOfContents
		toggleIconAndLabel();
	}
}

module.exports = init;
