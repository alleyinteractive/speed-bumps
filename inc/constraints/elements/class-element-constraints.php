<?php
namespace Speed_Bumps\Constraints\Elements;

class Element_Constraints {

	public static function adj_paragraph_not_contains_element( $can_insert, $context, $args, $already_inserted ) {

		$element_constraints = $args['element_constraints'];

		foreach ( $element_constraints as $constraint ) {
			$element_to_check = Factory::build( ucfirst( $constraint ) );
			$can_insert_prev_paragraph = $element_to_check->paragraph_not_contains_element( $context['prev_paragraph'] );
			$can_insert_next_paragraph = $element_to_check->paragraph_not_contains_element( $context['next_paragraph'] );

			if ( ! $can_insert_prev_paragraph || ! $can_insert_next_paragraph ) {
				$can_insert = false;
			}
		}

		return $can_insert;
	}

}
