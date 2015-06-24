<?php

class Test_Speed_Bumps_Injection_Constraints extends WP_UnitTestCase {
	public function setUp() {
		parent::setUp();
	}

	public function test_ad_already_inserted() {
		$already_inserted = array(
			array(
				'index' => 0,
				'speed_bump_id' => 'speed_bump1',
				'inserted_content' => 'content1',
			),
		);
		$ad_did_inserted = \Speed_Bumps\Constraints\Content\Injection::this_speed_bump_not_already_inserted( true, array( 'index' => 2 ), array( 'id' => 'speed_bump1' ), $already_inserted );

		$this->assertFalse( $ad_did_inserted );
	}

	public function test_ad_not_already_inserted() {
		$already_inserted = array();
		$ad_did_not_insert = \Speed_Bumps\Constraints\Content\Injection::this_speed_bump_not_already_inserted( true, array( 'index' => 0 ), array( 'id' => 'speed_bump1' ), array() );

		$this->assertTrue( $ad_did_not_insert );

	}

	public function test_ad_already_inserted_here() {
		$already_inserted = array(
			array(
				'index' => 1,
				'speed_bump_id' => 'speed_bump1',
				'inserted_content' => 'content1',
			),
		);
		$ad_did_inserted = \Speed_Bumps\Constraints\Content\Injection::no_speed_bump_inserted_here( true, array( 'index' => 1 ), array( 'id' => 'speed_bump2' ), $already_inserted );

		$this->assertFalse( $ad_did_inserted );
	}

	public function test_ad_not_already_inserted_here() {
		$already_inserted = array(
			array(
				'index' => 0,
				'speed_bump_id' => 'speed_bump1',
				'inserted_content' => 'content1',
			),
		);
		$ad_did_inserted = \Speed_Bumps\Constraints\Content\Injection::no_speed_bump_inserted_here( true, array( 'index' => 1 ), array( 'id' => 'speed_bump2' ), $already_inserted );

		$this->assertTrue( $ad_did_inserted );
	}

	public function test_ad_cannot_be_inserted_after_centain_paragraph() {
		$already_inserted = array(
			array(
				'index' => 0,
				'speed_bump_id' => 'speed_bump1',
				'inserted_content' => 'content1',
			),
		);
		$ad_did_inserted = \Speed_Bumps\Constraints\Content\Injection::paragraph_far_enough_away( true, array( 'index' => 1 ), array( 'id' => 'speed_bump2', 'minimum_space_from_other_inserts' => 4 ), $already_inserted );

		$this->assertFalse( $ad_did_inserted );
	}

	public function test_ad_can_be_inserted_after_centain_paragraph() {
		$already_inserted = array(
			array(
				'index' => 0,
				'speed_bump_id' => 'speed_bump1',
				'inserted_content' => 'content1',
			),
		);
		$ad_did_inserted = \Speed_Bumps\Constraints\Content\Injection::paragraph_far_enough_away( true, array( 'index' => 5 ), array( 'id' => 'speed_bump2', 'minimum_space_from_other_inserts' => 4 ), $already_inserted );

		$this->assertTrue( $ad_did_inserted );
	}

}
