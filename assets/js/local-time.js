/* Show comment times in the visitor's local timezone, labelled with the zone. */
( function () {
	'use strict';

	if ( ! window.Intl || ! Intl.DateTimeFormat ) {
		return;
	}

	function parse( el ) {
		var date = new Date( el.getAttribute( 'datetime' ) );
		return isNaN( date.getTime() ) ? null : date;
	}

	// Single-post comment list: "November 8, 2026 at 3:45 pm EST".
	var full = new Intl.DateTimeFormat( undefined, {
		year: 'numeric', month: 'long', day: 'numeric',
		hour: 'numeric', minute: '2-digit', timeZoneName: 'short'
	} );
	document.querySelectorAll( '.comment-metadata time[datetime]' ).forEach( function ( el ) {
		var date = parse( el );
		if ( date ) {
			el.textContent = full.format( date );
		}
	} );

	// Recent Comments river: separate date and clock spans.
	var dayFmt   = new Intl.DateTimeFormat( undefined, { month: 'short', day: 'numeric' } );
	var clockFmt = new Intl.DateTimeFormat( undefined, {
		hour: 'numeric', minute: '2-digit', timeZoneName: 'short'
	} );
	document.querySelectorAll( '.rc-time[datetime]' ).forEach( function ( el ) {
		var date  = parse( el );
		var dEl   = el.querySelector( '[data-rc-date]' );
		var cEl   = el.querySelector( '[data-rc-clock]' );
		if ( date && dEl && cEl ) {
			dEl.textContent = dayFmt.format( date );
			cEl.textContent = clockFmt.format( date );
		}
	} );
}() );
