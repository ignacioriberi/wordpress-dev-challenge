<?php
/**
 * Create 30 seconds filter for wp cron recurrence
 */
add_filter('cron_schedules', 'add_cron_interval');
function add_cron_interval($schedules) {
	$schedules['30_seconds'] = array(
		'interval' => 30,
		'display'  => esc_html__('Every 30 Seconds'),);
	return $schedules;
}

/**
 * Set WP cron schedule event
 */
if ( ! wp_next_scheduled( 'bl_cron_hook' ) ) {
	wp_schedule_event( time(), '30_seconds', 'bl_cron_hook' );
}