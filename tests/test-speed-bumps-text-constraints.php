<?


class Test_Speed_Bumps_Text_Constraints extends WP_UnitTestCase {
	
	private $speed_bump;

	public function setUp() {
		parent::setUp();
		$this->speed_bump = Speed_Bumps::get_instance();
	}

	public function test_if_content_has_more_than_1200() {
		$content = 'Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200';

		$context = array(
			'the_content' => $content
		);
		$args = array(
			'minimum_content_length' => 1200
		);
		$okToInsert = \Speed_Bumps\Constraint\Text\Speed_Bumps_Text_Constraints::minimum_content_length( true, $context, $args, array() );
		
		$this->assertTrue( $okToInsert );	
		
	}

	public function test_if_content_has_more_than_10_by_changing_filter() {
		$content = 'text';

		$context = array(
			'the_content' => $content
		);
		$args = array(
			'minimum_content_length' => 1
		);

		$okToInsert = \Speed_Bumps\Constraint\Text\Speed_Bumps_Text_Constraints::minimum_content_length( true, $context, $args, array() );
		
		$this->assertTrue( $okToInsert );	
		
	}

	public function test_if_content_has_less_than_1000_by_changing_filter() {
		$content = 'test';
		$context = array(
			'the_content' => $content
		);
		$args = array(
			'minimum_content_length' => 1000
		);

		$okToInsert = \Speed_Bumps\Constraint\Text\Speed_Bumps_Text_Constraints::minimum_content_length( true, $context, $args, array() );
		
		$this->assertFalse( $okToInsert );	
	}

	public function test_ad_already_inserted() {
		$ad_did_inserted = \Speed_Bumps\Constraint\Text\Speed_Bumps_Text_Constraints::did_already_insert_ad( true, array(), array(), array( 'something' ) );	

		$this->assertFalse( $ad_did_inserted );
	}

	public function test_ad_not_already_inserted() {
		$ad_did_not_insert = \Speed_Bumps\Constraint\Text\Speed_Bumps_Text_Constraints::did_already_insert_ad( true, array(), array(), array() );	

		$this->assertTrue( $ad_did_not_insert );
	
	}
	
}
