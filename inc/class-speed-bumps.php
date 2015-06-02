<?php

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
		require_once( dirname( __FILE__ ) . '/inc/class-speed-bumps-text-constraints.php' );
		require_once( dirname( __FILE__ ) . '/inc/class-speed-bumps-element-constraints.php' );
	}

	private static function setup_filters() {
		add_filter( 'speed_bumps_inject_content', 'Speed_Bumps::check_and_inject_ad', 10, 1 );
	}

	public function register_speed_bump( $id, $args) {

		$defaut = array(
			
		);

		wp_parse_args( $args, $default );

		
	}

	public static function check_and_inject_ad( $the_content ) {
		add_filter( 'speed_bumps_global_constraints', 'Speed_Bumps_Text_Constraints::minimum_content_length', 10, 2 );
		add_filter( 'speed_bumps_paragraph_constraints', 'Speed_Bumps_Element_Constraints::contains_inline_element', 10, 1 );
		
		if ( apply_filters( 'speed_bumps_global_constraints', true, $the_content ) ) {
			$output = array();
			$alreadyInsertAd = false;
			$parts = explode( PHP_EOL, $the_content );

			if( count( $parts ) <= 1 ) {
				return  array_shift( $parts ) . apply_filters( 'speed_bumps_insert_ad', '' );
			}

			$startFrom50Percent =  floor( count( $parts ) / 2 );
			$firstHalf = array_slice( $parts, 0, $startFrom50Percent );
			$secondHalf = array_slice( $parts, $startFrom50Percent );	
			
			foreach( $secondHalf as $index => $part ) {
				if( ! apply_filters( 'speed_bumps_paragraph_constraints', $part ) ) {
					if( ! $alreadyInsertAd ) {
						$output[] = $part . apply_filters( 'speed_bumps_insert_ad', '' ) . PHP_EOL;
						$alreadyInsertAd = true;
					} else {
						$output[] = $part;
					}
				} else {
					$output[] = $part;
				}
			}

			$output = array_merge( $firstHalf, $output );
			return implode( PHP_EOL, $output );
		}
		 

		return $the_content;

	}
	
}


