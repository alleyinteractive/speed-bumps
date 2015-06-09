<?php
namespace Speed_Bumps\Constraints\Elements;

class Strong extends Constraint_Abstract{
	public function can_insert( $paragraph ) {
		if ( false !== stripos( $paragraph, '<strong' ) ) {
			return false;
		}
		return true;
	}
}
