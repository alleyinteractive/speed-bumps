<?php

class Test_Speed_Bumps extends WP_UnitTestCase {
	public function setUp() {
		parent::setUp();
	}

	/**
	 * The ad is inserted at the end of the content because there's only one paragraph
	 * */
	public function test_algorithm_with_content_more_than_1200() {
		$content = 'Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200Something longer than 1200';

		add_filter( 'speed_bumps_insert_ad', array( $this, 'add_polar' ), 10, 0 );
		
		$newContent = Speed_Bumps::check_and_inject_ad( $content, null );
		$this->assertTrue( $this->endsWith( $newContent, $this->add_polar() ) );
	}

	public function test_algorithm_with_content_less_than_1200() {
		$content = 'less than 1200 character';
		
		add_filter( 'speed_bumps_insert_ad', array( $this, 'add_polar' ), 10, 0 );
		
		$newContent = Speed_Bumps::check_and_inject_ad( $content, null );
		$this->assertFalse( $this->endsWith( $newContent, $this->add_polar() ) );

	}

	/*
	 * The first <p> should exceed 1200 characters and the ads will be inserted in the middle between two paragraphs
	 * */
	public function test_algorithm_with_two_paragraphs() {		
		$content = <<<EOT
	Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.

	Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.

EOT;
		add_filter( 'speed_bumps_insert_ad', array( $this, 'add_polar' ), 10, 0 );
	
		$newContent = Speed_Bumps::check_and_inject_ad( $content, null );
		$this->assertContains( 'Lorem Ipsum.<div id="polar-ad"></div>', $newContent );

	}

	public function test_algorithm_with_custom_global_rule_false() {
		add_filter( 'speed_bumps_global_constraints', '__return_false' );

		$content = <<<EOT
	Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.

	Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.

EOT;

		add_filter( 'speed_bumps_insert_ad', array( $this, 'add_polar' ), 10, 0 );
	
		$newContent = Speed_Bumps::check_and_inject_ad( $content, null );
		$this->assertNotContains( '<div id="polar-ad"></div>', $newContent );

	}

	function add_polar() {
		return '<div id="polar-ad"></div>';
	}

	function endsWith($haystack, $needle)
	{
		$length = strlen($needle);
		if ($length == 0) {
			return true;
		}

	    	return (substr($haystack, -$length) === $needle);
	}



}
