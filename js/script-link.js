jQuery(document).ready( function() {

	// Generate Spinner
	// Dynamically generate spinner div
	var $spindiv = jQuery( '<div />' ).appendTo( 'body' );
	$spindiv.hide();
	$spindiv.attr( 'id', 'spinner' );

	var spinner = jQuery( '#spinner' );
	spinner.prepend( '<div id="spinner-image"></div>' );

	function spinnerIn() {

		spinner.fadeIn( 50 );

	}

	function spinnerOut() {

		spinner.fadeOut( 50 );

	} // End Spinner

	// Dynamically generate Notification div
	var $div = jQuery( '<div />').appendTo( 'body' );
	$div.hide();
	$div.attr( 'id', '#points-notice' );

	function pointsNotice( msg ){

		jQuery( "#points-notice" ).html( msg );
		jQuery( "#points-notice ").fadeIn( 500 ).delay( 5000 ).fadeOut( 500 );

	} // End Popup Code

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

			beforeSend: function() {

				spinnerIn();

			},

			complete: function() {

				spinnerOut();

			},

			success: function( response, textStatus, jqXHR) {

				//location.href = this.href;
				jQuery('#content').html( "Congratulations!  You just " + response.actions.action  + " for " + response.score.value + " points!" );

			},

			error: function( jqXHR, textStatus, errorThrown) {

				jQuery('#content').html( 'Failure: ' + errorThrown );

			}

		}); // ajax

	}); // click function

}); // document.ready
