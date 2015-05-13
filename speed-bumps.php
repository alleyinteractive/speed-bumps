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

class Speed_Bumps {
	private static $instance;

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {

			self::require_files();
			self::$instance = new Speed_Bumps;
			self::$instance->setup_filters();
			
		}
		return self::$instance;
	}

	private static function require_files() {
		require( dirname( __FILE__ ) . '/vendor/autoload.php' );
		require_once( dirname( __FILE__ ) . '/inc/class-speed-bumps-text-constraints.php' );
		require_once( dirname( __FILE__ ) . '/inc/class-speed-bumps-element-constraints.php' );
	}

	private static function setup_filters() {
		add_filter( 'the_content', 'Speed_Bumps::check_and_inject_ad', 20, 2 );
	}

	public static function check_and_inject_ad( $the_content, $post_id = null ) {
		add_filter( 'speed_bumps_global_constraints', 'Speed_Bumps_Text_Constraints::minimum_content_length', 10, 2 );
		
		if ( apply_filters( 'speed_bumps_global_constraints', true, $the_content ) ) {
			$output = '';
			$parts = explode( PHP_EOL, $the_content );
			foreach( $parts as $index => $part ) {
				if( $index === 0 ) {
					$output .= $part . apply_filters( 'speed_bumps_insert_ad', '' );
				} else {
					$output .= $part;
				}
			}

			return $output;
		}
		 

		return $the_content;

	}
	
	public static function elements_constraints( $canInsert, $the_content ) { 
		/*
		$ofAllIFrames = qp( $this->content, 'iframe' );

		$iframes = array();
		$startTag = 0;
		foreach( $ofAllIFrames as $iframe) {
			$startCurrentTag = strpos( $this->content, '<iframe', $startTag );
			$endCurrentTag = strpos( $this->content, '</iframe>', $startCurrentTag );
			$iframes[] = array(
				'start'	=>	$startCurrentTag,
				'end'	=>	$endCurrentTag
			);
			$startTag = $startCurrentTag + 1;
			
		}
		 */
		/*
		return array(
			'hasIFrame'	=>	count( $iframes ) > 0,
			'elements'	=>	$iframes
		);
		 */

		//return true;
		 
	}


}

add_action( 'init', 'Speed_Bumps::get_instance' );

