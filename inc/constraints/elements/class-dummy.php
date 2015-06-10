<?php
namespace Speed_Bumps\Constraints\Elements;

class Dummy extends Constraint_Abstract {
	public function can_insert( $paragraph ) {
		return false;
	}
}
