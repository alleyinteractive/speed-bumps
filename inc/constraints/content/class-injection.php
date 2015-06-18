<?php

namespace Speed_Bumps\Constraints\Content;

class Injection {
	public static function did_already_insert_ad( $can_insert, $context, $args, $already_inserted ) {
		if ( self::is_ad_already_inserted_here( $context, $already_inserted ) ) {
			$can_insert = false;
		}
		
		return $can_insert;
	}

	private static function is_ad_already_inserted_here( $context, $already_inserted ) {
		$current_index = $context['index'];
		foreach ( $already_inserted as $index => $element ) {
			if ( $element['index'] === $current_index ) {
				return true;
				break;
			}
		}

		return false;	
	}

}
