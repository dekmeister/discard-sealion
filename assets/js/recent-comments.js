/* Recent Comments page — filter chips and unread tracking */
( function () {
	'use strict';

	var STORAGE_KEY = 'pdc_last_seen';

	var lastSeen = parseInt( localStorage.getItem( STORAGE_KEY ) || '0', 10 );

	// Wire up filter chips.
	var chips = document.querySelectorAll( '[data-rc-filter]' );
	chips.forEach( function ( chip ) {
		chip.addEventListener( 'click', function () {
			var filter = chip.getAttribute( 'data-rc-filter' );

			chips.forEach( function ( c ) {
				c.setAttribute( 'aria-pressed', 'false' );
				c.classList.remove( 'is-active' );
			} );
			chip.setAttribute( 'aria-pressed', 'true' );
			chip.classList.add( 'is-active' );

			var rows = document.querySelectorAll( '.rc-row' );
			rows.forEach( function ( row ) {
				if ( 'unread' === filter ) {
					var timeEl = row.querySelector( 'time[data-rc-gmt]' );
					var gmt    = timeEl ? parseInt( timeEl.getAttribute( 'data-rc-gmt' ), 10 ) : 0;
					// Hide rows that were posted at or before last seen time.
					if ( lastSeen > 0 && gmt <= lastSeen ) {
						row.hidden = true;
					} else {
						row.hidden = false;
					}
				} else {
					row.hidden = false;
				}
			} );
		} );
	} );

	// Record visit timestamp so next visit can identify new comments.
	localStorage.setItem( STORAGE_KEY, String( Math.floor( Date.now() / 1000 ) ) );
}() );
