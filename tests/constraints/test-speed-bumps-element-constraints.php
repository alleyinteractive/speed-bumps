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

		$okToInsert = Element_Constraints::meets_minimum_distance_from_elements( true, $context, $args, array() );
		$this->assertFalse( $okToInsert );

		$context['index'] = 3;
		$okToInsert = Element_Constraints::meets_minimum_distance_from_elements( true, $context, $args, array() );
		$this->assertTrue( $okToInsert );

		$args['from_element'] = array(
			'paragraphs' => 2,
			'blockquote' => array(
				'paragraphs' => 4
			)
		);
		$okToInsert = Element_Constraints::meets_minimum_distance_from_elements( true, $context, $args, array() );
		$this->assertFalse( $okToInsert );

		$args['from_element']['blockquote'] = array( 'paragraphs' => 1 );
		$okToInsert = Element_Constraints::meets_minimum_distance_from_elements( true, $context, $args, array() );
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

		$okToInsert = Element_Constraints::meets_minimum_distance_from_elements( true, $context, $args, array() );
		$this->assertFalse( $okToInsert );

		$context['index'] = 3;
		$okToInsert = Element_Constraints::meets_minimum_distance_from_elements( true, $context, $args, array() );
		$this->assertTrue( $okToInsert );

		$args['from_element'] = array(
			'words' => 60,
			'blockquote' => array(
				'words' => 120
			)
		);
		$okToInsert = Element_Constraints::meets_minimum_distance_from_elements( true, $context, $args, array() );
		$this->assertFalse( $okToInsert );

		$args['from_element']['blockquote'] = array( 'words' => 1 );
		$okToInsert = Element_Constraints::meets_minimum_distance_from_elements( true, $context, $args, array() );
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

		$okToInsert = Element_Constraints::meets_minimum_distance_from_elements( true, $context, $args, array() );
		$this->assertFalse( $okToInsert );

		$context['index'] = 3;
		$okToInsert = Element_Constraints::meets_minimum_distance_from_elements( true, $context, $args, array() );
		$this->assertTrue( $okToInsert );

		$args['from_element'] = array(
			'characters' => 450,
			'blockquote' => array(
				'characters' => 1500
			)
		);
		$okToInsert = Element_Constraints::meets_minimum_distance_from_elements( true, $context, $args, array() );
		$this->assertFalse( $okToInsert );

		$args['from_element']['blockquote'] = array( 'characters' => 1 );
		$okToInsert = Element_Constraints::meets_minimum_distance_from_elements( true, $context, $args, array() );
		$this->assertTrue( $okToInsert );
	}

}
