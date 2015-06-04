<?php

class Test_Speed_Bumps_Registration extends WP_UnitTestCase {

	private $speed_bump;
	public function setUp() {
		parent::setUp();
		$this->speed_bump = Speed_Bumps::get_instance();
	}

	public function test_has_filter_speed_bumps_inject_content() {
		$filter_exists = has_filter( 'speed_bumps_inject_content' );
		$this->assertTrue( $filter_exists );
	}

	public function test_has_filter_speed_bump_global_constraints() {
		$this->speed_bump->register_speed_bump( 'id' );
		$filter_exists = has_filter( 'speed_bumps_global_constraints' );

		$this->assertTrue( $filter_exists );
	}

	public function test_has_filter_speed_bumps_paragraph_constraints() {
		$this->speed_bump->register_speed_bump( 'id' );

		$filter_exists = has_filter( 'speed_bumps_paragraph_constraints' );
		$this->assertTrue( $filter_exists );
	}

	public function test_speed_bump_registration_default_arguments() {
		$this->speed_bump->register_speed_bump( 'speed_bump1' );
		$speed_bump1_args = Speed_Bumps()->get_speed_bump_args( 'speed_bump1' );

		$this->assertEquals( $speed_bump1_args[ 'string_to_inject' ], function() { return ''; } );
		$this->assertEquals( $speed_bump1_args[ 'minimum_content_length' ], 1200 );
		$this->assertEquals( $speed_bump1_args[ 'element_constraints' ], array( 'iframe', 'oembed', 'image' ) );
	}

	public function test_speed_bump_registration_with_different_arguments() {
		$this->speed_bump->register_speed_bump( 'speed_bump1' );
		$speed_bump1_args = Speed_Bumps()->get_speed_bump_args( 'speed_bump1' );

		$this->assertEquals( $speed_bump1_args[ 'minimum_content_length' ], 1200 );
	
		$this->speed_bump->register_speed_bump( 'speed_bump2', array( 'minimum_content_length' => 10 ) );
		$speed_bump2_args = Speed_Bumps()->get_speed_bump_args( 'speed_bump2' );
		$this->assertEquals( $speed_bump2_args[ 'minimum_content_length' ], 10 );
	}

}
