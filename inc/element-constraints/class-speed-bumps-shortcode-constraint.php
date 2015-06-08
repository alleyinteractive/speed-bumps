<?php
namespace Speed_Bumps\Constraint\Element;

class Speed_Bumps_Shortcode_Constraint extends Speed_Bumps_Element_Constraint {
	public function can_insert( $paragraph ) {
		if ( preg_match_all( '/' . get_shortcode_regex() . '/s', $paragraph, $matches, PREG_SET_ORDER ) ) {
			return false;
		}

		return true;	
	}
}
