import {
	isValidDate,
	getValidDateOrNull,
	areDatesOnSameDay,
	areDatesOnSameTime,
	dayDiffBetweenDates,
} from '@tec/common/classy/functions';
import { describe, expect, it } from '@jest/globals';

describe( 'dateUtils', () => {
	describe( 'isValidDate', () => {
		it( 'should return true for valid date strings', () => {
			expect( isValidDate( '2024-01-01' ) ).toBe( true );
			expect( isValidDate( '2024-12-31' ) ).toBe( true );
			expect( isValidDate( '2024-01-01T12:00:00' ) ).toBe( true );
			expect( isValidDate( '2024-01-01T12:00:00Z' ) ).toBe( true );
			expect( isValidDate( 'January 1, 2024' ) ).toBe( true );
			expect( isValidDate( '01/01/2024' ) ).toBe( true );
			expect( isValidDate( '2024-01-01T12:00:00.000Z' ) ).toBe( true );
			expect( isValidDate( '2024-01-01 12:00:00' ) ).toBe( true );
		} );

		it( 'should return false for invalid date strings', () => {
			expect( isValidDate( 'invalid-date' ) ).toBe( false );
			expect( isValidDate( '' ) ).toBe( false );
			expect( isValidDate( '2024-13-01' ) ).toBe( false );
			expect( isValidDate( '2024-01-32' ) ).toBe( false );
			expect( isValidDate( 'abc123' ) ).toBe( false );
			expect( isValidDate( '2024/13/01' ) ).toBe( false );
			expect( isValidDate( 'not a date' ) ).toBe( false );
		} );
	} );

	describe( 'getValidDateOrNull', () => {
		it( 'should return a Date object for valid date strings', () => {
			const date1 = getValidDateOrNull( '2024-01-01' );
			expect( date1 ).toBeInstanceOf( Date );
			expect( date1?.getFullYear() ).toBe( 2024 );
			expect( date1?.getMonth() ).toBe( 0 ); // January is 0-indexed
			expect( date1?.getDate() ).toBe( 1 );

			const date2 = getValidDateOrNull( '2024-12-31T23:59:59Z' );
			expect( date2 ).toBeInstanceOf( Date );
			expect( date2?.getUTCFullYear() ).toBe( 2024 );
			expect( date2?.getUTCMonth() ).toBe( 11 ); // December is 11-indexed
			expect( date2?.getUTCDate() ).toBe( 31 );
			expect( date2?.getUTCHours() ).toBe( 23 );
			expect( date2?.getUTCMinutes() ).toBe( 59 );
			expect( date2?.getUTCSeconds() ).toBe( 59 );
		} );

		it( 'should return null for invalid date strings', () => {
			expect( getValidDateOrNull( 'invalid-date' ) ).toBeNull();
			expect( getValidDateOrNull( '' ) ).toBeNull();
			expect( getValidDateOrNull( '2024-13-01' ) ).toBeNull();
			expect( getValidDateOrNull( '2024-01-32' ) ).toBeNull();
			expect( getValidDateOrNull( 'abc123' ) ).toBeNull();
			expect( getValidDateOrNull( 'not a date' ) ).toBeNull();
		} );
	} );

	describe( 'areDatesOnSameDay', () => {
		it( 'should return true for dates on the same day', () => {
			const date1 = new Date( '2024-01-01T12:00:00' );
			const date2 = new Date( '2024-01-01T18:00:00' );
			expect( areDatesOnSameDay( date1, date2 ) ).toBe( true );

			const date3 = new Date( '2024-12-31T00:00:00' );
			const date4 = new Date( '2024-12-31T23:59:59' );
			expect( areDatesOnSameDay( date3, date4 ) ).toBe( true );
		} );

		it( 'should return false for dates on different days', () => {
			const date1 = new Date( '2024-01-01T12:00:00' );
			const date2 = new Date( '2024-01-02T12:00:00' );
			expect( areDatesOnSameDay( date1, date2 ) ).toBe( false );

			const date3 = new Date( '2024-01-31T23:59:59' );
			const date4 = new Date( '2024-02-01T00:00:00' );
			expect( areDatesOnSameDay( date3, date4 ) ).toBe( false );

			const date5 = new Date( '2023-12-31' );
			const date6 = new Date( '2024-01-01' );
			expect( areDatesOnSameDay( date5, date6 ) ).toBe( false );
		} );

		it( 'should handle same date objects', () => {
			const date = new Date( '2024-01-01T12:00:00' );
			expect( areDatesOnSameDay( date, date ) ).toBe( true );
		} );
	} );

	describe( 'areDatesOnSameTime', () => {
		it( 'should return true for dates with same hours and minutes when not checking seconds', () => {
			const date1 = new Date( '2024-01-01T12:30:00' );
			const date2 = new Date( '2024-01-01T12:30:45' );
			expect( areDatesOnSameTime( date1, date2 ) ).toBe( true );

			const date3 = new Date( '2024-12-31T23:59:00' );
			const date4 = new Date( '2024-01-31T23:59:59' );
			expect( areDatesOnSameTime( date3, date4 ) ).toBe( true );
		} );

		it( 'should return false for dates with different hours or minutes when not checking seconds', () => {
			const date1 = new Date( '2024-01-01T12:30:00' );
			const date2 = new Date( '2024-01-01T12:31:00' );
			expect( areDatesOnSameTime( date1, date2 ) ).toBe( false );

			const date3 = new Date( '2024-01-01T12:30:00' );
			const date4 = new Date( '2024-01-01T13:30:00' );
			expect( areDatesOnSameTime( date3, date4 ) ).toBe( false );
		} );

		it( 'should return true for dates with same hours, minutes, and seconds when checking seconds', () => {
			const date1 = new Date( '2024-01-01T12:30:45' );
			const date2 = new Date( '2024-01-15T12:30:45' );
			expect( areDatesOnSameTime( date1, date2, true ) ).toBe( true );
		} );

		it( 'should return false for dates with different seconds when checking seconds', () => {
			const date1 = new Date( '2024-01-01T12:30:00' );
			const date2 = new Date( '2024-01-01T12:30:01' );
			expect( areDatesOnSameTime( date1, date2, true ) ).toBe( false );
		} );

		it( 'should handle same date objects', () => {
			const date = new Date( '2024-01-01T12:30:45' );
			expect( areDatesOnSameTime( date, date ) ).toBe( true );
			expect( areDatesOnSameTime( date, date, true ) ).toBe( true );
		} );

		it( 'should use false as default value for checkSeconds parameter', () => {
			const date1 = new Date( '2024-01-01T12:30:00' );
			const date2 = new Date( '2024-01-01T12:30:45' );
			expect( areDatesOnSameTime( date1, date2 ) ).toBe( true );
		} );
	} );

	describe( 'dayDiffBetweenDates', () => {
		it( 'should return 0 for the same date', () => {
			const date1 = new Date( '2024-01-01T12:00:00' );
			const date2 = new Date( '2024-01-01T18:00:00' );
			expect( dayDiffBetweenDates( date1, date2 ) ).toBe( 0 );
		} );

		it( 'should return positive number for future dates', () => {
			const startDate = new Date( '2024-01-01' );
			const endDate = new Date( '2024-01-10' );
			expect( dayDiffBetweenDates( startDate, endDate ) ).toBe( 9 );
		} );

		it( 'should return negative number for past dates', () => {
			const startDate = new Date( '2024-01-10' );
			const endDate = new Date( '2024-01-01' );
			expect( dayDiffBetweenDates( startDate, endDate ) ).toBe( -9 );
		} );

		it( 'should handle dates across months', () => {
			const startDate = new Date( '2024-01-31' );
			const endDate = new Date( '2024-02-05' );
			expect( dayDiffBetweenDates( startDate, endDate ) ).toBe( 5 );
		} );

		it( 'should handle dates across years', () => {
			const startDate = new Date( '2023-12-31' );
			const endDate = new Date( '2024-01-01' );
			expect( dayDiffBetweenDates( startDate, endDate ) ).toBe( 1 );
		} );

		it( 'should handle leap year dates', () => {
			const startDate = new Date( '2024-02-28' );
			const endDate = new Date( '2024-03-01' );
			expect( dayDiffBetweenDates( startDate, endDate ) ).toBe( 2 ); // 2024 is a leap year
		} );

		it( 'should floor the result for partial days', () => {
			const startDate = new Date( '2024-01-01T23:59:59' );
			const endDate = new Date( '2024-01-02T00:00:01' );
			expect( dayDiffBetweenDates( startDate, endDate ) ).toBe( 0 ); // Less than 24 hours
		} );

		it( 'should handle large date differences', () => {
			const startDate = new Date( '2020-01-01' );
			const endDate = new Date( '2024-01-01' );
			expect( dayDiffBetweenDates( startDate, endDate ) ).toBe( 1461 ); // 4 years including a leap year
		} );

		it( 'should handle exact 24 hour differences', () => {
			const startDate = new Date( '2024-01-01T12:00:00' );
			const endDate = new Date( '2024-01-02T12:00:00' );
			expect( dayDiffBetweenDates( startDate, endDate ) ).toBe( 1 );
		} );

		it( 'should handle timezone-aware dates', () => {
			const startDate = new Date( '2024-01-01T00:00:00Z' );
			const endDate = new Date( '2024-01-02T00:00:00Z' );
			expect( dayDiffBetweenDates( startDate, endDate ) ).toBe( 1 );
		} );
	} );
} );
