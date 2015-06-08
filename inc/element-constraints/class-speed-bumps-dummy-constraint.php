<?php
namespace Speed_Bumps\Constraint\Element;

class Speed_Bumps_Dummy_Constraint extends Speed_Bumps_Element_Constraint {
	public function can_insert( $paragraph ) {
		return false;
	}
}
