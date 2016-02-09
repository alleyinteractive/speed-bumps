<?php
/*
Plugin Name: Speed-bumps
Version: 0.1.0
Description: A Plugin to insert a piece of content intelligently.
Author: Fusion Engineering
Author URI: http://fusion.net
Plugin URI: https://github.com/fusioneng/speed-bumps
Text Domain: speed-bumps
Domain Path: /languages
*/

use Speed_Bumps\Utils\Text;

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
	function spl_autoload( $class ) {

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
			require( $file );
		}
	}

	private function setup_filters() {
		add_filter( 'speed_bumps_inject_content', array( $this, 'insert_speed_bumps' ) );
	}

	/**
	 * Inject speed bumps into a block of text, like post content.
	 *
	 * Can be called directly, like `Speed_Bumps()->insert_speed_bumps( $post->post_content );`.
	 *
	 * More common usage is by adding this function to a filter:
	 * `add_filter( 'the_content', array( Speed_Bumps(), 'insert_speed_bumps' ), 1 );`
	 * (Note the early priority, as it should be attached before `wpautop` runs.)
	 *
	 * @param string $the_content A block of text. Expected to be pre-texturized.
	 * @return string The text with all registered speed bumps inserted at appropriate locations if possible.
	 */
	public function insert_speed_bumps( $the_content ) {
		global $_wp_filters_backed_up, $wp_filter;
		$_wp_filters_backed_up = array();
		$output = array();
		$already_inserted = array();
		$parts = Text::split_paragraphs( $the_content );
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

				$speed_bump_filter = sprintf( self::$filter_id, $id );

				if ( apply_filters( $speed_bump_filter, true, $context, $args, $already_inserted ) ) {

					$content_to_be_inserted = call_user_func( $args['string_to_inject'], $context, $already_inserted );

					$output[] = apply_filters( 'speed_bumps_content_inserted', $content_to_be_inserted, $args, $context, $already_inserted );

					$already_inserted[] = array(
						'index' => $index,
						'speed_bump_id' => $id,
						'inserted_content' => $content_to_be_inserted,
					);
				}

				do_action( 'done_speed_bump_constraints', $speed_bump_filter );
			}
		}

		$this->reset_all_speed_bumps();
		return implode( PHP_EOL . PHP_EOL, $output );
	}

	/**
	 * Register a speed bump for insertion.
	 *
	 * Adds a speed bump, which will be processed in the
	 * `speed_bumps_insert_content` filter.
	 *
	 * @param string $id ID used to reference the speed bump.
	 * @param array $args Array of arguments governing its behavior.
	 * @return void
	 */
	public function register_speed_bump( $id, $args = array() ) {
		$id = sanitize_key( $id );

		$defaults = array(

			// This should be a function which returns the content to be inserted
			'string_to_inject' => '__return_empty_string',

			// Maximum number of times this can be inserted in a post
			'maximum_inserts' => 1,

			// Rules which govern the content as a whole
			'minimum_content_length' => array(
				'paragraphs' => 8,
				'characters' => 1200,
			),

			// Positional rules: distance from start, end, and other elements
			'from_start' => array(
				'paragraphs' => 3,
				'words' => 75,
			),
			'from_end' => array(
				'paragraphs' => 3,
				'words' => 75,
			),
			'from_element' => array(

				// Distance rules (characters/words/paragraphs) applied to all elements listed here
				'paragraphs' => 1,

				// Can also be an array with an element as the key and an
				// array containing distance arguments as the value.
				'iframe',
				'oembed',
				'image' => array(
					'paragraphs' => 2,
				),
			),
			'from_speedbump' => array(

				// Distance rules (characters/words/paragraphs) applied to all speed bumps
				'paragraphs' => 1,

				// can also be an array with a speed bump ID as the key and an
				// array containing distance arguments as the value
			),

		);

		$args = wp_parse_args( $args, $defaults );
		$args['id'] = $id;

		if (isset( $args['element_constraints'] ) ) {
			$args['from_element'] = $args['element_constraints'];
			unset( $args['element_constraints'] );
		}

		if (isset( $args['paragraph_offset'] ) ) {
			$args['from_start'] = $args['paragraph_offset'];
			unset( $args['paragraph_offset'] );
		}

		self::$speed_bumps[ $id ] = $args;

		$filter_id = sprintf( self::$filter_id, $id );

		add_filter( $filter_id, '\Speed_Bumps\Constraints\Text\Minimum_Text::content_is_long_enough_to_insert', 10, 4 );
		add_filter( $filter_id, '\Speed_Bumps\Constraints\Text\Minimum_Text::meets_minimum_distance_from_start', 10, 4 );
		add_filter( $filter_id, '\Speed_Bumps\Constraints\Text\Minimum_Text::meets_minimum_distance_from_end', 10, 4 );
		add_filter( $filter_id, '\Speed_Bumps\Constraints\Content\Injection::less_than_maximum_number_of_inserts', 10, 4 );
		add_filter( $filter_id, '\Speed_Bumps\Constraints\Content\Injection::meets_minimum_distance_from_other_inserts', 10, 4 );
		add_filter( $filter_id, '\Speed_Bumps\Constraints\Elements\Element_Constraints::meets_minimum_distance_from_elements', 10, 4 );
	}

	public function get_speed_bumps() {
		return self::$speed_bumps;
	}

	public function get_speed_bumps_filters() {
		$speed_bumps = $this->get_speed_bumps();
		$filter_pattern = self::$filter_id;

		return array_map(
			function( $id ) use ( $filter_pattern ) {
				return sprintf( $filter_pattern, $id );
			}, array_keys( $speed_bumps )
		);
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

	// Public control structures, which can be called by speed bumps
	public static function return_false_and_skip() {
		return self::skip_insertion_point( false );
	}

	public static function return_false_and_remove_all() {
		self::remove_speed_bump();
		return false;
	}

	public static function return_true_and_skip() {
		return self::skip_insertion_point( true );
	}

	public static function return_true_and_remove_all() {
		self::remove_speed_bump();
		return true;
	}

	/**
	 * Prevent a speed bump from running over the rest of the content.
	 *
	 * Run when a constraint function returns one of the special constant values
	 * SPEED_BUMPS_RETURN_FALSE_AND_REMOVE_ALL or SPEED_BUMPS_RETURN_TRUE_AND_REMOVE_ALL.
	 *
	 * @param string $speed_bump_id Defaults to the value of current_filter().
	 */
	public static function remove_speed_bump( $speed_bump_id = null ) {
		global $_wp_filters_backed_up, $wp_filter;

		if ( $speed_bump_id ) {
			$filter_id = sprintf( self::$filter_id, $speed_bump_id );
		} else {
			$filter_id = current_filter();
		}

		if ( isset( $wp_filter[ $filter_id ] )
				&& in_array( $filter_id, Speed_Bumps()->get_speed_bumps_filters(), true ) ) {
			$_wp_filters_backed_up[ $filter_id ] = $wp_filter[ $filter_id ];
			remove_all_filters( $filter_id );
			add_filter( $filter_id, '__return_false' );
		}
	}

	/**
	 * Skip all remaining constraint checks at the current insertion point.
	 *
	 * Can be run as a return value from a filter, in which case it returns the
	 * $return_value passed into it, removes all other filters, and adds an
	 * action to reset the speed bump after the current insertion point.
	 *
	 * @param bool The $can_insert value which should be returned from the current filter.
	 */
	public static function skip_insertion_point( $return_value = false ) {
		global $_wp_filters_backed_up, $wp_filter;

		$filter_id = current_filter();

		if ( isset( $wp_filter[ $filter_id ] )
				&& in_array( $filter_id, Speed_Bumps()->get_speed_bumps_filters(), true ) ) {


			$_wp_filters_backed_up[ $filter_id ] = $wp_filter[ $filter_id ];
			remove_all_filters( $filter_id );

			// pushing another method on to the end of the current filter, which restores it
			add_action( 'done_speed_bump_constraints', 'Speed_Bumps::restore_speed_bump' );
		}

		return $return_value;
	}

	/**
	 * Restore a speed bump that was temporarily removed using
	 * `skip_insertion_point()`.
	 *
	 * @param bool $return_value Required, since this function is hooked as a regular ilter
	 */
	public static function restore_speed_bump( $speed_bump_filter ) {
		global $wp_filter, $_wp_filters_backed_up;

		if ( isset( $_wp_filters_backed_up[ $speed_bump_filter ] )
				&& in_array( $speed_bump_filter, Speed_Bumps()->get_speed_bumps_filters(), true ) ) {

			$wp_filter[ $speed_bump_filter ] = $_wp_filters_backed_up[ $speed_bump_filter ];
			unset( $_wp_filters_backed_up[ $speed_bump_filter ] );
		}

		remove_action( 'done_speed_bump_constraints', 'Speed_Bumps::restore_speed_bump' );
	}

	/**
	 * Restore any filters removed by `remove_current_speed_bump_filters()`.
	 *
	 * Run at the end of processing the content, so that more than one content string can be processed in a
	 * single WP instance.
	 */
	public static function reset_all_speed_bumps() {
		global $_wp_filters_backed_up, $wp_filter;

		if ( is_array( $_wp_filters_backed_up ) ) {
			foreach ( $_wp_filters_backed_up as $hook => $filters ) {
				$wp_filter[ $hook ] = $filters;
			}
			$_wp_filters_backed_up = array();
		}
	}
}

// @codingStandardsIgnoreStart
function Speed_Bumps() {
	return Speed_Bumps::get_instance();
}
// @codingStandardsIgnoreEnd

add_action( 'init', 'Speed_Bumps' );

/**
 * The Public API for this plugin.
 *
 * All functions that should be available in the global namespace are listed here.
 *
 */
function register_speed_bump( $id, $args = array() ) {
	return Speed_Bumps()->register_speed_bump( $id, $args );
}

function insert_speed_bumps( $thecontent ) {
	return Speed_Bumps()->insert_speed_bumps( $thecontent );
}

function clear_speed_bump( $id ) {
	return Speed_Bumps()->clear_speed_bump( $id );
}
