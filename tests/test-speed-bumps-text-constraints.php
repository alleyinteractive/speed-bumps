<?php

class Test_Speed_Bumps_Text_Constraints extends WP_UnitTestCase {
	private $speed_bump;

	public function setUp() {
		parent::setUp();
		$this->speed_bumps = Speed_Bumps();
	}

	public function test_if_content_has_more_than_1200() {
		$content = 'Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200';

		$context = array(
			'the_content' => $content,
		);
		$args = array(
			'minimum_content_length' => 1200,
		);
		$okToInsert = \Speed_Bumps\Constraints\Text\Minimum_Text::content_is_long_enough_to_insert( true, $context, $args, array() );

		$this->assertTrue( $okToInsert );

	}

	public function test_if_content_has_more_than_10_by_changing_filter() {
		$content = 'text';

		$context = array(
			'the_content' => $content,
		);
		$args = array(
			'minimum_content_length' => 1,
		);

		$okToInsert = \Speed_Bumps\Constraints\Text\Minimum_Text::content_is_long_enough_to_insert( true, $context, $args, array() );

		$this->assertTrue( $okToInsert );

	}

	public function test_if_content_has_less_than_1000_by_changing_filter() {
		$content = 'test';
		$context = array(
			'the_content' => $content,
		);
		$args = array(
			'minimum_content_length' => 1000,
		);

		$okToInsert = \Speed_Bumps\Constraints\Text\Minimum_Text::content_is_long_enough_to_insert( true, $context, $args, array() );

		$this->assertFalse( $okToInsert );
	}

	public function test_content_too_close_to_end_paragraphs() {

		$args = array( 'minimum_paragraphs_from_end' => 2 );

		$context = array(
			'total_paragraphs' => 4,
			'index' => 1,
		);

		$okToInsert = \Speed_Bumps\Constraints\Text\Minimum_Text::meets_minimum_distance_from_article_end_paragraphs( true, $context, $args, array() );
		$this->assertTrue( $okToInsert );

		$context['index'] = 3;
		$okToInsert = \Speed_Bumps\Constraints\Text\Minimum_Text::meets_minimum_distance_from_article_end_paragraphs( true, $context, $args, array() );
		$this->assertFalse( $okToInsert );
	}

	public function test_content_too_close_to_end_words() {

		$args = array( 'minimum_words_from_end' => 75 );

		$content = 'First paragraph

A long paragraph, full of turns and twists. <!--lorem-->Ea commodo consequat. Duis splople autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Typi non habent claritatem insitam; est usus legentis in iis qui facit eorum claritatem. Investigationes demonstraverunt lectores legere me lius quod ii legunt saepius. Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum. Mirum.

Second paragraph.

Third paragraph.

Fourth paragraph';

		$context = array(
			'index' => 1,
			'total_paragraphs' => 5,
			'parts' => preg_split( '/\n\s*\n/', $content ),
		);

		$okToInsert = \Speed_Bumps\Constraints\Text\Minimum_Text::meets_minimum_distance_from_article_end_words( true, $context, $args, array() );
		$this->assertTrue( $okToInsert );

		$context['index'] = 2;
		$okToInsert = \Speed_Bumps\Constraints\Text\Minimum_Text::meets_minimum_distance_from_article_end_words( true, $context, $args, array() );
		$this->assertFalse( $okToInsert );
	}
}
