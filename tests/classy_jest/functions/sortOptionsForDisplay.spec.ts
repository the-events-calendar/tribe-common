// @ts-nocheck
// eslint-disable-next-line @typescript-eslint/triple-slash-reference
/// <reference types="jest" />
import { describe, expect, it } from '@jest/globals';
import { sortOptionsForDisplay } from '../../../src/resources/packages/classy/functions/sortOptionsForDisplay';

describe( 'sortOptionsForDisplay', () => {
	it( 'keeps placeholder with value "0" at the top when it is first argument', () => {
		const placeholder = { value: '0', label: 'Select an option' };
		const option = { value: '1', label: 'Option A' };

		const result = sortOptionsForDisplay( placeholder, option );

		expect( result ).toBe( -1 );
	} );

	it( 'keeps placeholder with value "0" at the top when it is second argument', () => {
		const option = { value: '1', label: 'Option A' };
		const placeholder = { value: '0', label: 'Select an option' };

		const result = sortOptionsForDisplay( option, placeholder );

		expect( result ).toBe( 1 );
	} );

	it( 'sorts alphabetically by label when neither is placeholder', () => {
		const optionA = { value: '1', label: 'Apple' };
		const optionB = { value: '2', label: 'Banana' };

		const result = sortOptionsForDisplay( optionA, optionB );

		expect( result ).toBe( -1 );
	} );

	it( 'sorts alphabetically in reverse when labels are reversed', () => {
		const optionA = { value: '1', label: 'Zebra' };
		const optionB = { value: '2', label: 'Apple' };

		const result = sortOptionsForDisplay( optionA, optionB );

		expect( result ).toBe( 1 );
	} );

	it( 'returns 0 when labels are equal', () => {
		const optionA = { value: '1', label: 'Same Label' };
		const optionB = { value: '2', label: 'Same Label' };

		const result = sortOptionsForDisplay( optionA, optionB );

		expect( result ).toBe( 0 );
	} );

	it( 'handles case-sensitive sorting correctly', () => {
		const optionA = { value: '1', label: 'apple' };
		const optionB = { value: '2', label: 'Apple' };

		// JavaScript's string comparison is case-sensitive.
		// 'a' > 'A' in ASCII/Unicode ordering.
		const result = sortOptionsForDisplay( optionA, optionB );

		expect( result ).toBe( 1 );
	} );

	it( 'sorts array of options correctly', () => {
		const options = [
			{ value: '3', label: 'Charlie' },
			{ value: '1', label: 'Alpha' },
			{ value: '0', label: 'Select...' },
			{ value: '2', label: 'Bravo' },
			{ value: '4', label: 'Delta' },
		];

		const sorted = [ ...options ].sort( sortOptionsForDisplay );

		expect( sorted ).toEqual( [
			{ value: '0', label: 'Select...' },
			{ value: '1', label: 'Alpha' },
			{ value: '2', label: 'Bravo' },
			{ value: '3', label: 'Charlie' },
			{ value: '4', label: 'Delta' },
		] );
	} );

	it( 'handles multiple placeholders correctly', () => {
		const options = [
			{ value: '1', label: 'Option' },
			{ value: '0', label: 'Placeholder B' },
			{ value: '0', label: 'Placeholder A' },
		];

		const sorted = [ ...options ].sort( sortOptionsForDisplay );

		// Both placeholders should be at the top, maintaining relative order.
		expect( sorted[ 0 ].value ).toBe( '0' );
		expect( sorted[ 1 ].value ).toBe( '0' );
		expect( sorted[ 2 ].value ).toBe( '1' );
	} );

	it( 'handles numeric labels correctly', () => {
		const option1 = { value: 'a', label: '10' };
		const option2 = { value: 'b', label: '2' };

		// String comparison: '10' < '2' because '1' < '2'.
		const result = sortOptionsForDisplay( option1, option2 );

		expect( result ).toBe( -1 );
	} );

	it( 'handles special characters in labels', () => {
		const optionA = { value: '1', label: '@Special' };
		const optionB = { value: '2', label: 'Normal' };

		// '@' comes before 'N' in ASCII.
		const result = sortOptionsForDisplay( optionA, optionB );

		expect( result ).toBe( -1 );
	} );

	it( 'handles empty labels', () => {
		const optionEmpty = { value: '1', label: '' };
		const optionNormal = { value: '2', label: 'Normal' };

		// Empty string comes before any character.
		const result = sortOptionsForDisplay( optionEmpty, optionNormal );

		expect( result ).toBe( -1 );
	} );

	it( 'works with CustomSelectOption type', () => {
		// CustomSelectOption can have additional properties.
		const optionA = { value: '1', label: 'Alpha', name: 'alpha', __experimentalHint: 'Hint A' };
		const optionB = { value: '2', label: 'Beta', name: 'beta', __experimentalHint: 'Hint B' };

		const result = sortOptionsForDisplay( optionA, optionB );

		expect( result ).toBe( -1 );
	} );
} );
