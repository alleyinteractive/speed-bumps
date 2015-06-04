<?php

class Speed_Bumps_Text_Constraints {
	public static function minimum_content_length( $canInsert, $speed_bump_id, $the_content ) {
		$minimum_length = Speed_Bumps()->get_speed_bump_args( $speed_bump_id )[ 'minimum_content_length' ];
		$minimum_content = apply_filters( 'speed_bumps_minimum_content_length', $minimum_length); 
		
		if ( strlen( $the_content ) < $minimum_content ) {
			$canInsert = false;
		}
    		return $canInsert;
	}

}
