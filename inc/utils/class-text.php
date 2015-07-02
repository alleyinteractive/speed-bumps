<?php
namespace Speed_Bumps\Utils;

class Text {

	public static function split_paragraphs( $content ) {
		return preg_split( '/\n\s*\n/', $content );
	}


	public static function split_words( $content ) {
		return array_filter( explode( ' ', strip_tags( $content ) ) );
	}


	public static function split_chars( $content ) {
		return str_split( $content );
	}

	/**
	 * Abstracted helper function for counting the words in a chunk of text.
	 *
	 * Given either a string of text or an array of strings, will split it into words and return the word count
	 *
	 * @param string|array Text to count words in
	 * @return int Word count
	 */
	public static function word_count( $text ) {
		if ( is_array( $text ) ) {
			$text = implode( ' ', $text );
		}

		$words = Text::split_words( $text );

		return count( $words );
	}
}
