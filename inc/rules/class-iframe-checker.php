<?php

require 'vendor/autoload.php';
use Sunra\PhpSimple\HtmlDomParser;

class IFrame_Checker {

	private $content;
	public function __construct( $content ) {
		$this->content = $content;
	}

	public function check() {
		$dom = HtmlDomParser::str_get_html( $this->content );

		$iframes = array();
		$allIframes = array();
		foreach( $dom->find( 'iframe' ) as $iframe) {
			$iframes[] = array(
				'start'	=>	$iframe->tag_start,
				'end'	=>	strpos( $this->content, '</iframe>', $iframe->tag_start )

			);
			$allIframes[] = $iframe;
		}
		return array(
			'hasIFrame'	=>	count( $allIframes ) > 0,
			'elements'	=>	$iframes
		);

		
	}
}
