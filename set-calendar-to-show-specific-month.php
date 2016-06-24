<?php
/**
 * Plugin Name: The Events Calendar — Set Calendar to Show Specific Month
 * Description: Override The Events Calendar's default behavior of loading the "current month", and load a specified month instead.
 * Version: 1.0.0
 * Author: Modern Tribe, Inc.
 * Author URI: http://m.tri.be/1x
 * License: GPLv2 or later
 */
 
defined( 'WPINC' ) or die;

function tribe_force_event_date( WP_Query $query ) {
	
	// Don't touch single posts or queries other than the main query.
	if ( ! $query->is_main_query() || is_single() ) {
	    return;
	}

	// If a date has already been set by some other means, bail out.
	if ( strlen( $query->get( 'eventDate' ) ) || ! empty( $_REQUEST['tribe-bar-date'] ) ) {
	    return;
	}

	// NOTE: Change this to whatever date you prefer.
	$default_date = '2016-10-01';
	
	// Use the preferred default date.
	$query->set( 'eventDate', $default_date );
	$query->set( 'start_date', $default_date );

	$_REQUEST['tribe-bar-date'] = $default_date;
}

add_action( 'tribe_events_pre_get_posts', 'tribe_force_event_date' ); 
