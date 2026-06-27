( function () {
	'use strict';

	/* ── Menu category filter tabs ────────────────────────────────────────── */
	var filters    = document.querySelectorAll( '.menu-filter' );
	var categories = document.querySelectorAll( '.menu-category' );

	if ( filters.length && categories.length ) {
		function activateFilter( btn ) {
			var target = btn.getAttribute( 'data-filter' );
			filters.forEach( function ( b ) {
				b.classList.remove( 'is-active' );
				b.setAttribute( 'aria-selected', 'false' );
				b.setAttribute( 'tabindex', '-1' );
			} );
			btn.classList.add( 'is-active' );
			btn.setAttribute( 'aria-selected', 'true' );
			btn.setAttribute( 'tabindex', '0' );
			categories.forEach( function ( group ) {
				var cat = group.getAttribute( 'data-category' );
				group.style.display = ( 'all' === target || cat === target ) ? '' : 'none';
			} );
		}

		// Initialise roving tabindex: first tab focusable, others removed from sequence.
		filters.forEach( function ( btn, i ) {
			btn.setAttribute( 'tabindex', i === 0 ? '0' : '-1' );
		} );

		filters.forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				activateFilter( btn );
				btn.focus();
			} );

			// WCAG 2.1 §4.2.7 — arrow-key navigation within the tab list.
			btn.addEventListener( 'keydown', function ( e ) {
				var all = Array.prototype.slice.call( filters );
				var idx = all.indexOf( btn );
				var next;
				if ( e.key === 'ArrowRight' || e.key === 'ArrowDown' ) {
					e.preventDefault();
					next = all[ ( idx + 1 ) % all.length ];
					activateFilter( next );
					next.focus();
				} else if ( e.key === 'ArrowLeft' || e.key === 'ArrowUp' ) {
					e.preventDefault();
					next = all[ ( idx - 1 + all.length ) % all.length ];
					activateFilter( next );
					next.focus();
				} else if ( e.key === 'Home' ) {
					e.preventDefault();
					activateFilter( all[ 0 ] );
					all[ 0 ].focus();
				} else if ( e.key === 'End' ) {
					e.preventDefault();
					activateFilter( all[ all.length - 1 ] );
					all[ all.length - 1 ].focus();
				}
			} );
		} );
	}

	/* ── Volt-line scroll animation ───────────────────────────────────────── */
	var lines = document.querySelectorAll( '.volt-line' );
	if ( lines.length && 'IntersectionObserver' in window ) {
		var observer = new IntersectionObserver( function ( entries ) {
			entries.forEach( function ( entry ) {
				if ( entry.isIntersecting ) {
					entry.target.classList.add( 'is-charged' );
					observer.unobserve( entry.target );
				}
			} );
		}, { threshold: 0.5 } );
		lines.forEach( function ( line ) { observer.observe( line ); } );
	}
}() );
