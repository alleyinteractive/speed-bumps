<?php

class Speed_Bumps_Text_Constraints {

	public static function minimum_content_length( $canInsert, $context, $args ) {

		if ( strlen( $context['the_content'] ) < $args['minimum_content'] ) {
			$canInsert = false;
		}

		return $canInsert;
	}

	public static function did_already_insert_ad( $canInsert, $context, $args, $alreadyInsertAd ) {

		if ( count( $alreadyInsertAd ) > 0 ) {
			$canInsert = false;
		}

		return $canInsert;
	}

}
