<?

class Test_Speed_Bumps_Element_Constraints extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();		
	}

	public function test_if_the_paragraph_not_passed_constraint_check() {
		$content = <<<EOT
Some text before blockquote <blockquote>Awesome quote</blockquote> <img src=""></img>
EOT;
		Speed_Bumps()->register_speed_bump( 'speed_bump1', array( 'element_constraints' => array( 'blockquote', 'image' ) ) );
		$contains_blockquote_and_image = Speed_Bumps_Element_Constraints::prev_paragraph_contains_element( 'speed_bump1', $content );
		
		$this->assertTrue( $contains_blockquote_and_image );
	}

	public function test_if_the_paragraph_passed_constraint_check() {
		$content = <<<EOT
Some text
EOT;
		Speed_Bumps()->register_speed_bump( 'speed_bump1', array( 'element_constraints' => array( 'blockquote', 'image' ) ) );
		$contains_blockquote_and_image = Speed_Bumps_Element_Constraints::prev_paragraph_contains_element( 'speed_bump1', $content );
		
		$this->assertFalse( $contains_blockquote_and_image );
	}


	public function test_if_the_paragraph_has_blockquote() {
		$content = <<<EOT
Some text before blockquote <blockquote>Awesome quote</blockquote>
EOT;
		$blockquote_constraint = new Speed_Bumps_Blockquote_Constraint();
		$containsBlockquote = $blockquote_constraint->contains( $content );
		
		$this->assertTrue( $containsBlockquote );

	}

	public function test_if_the_paragraph_has_image() {
		$content = <<<EOT
Some text before blockquote <img src="some_awesome_image.png"></img>
EOT;
		$image_constraint = new Speed_Bumps_Image_Constraint();

		$containsImage = $image_constraint->contains( $content );
		
		$this->assertTrue( $containsImage );

	}

	public function test_if_the_paragraph_has_iframe() {
		$content = <<<EOT
Some text before blockquote <iframe src="some_awesome_image.png"></iframe>
EOT;

		$iframe_constraint = new Speed_Bumps_Iframe_Constraint();
		$containsIFrame = $iframe_constraint->contains( $content );
		
		$this->assertTrue( $containsIFrame );
	}

	public function test_if_the_paragraph_has_shortcode() {	
		$content = <<<EOT
some text before [caption id="attachment_131804" align="aligncenter" width="1024"]<img class="size-large wp-image-131804" src="https://fusiondotnet.files.wordpress.com/2015/05/451577070.jpg?quality=80&amp;strip=all&amp;w=1024" alt="Getty Images" width="1024" height="712" /> Getty Images[/caption]
EOT;

		$shortcode_constraint = new Speed_Bumps_Shortcode_Constraint();
		$containsCaption = $shortcode_constraint->contains( $content );
		
		$this->assertTrue( $containsCaption );
	}

	public function test_if_the_paragraph_has_twitter() {
		$content = 'https://twitter.com/ML_toparticles/status/606513045519659009';

		$oembed_constraint = new Speed_Bumps_Oembed_Constraint();

		$containsTwitter = $oembed_constraint->contains( $content );

		$this->assertTrue( $containsTwitter );	
	}

	public function test_if_the_paragraph_has_video() {
		$content = 'https://www.youtube.com/watch?v=HG7I4oniOyA';
		
		$oembed_constraint = new Speed_Bumps_Oembed_Constraint();

		$containsYoutube = $oembed_constraint->contains( $content );
		$this->assertTrue( $containsYoutube );
	}

	public function test_if_the_paragraph_has_vine() {
		$content = 'https://vine.co/v/ehuvrWg6PgA/embed/postcard';

		$oembed_constraint = new Speed_Bumps_Oembed_Constraint();

		$containsVine = $oembed_constraint->contains( $content );
		$this->assertTrue( $containsVine );

	}
}
