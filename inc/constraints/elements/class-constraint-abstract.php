<?php
namespace Speed_Bumps\Constraints\Elements;

abstract class Constraint_Abstract {
	abstract function can_insert( $paragraph );
}
