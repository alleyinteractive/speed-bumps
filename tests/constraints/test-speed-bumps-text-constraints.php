<?php

use Speed_Bumps\Utils\Text;
use Speed_Bumps\Constraints\Text\Minimum_Text;

class Test_Speed_Bumps_Text_Constraints extends WP_UnitTestCase {
	private $speed_bump;

	public function setUp() {
		parent::setUp();

		$this->content = 'Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something

longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something

longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than

1200Something longer than 1200Something longer

than 1200Something longer than 1200';

	}

	public function test_minimum_content_length_words() {
		$context = array(
			'the_content' => $this->content,
		);

		$args = array( 'minimum_content_length' => array( 'words' => 150 ) );
		$ok_to_insert = Minimum_Text::content_is_long_enough_to_insert( true, $context, $args, array() );
		$this->assertTrue( $ok_to_insert );

		$args = array( 'minimum_content_length' => array( 'words' => 180 ) );
		$ok_to_insert = Minimum_Text::content_is_long_enough_to_insert( true, $context, $args, array() );
		$this->assertEquals( Speed_Bumps::return_false_and_remove_all(), $ok_to_insert );
	}

	public function test_minimum_content_length_paragraphs() {
		$context = array(
			'the_content' => $this->content,
		);

		$args = array( 'minimum_content_length' => array( 'paragraphs' => 5 ) );
		$ok_to_insert = Minimum_Text::content_is_long_enough_to_insert( true, $context, $args, array() );
		$this->assertTrue( $ok_to_insert );

		$args = array( 'minimum_content_length' => array( 'paragraphs' => 6 ) );
		$ok_to_insert = Minimum_Text::content_is_long_enough_to_insert( true, $context, $args, array() );
		$this->assertEquals( Speed_Bumps::return_false_and_remove_all(), $ok_to_insert );

	}

	public function test_minimum_content_length_characters() {
		$context = array(
			'the_content' => $this->content,
		);

		$args = array( 'minimum_content_length' => array( 'characters' => 1200 ) );
		$ok_to_insert = Minimum_Text::content_is_long_enough_to_insert( true, $context, $args, array() );
		$this->assertTrue( $ok_to_insert );

		$args = array( 'minimum_content_length' => array( 'characters' => 1600 ) );
		$ok_to_insert = Minimum_Text::content_is_long_enough_to_insert( true, $context, $args, array() );
		$this->assertEquals( Speed_Bumps::return_false_and_remove_all(), $ok_to_insert );
	}

	public function test_meets_minimum_distance_from_start_paragraphs() {

		$args = array( 'from_start' => array( 'paragraphs' => 2 ) );

		$context = array(
			'the_content' => $this->content,
			'parts' => Text::split_paragraphs( $this->content ),
			'total_paragraphs' => 5,
			'index' => 1,
		);

		$ok_to_insert = Minimum_Text::meets_minimum_distance_from_start( true, $context, $args, array() );
		$this->assertTrue( $ok_to_insert );

		$context['index'] = 0;
		$ok_to_insert = Minimum_Text::meets_minimum_distance_from_start( true, $context, $args, array() );
		$this->assertFalse( $ok_to_insert );
	}

	public function test_meets_minimum_distance_from_start_words() {

		$args = array( 'from_start' => array( 'words' => 80 ) );

		$context = array(
			'the_content' => $this->content,
			'parts' => Text::split_paragraphs( $this->content ),
			'total_paragraphs' => 5,
			'index' => 1,
		);

		$ok_to_insert = Minimum_Text::meets_minimum_distance_from_start( true, $context, $args, array() );
		$this->assertTrue( $ok_to_insert );

		$context['index'] = 0;
		$ok_to_insert = Minimum_Text::meets_minimum_distance_from_start( true, $context, $args, array() );
		$this->assertFalse( $ok_to_insert );
	}

	public function test_meets_minimum_distance_from_start_characters() {

		$args = array( 'from_start' => array( 'characters' => 600 ) );

		$context = array(
			'the_content' => $this->content,
			'parts' => Text::split_paragraphs( $this->content ),
			'total_paragraphs' => 5,
			'index' => 1,
		);

		$ok_to_insert = Minimum_Text::meets_minimum_distance_from_start( true, $context, $args, array() );
		$this->assertTrue( $ok_to_insert );

		$context['index'] = 0;
		$ok_to_insert = Minimum_Text::meets_minimum_distance_from_start( true, $context, $args, array() );
		$this->assertFalse( $ok_to_insert );
	}

	public function test_meets_minimum_distance_from_end_paragraphs() {

		$args = array( 'from_end' => array( 'paragraphs' => 2 ) );

		$context = array(
			'the_content' => $this->content,
			'parts' => Text::split_paragraphs( $this->content ),
			'total_paragraphs' => 5,
			'index' => 1,
		);

		$ok_to_insert = Minimum_Text::meets_minimum_distance_from_end( true, $context, $args, array() );
		$this->assertTrue( $ok_to_insert );

		$context['index'] = 4;
		$ok_to_insert = Minimum_Text::meets_minimum_distance_from_end( true, $context, $args, array() );
		$this->assertFalse( $ok_to_insert );
	}

	public function test_meets_minimum_distance_from_end_words() {

		$args = array( 'from_end' => array( 'words' => 30 ) );

		$context = array(
			'the_content' => $this->content,
			'parts' => Text::split_paragraphs( $this->content ),
			'total_paragraphs' => 5,
			'index' => 1,
		);

		$ok_to_insert = Minimum_Text::meets_minimum_distance_from_end( true, $context, $args, array() );
		$this->assertTrue( $ok_to_insert );

		$context['index'] = 4;
		$ok_to_insert = Minimum_Text::meets_minimum_distance_from_end( true, $context, $args, array() );
		$this->assertFalse( $ok_to_insert );
	}

	public function test_meets_minimum_distance_from_end_characters() {

		$args = array( 'from_end' => array( 'characters' => 300 ) );

		$context = array(
			'the_content' => $this->content,
			'parts' => Text::split_paragraphs( $this->content ),
			'total_paragraphs' => 5,
			'index' => 1,
		);

		$ok_to_insert = Minimum_Text::meets_minimum_distance_from_end( true, $context, $args, array() );
		$this->assertTrue( $ok_to_insert );

		$context['index'] = 4;
		$ok_to_insert = Minimum_Text::meets_minimum_distance_from_end( true, $context, $args, array() );
		$this->assertFalse( $ok_to_insert );
	}
}
