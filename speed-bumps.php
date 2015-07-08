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
	private static $speed_bumps = array();

	private static $filter_id = 'speed_bumps_%s_constraints';

	/**
	 * Prevent the creation of a new instance of the "SINGLETON" using the
	 * 'new' operator from outside of this class.
	 **/
	protected function __construct(){}

	/**
	 * Prevent cloning the instance of the "SINGLETON" instance.
	 * @return void
	 **/
	private function __clone(){}

	/**
	 * Prevent the unserialization of the "SINGLETON" instance.
	 * @return void
	 **/
	private function __wakeup(){}

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new Speed_Bumps;
			self::$instance->register_autoloader();
			self::$instance->setup_filters();
		}
		return self::$instance;
	}

	function register_autoloader() {
		spl_autoload_register( array( $this, 'spl_autoload' ) );
	}

	/**
	 * Autoloader function for any class in the plugin's namespace.
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
	function spl_autoload($class) {

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

	private function setup_filters() {
		add_filter( 'speed_bumps_inject_content', array( $this, 'insert_speed_bumps' ) );
	}

	public function insert_speed_bumps( $the_content ) {
		$output = array();
		$already_inserted = array();
		$parts = preg_split( '/\n\s*\n/', $the_content );
		$total_paragraphs = count( $parts );
		foreach ( $parts as $index => $part ) {
			$output[] = $part;
			$context = array(
				'index'            => $index,
				'prev_paragraph'   => $part,
				'next_paragraph'   => ( $index + 1 < $total_paragraphs ) ? $parts[ $index + 1 ] : '',
				'total_paragraphs' => $total_paragraphs,
				'the_content'      => $the_content,
				'parts'            => $parts,
				);

			foreach ( $this->get_speed_bumps() as $id => $args ) {

				if ( $index < $args['paragraph_offset'] ) {
					break;
				}

				if ( apply_filters( 'speed_bumps_'. $id . '_constraints', true, $context, $args, $already_inserted ) ) {

					$content_to_be_inserted = call_user_func( $args['string_to_inject'], $context, $already_inserted );

					$output[] = $content_to_be_inserted;
					$already_inserted[] = array(
						'index' => $index,
						'speed_bump_id' => $id,
						'inserted_content' => $content_to_be_inserted,
					);
				}
			}
		}

		return implode( PHP_EOL . PHP_EOL, $output );
	}

	public function register_speed_bump( $id, $args = array() ) {
		$id = sanitize_key( $id );
		$default = array(
			'id' => $id,
			'string_to_inject' => '__return_empty_string',
			'minimum_content_length' => 1200,
			'paragraph_offset' => 0,
			'element_constraints' => array(
				'iframe',
				'oembed',
				'image',
				),
			'minimum_space_from_other_inserts' => 1,
			);
		$args = wp_parse_args( $args, $default );
		self::$speed_bumps[ $id ] = $args;

		$filter_id = sprintf( self::$filter_id, $id );

		add_filter( $filter_id, '\Speed_Bumps\Constraints\Text\Minimum_Text::content_is_long_enough_to_insert', 10, 4 );
		add_filter( $filter_id, '\Speed_Bumps\Constraints\Text\Minimum_Text::meets_minimum_distance_from_article_end_paragraphs', 10, 4 );
		add_filter( $filter_id, '\Speed_Bumps\Constraints\Text\Minimum_Text::meets_minimum_distance_from_article_end_words', 10, 4 );
		add_filter( $filter_id, '\Speed_Bumps\Constraints\Content\Injection::this_speed_bump_not_already_inserted', 10, 4 );
		add_filter( $filter_id, '\Speed_Bumps\Constraints\Content\Injection::no_speed_bump_inserted_here', 10, 4 );
		add_filter( $filter_id, '\Speed_Bumps\Constraints\Content\Injection::minimum_space_from_other_inserts_paragraphs', 10, 4 );
		add_filter( $filter_id, '\Speed_Bumps\Constraints\Content\Injection::minimum_space_from_other_inserts_words', 10, 4 );
		add_filter( $filter_id, '\Speed_Bumps\Constraints\Elements\Element_Constraints::adj_paragraph_not_contains_element', 10, 4 );
	}

	public function get_speed_bumps() {
		return self::$speed_bumps;
	}

	public function get_speed_bump( $id ) {
		return self::$speed_bumps[ $id ];
	}

	public function clear_speed_bump( $id ) {
		$filter_id = sprintf( self::$filter_id, $id );
		remove_all_filters( $filter_id );
		unset( self::$speed_bumps[ $id ] );
	}

	public function clear_all_speed_bumps() {
		foreach ( $this->get_speed_bumps() as $id => $args ) {
			$this->clear_speed_bump( $id );
		}
	}
}

// @codingStandardsIgnoreStart
function Speed_Bumps() {
	return Speed_Bumps::get_instance();
}
// @codingStandardsIgnoreEnd

add_action( 'init', 'Speed_Bumps' );

