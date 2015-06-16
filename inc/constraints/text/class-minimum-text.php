<?php
namespace Speed_Bumps\Constraints\Text;

class Minimum_Text {

	public static function minimum_content_length( $canInsert, $context, $args, $alreadyInsertAd ) {

		if ( strlen( $context['the_content'] ) < $args['minimum_content_length'] ) {
			$canInsert = false;
		}

		return $canInsert;
	}

}
