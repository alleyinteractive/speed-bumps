<?php
namespace Speed_Bumps\Constraint\Element;

class Speed_Bumps_Image_Constraint extends Speed_Bumps_Element_Constraint {
	public function contains( $paragraph ) {
		if ( false !== stripos( $paragraph, '<img' ) ) {
			return true;
		}

		return false;	
	}
}
