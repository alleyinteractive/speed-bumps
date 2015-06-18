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
			) );
		$ad_did_inserted = \Speed_Bumps\Constraints\Content\Injection::did_already_insert_ad( true, array( 'index' => 0 ), array(), $already_inserted );

		$this->assertFalse( $ad_did_inserted );
	}

	public function test_ad_not_already_inserted() {
		$ad_did_not_insert = \Speed_Bumps\Constraints\Content\Injection::did_already_insert_ad( true, array( 'index' => 0 ), array(), array() );

		$this->assertTrue( $ad_did_not_insert );

	}


}
