/**
 * Logs the user in across multiple wikis.
 */

// Which wiki will log the user on which other wiki.
const FOREIGN_WIKI_MAP = {
	'https://undertale.wiki': 'https://deltarune.wiki/api.php',
	'https://deltarune.wiki': 'https://undertale.wiki/api.php',
	'https://dev.undertale.wiki': 'https://dev.deltarune.wiki/api.php',
	'https://dev.deltarune.wiki': 'https://dev.undertale.wiki/api.php'
};
// Temporarily set this flag when submitting the form, so that our event handler
// does not intercept the form submission again.
let doNotIntercept = false;

/**
 * Retrieves the login token from the foreign wiki.
 * @param {mw.ForeignApi} api API object for the foreign wiki
 * @returns {$.Deferred} Resolves with the login token
 */
function getLoginToken( api ) {
	return api.get( {
		action: 'query',
		meta: 'tokens',
		type: 'login'
	} ).then( r => r.query.tokens.logintoken );
}

/**
 * Starts the login procedure on the foreign wiki.
 * @param {mw.ForeignApi} api API object for the foreign wiki
 * @param {string} username Username of the user to log in
 * @param {string} password Password of the user to log in
 * @returns {$.Deferred} Resolves with the login response
 */
function startLogin( api, username, password ) {
	return getLoginToken( api ).then( logintoken => api.post( {
		action: 'clientlogin',
		loginreturnurl: api.apiUrl.replace( '/api.php', '' ),
		logintoken,
		username,
		password
	} ) ).then( r => r.clientlogin );
}

/**
 * Continues the login procedure on the foreign wiki with a two-factor token.
 * @param {mw.ForeignApi} api API object for the foreign wiki
 * @param {string} token Two-factor token to use for logging in
 * @returns {$.Deferred} Resolves with the login response
 */
function twoFactorAuth( api, token ) {
	return getLoginToken( api ).then( logintoken => api.post( {
		action: 'clientlogin',
		logincontinue: true,
		logintoken,
		OATHToken: token
	} ) ).then( r => r.clientlogin );
}

/**
 * Intercepts a click on the login button and sends the login request to the
 * other wiki.
 * @param {Event} event Intercepted event arguments
 */
function submit( event ) {
	const wikiUrl = mw.config.get( 'wgServer' );
	const foreignWikiApiUrl = FOREIGN_WIKI_MAP[wikiUrl];
	if ( !foreignWikiApiUrl || doNotIntercept ) {
		return;
	}
	event.preventDefault();
	const $form = $( '#userloginForm form' );
	setTimeout( () => {
		// If the login to the foreign wiki has not completed in 10 seconds,
		// something has gone wrong and we can submit the form anyways.
		doNotIntercept = true;
		$form.submit();
	}, 10 * 1000 );
	const formData = new FormData( $form[0] );
	mw.loader.using( 'mediawiki.ForeignApi' ).then( () => {
		const api = new mw.ForeignApi( foreignWikiApiUrl );
		let response;
		if ( formData.get( 'OATHToken' ) === null ) {
			// We are on the login page.
			const username = formData.get( 'wpName' );
			const password = formData.get( 'wpPassword' );
			response = startLogin( api, username, password );
		} else {
			// We are on the two-factor auth page.
			const token = formData.get( 'OATHToken' );
			response = twoFactorAuth( api, token );
		}
		response.then( r => {
			if ( r.status === 'PASS' ) {
				mw.log( `Login on ${foreignWikiApiUrl} successful!` );
			} else if ( r.status === 'UI' ) {
				mw.log( `${foreignWikiApiUrl} requests UI login with message "${r.message}".` );
			} else if ( r.status === 'FAIL' ) {
				mw.log( `Login on ${foreignWikiApiUrl} failed with message "${r.message}".` );
			}
			doNotIntercept = true;
			$form.submit();
		} ).fail( code => {
			mw.log( `Login on ${foreignWikiApiUrl} failed with code ${code}.` );
			doNotIntercept = true;
			$form.submit();
		} );
	} );
}

function init() {
	mw.loader.load( 'mediawiki.ForeignApi' );
	$( '#userloginForm form' ).submit( submit );
}

module.exports = init;
