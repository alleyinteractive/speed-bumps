<?
class Test_Speed_Bumps_Text_Constraints extends WP_UnitTestCase {
	public function setUp() {
		parent::setUp();
	}

	public function test_if_content_has_more_than_1200() {
		$content = 'Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200';

		$okToInsert = Speed_Bumps_Text_Constraints::minimum_content_length( true, $content );
		
		$this->assertTrue( $okToInsert );	
		
	}

	public function test_if_content_has_more_than_10_by_changing_filter() {
		$content = 'text';

		add_filter( 'speed_bumps_minimum_content_length', function( $args ) { 
			return 1;
		});

		$okToInsert = Speed_Bumps_Text_Constraints::minimum_content_length( true, $content );
		
		$this->assertTrue( $okToInsert );	
		
	}

	public function test_if_content_has_less_than_1000_by_changing_filter() {
		$content = 'test';

		add_filter( 'speed_bumps_minimum_content_length', function( $args ) { 
			return 1000;
		});
		
		$okToInsert = Speed_Bumps_Text_Constraints::minimum_content_length( true, $content );
		
		$this->assertFalse( $okToInsert );	

	}
	
}
