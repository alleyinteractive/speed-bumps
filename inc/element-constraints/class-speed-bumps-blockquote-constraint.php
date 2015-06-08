<?php
namespace Speed_Bumps\Constraint\Element;

class Speed_Bumps_Blockquote_Constraint extends Speed_Bumps_Element_Constraint {
	public function can_insert( $paragraph ) {
		if ( false !== stripos( $paragraph, '<blockquote' ) ) {
			return false;
		}

		return true;	
	}
}
