// @ts-nocheck
// eslint-disable-next-line @typescript-eslint/triple-slash-reference
/// <reference types="jest" />
import React, { Fragment } from 'react';
import { render } from '@testing-library/react';
import '@testing-library/jest-dom';
import { Classy } from '../../../src/resources/packages/classy/components/Classy';
import { addFilter, removeFilter } from '@wordpress/hooks';
import { Fill } from '@wordpress/components';

describe( 'Classy', () => {
	afterEach( () => {
		// Clean up any filters after each test.
		removeFilter( 'tec.classy.render', 'test-plugin' );
	} );

	it( 'renders the basic structure', () => {
		const { container } = render( <Classy /> );

		expect( container.querySelector( '.classy-container' ) ).toBeInTheDocument();
		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'renders content in the before slot', () => {
		addFilter( 'tec.classy.render', 'test-plugin', ( fields ) => (
			<Fragment>
				{ fields }
				<Fill name="tec.classy.before">
					<div className="before-content">Before Classy Content</div>
				</Fill>
			</Fragment>
		) );

		const { container } = render( <Classy /> );
		expect( container.querySelector( '.before-content' ) ).toBeInTheDocument();
		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'renders content in the fields slot', () => {
		addFilter( 'tec.classy.render', 'test-plugin', ( fields ) => (
			<Fragment>
				{ fields }
				<Fill name="tec.classy.fields">
					<div className="fields-content">Fields Content</div>
				</Fill>
			</Fragment>
		) );

		const { container } = render( <Classy /> );
		const fieldsContent = container.querySelector( '.fields-content' );
		const classyContainer = container.querySelector( '.classy-container' );

		expect( fieldsContent ).toBeInTheDocument();
		expect( classyContainer ).toContainElement( fieldsContent );
		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'renders content in the fields.before slot', () => {
		addFilter( 'tec.classy.render', 'test-plugin', ( fields ) => (
			<Fragment>
				{ fields }
				<Fill name="tec.classy.fields.before">
					<div className="fields-before-content">Before Fields Content</div>
				</Fill>
			</Fragment>
		) );

		const { container } = render( <Classy /> );
		const beforeFieldsContent = container.querySelector( '.fields-before-content' );
		const classyContainer = container.querySelector( '.classy-container' );

		expect( beforeFieldsContent ).toBeInTheDocument();
		expect( classyContainer ).toContainElement( beforeFieldsContent );
		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'renders content in the fields.after slot', () => {
		addFilter( 'tec.classy.render', 'test-plugin', ( fields ) => (
			<Fragment>
				{ fields }
				<Fill name="tec.classy.fields.after">
					<div className="fields-after-content">After Fields Content</div>
				</Fill>
			</Fragment>
		) );

		const { container } = render( <Classy /> );
		const afterFieldsContent = container.querySelector( '.fields-after-content' );
		const classyContainer = container.querySelector( '.classy-container' );

		expect( afterFieldsContent ).toBeInTheDocument();
		expect( classyContainer ).toContainElement( afterFieldsContent );
		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'renders content in the after slot', () => {
		addFilter( 'tec.classy.render', 'test-plugin', ( fields ) => (
			<Fragment>
				{ fields }
				<Fill name="tec.classy.after">
					<div className="after-content">After Classy Content</div>
				</Fill>
			</Fragment>
		) );

		const { container } = render( <Classy /> );
		const afterContent = container.querySelector( '.after-content' );
		const classyContainer = container.querySelector( '.classy-container' );

		expect( afterContent ).toBeInTheDocument();
		// After content should be outside the classy container.
		expect( classyContainer ).not.toContainElement( afterContent );
		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'renders content in multiple slots', () => {
		addFilter( 'tec.classy.render', 'test-plugin', ( fields ) => (
			<Fragment>
				{ fields }
				<Fill name="tec.classy.before">
					<div className="before">Before</div>
				</Fill>
				<Fill name="tec.classy.fields.before">
					<div className="fields-before">Fields Before</div>
				</Fill>
				<Fill name="tec.classy.fields">
					<div className="fields">Fields</div>
				</Fill>
				<Fill name="tec.classy.fields.after">
					<div className="fields-after">Fields After</div>
				</Fill>
				<Fill name="tec.classy.after">
					<div className="after">After</div>
				</Fill>
			</Fragment>
		) );

		const { container } = render( <Classy /> );

		expect( container.querySelector( '.before' ) ).toBeInTheDocument();
		expect( container.querySelector( '.fields-before' ) ).toBeInTheDocument();
		expect( container.querySelector( '.fields' ) ).toBeInTheDocument();
		expect( container.querySelector( '.fields-after' ) ).toBeInTheDocument();
		expect( container.querySelector( '.after' ) ).toBeInTheDocument();
		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'handles filter with no fills gracefully', () => {
		addFilter( 'tec.classy.render', 'test-plugin', ( fields ) => fields );

		const { container } = render( <Classy /> );
		expect( container.querySelector( '.classy-container' ) ).toBeInTheDocument();
		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'handles null filter return', () => {
		addFilter( 'tec.classy.render', 'test-plugin', () => null );

		const { container } = render( <Classy /> );
		expect( container.querySelector( '.classy-container' ) ).toBeInTheDocument();
		expect( container.firstChild ).toMatchSnapshot();
	} );

	it( 'preserves filter chain when multiple filters are applied', () => {
		addFilter(
			'tec.classy.render',
			'test-plugin',
			( fields ) => (
				<Fragment>
					{ fields }
					<Fill name="tec.classy.fields">
						<div className="first-plugin">First Plugin</div>
					</Fill>
				</Fragment>
			),
			10
		);

		addFilter(
			'tec.classy.render',
			'test-plugin-2',
			( fields ) => (
				<Fragment>
					{ fields }
					<Fill name="tec.classy.fields">
						<div className="second-plugin">Second Plugin</div>
					</Fill>
				</Fragment>
			),
			20
		);

		const { container } = render( <Classy /> );

		expect( container.querySelector( '.first-plugin' ) ).toBeInTheDocument();
		expect( container.querySelector( '.second-plugin' ) ).toBeInTheDocument();
		expect( container.firstChild ).toMatchSnapshot();

		// Clean up the second filter.
		removeFilter( 'tec.classy.render', 'test-plugin-2' );
	} );
} );
