<?php
// anthony2727/speed-bumps
// forked from fusioneng/speed-bumps
// AnthonyRodriguez.itt@gmail.com
// www.github.com/anthony2727
//  
//  

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


//NO necesary to manually include the SpeedBumps class anymore. 
//Dependencies are handled by the root level loader.php file
// 
//require_once dirname( __FILE__ ) . '/inc/class-speed-bumps.php';
//
//
//Only the 'loader.php' file will be included within the whole project.  
require_once dirname (__FILE__) . '/loader.php';
// @codingStandardsIgnoreStart
function Speed_Bumps() {
	//Calling the SpeedBumps class from the respective namespace
	return \SpeedBumps\Inc\SpeedBumps::get_instance();
}
// @codingStandardsIgnoreEnd
add_action( 'init', 'Speed_Bumps' );

