<?php

namespace Speed_Bumps\Constraints\Content;

class Injection {
	public static function did_already_insert_ad( $can_insert, $context, $args, $already_inserted ) {

		if ( count( $already_inserted ) > 0 ) {
			$can_insert = false;
		}

		return $can_insert;
	}

}
