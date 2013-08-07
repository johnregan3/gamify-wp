<?php

/**
 * Generate User Stats Page shortcode
 *
 * @since 1.0
 */

function gamwp_stats_shortcode() {

	$shortcode = New GAMWP_Shortcode;

	// Gather Vars
	$time = current_time( 'timestamp', 1 );
	$current_user = wp_get_current_user();
	$username = $current_user->user_login;
	$user_id =  $current_user->ID;

	$todays_points = $shortcode->calc_daily_points( $user_id, $time );

	//Set up user log array
	$user_log_array = get_user_meta( $user_id, 'gamwp_user_log', true );
	$reverse_user_log_array = array_reverse( $user_log_array, true );
	$user_log_array = array_slice( $reverse_user_log_array, 0, 10, true );

	if ( is_user_logged_in() ) : ?>

		<h3><?php echo sprintf( __( 'Points Totals for %s ', 'gamwp' ), esc_html( $username ) ); ?></h3>

		<p><strong><?php echo sprintf( __( 'Points Earned in Last 24 Hours:  %s', 'gamwp' ), esc_html( $todays_points ) ); ?></strong></p>

		<?php if ( $user_log_array ) : ?>

			<p><strong><?php _e( 'Recent Activity', 'gamwp' ) ?></strong></p>

			<ul>
				<?php
				//Generate recent activity
				foreach ( $user_log_array as $timestamp => $value ) :
					$offset = human_time_diff( $timestamp, $time );
					?>
					<li>
						<?php echo esc_html( $value['activity_title'] ); ?>&nbsp;<?php _e( 'for', 'gamwp' ); ?>&nbsp;<?php echo esc_html( $value['activity_points'] ); ?>&nbsp;<?php _e( 'points', 'gamwp' ); ?>&nbsp;(<?php echo esc_html( $offset ); ?>&nbsp;<?php _e( 'ago', 'gamwp' ); ?>)
					</li>
				<?php endforeach; ?>
			</ul>

		<?php endif; ?>

	<?php else : ?>

		<p><em><?php _e( 'You must be logged in to view your stats.', 'gamwp' ); ?></em></p>

		<?php wp_register('', ''); ?>&nbsp;|&nbsp;<a href="<?php esc_url( wp_login_url() ); ?>" title="<?php __( 'Login', 'gamwp' ); ?>"><?php __( 'Login', 'gamwp' ); ?></a></p>

	<?php endif;
}

add_shortcode( 'gamwp-stats', 'gamwp_stats_shortcode' );
