<?php
namespace Speed_Bumps\Utils;

class Comparison {

	public static function content_less_than( $unit, $measurement, $content ) {
		switch ( $unit ) {
		case 'paras':
			return self::less_than_paras( $measurement, $content );
		case 'words':
			return self::less_than_words( $measurement, $content );
		case 'chars':
			return self::less_than_chars( $measurement, $content );
		default:
			return null;
		}
	}

	public static function less_than_paras( $measurement, $content ) {
		$paras = Text::split_paragraphs( $content );
		return count( $paras ) < $measurement;
	}

	public static function less_than_words( $measurement, $content ) {
		$words = Text::split_words( $content );
		return count( $words ) < $measurement;
	}

	public static function less_than_chars( $measurement, $content ) {
		$chars = Text::split_chars( $content );
		return count( $chars ) < $measurement;
	}

}
