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

}
