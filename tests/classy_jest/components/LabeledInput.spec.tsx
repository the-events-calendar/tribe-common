import * as React from 'react';
import { render } from '@testing-library/react';
import { describe, expect, it, jest } from '@jest/globals';
import { LabeledInput } from '../../../src/resources/packages/classy/components/LabeledInput';

describe( 'LabeledInput Component', () => {
	const defaultProps = {
		label: 'Test Label',
		children: <input type="text" />,
	};

	it( 'renders correctly', () => {
		const { container } = render( <LabeledInput { ...defaultProps } /> );

		expect( container.firstChild ).toMatchSnapshot();
	} );
} );
