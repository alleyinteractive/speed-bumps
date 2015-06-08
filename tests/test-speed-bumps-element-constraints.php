<?

class Test_Speed_Bumps_Element_Constraints extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();		
	}

	public function test_if_the_paragraph_not_passed_constraint_check() {
		$content = <<<EOT
Some text before blockquote <blockquote>Awesome quote</blockquote> <img src=""></img>
EOT;
		$context = array(
			'prev_paragraph' => $content,
			'next_paragraph' => ''
		);
		$args = array(
			'element_constraints' => array( 'image' )
		);
		
		$can_insert = \Speed_Bumps\Constraints\Elements\Element_Constraints::adj_paragraph_contains_element( false, $context, $args, false );
		
		$this->assertFalse( $can_insert );
	}

	public function test_if_the_paragraph_passed_constraint_check() {
		$content = <<<EOT
Some text
EOT;
		$context = array(
			'prev_paragraph' => $content,
			'next_paragraph' => ''
		);
		$args = array(
			'element_constraints' => array( 'blockquote' )
		);
		$can_insert = \Speed_Bumps\Constraints\Elements\Element_Constraints::adj_paragraph_contains_element( true, $context, $args, false );
		
		$this->assertTrue( $can_insert );
	}


	public function test_if_the_paragraph_has_blockquote() {
		$content = <<<EOT
Some text before blockquote <blockquote>Awesome quote</blockquote>
EOT;
		$blockquote_constraint = new \Speed_Bumps\Constraints\Elements\Blockquote();
		$can_insert = $blockquote_constraint->can_insert( $content );
		
		$this->assertFalse( $can_insert );

	}

	public function test_if_the_paragraph_has_image() {
		$content = <<<EOT
Some text before blockquote <img src="some_awesome_image.png"></img>
EOT;
		$image_constraint = new \Speed_Bumps\Constraints\Elements\Image();

		$can_insert = $image_constraint->can_insert( $content );
		
		$this->assertFalse( $can_insert );

	}

	public function test_if_the_paragraph_has_iframe() {
		$content = <<<EOT
Some text before blockquote <iframe src="some_awesome_image.png"></iframe>
EOT;

		$iframe_constraint = new \Speed_Bumps\Constraints\Elements\Iframe();
		$can_insert = $iframe_constraint->can_insert( $content );
		
		$this->assertFalse( $can_insert );
	}

	public function test_if_the_paragraph_has_shortcode() {	
		$content = <<<EOT
some text before [caption id="attachment_131804" align="aligncenter" width="1024"]<img class="size-large wp-image-131804" src="https://fusiondotnet.files.wordpress.com/2015/05/451577070.jpg?quality=80&amp;strip=all&amp;w=1024" alt="Getty Images" width="1024" height="712" /> Getty Images[/caption]
EOT;

		$shortcode_constraint = new \Speed_Bumps\Constraints\Elements\Shortcode();
		$can_insert = $shortcode_constraint->can_insert( $content );
		
		$this->assertFalse( $can_insert );
	}

	public function test_if_the_paragraph_has_twitter() {
		$content = 'https://twitter.com/ML_toparticles/status/606513045519659009';

		$oembed_constraint = new \Speed_Bumps\Constraints\Elements\Oembed();

		$can_insert = $oembed_constraint->can_insert( $content );

		$this->assertFalse( $can_insert );	
	}

	public function test_if_the_paragraph_has_video() {
		$content = 'https://www.youtube.com/watch?v=HG7I4oniOyA';
		
		$oembed_constraint = new \Speed_Bumps\Constraints\Elements\Oembed();

		$can_insert = $oembed_constraint->can_insert( $content );
		$this->assertFalse( $can_insert );
	}

	public function test_if_the_paragraph_has_vine() {
		$content = 'https://vine.co/v/ehuvrWg6PgA/embed/postcard';

		$oembed_constraint = new \Speed_Bumps\Constraints\Elements\Oembed();

		$can_insert = $oembed_constraint->can_insert( $content );
		$this->assertFalse( $can_insert );

	}
}
