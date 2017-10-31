( function( $ ) {
    "use strict";

    function componentToHex(c) {
        var hex = c.toString(16);
        return hex.length == 1 ? "0" + hex : hex;
    }

    function rgbToHex(r, g, b) {
        return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
    }

    function _set_palette() {
        if ( ! $( "[data-dominant-palette]" ).length ) {
            return;
        }

        $( "[data-dominant-palette]" ).html( "" );

        var featured_image = $( "#postimagediv img" ),
            input = $( "[data-dominant]" ).first();

        if ( featured_image.length ) {
            var image = new Image();

            image.onload = function() {
                var colorThief = new ColorThief(),
                    palette = colorThief.getPalette( this, 5 );

                $.each( palette, function( i ) {
                    var hex = rgbToHex( this[0], this[1], this[2] );

                    $( "[data-dominant-palette]" ).append( "<li><span style='background:" + hex + "'></span> " + hex + "</li>" );

                    if ( i === 0 && input.val() == "" ) {
                        input.val( hex.trim() );
                        input.trigger( "input" );
                    }
                } );
            };

            image.src = featured_image.attr( "src" );
        }
        else {
            $( "[data-dominant]" ).val( "" );
        }

        if ( input.length ) {
            input.trigger( "input" );
        }
    };

    /**
     * Hooks into a given method
     *
     * @param method
     * @param fn
     */
    $.fn.hook = function (method, fn) {
        var def = $.fn[method];

        if (def) {
            $.fn[method] = function() {
                var r = def.apply(this, arguments); //original method
                fn(this, method, arguments); //injected method
                return r;
            }
        }
    };

    $( document ).on( "click", ".agncy-refresh-dominant", function() {
        _set_palette();

        return false;
    } );

    $( document ).on( "click", "[data-dominant-palette] li", function() {
        var input = $( "[data-dominant]" ).first();

        input.val( $( this ).text() );
        input.trigger( "input" );

        return false;
    } );

    $( document ).on( "input", "[data-dominant]", function() {
        $( "[data-dominant-preview]" ).css( "background-color", $( this ).val() );

        return false;
    } );

    /**
	* Attempt to detect the change of featured image.
	*/
	$( ".inside", "#postimagediv" ).hook( 'html', function( el, method, args ) {
		if ( $( el ).is( "#postimagediv .inside" ) ) {
			_set_palette();
		}
	} );

    $( document ).on( "ready", function() {
        _set_palette();
    } );

} )( jQuery );