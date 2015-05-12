<?php
/*
Plugin Name: Speed-bumps
Version: 0.1-alpha
Description: PLUGIN DESCRIPTION HERE
Author: YOUR NAME HERE
Author URI: YOUR SITE HERE
Plugin URI: PLUGIN SITE HERE
Text Domain: speed-bumps
Domain Path: /languages
*/

class Speed_Bumps {
	private static $instance;

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {

			self::$instance = new Speed_Bumps;
			
		}

		return self::$instance;
	}
}
