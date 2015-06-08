<?php

namespace Speed_Bumps\Constraint\Content;

class Injection {
	public static function did_already_insert_ad( $canInsert, $context, $args, $alreadyInsertAd ) {

		if ( count( $alreadyInsertAd ) > 0 ) {
			$canInsert = false;
		}

		return $canInsert;
	}

}
