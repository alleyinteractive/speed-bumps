<?
require_once( dirname( __FILE__ ) . '/../inc/rules/class-iframe-checker.php' );

class Test_Speed_Bumps_IFrame extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();		
	}

	public function test_if_content_has_iframe() {
		$content = 'some content <iframe></iframe> and another content';
		$iframeChecker = new IFrame_Checker( $content );

		$params = $iframeChecker->check();

		$this->assertTrue( $params['hasIFrame'] );
	       	$this->assertEquals( 13, $params['elements'][0]['start'] );
		$this->assertEquals( 21, $params['elements'][0]['end'] );	

	
	}

	public function test_if_content_has_multiple_iframes() {
		$content = 'some content <iframe></iframe> <iframe id="1"></iframe> and another content';
		$iframeChecker = new IFrame_Checker( $content );

		$params = $iframeChecker->check();

		$this->assertTrue( $params['hasIFrame'] );
	       	$this->assertEquals( 13, $params['elements'][0]['start'] );
		$this->assertEquals( 21, $params['elements'][0]['end'] );	
		
	       	$this->assertEquals( 31, $params['elements'][1]['start'] );
		$this->assertEquals( 46, $params['elements'][1]['end'] );	
	}
}
