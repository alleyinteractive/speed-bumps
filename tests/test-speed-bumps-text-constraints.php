<?
class Test_Speed_Bumps_Text_Constraints extends WP_UnitTestCase {
	
	private $speed_bump;

	public function setUp() {
		parent::setUp();
		$this->speed_bump = Speed_Bumps::get_instance();
	}

	public function test_if_content_has_more_than_1200() {
		$content = 'Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200';

		$this->speed_bump->register_speed_bump( 'speed_bump1' );
		$okToInsert = Speed_Bumps_Text_Constraints::minimum_content_length( true, 'speed_bump1', $content );
		
		$this->assertTrue( $okToInsert );	
		
	}

	public function test_if_content_has_more_than_10_by_changing_filter() {
		$content = 'text';
		$this->speed_bump->register_speed_bump( 'speed_bump1', array( 'minimum_content_length' => 1 ) );

		$okToInsert = Speed_Bumps_Text_Constraints::minimum_content_length( true, 'speed_bump1', $content );
		
		$this->assertTrue( $okToInsert );	
		
	}

	public function test_if_content_has_less_than_1000_by_changing_filter() {
		$content = 'test';
		$this->speed_bump->register_speed_bump( 'speed_bump1', array( 'minimum_content_length' => 1000 ) );

		$okToInsert = Speed_Bumps_Text_Constraints::minimum_content_length( true, 'speed_bump1', $content );
		
		$this->assertFalse( $okToInsert );	

	}
	
}
