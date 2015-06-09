<?php
/*
Plugin Name: Speed-bumps
Version: 0.1-alpha
Description: A Plugin to insert a piece of content intelligently.
Author: Fusion Engineering
Author URI: http://fusion.net
Plugin URI: https://github.com/fusioneng/speed-bumps
Text Domain: speed-bumps
Domain Path: /languages
*/

//Only the 'loader.php' file will be included within the whole project.  
require_once dirname (__FILE__) . '/loader.php';

// @codingStandardsIgnoreStart
function Speed_Bumps() {
	return \SpeedBumps\Inc\Speed_Bumps::get_instance();
}
// @codingStandardsIgnoreEnd
add_action( 'init', 'Speed_Bumps' );

