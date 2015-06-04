<?
class Test_Speed_Bumps_Text_Constraints extends WP_UnitTestCase {
	public function setUp() {
		parent::setUp();
	}

	public function test_if_content_has_more_than_1200() {
		$content = 'Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200';

		$okToInsert = Speed_Bumps_Text_Constraints::minimum_content_length( true, 'speed_bump1', $content );
		
		$this->assertTrue( $okToInsert );	
		
	}

	public function test_if_content_has_more_than_10_by_changing_filter() {
		global $_speed_bumps_args;

		$content = 'text';
		$_speed_bumps_args['speed_bump1']['minimum_content_length'] = 1;
		
		$okToInsert = Speed_Bumps_Text_Constraints::minimum_content_length( true, 'speed_bump1', $content );
		
		$this->assertTrue( $okToInsert );	
		
	}

	public function test_if_content_has_less_than_1000_by_changing_filter() {
		global $_speed_bumps_args;

		$content = 'test';
		$_speed_bumps_args['speed_bump1']['minimum_content_length'] = 1000;

		$okToInsert = Speed_Bumps_Text_Constraints::minimum_content_length( true, 'speed_bump1', $content );
		
		$this->assertFalse( $okToInsert );	

	}
	
}
