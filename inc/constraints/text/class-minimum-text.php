<?php
namespace Speed_Bumps\Constraints\Text;

use Speed_Bumps\Utils\Text;

/**
 * Constraints for inserting speed bumps relating to text length.
 *
 * This class holds rules for determining speed bump insertion location based
 * on the length of the body of text as a whole.
 *
 * As with all constraint rules, each rule receieves four arguments as parameters.
 *
 * @param bool $can_insert Logic as returned from other constraint rules.
 * @param array $context Context surround the current point in the document
 * @param array $args Arguments provided in definition of this speed bump
 * @param array $already_inserted array of speed bumps which have already been inserted
 *
 * @return bool True indicates that it is allowable (based on this rule) to insert here, false blocks insertion.
 */
class Minimum_Text {

	/**
	 * Is the text long enough to insert this speed bump anywhere?
	 *
	 * Blocks insertion if the content being processed is shorter (in character
	 * length) than the "minimum_content_length" defined in speed bump
	 * registration arguments.
	 */
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

		if ( Text::word_count( $remaining_paragraphs ) < intval( $args['minimum_words_from_end'] ) ) {
			$can_insert = false;
		}

		return $can_insert;
	}
}
