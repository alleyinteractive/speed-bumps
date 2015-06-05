<?php

class Speed_Bumps_Element_Constraints {

	public static function adj_paragraph_contains_element( $canInsert, $context, $args, $alreadyInsertAd ) {

		$element_constraints = $args[ 'element_constraints' ];

		foreach( $element_constraints as $constraint ) {
			if ( false !== stripos( $context['prev_paragraph'], '<' . $contraint ) ||
				 false !== stripos( $context['next_paragraph'], '<' . $contraint ) ) {
				$canInsert = false;
			}
		}

		return $canInsert;
	}

}
