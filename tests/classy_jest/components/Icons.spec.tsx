// @ts-nocheck
// eslint-disable-next-line @typescript-eslint/triple-slash-reference
/// <reference types="jest" />
import React from 'react';
import { render } from '@testing-library/react';
import '@testing-library/jest-dom';
import {
	IconAdd,
	IconCalendar,
	IconClose,
	IconCog,
	IconEdit,
	IconNew,
	IconTicket,
	IconTrash,
	IconVideoCamera,
} from '../../../src/resources/packages/classy/components/Icons';

describe( 'Icon Components', () => {
	describe( 'IconAdd', () => {
		it( 'renders with correct default class names', () => {
			const { container } = render( <IconAdd /> );
			const wrapper = container.querySelector( '.classy-icon' );

			expect( wrapper ).toHaveClass( 'classy-icon', 'classy-icon--add' );
			expect( wrapper?.className ).toBe( 'classy-icon classy-icon--add' );
		} );

		it( 'appends custom className to default classes', () => {
			const customClass = 'custom-test-class';
			const { container } = render( <IconAdd className={ customClass } /> );
			const wrapper = container.querySelector( '.classy-icon' );

			expect( wrapper ).toHaveClass( 'classy-icon', 'classy-icon--add', customClass );
		} );
	} );

	describe( 'IconCalendar', () => {
		it( 'renders with correct default class names', () => {
			const { container } = render( <IconCalendar /> );
			const svg = container.querySelector( 'svg' );

			expect( svg ).toHaveClass( 'classy-suffix-icon', 'classy-suffix-icon--calendar' );
		} );

		it( 'does not accept custom className prop', () => {
			// IconCalendar doesn't accept className prop based on its implementation.
			const { container } = render( <IconCalendar /> );
			const svg = container.querySelector( 'svg' );

			expect( svg ).toHaveClass( 'classy-suffix-icon', 'classy-suffix-icon--calendar' );
			// SVG className is an SVGAnimatedString object, so we check getAttribute instead.
			expect( svg?.getAttribute( 'class' ) ).toBe( 'classy-suffix-icon classy-suffix-icon--calendar' );
		} );
	} );

	describe( 'IconClose', () => {
		it( 'renders with correct default class names', () => {
			const { container } = render( <IconClose /> );
			const wrapper = container.querySelector( '.classy-icon' );

			expect( wrapper ).toHaveClass( 'classy-icon', 'classy-icon--close' );
			expect( wrapper?.className ).toBe( 'classy-icon classy-icon--close' );
		} );

		it( 'appends custom className to default classes', () => {
			const customClass = 'custom-test-class';
			const { container } = render( <IconClose className={ customClass } /> );
			const wrapper = container.querySelector( '.classy-icon' );

			expect( wrapper ).toHaveClass( 'classy-icon', 'classy-icon--close', customClass );
		} );
	} );

	describe( 'IconCog', () => {
		it( 'renders with correct default class names', () => {
			const { container } = render( <IconCog /> );
			const wrapper = container.querySelector( '.classy-icon' );

			expect( wrapper ).toHaveClass( 'classy-icon', 'classy-icon--cog' );
			expect( wrapper?.className ).toBe( 'classy-icon classy-icon--cog' );
		} );

		it( 'appends custom className to default classes', () => {
			const customClass = 'custom-test-class';
			const { container } = render( <IconCog className={ customClass } /> );
			const wrapper = container.querySelector( '.classy-icon' );

			expect( wrapper ).toHaveClass( 'classy-icon', 'classy-icon--cog', customClass );
		} );
	} );

	describe( 'IconEdit', () => {
		it( 'renders with correct default class names', () => {
			const { container } = render( <IconEdit /> );
			const wrapper = container.querySelector( '.classy-icon' );

			expect( wrapper ).toHaveClass( 'classy-icon', 'classy-icon--edit' );
			expect( wrapper?.className ).toBe( 'classy-icon classy-icon--edit' );
		} );

		it( 'appends custom className to default classes', () => {
			const customClass = 'custom-test-class';
			const { container } = render( <IconEdit className={ customClass } /> );
			const wrapper = container.querySelector( '.classy-icon' );

			expect( wrapper ).toHaveClass( 'classy-icon', 'classy-icon--edit', customClass );
		} );
	} );

	describe( 'IconNew', () => {
		it( 'renders with correct default class names', () => {
			const { container } = render( <IconNew /> );
			const wrapper = container.querySelector( '.classy-icon' );

			expect( wrapper ).toHaveClass( 'classy-icon', 'classy-icon--new' );
			expect( wrapper?.className ).toBe( 'classy-icon classy-icon--new' );
		} );

		it( 'appends custom className to default classes', () => {
			const customClass = 'custom-test-class';
			const { container } = render( <IconNew className={ customClass } /> );
			const wrapper = container.querySelector( '.classy-icon' );

			expect( wrapper ).toHaveClass( 'classy-icon', 'classy-icon--new', customClass );
		} );
	} );

	describe( 'IconTicket', () => {
		it( 'renders with correct default class names', () => {
			const { container } = render( <IconTicket /> );
			const wrapper = container.querySelector( '.classy-icon' );

			expect( wrapper ).toHaveClass( 'classy-icon', 'classy-icon--ticket' );
			expect( wrapper?.className ).toBe( 'classy-icon classy-icon--ticket' );
		} );

		it( 'appends custom className to default classes', () => {
			const customClass = 'custom-test-class';
			const { container } = render( <IconTicket className={ customClass } /> );
			const wrapper = container.querySelector( '.classy-icon' );

			expect( wrapper ).toHaveClass( 'classy-icon', 'classy-icon--ticket', customClass );
		} );
	} );

	describe( 'IconTrash', () => {
		it( 'renders with correct default class names', () => {
			const { container } = render( <IconTrash /> );
			const wrapper = container.querySelector( '.classy-icon' );

			expect( wrapper ).toHaveClass( 'classy-icon', 'classy-icon--trash' );
			expect( wrapper?.className ).toBe( 'classy-icon classy-icon--trash' );
		} );

		it( 'appends custom className to default classes', () => {
			const customClass = 'custom-test-class';
			const { container } = render( <IconTrash className={ customClass } /> );
			const wrapper = container.querySelector( '.classy-icon' );

			expect( wrapper ).toHaveClass( 'classy-icon', 'classy-icon--trash', customClass );
		} );
	} );

	describe( 'IconVideoCamera', () => {
		it( 'renders with correct default class names', () => {
			const { container } = render( <IconVideoCamera /> );
			const wrapper = container.querySelector( '.classy-icon' );

			expect( wrapper ).toHaveClass( 'classy-icon', 'classy-icon--video-camera' );
			expect( wrapper?.className ).toBe( 'classy-icon classy-icon--video-camera' );
		} );

		it( 'appends custom className to default classes', () => {
			const customClass = 'custom-test-class';
			const { container } = render( <IconVideoCamera className={ customClass } /> );
			const wrapper = container.querySelector( '.classy-icon' );

			expect( wrapper ).toHaveClass( 'classy-icon', 'classy-icon--video-camera', customClass );
		} );
	} );
} );
