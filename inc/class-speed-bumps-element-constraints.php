<?php
namespace Speed_Bumps\Constraint\Element;

class Speed_Bumps_Element_Constraints {

	public static function adj_paragraph_contains_element( $canInsert, $context, $args, $alreadyInsertAd ) {

		$element_constraints = $args[ 'element_constraints' ];

		foreach( $element_constraints as $constraint ) {
			$element_to_check = Speed_Bumps_Element_Factory::build( ucfirst( $constraint ) );
			$prev_paragraph_not_insertable = $element_to_check->contains( $context['prev_paragraph'] );
			$next_paragraph_not_insertable = $element_to_check->contains( $context['next_paragraph'] );

			if ( $prev_paragraph_not_insertable || $next_paragraph_not_insertable ) {
				$canInsert = false;
			}
		}

		return $canInsert;
	}

}
