<?php
namespace Speed_Bumps\Constraint\Element;

class Speed_Bumps_Oembed_Constraint extends Speed_Bumps_Element_Constraint {
	
	public function can_insert( $paragraph ) {
		preg_match_all( '|^\s*(https?://[^\s"]+)\s*$|im', $paragraph, $matches ); 
		foreach ( $matches[1] as $match ) {
			if ( wp_oembed_get( $match ) ) { 
				return false;
			} 
		} 
		return true; 
	}

}
