<?php
namespace Speed_Bumps\Constraints\Content;

use Speed_Bumps\Utils\Text;

/**
 * Constraints for inserting speed bumps relating to other speed bumps.
 *
 * This class holds rules for determining speed bump insertion location based
 * on the number of other speed bumps which have already been inserted.
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

class Injection {

	/**
	 * Has this particular speedbump already been inserted?
	 *
	 * Blocks a speed bump from being inserted if it has already been inserted
	 * elsewhere in the document.
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
	 * Blocks a speed bump from being inserted if another speed bump has
	 * already been inserted at the current index of the document.
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

	/**
	 * Is this speed bump far enough away from others to insert here?
	 *
	 * Blocks a speed bump from being inserted if it doesn't mean the
	 * 'minimum_space_from_other_inserts' defined in the speed bumps
	 * registration arguments.
	 */
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
			if ( Text::word_count( $content_since_last_insertion ) < intval( $args['minimum_space_from_other_inserts_words'] ) ) {
				$can_insert = false;
			}
		}

		return $can_insert;
	}
}
