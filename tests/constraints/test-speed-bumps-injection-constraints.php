<?php

use Speed_Bumps\Constraints\Content\Injection;
use Speed_Bumps\Utils\Text;

class Test_Speed_Bumps_Injection_Constraints extends WP_UnitTestCase {
	public function setUp() {
		parent::setUp();

		$this->content = 'Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something

longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something

longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than

1200Something longer than 1200Something longer

than 1200Something longer than 1200';

	}

	public function test_less_than_maximum_number_of_inserts() {
		$already_inserted = array(
			array(
				'index' => 0,
				'speed_bump_id' => 'speed_bump1',
				'inserted_content' => 'content1',
			),
		);
		$args = array(
			'id' => 'speed_bump1',
			'maximum_inserts' => 1,
		);

		$okToInsert = Injection::less_than_maximum_number_of_inserts( true, array( 'index' => 2 ), $args, $already_inserted );
		$this->assertFalse( $okToInsert );

		$args['maximum_inserts'] = 2;
		$okToInsert = Injection::less_than_maximum_number_of_inserts( true, array( 'index' => 2 ), $args, $already_inserted );
		$this->assertTrue( $okToInsert );
	}

	public function test_no_speed_bump_inserted_here() {
		$already_inserted = array(
			array(
				'index' => 1,
				'speed_bump_id' => 'speed_bump1',
				'inserted_content' => 'content1',
			),
		);

		$okToInsert = Injection::no_speed_bump_inserted_here( true, array( 'index' => 1 ), array( 'id' => 'speed_bump2' ), $already_inserted );
		$this->assertFalse( $okToInsert );

		$okToInsert = Injection::no_speed_bump_inserted_here( true, array( 'index' => 2 ), array( 'id' => 'speed_bump2' ), $already_inserted );
		$this->assertTrue( $okToInsert );
	}

	public function test_meets_minimum_distance_from_other_inserts_paragraphs() {
		$already_inserted = array(
			array(
				'index' => 0,
				'speed_bump_id' => 'speed_bump1',
				'inserted_content' => 'content1',
			),
		);

		$context = array(
			'the_content' => $this->content,
			'index' => 1,
			'parts' => Text::split_paragraphs( $this->content ),
		);

		$args = array(
			'id' => 'speed_bump2',
			'from_speedbump' => array(
				'paragraphs' => 2,
			),
		);

		$okToInsert = Injection::meets_minimum_distance_from_other_inserts( true, $context, $args, $already_inserted );
		$this->assertFalse( $okToInsert );

		$context['index'] = 3;
		$okToInsert = Injection::meets_minimum_distance_from_other_inserts( true, $context, $args, $already_inserted );
		$this->assertTrue( $okToInsert );

		$args['from_speedbump']['speed_bump1'] = array( 'paragraphs' => 4 );
		$okToInsert = Injection::meets_minimum_distance_from_other_inserts( true, $context, $args, $already_inserted );
		$this->assertFalse( $okToInsert );

		$args['from_speedbump']['speed_bump1'] = array( 'paragraphs' => 1 );
		$okToInsert = Injection::meets_minimum_distance_from_other_inserts( true, $context, $args, $already_inserted );
		$this->assertTrue( $okToInsert );
	}

	public function test_meets_minimum_distance_from_other_inserts_words() {
		$already_inserted = array(
			array(
				'index' => 0,
				'speed_bump_id' => 'speed_bump1',
				'inserted_content' => 'content1',
			),
		);

		$context = array(
			'the_content' => $this->content,
			'index' => 1,
			'parts' => Text::split_paragraphs( $this->content ),
		);

		$args = array(
			'id' => 'speed_bump2',
			'from_speedbump' => array(
				'words' => 80,
			),
		);

		$okToInsert = Injection::meets_minimum_distance_from_other_inserts( true, $context, $args, $already_inserted );
		$this->assertFalse( $okToInsert );

		$context['index'] = 3;
		$okToInsert = Injection::meets_minimum_distance_from_other_inserts( true, $context, $args, $already_inserted );
		$this->assertTrue( $okToInsert );

		$args['from_speedbump']['speed_bump1'] = array( 'words' => 200 );
		$okToInsert = Injection::meets_minimum_distance_from_other_inserts( true, $context, $args, $already_inserted );
		$this->assertFalse( $okToInsert );

		$args['from_speedbump']['speed_bump1'] = array( 'words' => 20 );
		$okToInsert = Injection::meets_minimum_distance_from_other_inserts( true, $context, $args, $already_inserted );
		$this->assertTrue( $okToInsert );
	}

	public function test_meets_minimum_distance_from_other_inserts_characters() {
		$already_inserted = array(
			array(
				'index' => 0,
				'speed_bump_id' => 'speed_bump1',
				'inserted_content' => 'content1',
			),
		);

		$context = array(
			'the_content' => $this->content,
			'index' => 1,
			'parts' => Text::split_paragraphs( $this->content ),
		);

		$args = array(
			'id' => 'speed_bump2',
			'from_speedbump' => array(
				'characters' => 600,
			),
		);

		$okToInsert = Injection::meets_minimum_distance_from_other_inserts( true, $context, $args, $already_inserted );
		$this->assertFalse( $okToInsert );

		$context['index'] = 3;
		$okToInsert = Injection::meets_minimum_distance_from_other_inserts( true, $context, $args, $already_inserted );
		$this->assertTrue( $okToInsert );

		$args['from_speedbump']['speed_bump1'] = array( 'characters' => 1500 );
		$okToInsert = Injection::meets_minimum_distance_from_other_inserts( true, $context, $args, $already_inserted );
		$this->assertFalse( $okToInsert );

		$args['from_speedbump']['speed_bump1'] = array( 'characters' => 50 );
		$okToInsert = Injection::meets_minimum_distance_from_other_inserts( true, $context, $args, $already_inserted );
		$this->assertTrue( $okToInsert );
	}
}
