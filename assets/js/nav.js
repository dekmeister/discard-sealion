/* Navigation hamburger menu toggle */
( function () {
	'use strict';

	var btn = document.querySelector( '.menu-toggle' );
	var nav = document.querySelector( '.site-navigation' );
	if ( ! btn || ! nav ) {
		return;
	}

	btn.addEventListener( 'click', function () {
		var open = nav.classList.toggle( 'is-open' );
		btn.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
	} );
}() );
