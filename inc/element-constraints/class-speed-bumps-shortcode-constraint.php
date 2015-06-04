<?php

class Speed_Bumps_Shortcode_Constraint extends Speed_Bumps_Element_Constraint {
	public function contains( $paragraph ) {
		if ( preg_match_all( '/' . get_shortcode_regex() . '/s', $paragraph, $matches, PREG_SET_ORDER ) ) {
			return true;
		}

		return false;	
	}
}
