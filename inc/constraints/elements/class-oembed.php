<?php
namespace Speed_Bumps\Constraints\Elements;

class Oembed extends Constraint_Abstract {

	public function paragraph_not_contains_element( $paragraph ) {
		preg_match_all( '|^\s*(https?://[^\s"]+)\s*$|im', $paragraph, $matches );
		foreach ( $matches[1] as $match ) {
			if ( wp_oembed_get( $match ) ) {
				return false;
			}
		}
		return true;
	}

}
