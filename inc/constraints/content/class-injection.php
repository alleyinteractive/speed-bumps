<?php
namespace Speed_Bumps\Constraints\Content;

use Speed_Bumps\Utils\Comparison;
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
	 * distance defined in the speed bump's 'from_speedbump' registration
	 * arguments.
	 */
	public static function meets_minimum_distance_from_other_inserts( $can_insert, $context, $args, $already_inserted ) {
		if ( ! isset( $args['from_speedbump'] ) ) {
			return $can_insert;
		}

		$distance_constraints = array_intersect( array( 'paras' => 1, 'words' => null, 'chars' => null ), $args['from_speedbump'] );

		$this_paragraph_index = $context['index'];

		if ( count( $already_inserted ) ) {
			foreach ( $already_inserted as $speed_bump ) {

				$distance = Text::content_between_points( $speed_bump['index'], $context['index'] );
				foreach( array( 'paras', 'words', 'chars' ) as $unit ) {
					$constraint = ( isset( $args['from_speedbump'][ $speed_bump['id'] ] ) &&
							isset( $args['from_speedbump'][ $speed_bump['id'] ][ $unit ] ) ?
						$args['from_speedbump'][ $speed_bump['id'] ][ $unit ] :
							isset( $args['from_speedbump'][ $unit ] ) ?
								 $args['from_speedbump'][ $unit ] : false;
					if ( $constraint && Comparison::content_less_than( $unit, $constraint, $distance ) ) {
						$can_insert = false;
					}
				}
			}
		}

		return $can_insert;
	}

}
