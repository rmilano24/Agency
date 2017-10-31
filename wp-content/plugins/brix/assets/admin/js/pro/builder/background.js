( function( $ ) {
	"use strict";

	/**
	 * Refresh the gradient preview.
	 */
	function brix_refresh_gradient( container ) {
		var stops = [],
			direction = $( ".brix-radio-style-color-attributes input:checked", container ).val(),
			reverse = $( ".brix-background-color-gradient-reverse input[type='checkbox']", container )[0].checked;

		$( ".brix-background-color-gradient-table-row", container ).each( function() {
			stops.push( {
				"color": $( ".brix-color-input", this ).val(),
				"position": $( "[type='number']", this ).val()
			} );
		} );

		var c = $( ".brix-background-color-gradient-preview", container )[0],
			size = $( c ).attr( "width" ),
			ctx = c.getContext( "2d" ),
			grd = null;

		ctx.clearRect( 0, 0, c.width, c.height );

		if ( direction === "radial" ) {
			grd = ctx.createRadialGradient( size/2, size/2, 0, size/2, size/2, size/2 );
		}
		else {
			var f1 = 0,
				f2 = 0,
				t1 = 0,
				t2 = 0;

			switch ( direction ) {
				case "diagonal_up":
					f2 = size;
					t1 = size;
					break;
				case "diagonal_down":
					t1 = size;
					t2 = size;
					break;
				case "vertical":
					t2 = size;
					break;
				case "horizontal":
				default:
					t1 = size;
					break;
			}

			grd = ctx.createLinearGradient( f1, f2, t1, t2 );
		}

		var positions = [];

		$.each( stops, function() {
			positions.push( this.position );
		} );

		if ( reverse ) {
			stops.reverse();
		}

		$.each( stops, function( i ) {
			var color = this.color.replace( / /g, "" ) + "";

			try {
				grd.addColorStop( parseInt( positions[ i ], 10 ) / 100, color );
			}
			catch( e ) {}
		} );

		ctx.fillStyle = grd;
		ctx.fillRect( 0, 0, size, size );
	};

	$.brixf.delegate( ".brix-radio-style-color-attributes", "change", "background", function() {
		var container = $( this ).parents( ".brix-background-color-gradient" ).first();

		brix_refresh_gradient( container );
	} );

	$.brixf.delegate( ".brix-background-color-gradient-table-row input[type='number']", "input keyup", "background", function() {
		var container = $( this ).parents( ".brix-background-color-gradient" ).first();

		brix_refresh_gradient( container );
	} );

	$.brixf.delegate( ".brix-background-color-gradient-table-row .brix-color-input", "change", "background", function() {
		var container = $( this ).parents( ".brix-background-color-gradient" ).first();

		brix_refresh_gradient( container );
	} );

	$.brixf.delegate( ".brix-background-color-gradient-reverse input[type='checkbox']", "change", "background", function() {
		var container = $( this ).parents( ".brix-background-color-gradient" ).first();

		brix_refresh_gradient( container );
	} );

	/**
	 * Boot the gradient UI.
	 */
	$.brixf.ui.add( '.brix-background-breakpoint', function() {
		$( ".brix-background-color-gradient", this ).each( function() {
			brix_refresh_gradient( this );
		} );
	} );

	$.brixf.delegate( ".brix-background-breakpoints-select-wrapper select", "change", "background", function() {
		var breakpoint = $( this ).val(),
			field = $( this ).parents( ".brix-field" ).first();

		$( ".brix-background-breakpoint", field ).removeClass( "active" );
		$( ".brix-background-breakpoint[data-breakpoint='" + breakpoint + "']", field ).addClass( "active" );
	} );

	$.brixf.delegate( ".brix-background-inherit-wrapper input[type='checkbox']", "change", "background", function() {
		var wrapper = $( this ).parents( ".brix-background-breakpoint" );

		wrapper.removeClass( "brix-background-inherit" );

		if ( this.checked ) {
			wrapper.addClass( "brix-background-inherit" );
		}
	} );

	$.brixf.delegate( ".brix-background-reponsive-mode-checkbox-wrapper input[name]", "change", "background", function() {
		var field = $( this ).parents( ".brix-field" ).first();

		$( ".brix-background-reponsive-mode-breakpoints-wrapper", field ).removeClass( "active" );

		if ( this.checked ) {
			$( ".brix-background-reponsive-mode-breakpoints-wrapper", field ).addClass( "active" );
		}
		else {
			$( ".brix-background-breakpoints-select-wrapper select" )
				.val( "desktop" )
				.trigger( "change" );
		}
	} );

} )( jQuery );