jQuery( function ( $ ) {

	if ( $( '#_billing_vat_id' ).length > 0 ) {

		// Hook into ajax request to transmit vat id for tax calculation
		$.ajaxSetup({
		    beforeSend: function( request, settings ) {
		        if ( typeof settings.data !== "undefined" && typeof( settings.data ) === "string" && settings.data.indexOf( "woocommerce_calc_line_taxes" ) >= 0 ) {
		        	console.log("jaaa");
		        	settings.data += '&vat_id=' + $( '#_billing_vat_id' ).val();
		        }
		    }
		});

	}

});