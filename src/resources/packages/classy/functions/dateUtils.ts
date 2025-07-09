/**
 * Checks if a given date string is valid.
 *
 * @since TBD
 *
 * @param {string} date The date string to validate.
 *
 * @returns {boolean} Returns true if the date is valid, otherwise false.
 */
export function isValidDate( date: string ): boolean {
	return ! isNaN( Date.parse( date ) );
}

/**
 * Converts a date string into a Date object if it's valid, otherwise returns null.
 *
 * @since TBD
 *
 * @param {string} date The date string to convert.
 *
 * @returns {Date|null} Returns a Date object if the date is valid, otherwise null.
 */
export function getValidDateOrNull( date: string ): Date | null {
	const parsedDate = Date.parse( date );
	return isNaN( parsedDate ) ? null : new Date( parsedDate );
}

/**
 * Compares two dates to determine if they are on the same day.
 *
 * Note the comparison is timezone-agnostic and will not take into account the timezone offset.
 *
 * @param {Date} date1 The first date to compare.
 * @param {Date} date2 The second date to compare.
 *
 * @return {boolean} Returns true if the dates are on the same day, otherwise false.
 */
export function areDatesOnSameDay( date1: Date, date2: Date ): boolean {
	return (
		date1.getFullYear() === date2.getFullYear() &&
		date1.getMonth() === date2.getMonth() &&
		date1.getDate() === date2.getDate()
	);
}
