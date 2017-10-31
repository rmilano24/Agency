( function( $ ) {
	"use strict";

	function agncy_size_footer() {
		var footer = $( '.agncy-f' ),
			content = $( '.agncy-l' );

		if ( $( ".agncy-fixed-footer" ).length ) {
			setTimeout( function() {
				content.css( 'margin-bottom', footer.outerHeight() );
			}, 1 );
		}

	};

	document.documentElement.addEventListener( "agncy-p-loaded", agncy_size_footer );
	$( window ).on( "resize", agncy_size_footer );

} )( jQuery );