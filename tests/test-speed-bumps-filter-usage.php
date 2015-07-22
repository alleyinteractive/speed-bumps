<?php

class Test_Speed_Bumps_Filter_Usage extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();
		add_filter( 'the_content', 'insert_speed_bumps', 1 );
	}

	public function test_speed_bump_filter_usage() {
		/* Removing all default filters so we can test placement */
		remove_all_filters( 'the_content', 10 );
		register_speed_bump( 'speed_bump_test', array(
			'string_to_inject' => function() { return '<div id="speed-bump-test"></div>'; },
		));
		$this->assertSpeedBumpAtParagraph( apply_filters( 'the_content', $this->get_dummy_content() ), 5, '<div id="speed-bump-test"></div>' );
		clear_speed_bump( 'speed_bump_test' );
	}

	public function test_speed_bump_filter_usage_needy() {
		/* Removing all default filters so we can test placement */
		remove_all_filters( 'the_content', 10 );
		register_speed_bump( 'needy_rickroll', array(
			'string_to_inject' => function() { return '<iframe width="560" height="315" src="https://www.youtube.com/embed/YwlVgpXXJS0" frameborder="0" allowfullscreen></iframe>'; },
			'from_start' => 0,
		));
		$this->assertSpeedBumpAtParagraph( apply_filters( 'the_content', $this->get_dummy_content() ), 2, '<iframe width="560" height="315" src="https://www.youtube.com/embed/YwlVgpXXJS0" frameborder="0" allowfullscreen></iframe>' );
		clear_speed_bump( 'needy_rickroll' );
	}

	public function test_speed_bump_filter_usage_with_default_filters() {
		register_speed_bump( 'speed_bump_test', array(
			'string_to_inject' => function() { return '<div id="speed-bump-test"></div>'; },
		));
		$this->assertContains( '<div id="speed-bump-test"></div>', apply_filters( 'the_content', $this->get_dummy_content() ) );
		clear_speed_bump( 'speed_bump_test' );
	}

	public function test_rickroll_example_with_filter() {
		register_speed_bump( 'rickroll', array(
			'string_to_inject' => function() { return '<iframe width="420" height="315" src="https://www.youtube.com/embed/dQw4w9WgXcQ" frameborder="0" allowfullscreen></iframe>'; },
			'minimum_content_length' => false,
			'from_start' => false,
			'from_end' => false,
		) );
		$this->assertContains( '<iframe width="420" height="315" src="https://www.youtube.com/embed/dQw4w9WgXcQ" frameborder="0" allowfullscreen></iframe>', apply_filters( 'the_content', $this->get_dummy_content() ) );
		clear_speed_bump( 'rickroll' );
	}

	public function test_rickroll_example_with_insert() {
		register_speed_bump( 'rickroll', array(
			'string_to_inject' => function() { return '<video>This is the best video imaginable</video>'; },
			'minimum_content_length' => false,
			'from_start' => false,
			'from_end' => false,
		) );
		$this->assertSpeedBumpAtParagraph( insert_speed_bumps( $this->get_dummy_content() ), 2, '<video>This is the best video imaginable</video>' );
		clear_speed_bump( 'rickroll' );
	}

	public function test_rickroll_example_with_two_filters() {
		register_speed_bump( 'rickroll', array(
			'string_to_inject' => function() { return '<video>This is the worst video imaginable</video>'; },
			'minimum_content_length' => false,
			'from_start' => false,
			'from_end' => false,
		) );

		add_filter( 'speed_bumps_rickroll_constraints', 'give_you_up', 10, 4 );

		function give_you_up( $can_insert, $context, $args, $already_inserted ) {
			if ( ! preg_match( '/give [^ ]+ up/i', $context['prev_paragraph'] ) ) {
				$can_insert = false;
			}
			return $can_insert;
		}

		$this->assertContains( '<video>This is the worst video imaginable</video>', insert_speed_bumps( $this->get_rick_rolly_content() ) );
		$this->assertNotContains( '<video>This is the worst video imaginable</video>', insert_speed_bumps( $this->get_dummy_content() ) );
		clear_speed_bump( 'rickroll' );
	}

	public function test_rickroll_example_with_two_filters_and_removal() {
		register_speed_bump( 'rickroll', array(
			'string_to_inject' => function() { return '<video>This is the most mediocre video imaginable</video>'; },
			'minimum_content_length' => false,
			'from_start' => false,
			'from_end' => false,
		) );

		$non_rick_roll_id = $this->factory->post->create( array( 'post_content' => $this->get_dummy_content() ) );
		$non_rick_roll_post = get_post( $non_rick_roll_id );

		$rick_roll_id = $this->factory->post->create( array( 'post_content' => $this->get_rick_rolly_content() ) );
		$rick_roll_post = get_post( $non_rick_roll_id );

		add_filter( 'speed_bumps_rickroll_constraints', 'let_you_go', 10, 4 );

		function let_you_go( $can_insert, $context, $args, $already_inserted ) {
			if ( ! preg_match( '/give [^ ]+ up/i', $context['prev_paragraph'] ) ) {
				$can_insert = false;
			}
			return $can_insert;
		}

		add_filter( 'speed_bumps_rickroll_constraints', '__return_false' );

		$this->assertNotContains( '<video>This is the most mediocre video imaginable</video>', insert_speed_bumps( $rick_roll_post->post_content ) );
		$this->assertNotContains( '<video>This is the most mediocre video imaginable</video>', insert_speed_bumps( $non_rick_roll_post->post_content ) );
		clear_speed_bump( 'rickroll' );
	}

	public function test_rickroll_example_with_two_filters_and_removal_at_lower_priority() {
		register_speed_bump( 'rickroll', array(
			'string_to_inject' => function() { return '<video>This is the most mediocre video imaginable</video>'; },
			'minimum_content_length' => false,
			'from_start' => false,
			'from_end' => false,
		) );

		$non_rick_roll_id = $this->factory->post->create( array( 'post_content' => $this->get_dummy_content() ) );
		$non_rick_roll_post = get_post( $non_rick_roll_id );

		$rick_roll_id = $this->factory->post->create( array( 'post_content' => $this->get_rick_rolly_content() ) );
		$rick_roll_post = get_post( $non_rick_roll_id );

		add_filter( 'speed_bumps_rickroll_constraints', 'desert_you', 10, 4 );

		function desert_you( $can_insert, $context, $args, $already_inserted ) {
			if ( ! preg_match( '/give [^ ]+ up/i', $context['prev_paragraph'] ) ) {
				$can_insert = false;
			}
			return $can_insert;
		}

		add_filter( 'speed_bumps_rickroll_constraints', '__return_false', 9 );

		$this->assertNotContains( '<video>This is the most mediocre video imaginable</video>', insert_speed_bumps( $rick_roll_post->post_content ) );
		$this->assertNotContains( '<video>This is the most mediocre video imaginable</video>', insert_speed_bumps( $non_rick_roll_post->post_content ) );
		clear_speed_bump( 'rickroll' );
	}

	private function get_dummy_content() {
		$content = <<<EOT
Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.

Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.

A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth.

Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.

The Big Oxmox advised her not to do so, because there were thousands of bad Commas, wild Question Marks and devious Semikoli, but the Little Blind Text didn’t listen.

She packed her seven versalia, put her initial into the belt and made herself on the way.

When she reached the first hills of the Italic Mountains, she had a last view back on the skyline of her hometown Bookmarksgrove, the headline of Alphabet Village and the subline of her own road, the Line Lane.

Pityful a rethoric question ran over her cheek, then she continued her way. On her way she met a copy. The copy warned the Little Blind Text, that where it came from it would have been rewritten a thousand times and everything that was left from its origin would be the word "and" and the Little Blind Text should
EOT;
		return $content;
	}

	private function get_rick_rolly_content() {
		$content = <<<EOT
Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.

Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.

A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth.

Even the all-powerful Pointing has no control to give it up about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.

The Big Oxmox advised her not to do so, because there were thousands of bad Commas, wild Question Marks and devious Semikoli, but the Little Blind Text didn’t listen.

She packed her seven versalia, put her initial into the belt and made herself on the way.

When she reached the first hills of the Italic Mountains, she had a last view back on the skyline of her hometown Bookmarksgrove, the headline of Alphabet Village and the subline of her own road, the Line Lane.

Pityful a rethoric question ran over her cheek, then she continued her way. On her way she met a copy. The copy warned the Little Blind Text, that where it came from it would have been rewritten a thousand times and everything that was left from its origin would be the word "and" and the Little Blind Text should
EOT;
		return $content;
	}

	private function assertSpeedBumpAtParagraph( $content_to_test, $speed_bump_paragraph, $injected_string ) {
		$parts = preg_split( '/\n\s*\n/', $content_to_test );
		$actual_speed_bump_paragraph = array_search( $injected_string, $parts );

		if ( false === $actual_speed_bump_paragraph ) {
			$this->fail( 'The speed bump is not in the content' );
		}

		$this->assertEquals( $speed_bump_paragraph, ++$actual_speed_bump_paragraph );

	}
}
