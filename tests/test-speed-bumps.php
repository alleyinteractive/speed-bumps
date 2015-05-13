<?php

class Test_Speed_Bumps extends WP_UnitTestCase {
	public function setUp() {
		parent::setUp();
	}

	public function test_algorithm_with_content_less_than_1200() {
		$content = 'Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200';

		add_filter( 'speed_bumps_insert_ad', array( $this, 'add_polar' ), 10, 0 );
		
		$newContent = Speed_Bumps::check_and_inject_ad( $content, null );
		$this->assertContains( 'polar-ad', $newContent );
	}

	public function add_polar() {
		return '<div id="polar-ad"</div>';
	}



}
