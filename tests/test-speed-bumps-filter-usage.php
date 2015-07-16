<?php

class Test_Speed_Bumps_Filter_Usage extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();
		$this->speed_bumps = Speed_Bumps();
		$this->speed_bumps->register_speed_bump( 'speed_bump_test', array(
			'string_to_inject' => function() { return '<div id="speed-bump-test"></div>'; },
			'element_constraints' => array(),
			'paragraph_offset' => 1
		));

		add_filter( 'the_content', function( $content ) {
			return apply_filters( 'speed_bumps_inject_content', $content );
		}, 1 );
	}

	public function tearDown() {
		parent::tearDown();
		$this->speed_bumps->clear_speed_bump( 'speed_bump_test' );
	}

	public function test_speed_bump_filter_usage() {
		$content = 'First paragraph

A long paragraph, full of turns and twists. <!--lorem-->Ea commodo consequat. Duis splople autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Typi non habent claritatem insitam; est usus legentis in iis qui facit eorum claritatem. Investigationes demonstraverunt lectores legere me lius quod ii legunt saepius. Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum. Mirum.

Second paragraph.

Third paragraph.

Fourth paragraph';
		$post_id = $this->factory->post->create( array( 'post_content' => $content ) );
		$post = get_post( $post_id );
		$this->assertContains( '<div id="speed-bump-test"></div>', apply_filters( 'the_content', $post->post_content ) );
	}
}