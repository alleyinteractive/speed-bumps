<?php

class Speed_Bumps_Element_Constraints {

	public static function contains_inline_element( $paragraph ) {
		if ( false !== stripos( $paragraph, '<blockquote' ) ) {
			return true;
		}
		
		if ( false !== stripos( $paragraph, '<img' ) ) {
			return true;
		}

		if ( false !== stripos( $paragraph, '<iframe' ) ) {
			return true;
		}

		if ( preg_match_all( '/' . get_shortcode_regex() . '/s', $paragraph, $matches, PREG_SET_ORDER ) ) {
			return true;
		}
		
		return false;
	}

	
}
