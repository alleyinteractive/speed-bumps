<?php

class Test_Speed_Bumps_Utils extends WP_UnitTestCase {


	public function setUp() {
		parent::setUp();
	}

	public function test_word_count() {

		$fifty_words = '

This is a sentence with 50 words. It might have <em>tags</em> or <br /> special characters. It might even use  double   or   more   spaces between words.
It has line breaks.

And double-line breaks for paragraphs...  --- That counts as a word too. So does this: &amp; :simple_smile: <mark>44</mark> 45 46 47 48 49 50.';

		$wc = \Speed_Bumps\Utils\Text::word_count( $fifty_words );

		$this->assertEquals( 50, $wc );
	}

}
