jQuery(document).ready( function() {

	jQuery( ".gamwp-link" ).click( function( event ) {

		event.preventDefault();

		nonce = jQuery( this ).attr( "data-nonce" );
		user_id = jQuery( this ).attr( "data-user-id" );
		action_title = jQuery( this ).attr( "data-action-title" );
		points = jQuery( this ).attr( "data-points" );
		daily_limit = jQuery( this ).attr( "data-limit" );

		jQuery.ajax({
			type : "post",
			context: this,
			dataType : "json",
			url : gamwp_link_ajax.ajaxurl,
			data : {action: "gamwp_processor", nonce: nonce, user_id: user_id, action_title: action_title, points: points, daily_limit: daily_limit },

			success: function( response, textStatus, jqXHR) {

			},

			error: function( jqXHR, textStatus, errorThrown) {

			}

		}); // ajax

	}); // click function

}); // document.ready
