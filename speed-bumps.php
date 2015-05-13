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
			self::$instance->setup_filters();
			
		}
		return self::$instance;
	}

	private static function setup_filters() {
		add_filter( 'the_content', 'Speed_Bumps::check_and_inject_ad', 20, 2 );
	}

	public static function check_and_inject_ad( $the_content, $post_id = null ) {
		add_filter( 'can_insert_here', 'Speed_Bumps::text_constraints', 10, 2 );
		
		if ( apply_filters( 'can_insert_here', true, $the_content ) ) {
			return $the_content . apply_filters( 'speed_bumps_insert_ad', '' );
		}
		
		return $the_content;

	}

	public static function text_constraints( $canInsert, $the_content ) {
		if ( strlen( $the_content ) < 1200 ) {
			$canInsert = false;
		}
    		return $canInsert;	
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

