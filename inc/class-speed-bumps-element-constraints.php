<?php
namespace Speed_Bumps\Constraint\Element;

class Speed_Bumps_Element_Constraints {

	public static function adj_paragraph_contains_element( $canInsert, $context, $args, $alreadyInsertAd ) {

		$element_constraints = $args[ 'element_constraints' ];

		foreach( $element_constraints as $constraint ) {
			$element_to_check = Speed_Bumps_Element_Factory::build( ucfirst( $constraint ) );
			$can_insert_prev_paragraph = $element_to_check->can_insert( $context['prev_paragraph'] );
			$can_insert_next_paragraph = $element_to_check->can_insert( $context['next_paragraph'] );

			if ( !$can_insert_prev_paragraph || !$can_insert_next_paragraph ) {
				$canInsert = false;
			}
		}

		return $canInsert;
	}

}
