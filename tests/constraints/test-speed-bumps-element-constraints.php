<?php

use Speed_Bumps\Utils\Text;
use Speed_Bumps\Constraints\Elements\Element_Constraints;

class Test_Speed_Bumps_Element_Constraints extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();

		$this->content = '<blockquote>Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something</blockquote>

longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something

longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than

1200Something longer than 1200Something longer

than 1200Something longer than 1200';
	}

	public function test_meets_minimum_distance_from_elements_paragraphs() {
		$context = array(
			'the_content' => $this->content,
			'parts' => Text::split_paragraphs( $this->content ),
			'index' => 1,
		);

		$args = array(
			'from_element' => array(
				'paragraphs' => 2,
				'blockquote',
			),
		);

		$okToInsert = Element_Constraints::adj_paragraph_not_contains_element( true, $context, $args, array() );
		$this->assertFalse( $okToInsert );

		$context['index'] = 3;
		$okToInsert = Element_Constraints::adj_paragraph_not_contains_element( true, $context, $args, array() );
		$this->assertTrue( $okToInsert );

		$args['from_element'] = array(
			'paragraphs' => 2,
			'blockquote' => array(
				'paragraphs' => 4
			)
		);
		$okToInsert = Element_Constraints::adj_paragraph_not_contains_element( true, $context, $args, array() );
		$this->assertFalse( $okToInsert );

		$args['from_element']['blockquote'] = array( 'paragraphs' => 1 );
		$okToInsert = Element_Constraints::adj_paragraph_not_contains_element( true, $context, $args, array() );
		$this->assertTrue( $okToInsert );
	}

	public function test_meets_minimum_distance_from_elements_words() {
		$context = array(
			'the_content' => $this->content,
			'parts' => Text::split_paragraphs( $this->content ),
			'index' => 1,
		);

		$args = array(
			'from_element' => array(
				'words' => 60,
				'blockquote',
			),
		);

		$okToInsert = Element_Constraints::adj_paragraph_not_contains_element( true, $context, $args, array() );
		$this->assertFalse( $okToInsert );

		$context['index'] = 3;
		$okToInsert = Element_Constraints::adj_paragraph_not_contains_element( true, $context, $args, array() );
		$this->assertTrue( $okToInsert );

		$args['from_element'] = array(
			'words' => 60,
			'blockquote' => array(
				'words' => 120
			)
		);
		$okToInsert = Element_Constraints::adj_paragraph_not_contains_element( true, $context, $args, array() );
		$this->assertFalse( $okToInsert );

		$args['from_element']['blockquote'] = array( 'words' => 1 );
		$okToInsert = Element_Constraints::adj_paragraph_not_contains_element( true, $context, $args, array() );
		$this->assertTrue( $okToInsert );
	}

	public function test_meets_minimum_distance_from_elements_characters() {
		$context = array(
			'the_content' => $this->content,
			'parts' => Text::split_paragraphs( $this->content ),
			'index' => 1,
		);

		$args = array(
			'from_element' => array(
				'characters' => 450,
				'blockquote',
			),
		);

		$okToInsert = Element_Constraints::adj_paragraph_not_contains_element( true, $context, $args, array() );
		$this->assertFalse( $okToInsert );

		$context['index'] = 3;
		$okToInsert = Element_Constraints::adj_paragraph_not_contains_element( true, $context, $args, array() );
		$this->assertTrue( $okToInsert );

		$args['from_element'] = array(
			'characters' => 450,
			'blockquote' => array(
				'characters' => 1500
			)
		);
		$okToInsert = Element_Constraints::adj_paragraph_not_contains_element( true, $context, $args, array() );
		$this->assertFalse( $okToInsert );

		$args['from_element']['blockquote'] = array( 'characters' => 1 );
		$okToInsert = Element_Constraints::adj_paragraph_not_contains_element( true, $context, $args, array() );
		$this->assertTrue( $okToInsert );
	}

	/*
	public function test_if_the_paragraph_not_passed_constraint_check() {
		$context = array(
			'the_content' => $this->content,
			'index' => 1,
			'parts' => Text::split_paragraphs( $this->content ),
		);
		$args = array(
			'from_element' => array(
				'paragraphs' => 2,
				'blockquote'
			),
		);

		$can_insert = Element_Constraints::adj_paragraph_not_contains_element( false, $context, $args, false );

		$this->assertFalse( $can_insert );
	}

	public function test_if_the_paragraph_passed_constraint_check() {
		$content = <<<EOT
Some text
EOT;
		$context = array(
			'prev_paragraph' => $content,
			'next_paragraph' => '',
		);
		$args = array(
			'from_element' => array( 'blockquote' ),
		);
		$can_insert = \Speed_Bumps\Constraints\Elements\Element_Constraints::adj_paragraph_not_contains_element( true, $context, $args, false );

		$this->assertTrue( $can_insert );
	}


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
	 */
}
