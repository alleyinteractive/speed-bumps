<?php

class Speed_Bumps {
	private static $instance;
	private static $_speed_bumps_args = array();

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {

			self::require_files();
			self::$instance = new Speed_Bumps;
			self::$instance->setup_filters();

		}
		return self::$instance;
	}

	private static function require_files() {
		require_once( dirname( __FILE__ ) . '/constraints/elements/class-constraint-abstract.php' );
		require_once( dirname( __FILE__ ) . '/constraints/elements/class-blockquote.php' );
		require_once( dirname( __FILE__ ) . '/constraints/elements/class-image.php' );
		require_once( dirname( __FILE__ ) . '/constraints/elements/class-iframe.php' );
		require_once( dirname( __FILE__ ) . '/constraints/elements/class-shortcode.php' );
		require_once( dirname( __FILE__ ) . '/constraints/elements/class-oembed.php' );
		require_once( dirname( __FILE__ ) . '/constraints/elements/class-header.php' );
		require_once( dirname( __FILE__ ) . '/constraints/elements/class-dummy.php' );
		require_once( dirname( __FILE__ ) . '/constraints/elements/class-factory.php' );
		require_once( dirname( __FILE__ ) . '/constraints/content/class-injection.php' );
		require_once( dirname( __FILE__ ) . '/constraints/text/class-text.php' );
		require_once( dirname( __FILE__ ) . '/constraints/elements/class-element-constraints.php' );
	}

	private static function setup_filters() {
		add_filter( 'speed_bumps_inject_content', 'Speed_Bumps::insert_speed_bumps', 10 );
	}

	public static function insert_speed_bumps( $the_content ) {
		$output = array();
		$alreadyInsertAd = array();
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

			foreach ( Speed_Bumps::$_speed_bumps_args as $id => $args ) {

				if ( $index < $args['paragraph_offset'] ) {
					break;
				}
				if ( apply_filters( 'speed_bumps_'. $id . '_constraints', true, $context, $args, $alreadyInsertAd ) ) {

					$content_to_be_inserted = call_user_func( $args['string_to_inject'], $context );

					$output[] = $content_to_be_inserted;
					$alreadyInsertAd[] = array(
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
		$default = array(
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
		Speed_Bumps::$_speed_bumps_args[ $id ] = $args;

		add_filter( 'speed_bumps_' . $id . '_constraints', '\Speed_Bumps\Constraints\Text\Text::minimum_content_length', 10, 4 );
		add_filter( 'speed_bumps_' . $id . '_constraints', '\Speed_Bumps\Constraints\Content\Injection::did_already_insert_ad', 10, 4 );

		add_filter( 'speed_bumps_' . $id . '_constraints', '\Speed_Bumps\Constraints\Elements\Element_Constraints::adj_paragraph_contains_element', 10, 4 );
	}

	public function get_speed_bump_args( $id ) {
		return Speed_Bumps::$_speed_bumps_args[ $id ];
	}

}


