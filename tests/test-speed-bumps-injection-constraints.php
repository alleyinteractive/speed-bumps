<?php

class Test_Speed_Bumps_Injection_Constraints extends WP_UnitTestCase {
	public function setUp() {
		parent::setUp();
	}

	public function test_ad_already_inserted() {
		$already_inserted = array(
			array(
				'index' => 0,
				'speed_bump_id' => 'speed_bump1',
				'inserted_content' => 'content1',
			),
		);
		$ad_did_inserted = \Speed_Bumps\Constraints\Content\Injection::this_speed_bump_not_already_inserted( true, array( 'index' => 2 ), array( 'id' => 'speed_bump1' ), $already_inserted );

		$this->assertFalse( $ad_did_inserted );
	}

	public function test_ad_not_already_inserted() {
		$already_inserted = array();
		$ad_did_not_insert = \Speed_Bumps\Constraints\Content\Injection::this_speed_bump_not_already_inserted( true, array( 'index' => 0 ), array( 'id' => 'speed_bump1' ), array() );

		$this->assertTrue( $ad_did_not_insert );

	}

	public function test_ad_already_inserted_here() {
		$already_inserted = array(
			array(
				'index' => 1,
				'speed_bump_id' => 'speed_bump1',
				'inserted_content' => 'content1',
			),
		);
		$ad_did_inserted = \Speed_Bumps\Constraints\Content\Injection::no_speed_bump_inserted_here( true, array( 'index' => 1 ), array( 'id' => 'speed_bump2' ), $already_inserted );

		$this->assertFalse( $ad_did_inserted );
	}

	public function test_ad_not_already_inserted_here() {
		$already_inserted = array(
			array(
				'index' => 0,
				'speed_bump_id' => 'speed_bump1',
				'inserted_content' => 'content1',
			),
		);
		$ad_did_inserted = \Speed_Bumps\Constraints\Content\Injection::no_speed_bump_inserted_here( true, array( 'index' => 1 ), array( 'id' => 'speed_bump2' ), $already_inserted );

		$this->assertTrue( $ad_did_inserted );
	}

	public function test_ad_cannot_be_inserted_after_centain_paragraph() {
		$already_inserted = array(
			array(
				'index' => 0,
				'speed_bump_id' => 'speed_bump1',
				'inserted_content' => 'content1',
			),
		);
		$ad_did_inserted = \Speed_Bumps\Constraints\Content\Injection::minimum_space_from_other_inserts_paragraphs( true, array( 'index' => 1 ), array( 'id' => 'speed_bump2', 'minimum_space_from_other_inserts' => 4 ), $already_inserted );

		$this->assertFalse( $ad_did_inserted );
	}

	public function test_ad_can_be_inserted_after_centain_paragraph() {
		$already_inserted = array(
			array(
				'index' => 0,
				'speed_bump_id' => 'speed_bump1',
				'inserted_content' => 'content1',
			),
		);
		$ad_did_inserted = \Speed_Bumps\Constraints\Content\Injection::minimum_space_from_other_inserts_paragraphs( true, array( 'index' => 5 ), array( 'id' => 'speed_bump2', 'minimum_space_from_other_inserts' => 4 ), $already_inserted );

		$this->assertTrue( $ad_did_inserted );
	}

	public function test_minimum_words_from_previous_insertion() {

		$args = array( 'minimum_space_from_other_inserts_words' => 75 );

		$content = 'First paragraph

Second paragraph.

Third paragraph.

A long paragraph, full of turns and twists. <!--lorem-->Ea commodo consequat. Duis splople autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Typi non habent claritatem insitam; est usus legentis in iis qui facit eorum claritatem. Investigationes demonstraverunt lectores legere me lius quod ii legunt saepius. Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum. Mirum.

Fourth paragraph';

		$context = array(
			'index' => 2,
			'total_paragraphs' => 5,
			'parts' => preg_split( '/\n\s*\n/', $content ),
		);

		$already_inserted = array(
			array(
				'index' => 0,
				'speed_bump_id' => 'speed_bump1',
				'inserted_content' => 'content1',
			),
		);

		$okToInsert = \Speed_Bumps\Constraints\Content\Injection::minimum_space_from_other_inserts_words( true, $context, $args, $already_inserted );
		$this->assertFalse( $okToInsert );

		$context['index'] = 4;
		$okToInsert = \Speed_Bumps\Constraints\Content\Injection::minimum_space_from_other_inserts_words( true, $context, $args, $already_inserted );
		$this->assertTrue( $okToInsert );

	}
}
