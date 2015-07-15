<?php
use Speed_Bumps\Utils\Comparison;

class Test_Speed_Bumps_Utils_Comparison extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();
	}


	public function test_less_than_characters() {

		$fifty_characters = 'Aenean metus. Vestibulum ac lacus. Vivamus portti';

		$this->assertFalse( Comparison::content_less_than( 'characters', 49, $fifty_characters ) );
		$this->assertTrue( Comparison::content_less_than( 'characters', 50, $fifty_characters ) );

	}

	public function test_less_than_words() {

		$fifty_words = 'Nam venenatis neque quis mauris. Proin felis. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Aliquam quam. Nam felis velit, semper nec, aliquam nec, iaculis vel, mi. Nullam et augue vitae nunc tristique vehicula. Suspendisse eget elit. Duis adipiscing dui quam. Duis posuere tortor sit amet.';

		$this->assertFalse( Comparison::content_less_than( 'words', 50, $fifty_words ) );
		$this->assertTrue( Comparison::content_less_than( 'words', 51, $fifty_words ) );

	}

	public function test_less_than_paragraphs() {

		$four_paragraphs = 'Nam venenatis neque quis mauris. Proin felis.

Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.

Aliquam quam. Nam felis velit, semper nec, aliquam nec, iaculis vel, mi. Nullam et augue vitae nunc tristique vehicula. Suspendisse eget elit.

Duis adipiscing dui quam. Duis posuere tortor sit amet.';

		$this->assertFalse( Comparison::content_less_than( 'paragraphs', 4, $four_paragraphs ) );
		$this->assertTrue( Comparison::content_less_than( 'paragraphs', 5, $four_paragraphs ) );

	}

	public function test_less_than_one_paragraph() {
		$paragraph = array(
			'Nam venenatis neque quis mauris. Proin felis. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Aliquam quam. Nam felis velit, semper nec, aliquam nec, iaculis vel, mi. Nullam et augue vitae nunc tristique vehicula. Suspendisse eget elit. Duis adipiscing dui quam. Duis posuere tortor sit amet.'
		);

		$this->assertFalse( Comparison::content_less_than( 'paragraphs', 1, $paragraph ) );
	}
}

