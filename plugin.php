<?php
/**
 * Plugin Name: The Events Calendar Extension: Set Calendar to Show Specific Month
 * Description: Override The Events Calendar's default behavior of loading the "current month", and load a specified month instead.
 * Version: 1.0.0
 * Author: Modern Tribe, Inc.
 * Author URI: http://m.tri.be/1971
 * License: GPLv2 or later
 */

defined( 'WPINC' ) or die;

class Tribe__Extension__Set_Calendar_to_Show_Specific_Month {

    /**
     * The semantic version number of this extension; should always match the plugin header.
     */
    const VERSION = '1.0.0';

    /**
     * Each plugin required by this extension
     *
     * @var array Plugins are listed in 'main class' => 'minimum version #' format
     */
    public $plugins_required = array(
        'Tribe__Events__Main' => '4.2',
    );

    /**
     * The constructor; delays initializing the extension until all other plugins are loaded.
     */
    public function __construct() {
        add_action( 'plugins_loaded', array( $this, 'init' ), 100 );
    }

    /**
     * Extension hooks and initialization; exits if the extension is not authorized by Tribe Common to run.
     */
    public function init() {

        // Exit early if our framework is saying this extension should not run.
        if ( ! function_exists( 'tribe_register_plugin' ) || ! tribe_register_plugin( __FILE__, __CLASS__, self::VERSION, $this->plugins_required ) ) {
            return;
        }

        add_action( 'tribe_events_pre_get_posts', array( $this, 'force_event_date' ) ); 
    }

    /**
     * Override The Events Calendar's default behavior of loading the "current month", and load a specified month instead.
     *
     * @param object $query
     * @return void
     */
    public function force_event_date( WP_Query $query ) {
    
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
}

new Tribe__Extension__Set_Calendar_to_Show_Specific_Month();
