( function () {
	'use strict';
	var filters = document.querySelectorAll( '.menu-filter' );
	var categories = document.querySelectorAll( '.menu-category' );
	if ( filters.length && categories.length ) {
		filters.forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				var target = btn.getAttribute( 'data-filter' );
				filters.forEach( function ( b ) {
					b.classList.remove( 'is-active' );
					b.setAttribute( 'aria-selected', 'false' );
				} );
				btn.classList.add( 'is-active' );
				btn.setAttribute( 'aria-selected', 'true' );
				categories.forEach( function ( group ) {
					var cat = group.getAttribute( 'data-category' );
					group.style.display = ( 'all' === target || cat === target ) ? '' : 'none';
				} );
			} );
		} );
	}
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
