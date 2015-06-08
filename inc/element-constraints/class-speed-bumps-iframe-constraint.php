<?php
namespace Speed_Bumps\Constraint\Element;

class Speed_Bumps_Iframe_Constraint extends Speed_Bumps_Element_Constraint {
	public function can_insert( $paragraph ) {
		if ( false !== stripos( $paragraph, '<iframe' ) ) {
			return false;
		}

		return true;	
	}
}
