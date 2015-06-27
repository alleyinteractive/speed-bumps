<?php
namespace Speed_Bumps\Constraints\Text;

class Minimum_Text {

	public static function content_is_long_enough_to_insert( $can_insert, $context, $args, $already_inserted ) {

		if ( strlen( $context['the_content'] ) < $args['minimum_content_length'] ) {
			$can_insert = false;
		}

		return $can_insert;
	}

	/**
	 * Is this point far enough from the end to insert, counting by paragraphs?
	 *
	 * Blocks insertion if the current insertion point is less than the minimum
	 * paragraphs from end value, if "minimum_paragraphs_from_end" is defined
	 * in the speed bump registration arguments.
	 */
	public static function meets_minimum_distance_from_article_end_paragraphs( $can_insert, $context, $args, $already_inserted ) {
		if ( ! isset( $args['minimum_paragraphs_from_end'] ) ) {
			return $can_insert;
		}
		$paragraphs_from_end = $context['total_paragraphs'] - $context['index'] - 1;
		if ( $paragraphs_from_end < intval( $args['minimum_paragraphs_from_end'] ) ) {
			$can_insert = false;
		}

		return $can_insert;
	}

	/**
	 * Is this point far enough from the end to insert, counting by words?
	 *
	 * Blocks insertion if the current insertion point is less than the minimum
	 * number of words from end value, if "minimum_words_from_end" is defined
	 * in the speed bump registration arguments.
	 *
	 * Used in conjunction with `meets_minimum_distance_from_article_end_paragraphs`, 
	 * to account for cases where very short paragraphs are used.
	 */
	public static function meets_minimum_distance_from_article_end_words( $can_insert, $context, $args, $already_inserted ) {
		if ( ! isset( $args['minimum_words_from_end'] ) ) {
			return $can_insert;
		}
		
		$remaining_paragraphs = array_slice( $context['parts'], $context['index'] );
		$remaining_text = implode( ' ', $remaining_paragraphs );
		$remaining_words = array_filter( explode( ' ', $remaining_text ) );

		if ( count( $remaining_words ) < intval( $args['minimum_words_from_end'] ) ) {
			$can_insert = false;
		}

		return $can_insert;
	}
}
