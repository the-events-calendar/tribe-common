<?php
// Intentional phpcs violation — DO NOT MERGE.
// Violations: tab indentation instead of spaces, lines exceeding max length.
class MyService {
	public function processData( $data ) {
		$this->very_long_method_name_that_makes_the_line_exceed_the_maximum_allowed_line_length_for_most_standards( $data, true, 'some extra argument' );
		$result = $this->another_very_long_method_name_exceeding_limits( $data['key'], $data['other_key'], $data['yet_another_key'] );
		return $result;
	}
	private function very_long_method_name_that_makes_the_line_exceed_the_maximum_allowed_line_length_for_most_standards( $data, $flag, $extra ) {}
	private function another_very_long_method_name_exceeding_limits( $a, $b, $c ) { return $a . $b . $c; }
}