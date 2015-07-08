<?php

class Test_Speed_Bumps_Element_Constraints_Definitions extends WP_UnitTestCase {

	public function test_if_the_paragraph_has_blockquote() {
		$content = <<<EOT
Some text before blockquote <blockquote>Awesome quote</blockquote>
EOT;
		$blockquote_constraint = new \Speed_Bumps\Constraints\Elements\Blockquote();
		$can_insert = $blockquote_constraint->paragraph_not_contains_element( $content );

		$this->assertFalse( $can_insert );

	}

	public function test_if_the_paragraph_has_image() {
		$content = <<<EOT
Some text before blockquote <img src="some_awesome_image.png"></img>
EOT;
		$image_constraint = new \Speed_Bumps\Constraints\Elements\Image();

		$can_insert = $image_constraint->paragraph_not_contains_element( $content );

		$this->assertFalse( $can_insert );

	}

	public function test_if_the_paragraph_has_iframe() {
		$content = <<<EOT
Some text before blockquote <iframe src="some_awesome_image.png"></iframe>
EOT;

		$iframe_constraint = new \Speed_Bumps\Constraints\Elements\Iframe();
		$can_insert = $iframe_constraint->paragraph_not_contains_element( $content );

		$this->assertFalse( $can_insert );
	}

	public function test_if_the_paragraph_has_shortcode() {
		$content = <<<EOT
some text before [caption id="attachment_131804" align="aligncenter" width="1024"]<img class="size-large wp-image-131804" src="https://fusiondotnet.files.wordpress.com/2015/05/451577070.jpg?quality=80&amp;strip=all&amp;w=1024" alt="Getty Images" width="1024" height="712" /> Getty Images[/caption]
EOT;

		$shortcode_constraint = new \Speed_Bumps\Constraints\Elements\Shortcode();
		$can_insert = $shortcode_constraint->paragraph_not_contains_element( $content );

		$this->assertFalse( $can_insert );
	}

	public function test_if_the_paragraph_has_twitter() {
		$content = 'https://twitter.com/ML_toparticles/status/606513045519659009';

		$oembed_constraint = new \Speed_Bumps\Constraints\Elements\Oembed();

		$can_insert = $oembed_constraint->paragraph_not_contains_element( $content );

		$this->assertFalse( $can_insert );
	}

	public function test_if_the_paragraph_has_video() {
		$content = 'https://www.youtube.com/watch?v=HG7I4oniOyA';

		$oembed_constraint = new \Speed_Bumps\Constraints\Elements\Oembed();

		$can_insert = $oembed_constraint->paragraph_not_contains_element( $content );
		$this->assertFalse( $can_insert );
	}

	public function test_if_the_paragraph_has_vine() {
		$content = 'https://vine.co/v/ehuvrWg6PgA/embed/postcard';

		$oembed_constraint = new \Speed_Bumps\Constraints\Elements\Oembed();

		$can_insert = $oembed_constraint->paragraph_not_contains_element( $content );
		$this->assertFalse( $can_insert );
	}

	public function test_if_the_paragraph_has_h1() {
		$content = '<h1>header</h1>';

		$header_constraint = new \Speed_Bumps\Constraints\Elements\Header();

		$can_insert = $header_constraint->paragraph_not_contains_element( $content );
		$this->assertFalse( $can_insert );
	}

	public function test_if_the_paragraph_has_h6() {
		$content = '<h6>header</h6>';
		$header_constraint = new \Speed_Bumps\Constraints\Elements\Header();

		$can_insert = $header_constraint->paragraph_not_contains_element( $content );
		$this->assertFalse( $can_insert );
	}

	public function test_if_the_paragraph_has_header() {
		$content = '<header>awesome headline</header>';
		$header_constraint = new \Speed_Bumps\Constraints\Elements\Header();

		$can_insert = $header_constraint->paragraph_not_contains_element( $content );
		$this->assertFalse( $can_insert );

	}

}
