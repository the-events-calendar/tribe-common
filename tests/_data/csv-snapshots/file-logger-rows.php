<?php
/**
 * Single source of truth for the File_Logger CSV snapshot fixtures.
 *
 * @return array<int, array<int, string>>
 */
return [
	// Plain, unremarkable entry.
	[ '2026-01-01 00:00:00', 'Plain log message', 'debug', 'tribe' ],
	// Field containing the delimiter (comma).
	[ '2026-01-01 00:00:01', 'Message, with a comma', 'warning', 'tribe,src' ],
	// Field containing the enclosure (double quote).
	[ '2026-01-01 00:00:02', 'He said "hello" loudly', 'error', 'tribe"src' ],
	// Field containing the escape character (backslash).
	[ '2026-01-01 00:00:03', 'Path C:\\Windows\\system32', 'debug', 'back\\slash' ],
	// Field containing an embedded newline.
	[ '2026-01-01 00:00:04', "Line one\nLine two", 'error', 'multi\nline' ],
	// Mixture of quote, comma and backslash in a single field.
	[ '2026-01-01 00:00:05', 'Mix "q", c, and \\ back', 'warning', 'mixed' ],
	// Empty fields.
	[ '2026-01-01 00:00:06', '', 'debug', '' ],
	// Leading / trailing whitespace.
	[ '2026-01-01 00:00:07', '  padded message  ', 'debug', '  src  ' ],
	// Unicode payload.
	[ '2026-01-01 00:00:08', 'Café — naïve façade 日本語', 'debug', 'tribe' ],
];
