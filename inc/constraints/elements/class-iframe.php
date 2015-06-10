<?php
namespace Speed_Bumps\Constraints\Elements;

class Iframe extends Constraint_Abstract {
	public function can_insert( $paragraph ) {
		if ( false !== stripos( $paragraph, '<iframe' ) ) {
			return false;
		}

		return true;
	}
}
