<?php

class Test_Speed_Bumps_Element_Factory extends WP_UnitTestCase {
	public function setUp() {
		parent::setUp();
	}

	public function test_create_existed_class() {
		$image_class = \Speed_Bumps\Constraints\Elements\Factory::build( 'Image' );
		$is_image_class = is_a( $image_class, '\Speed_Bumps\Constraints\Elements\Image' );

		$this->assertTrue( $is_image_class );
	}

	public function test_create_non_exists_class() {
		$dummy_class = \Speed_Bumps\Constraints\Elements\Factory::build( 'Wrong' );
		$is_dummy_class = is_a( $dummy_class, '\Speed_Bumps\Constraints\Elements\Dummy' );

		$this->assertTrue( $is_dummy_class );

	}
}
