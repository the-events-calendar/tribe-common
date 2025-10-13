import { DatePickerEvent } from '@wordpress/components/build-types/date-time/types';

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
 * @since TBD
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

/**
 * Compares two dates to determine if they are on the same time.
 *
 * @since TBD
 *
 * @param {Date} date1 The first date to compare.
 * @param {Date} date2 The second date to compare.
 * @param {boolean} checkSeconds Indicates whether to check the seconds as well.
 *
 * @return {boolean} Returns true if the dates are on the same time, otherwise false.
 */
export function areDatesOnSameTime( date1: Date, date2: Date, checkSeconds: boolean = false ): boolean {
	const sameTime = date1.getHours() === date2.getHours() && date1.getMinutes() === date2.getMinutes();

	if ( ! checkSeconds ) {
		return sameTime;
	}

	return sameTime && date1.getSeconds() === date2.getSeconds();
}

/**
 * Returns the difference, in days, between two dates.
 *
 * @since TBD
 *
 * @param {Date} startDate The start date.
 * @param {Date} endDate The end date.
 *
 * @return {number} The difference in days between the two dates.
 */
export function dayDiffBetweenDates( startDate: Date, endDate: Date ): number {
	const timeDiff = endDate.getTime() - startDate.getTime();
	return Math.floor( timeDiff / ( 1000 * 60 * 60 * 24 ) );
}

/**
 * Generates an array of DatePickerEvent objects for each date between the start and end dates, inclusive.
 *
 * @since TBD
 *
 * @param {Date} start The start date.
 * @param {Date} end The end date.
 *
 * @throws {Error} Throws an error if the start date is after the end date.
 *
 * @return {DatePickerEvent[]} An array of DatePickerEvent objects for each date in the range.
 */
export function getDatePickerEventsBetweenDates( start: Date, end: Date ): DatePickerEvent[] {
	if ( start > end ) {
		throw new Error( 'Start date must be on or before the end date.' );
	}

	const dateArray: Date[] = [];
	let currentDate = new Date( start );
	while ( currentDate <= end ) {
		dateArray.push( new Date( currentDate ) );
		currentDate.setDate( currentDate.getDate() + 1 );
	}

	return dateArray.map( ( date: Date ): DatePickerEvent => {
		return { date };
	} );
}
