<?php
namespace Speed_Bumps\Constraint\Element;

class Speed_Bumps_Iframe_Constraint extends Speed_Bumps_Element_Constraint {
	public function contains( $paragraph ) {
		if ( false !== stripos( $paragraph, '<iframe' ) ) {
			return true;
		}

		return false;	
	}
}
