<?php
namespace Speed_Bumps\Constraints\Elements;

class Header extends Constraint_Abstract {
	public function can_insert( $paragraph ) {
		if ( 1 === preg_match( '/<h[1-6]|eader/', $paragraph, $matches, PREG_OFFSET_CAPTURE ) ) {
			return false;
		}
		return true;
	}
}
