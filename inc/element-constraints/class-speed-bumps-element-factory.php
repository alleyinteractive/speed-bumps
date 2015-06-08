<?php
namespace Speed_Bumps\Constraint\Element;

class Speed_Bumps_Element_Factory {
	
	public static function build( $type ) {
		$element_constraint = "\Speed_Bumps\Constraint\Element\Speed_Bumps_" . $type . '_Constraint';

		if ( class_exists( $element_constraint ) ) {
			return new $element_constraint();
		}
		else {
		}
	} 
}
