<?php

class Speed_Bumps_Blockquote_Constraint extends Speed_Bumps_Element_Constraint {
	public function contains( $paragraph ) {
		if ( false !== stripos( $paragraph, '<blockquote' ) ) {
			return true;
		}

		return false;	
	}
}
