( function( $ ) {
	"use strict";

	var map_style = [{"featureType":"water","elementType":"geometry","stylers":[{"color":"#e9e9e9"},{"lightness":17}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffffff"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#ffffff"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":16}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":21}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#dedede"},{"lightness":21}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#ffffff"},{"lightness":16}]},{"elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#333333"},{"lightness":40}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#f2f2f2"},{"lightness":19}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#fefefe"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#fefefe"},{"lightness":17},{"weight":1.2}]}];

	function agncy_init_office_maps() {
        if ( typeof google === "undefined" || typeof google.maps === "undefined" ) {
            return;
        }

		$( ".brix-section-column-block-agncy_office [data-latlong]" ).each( function() {
			var latlong = $( this ).attr( "data-latlong" ),
				zoom = $( this ).attr( "data-zoom" ),
				marker_icon = $( this ).attr( "data-marker" ),
				lat = 0,
				long = 0;


			[ lat, long ] = latlong.split( "," );

			var center = {
				lat: parseFloat( lat.replace(',','.') ),
				lng: parseFloat( long.replace(',','.') )
			};

			if ( ! $( this ).data( "map" ) ) {
				var map = new google.maps.Map( this, {
					zoom: parseInt( zoom, 10 ),
					center: center,
					styles: map_style,
					disableDefaultUI: true,
					scrollwheel: false,
					draggable: false // ! ( "ontouchend" in document ) // disable drag on mobile devices
				} );

				$( this ).data( "map", map );

				var marker = new google.maps.Marker( {
					position: center,
					map: map,
					icon: marker_icon
				} );
			}
			else {
				$( this ).data( "map" ).setCenter( center );
			}
		} );
	};

	window.agncy_office_init_map = function() {
		$( window ).on( "brix_ready", function() {
			agncy_init_office_maps();
		} );

		$( window ).on( "resize", function() {
			agncy_init_office_maps();
		} );
	};
} )( jQuery );