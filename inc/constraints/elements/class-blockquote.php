<?php
namespace Speed_Bumps\Constraints\Elements;

class Blockquote extends Constraint_Abstract {
	public function can_insert( $paragraph ) {
		if ( false !== stripos( $paragraph, '<blockquote' ) ) {
			return false;
		}

		return true;
	}
}
