<?php
namespace SpeedBumps\Inc;

class SpeedBumps {
	private static $instance;
	private static $_speed_bumps_args = array();

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {

			//self::require_files();
			self::$instance = new SpeedBumps;
			self::$instance->setup_filters();
			
		}
		return self::$instance;
	}

	//Not required anymore. The root level loader.php file is taking care of any 
	//possible dependecy within the plugin namespace {SpeedBumps}
	//
	// Please, make sure to include the {SpeedBumps} namespace when creating an instance of an object or calling an static method,
	// from a class or file with namesapce different from {SpeedBumps} or without any namespace declaration at all. 
	// 
	// e.g:
	// 
	// class test{ 
	//                 |namespace| |className|
	//      $foo = new \SpeedBumps\SpeedBumpsElementConstrain;
	// }
	// 
	// It isn't necesaty if the class or file from which the call is happening has the same namespace of the instantiated object.
	// e.g:
	// namespace SpeedBumps;
	// class test{ 
	// 						      |just the class name is necesary bacause both clases share the same namespace|
	//      e.g: just: $foo = new SpeedBumpsElementConstrain;
	// }

	// private static function require_files() {
	// 	require_once( dirname( __FILE__ ) . '/class-speed-bumps-element-constraint.php' );
	// 	require_once( dirname( __FILE__ ) . '/element-constraints/class-speed-bumps-blockquote-constraint.php' );
	// 	require_once( dirname( __FILE__ ) . '/element-constraints/class-speed-bumps-image-constraint.php' );
	// 	require_once( dirname( __FILE__ ) . '/element-constraints/class-speed-bumps-iframe-constraint.php' );
	// 	require_once( dirname( __FILE__ ) . '/element-constraints/class-speed-bumps-shortcode-constraint.php' );
	// 	require_once( dirname( __FILE__ ) . '/element-constraints/class-speed-bumps-oembed-constraint.php' );
	// 	require_once( dirname( __FILE__ ) . '/element-constraints/class-speed-bumps-element-factory.php' );
	// 	require_once( dirname( __FILE__ ) . '/class-speed-bumps-text-constraints.php' );
	// 	require_once( dirname( __FILE__ ) . '/class-speed-bumps-element-constraints.php' );
	// }

	private static function setup_filters() {
		add_filter( 'speed_bumps_inject_content', 'Speed_Bumps::insert_speed_bumps', 10, 2 );
	}

	public static function insert_speed_bumps( $speed_bump_id, $the_content ) {
		$content_to_be_inserted = call_user_func( self::$_speed_bumps_args[ $speed_bump_id ][ 'string_to_inject' ] );
		$paragraph_offset = self::$_speed_bumps_args[ $speed_bump_id ][ 'paragraph_offset' ]; 
		if( apply_filters( 'speed_bumps_global_constraints', true, $speed_bump_id, $the_content ) ) {
			$output = array();
			$alreadyInsertAd = false;
			$parts = explode( PHP_EOL, $the_content );

			if( count( $parts ) <= 1 ) {
				return  array_shift( $parts ) . $content_to_be_inserted;
			}

			$first_half = array_slice( $parts, 0, $paragraph_offset );
			$second_half = array_slice( $parts, $paragraph_offset );	

			foreach( $second_half as $index => $part ) {
				if( ! apply_filters( 'speed_bumps_paragraph_constraints', $speed_bump_id, $part ) && $part !== '' ) {
					if( ! $alreadyInsertAd ) {
						$output[] = $part . $content_to_be_inserted . PHP_EOL;
						$alreadyInsertAd = true;
					} else {
						$output[] = $part;
					}
				} else {
					$output[] = $part;
				}
			}

			$output = array_merge( $first_half, $output );
			return implode( PHP_EOL, $output );
		}
		
		return $the_content;
	}

	public function register_speed_bump( $id, $args = array() ) {
		$default = array(
			'string_to_inject' => function() { return ''; },
			'minimum_content_length' => 1200,
			'paragraph_offset' => 0,
			'element_constraints' => array( 
				'iframe',
				'oembed',
				'image',
			)
		);
		
		$args = wp_parse_args( $args, $default );
		SpeedBumps::$_speed_bumps_args[ $id ] = $args;
		
		add_filter( 'speed_bumps_global_constraints', 'Speed_Bumps_Text_Constraints::minimum_content_length', 10, 3 );
		add_filter( 'speed_bumps_paragraph_constraints', 'Speed_Bumps_Element_Constraints::prev_paragraph_contains_element', 10, 2 );
	}

	public function get_speed_bump_args( $id ) {
		return SpeedBumps::$_speed_bumps_args[ $id ];
	}

}


