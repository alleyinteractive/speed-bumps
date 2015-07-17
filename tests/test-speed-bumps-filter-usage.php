<?php

class Test_Speed_Bumps_Filter_Usage extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();
		$this->speed_bumps = Speed_Bumps();
	}

	public function tearDown() {
		parent::tearDown();
		$this->speed_bumps->clear_speed_bump( 'speed_bump_test' );
	}

	public function test_speed_bump_filter_usage() {
		register_speed_bump( 'speed_bump_test', array(
			'string_to_inject' => function() { return '<div id="speed-bump-test"></div>'; },
			'minimum_content_length' => false,
			'from_start' => false,
			'from_end' => false,
		));
		add_filter( 'the_content', 'insert_speed_bumps' );

		$content = 'At a recent dinner, a friend confided that she was spending her masturbation sessions with a new lover: porn GIFs. “They’re incredible,” she said. I didn’t get it give you up.

I know what a GIF is—four to six seconds of silent video looped. That seems like the perfect amount of time to capture a kitten falling into a garbage can or Tina Fey rolling her eyes, but the notion that one moving image could translate into a satisfactory—nay, “incredible”—sexual experience? Well, that didn’t compute. So she passed me her phone.

And then, I was gone.

While GIFs may seem like a flash in the pan—really, how can four seconds turn you on?—the nature of the loop actually allows the viewer to spend an elongated amount of time taking in the presented scenario. GIFs give the viewer time to notice the caress of a hand floating from neck to shoulder to forearm, the tensing of an abdomen, the arching of a back, and the reflex of a thigh. After a few loops, you may find yourself empathizing with the players involved. Maybe you can even feel what they’re feeling.

Of course, porn GIFs don’t appeal only to women, but the “microporn” does appear to have struck a unique chord with the ladies, according to Hester—an audience known to feel alienated by mainstream porn, historically geared toward men. Spend five minutes on Tumblr, and you’ll find yourself sucked into a pulsing subculture of porn GIFs curated for women, living on pages like Porn-Gifs-For-Women and YummyPornForGirls. GIFs that almost exclusively spotlight erotic female pleasure.';
		$post_id = $this->factory->post->create( array( 'post_content' => $content ) );
		$post = get_post( $post_id );
		$this->assertContains( '<div id="speed-bump-test"></div>', apply_filters( 'the_content', $post->post_content ) );
	}
}
