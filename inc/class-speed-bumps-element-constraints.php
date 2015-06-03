<?php

class Speed_Bumps_Element_Constraints {

	public static function contains_blockquote( $paragraph ) {
		if ( false !== stripos( $paragraph, '<blockquote' ) ) {
			return true;
		}

		return false;
	}

	public static function contains_image( $paragraph ) {
		if ( false !== stripos( $paragraph, '<img' ) ) {
			return true;
		}

		return false;
	}

	public static function contains_iframe( $paragraph ) {
		if ( false !== stripos( $paragraph, '<iframe' ) ) {
			return true;
		}

		return false;
	}

	public static function contains_embed( $paragraph ) {
		if ( preg_match_all( '/' . get_shortcode_regex() . '/s', $paragraph, $matches, PREG_SET_ORDER ) ) {
			return true;
		}
		
		return false;
	}

	public static function contains_blank( $paragraph ) {
		if ( trim( $paragraph ) === '' ) {
			return true;
		}
		return false;
	}

	public static function contains_twitter( $paragraph ) {
		if ( false !== stripos( $paragraph, 'https://twitter.com' ) ) {
			return true;
		}

		return false;
	}

	public static function contains_video( $paragraph ) {
		if ( false !== stripos( $paragraph, 'https://www.youtube.com/watch' ) ) {
			return true;
		}

		return false;

	}

	public static function contains_vine( $paragraph ) {
		if ( false !== stripos( $paragraph, 'https://vine.co/v' ) ) {
			return true;
		}	
		
		return false;
	}
}
