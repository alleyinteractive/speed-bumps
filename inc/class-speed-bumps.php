<?php
namespace Speed_Bumps;

class Speed_Bumps {
	private static $instance;
	private static $speed_bumps = array();
	private static $filter_id = 'speed_bumps_%s_constraints';
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new Speed_Bumps;
			self::$instance->setup_filters();
		}
		return self::$instance;
	}
	/**
	 * Prevent the creation of a new instance of the "SINGLETON" using the operator 'new' from
	 * outside of this class.
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
				);

			foreach ( $this->get_speed_bumps() as $id => $args ) {

				if ( $index < $args['paragraph_offset'] ) {
					break;
				}

				if ( apply_filters( 'speed_bumps_'. $id . '_constraints', true, $context, $args, $already_inserted ) ) {

					$content_to_be_inserted = call_user_func( $args['string_to_inject'], $context );

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
			'string_to_inject' => function() { return ''; },
			'minimum_content_length' => 1200,
			'paragraph_offset' => 0,
			'element_constraints' => array(
				'iframe',
				'oembed',
				'image',
				),
			);
		$args = wp_parse_args( $args, $default );
		self::$speed_bumps[ $id ] = $args;

		$filter_id = sprintf( self::$filter_id, $id );

		add_filter( $filter_id, '\Speed_Bumps\Constraints\Text\Minimum_Text::content_is_long_enough_to_insert', 10, 4 );
		add_filter( $filter_id, '\Speed_Bumps\Constraints\Content\Injection::this_speed_bump_not_already_inserted', 10, 4 );
		add_filter( $filter_id, '\Speed_Bumps\Constraints\Content\Injection::no_speed_bump_inserted_here', 10, 4 );
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
