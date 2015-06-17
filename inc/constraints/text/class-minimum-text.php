<?php
namespace Speed_Bumps\Constraints\Text;

class Minimum_Text {

	public static function minimum_content_length( $can_insert, $context, $args, $already_inserted ) {

		if ( strlen( $context['the_content'] ) < $args['minimum_content_length'] ) {
			$can_insert = false;
		}

		return $can_insert;
	}

}
