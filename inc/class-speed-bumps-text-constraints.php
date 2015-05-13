<?php

class Speed_Bumps_Text_Constraints {
	public static function minimum_content_length( $canInsert, $the_content ) {

		$minimum_content = apply_filters( 'speed_bumps_arguments', 'Speed_Bumps_Text_Constraints::speed_bumps_minimum_content_length', 1200 ); 
		
		if ( strlen( $the_content ) < $minimum_content ) {
			$canInsert = false;
		}
    		return $canInsert;
	}

	public static function speed_bumps_minimum_content_length( $minimum_content ) {
		return $minimum_content;
	}

}
