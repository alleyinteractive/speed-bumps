<?php

class Test_Speed_Bumps_Registration extends WP_UnitTestCase {

	private $speed_bump;
	public function setUp() {
		parent::setUp();
		$this->speed_bumps = Speed_Bumps();
	}

	public function test_has_filter_speed_bumps_inject_content() {
		$filter_exists = has_filter( 'speed_bumps_inject_content' );
		$this->assertTrue( $filter_exists );
	}

	public function test_has_filter_speed_bumps_paragraph_constraints() {
		$this->speed_bumps->register_speed_bump( 'id' );

		$filter_exists = has_filter( 'speed_bumps_id_constraints' );
		$this->assertTrue( $filter_exists );
	}

	public function test_speed_bump_registration_default_arguments() {
		$this->speed_bumps->register_speed_bump( 'speed_bump1' );
		$speed_bump1_args = $this->speed_bumps->get_speed_bump( 'speed_bump1' );

		$this->assertEquals( call_user_func( $speed_bump1_args['string_to_inject'] ), '' );

		$expected_args = array( 'paragraphs' => 1, 0 => 'iframe', 1 => 'oembed', 'image' => array( 'paragraphs' => 2 ) );
		$this->assertEquals( $expected_args, $speed_bump1_args['from_element'] );
	}

	public function test_speed_bump_registration_with_different_arguments() {
		$this->speed_bumps->register_speed_bump( 'speed_bump1' );
		$speed_bump1_args = $this->speed_bumps->get_speed_bump( 'speed_bump1' );

		$this->assertEquals( $speed_bump1_args['minimum_content_length'], array( 'paragraphs' => 8, 'characters' => 1200 ) );

		$this->speed_bumps->register_speed_bump( 'speed_bump2', array( 'minimum_content_length' => array( 'paragraphs' => 10 ) ) );
		$speed_bump2_args = $this->speed_bumps->get_speed_bump( 'speed_bump2' );
		$this->assertEquals( $speed_bump2_args['minimum_content_length'], array( 'paragraphs' => 10 ) );

	}

	public function test_get_speed_bumps_filters() {
		Speed_Bumps()->clear_all_speed_bumps();
		$this->speed_bumps->register_speed_bump( '1' );
		$this->speed_bumps->register_speed_bump( '2' );
		$this->assertEquals( array( 'speed_bumps_1_constraints', 'speed_bumps_2_constraints' ), Speed_Bumps()->get_speed_bumps_filters() );
	}

	public function test_wp_backed_up_filters() {
		$this->speed_bumps->register_speed_bump( '1' );
		$context = array(
			'index'            => 1,
			'prev_paragraph'   => '',
			'next_paragraph'   => '',
			'total_paragraphs' => 5,
			'the_content'      => '',
			'parts'            => array(),
		);
		$args = Speed_Bumps()->get_speed_bump( '1' );
		$already_inserted = array(
			array(
				'index' => 0,
				'speed_bump_id' => '1',
				'inserted_content' => '',
			),
		);
		apply_filters( 'speed_bumps_1_constraints', true, $context, $args, $already_inserted );

		global $_wp_filters_backed_up, $wp_filter;
		$this->assertNotEmpty( $_wp_filters_backed_up['speed_bumps_1_constraints'] );
		$this->assertCount( 1, $wp_filter['speed_bumps_1_constraints'][10] );
		$this->assertNotEmpty( $wp_filter['speed_bumps_1_constraints'][10]['__return_false'] );

		Speed_Bumps()->reset_all_speed_bumps();
		$this->assertEmpty( $_wp_filters_backed_up );
		$this->assertCount( 6, $wp_filter['speed_bumps_1_constraints'][10] );
	}
}
