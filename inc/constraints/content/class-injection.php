<?php

namespace Speed_Bumps\Constraints\Content;

class Injection {

	/**
	 * Has this particular speedbump already been inserted?
	 *
	 */
	public static function this_speed_bump_not_already_inserted( $can_insert, $context, $args, $already_inserted ) {
		if ( in_array( $args['id'], wp_list_pluck( $already_inserted, 'speed_bump_id' ) ) ) {
			$can_insert = false;
		}

		return $can_insert;
	}

	/**
	 * Has another speed bump been inserted at this index?
	 *
	 */
	public static function no_speed_bump_inserted_here( $can_insert, $context, $args, $already_inserted ) {
		$current_index = $context['index'];

		foreach ( $already_inserted as $index => $element ) {
			if ( $element['index'] === $current_index ) {
				$can_insert = false;
			}
		}

		return $can_insert;
	}

	public static function minimum_space_from_other_inserts_paragraphs( $can_insert, $context, $args, $already_inserted ) {
		$this_paragraph_index = $context['index'];
		if ( count( $already_inserted ) ) {
			foreach ( $already_inserted as $speed_bump ) {
				if ( $this_paragraph_index - $speed_bump['index'] < $args['minimum_space_from_other_inserts'] ) {
					$can_insert = false;
				}
			}
		}
		return $can_insert;
	}

	public static function minimum_space_from_other_inserts_words( $can_insert, $context, $args, $already_inserted ) {
		if ( ! isset( $args['minimum_space_from_other_inserts_words'] ) ) {
			return $can_insert;
		}
		if ( count( $already_inserted ) ) {
			
			$last_insertion_point = max( wp_list_pluck( $already_inserted, 'index' ) );
			$content_since_last_insertion = array_slice( $context['parts'], $last_insertion_point, $context['index'] - $last_insertion_point );

			$text_since_last_insert = implode( ' ', $content_since_last_insertion );
			$words_since_last_insert = array_filter( explode( ' ', $text_since_last_insert ) );

			if ( count( $words_since_last_insert ) < intval( $args['minimum_space_from_other_inserts_words'] ) ) {
				$can_insert = false;
			}

		}

		return $can_insert;
	}
}
