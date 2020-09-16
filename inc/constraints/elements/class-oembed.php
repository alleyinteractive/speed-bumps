<?php
namespace Speed_Bumps\Constraints\Elements;

class Oembed extends Constraint_Abstract {

	public function paragraph_not_contains_element( $paragraph ) {
		$wp_oembed = _wp_oembed_get_object();
		preg_match_all( '|^\s*(https?://[^\s"]+)\s*$|im', $paragraph, $matches );
		foreach ( $matches[1] as $match ) {
			if ( $wp_oembed->get_provider( $match, array( 'discover' => false ) ) ) {
				return false;
			}
		}
		return true;
	}

}
