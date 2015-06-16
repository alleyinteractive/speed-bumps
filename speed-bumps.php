<?php
use Speed_Bumps\Speed_Bumps;
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

// @codingStandardsIgnoreStart
function Speed_Bumps() {
	require_once( 'inc/class-speed-bumps.php' );
	return Speed_Bumps::get_instance();
}
// @codingStandardsIgnoreEnd

add_action( 'init', 'Speed_Bumps' );


/**
 * Register an autoloader function for any class in the plugin's namespace.
 *
 * If a class referenced is within the 'Speed_Bumps' namespace, it will be
 * autoloaded from a file name matching the class namespace and name, as
 * adjusted to follow WordPress naming conventions:
 * https://make.wordpress.org/core/handbook/coding-standards/php/#naming-conventions
 *
 * Specifically, the following transforms will be applied:
 *
 * - The top-level "Speed_Bumps" prefix will be removed,
 * - each portion of the namespace hierarchy will be downcased & transformed into harpoon-case,
 * - and used as a path segment of a directory within the `/inc/` directory,
 * - and the file containing the class itself will be named `class-{classname}.php`
 */
spl_autoload_register(
	function ($class) {

		// project-specific namespace
		$prefix = 'Speed_Bumps';

		$parts = explode( '\\', $class );

		if ( $parts[0] !== $prefix ) {
			return;
		}

		array_shift( $parts );

		$last = array_pop( $parts ); // File should be 'class-[...].php'
		$last = 'class-' . $last . '.php';

		$parts[] = $last;
		$file = dirname( __FILE__ ) . '/inc/' . str_replace( '_', '-', strtolower( implode( $parts, '/' ) ) );

		//If the file exists....
		if ( file_exists( $file ) ) {
			//Require the file
			require($file);
		}
	}
);

