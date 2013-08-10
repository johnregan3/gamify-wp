<?php

/**
 * Generate User Stats Page shortcode
 *
 * @since 1.0
 */

function gamify_stats_shortcode() {

	$shortcode = New gamify_Shortcode;

	// Gather Vars
	$time = current_time( 'timestamp', 1 );
	$current_user = wp_get_current_user();
	$username = $current_user->user_login;
	$user_id =  $current_user->ID;

	$todays_points = $shortcode->calc_daily_points( $user_id, $time );

	//Set up user log array
	$user_log_array = get_user_meta( $user_id, 'gamify_user_log', true );
	if ( $user_log_array ) {
		$reverse_user_log_array = array_reverse( $user_log_array, true );

		// Get array of all rewards
		foreach ( $reverse_user_log_array as $timestamp => $value ) {
			if ( $value['activity_type'] == 'reward' ) {
				$user_reward_array[$timestamp] = $value;
			}
		}

		//Trim User Activites displayed to 10 most recent
		$user_activity_array = array_slice( $reverse_user_log_array, 0, 10, true );
	}

	if ( is_user_logged_in() ) : ?>

		<div id="gamify-wp-user=stats-wrap">
			<h3 class="gamify-title"><?php echo sprintf( __( 'Points Totals for %s ', 'gamify' ), esc_html( $username ) ); ?></h3>
			<p class="gamify-24hrs"><?php _e( 'Points Earned in Last 24 Hours: ', 'gamify' ); echo esc_html( $todays_points ); ?></p>

			<?php if ( $user_log_array ) : ?>
				<p class="gamify-rewards-list"><?php _e( 'Recent Levels Achieved', 'gamify' ) ?></p>
				<?php $shortcode->produce_list( $user_reward_array ); ?>
				<p class="gamify-activity-list"><?php _e( 'Recent Points', 'gamify' ) ?></p>
				<?php $shortcode->produce_list( $user_activity_array ); ?>
			<?php endif; ?>
		</div>

	<?php else : ?>

		<p><em><?php _e( 'You must be logged in to view your stats.', 'gamify' ); ?></em></p>
		<?php wp_register('', ''); ?>&nbsp;|&nbsp;<a href="<?php esc_url( wp_login_url() ); ?>" title="<?php __( 'Login', 'gamify' ); ?>"><?php __( 'Login', 'gamify' ); ?></a></p>

	<?php endif;
}

add_shortcode( 'gamify-stats', 'gamify_stats_shortcode' );
