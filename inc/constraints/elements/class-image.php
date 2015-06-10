<?php
namespace Speed_Bumps\Constraints\Elements;

class Image extends Constraint_Abstract {
	public function can_insert( $paragraph ) {
		if ( false !== stripos( $paragraph, '<img' ) ) {
			return false;
		}

		return true;
	}
}
