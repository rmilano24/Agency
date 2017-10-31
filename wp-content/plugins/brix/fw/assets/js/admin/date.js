( function( $ ) {
	"use strict";

	/**
	 * Adding the date component to the UI building queue.
	 */
	$.brixf.ui.add( ".brix-date-input", function() {
		$( this ).each( function() {
			$( this ).datepicker( {
				dateFormat     : $( this ).attr( "data-format" ),
				dayNamesShort  : brix_date_field.dayNamesShort,
				dayNames       : brix_date_field.dayNames,
				monthNamesShort: brix_date_field.monthNamesShort,
				monthNames     : brix_date_field.monthNames,
				prevText       : brix_date_field.prevText,
				nextText       : brix_date_field.nextText,
				showAnim: ""
			} );
		} );
	} );

} )( jQuery );