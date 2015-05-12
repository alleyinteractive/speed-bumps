<?php

require 'vendor/autoload.php';
class IFrame_Checker {

	private $content;
	public function __construct( $content ) {
		$this->content = $content;
	}

	public function check() {
		
		$ofAllIFrames = qp( $this->content, 'iframe' );

		$iframes = array();
		$startTag = 0;
		foreach( $ofAllIFrames as $iframe) {
			$startCurrentTag = strpos( $this->content, '<iframe', $startTag );
			$endCurrentTag = strpos( $this->content, '</iframe>', $startCurrentTag );
			$iframes[] = array(
				'start'	=>	$startCurrentTag,
				'end'	=>	$endCurrentTag
			);
			$startTag = $startCurrentTag + 1;
			
		}
		return array(
			'hasIFrame'	=>	count( $iframes ) > 0,
			'elements'	=>	$iframes
		);
		 
	}
}
