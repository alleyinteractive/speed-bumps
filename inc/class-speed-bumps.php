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
		add_filter( 'speed_bumps_inject_content', 'Speed_Bumps::insert_speed_bumps', 10, 1 );
	}

	public function register_speed_bump( $args = array() ) {
		$default = array(
			'injecting_content' => '',
			'minimum_content_length' => 1200,
			'element_constraints' => array( 
				'iframe',
				'embed',
				'image',
			)
		);
		
		$args = wp_parse_args( $args, $default );

		if( isset( $args['minimum_content_length'] ) ) {
			$minimum_content = $args['minimum_content_length'];
			add_filter( 'speed_bumps_global_constraints', 'Speed_Bumps_Text_Constraints::minimum_content_length', 10, 2 );
			add_filter( 'speed_bumps_minimum_content_length', function() use( $minimum_content ){ return $minimum_content; } );
		}

		if( isset( $args['element_constraints'] ) ) {
			foreach( $args['element_constraints'] as $constraint ) {
				add_filter( 'speed_bumps_paragraph_constraints', 'Speed_Bumps_Element_Constraints::contains_' . $constraint, 10, 1 );
			}
		}

		if( isset( $args['injecting_content'] ) ) {
			$injecting_content = $args['injecting_content'];
			add_filter( 'speed_bumps_insert_ad', function() use ($injecting_content ) {
				return $injecting_content . PHP_EOL . PHP_EOL;
			}, 10, 0 );
		}

	}

	public static function insert_speed_bumps( $the_content ) {
		if( apply_filters( 'speed_bumps_global_constraints', true, $the_content ) ) {
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


