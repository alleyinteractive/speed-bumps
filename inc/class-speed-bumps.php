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
		require_once( dirname( __FILE__ ) . '/class-speed-bumps-text-constraints.php' );
		require_once( dirname( __FILE__ ) . '/class-speed-bumps-element-constraints.php' );
	}

	private static function setup_filters() {
		add_filter( 'speed_bumps_inject_content', 'Speed_Bumps::insert_speed_bumps', 10, 2 );
	}

	public function register_speed_bump( $id, $args = array() ) {
		global $_speed_bumps_args;
		
		$default = array(
			'string_to_inject' => '',
			'minimum_content_length' => 1200,
			'element_constraints' => array( 
				'iframe',
				'embed',
				'image',
			)
		);
		
		$args = wp_parse_args( $args, $default );
		$_speed_bumps_args = array();
		$_speed_bumps_args[ $id ] = $args;
		
		add_filter( 'speed_bumps_global_constraints', 'Speed_Bumps_Text_Constraints::minimum_content_length', 10, 3 );
		add_filter( 'speed_bumps_paragraph_constraints', 'Speed_Bumps_Element_Constraints::prev_paragraph_contains_element', 10, 2 );

		add_filter( 'speed_bumps_insert_ad', 'Speed_Bumps::string_to_inject', 10, 1 );
	}

	public static function string_to_inject( $speed_bump_id ) {
		global $_speed_bumps_args;
		$string_to_inject = $_speed_bumps_args[ $speed_bump_id ][ 'string_to_inject' ];
	
		return $string_to_inject . PHP_EOL . PHP_EOL;
	}

	public static function insert_speed_bumps( $speed_bump_id, $the_content ) {
		if( apply_filters( 'speed_bumps_global_constraints', true, $speed_bump_id, $the_content ) ) {
			$output = array();
			$alreadyInsertAd = false;
			$parts = explode( PHP_EOL, $the_content );

			if( count( $parts ) <= 1 ) {
				return  array_shift( $parts ) . apply_filters( 'speed_bumps_insert_ad', $speed_bump_id );
			}

			$startFrom50Percent =  floor( count( $parts ) / 2 );
			$firstHalf = array_slice( $parts, 0, $startFrom50Percent );
			$secondHalf = array_slice( $parts, $startFrom50Percent );	

			foreach( $secondHalf as $index => $part ) {
				if( ! apply_filters( 'speed_bumps_paragraph_constraints', $speed_bump_id, $part ) ) {
					if( ! $alreadyInsertAd ) {
						$output[] = $part . apply_filters( 'speed_bumps_insert_ad', $speed_bump_id ) . PHP_EOL;
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


