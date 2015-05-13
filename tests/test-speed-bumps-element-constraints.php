<?

class Test_Speed_Bumps_Element_Constraints extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();		
	}

	public function test_if_the_paragraph_has_blockquote() {
		$content = <<<EOT
Some text before blockquote <blockquote>Awesome quote</blockquote>
EOT;

		$containsBlockquote= Speed_Bumps_Element_Constraints::contains_inline_element( $content );
		
		$this->assertTrue( $containsBlockquote );

	}

	public function test_if_the_paragraph_has_image() {
		$content = <<<EOT
Some text before blockquote <img src="some_awesome_image.png"></img>
EOT;

		$containsImage = Speed_Bumps_Element_Constraints::contains_inline_element( $content );
		
		$this->assertTrue( $containsImage );

	}

	public function test_if_the_paragraph_has_iframe() {
		$content = <<<EOT
Some text before blockquote <iframe src="some_awesome_image.png"></iframe>
EOT;

		$containsIFrame = Speed_Bumps_Element_Constraints::contains_inline_element( $content );
		
		$this->assertTrue( $containsIFrame );
	}

	public function test_if_the_paragraph_has_caption() {	
		$content = <<<EOT
some text before [caption id="attachment_131804" align="aligncenter" width="1024"]<img class="size-large wp-image-131804" src="https://fusiondotnet.files.wordpress.com/2015/05/451577070.jpg?quality=80&amp;strip=all&amp;w=1024" alt="Getty Images" width="1024" height="712" /> Getty Images[/caption]
EOT;

		$containsCaption = Speed_Bumps_Element_Constraints::contains_inline_element( $content );
		
		$this->assertTrue( $containsCaption );

		
	}
}
