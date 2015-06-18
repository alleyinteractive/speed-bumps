<?php

namespace Speed_Bumps\Constraints\Content;

class Injection {

	/**
	 * Has this particular speedbump already been inserted?
	 *
	 */
	public static function did_already_insert_ad( $can_insert, $context, $args, $already_inserted ) {
		if ( in_array( $args['id'], wp_list_pluck( $already_inserted, 'speed_bump_id' ) ) ) {
			$can_insert = false;
		}

		return $can_insert;
	}

	/**
	 * Has another speed bump been inserted at this index?
	 *
	 */
	public static function is_ad_already_inserted_here( $can_insert, $context, $args, $already_inserted ) {
		$current_index = $context['index'];

		foreach ( $already_inserted as $index => $element ) {
			if ( $element['index'] === $current_index ) {
				$can_insert = false;
			}
		}

		return $can_insert;
	}

}
