jQuery(document).ready( function() {

	// Main AJAX
	jQuery( ".gamwp-button" ).click( function() {

		nonce = jQuery( this ).attr( "data-nonce" );
		user_id = jQuery( this ).attr( "data-user-id" );
		action_title = jQuery( this ).attr( "data-action-title" );
		points = jQuery( this ).attr( "data-points" );


		jQuery.ajax({

			type : "POST",
			dataType : "json",
			url : gamwp_custom.ajaxurl,
			data : { action: "gawmp_process", nonce: nonce, user_id : user_id, action_title: action_title, points: points, },

			beforeSend: function() {

				spinnerIn();

			},

			complete: function() {

				spinnerOut();

			},

			success: function( response ) {

				if ( response.events.type == "success" && response.total_score.type == "success" ) {

					message = "You just earned " + response.total_score.value + " points for " + response.events.event + "!"
					pointsNotice( message );

				} else {

					message = "Processing error.  Points not saved."
					pointsNotice( message );

				}

			} // end success

		}); // End Ajax

	}); // Button click

}); //end (document).ready
