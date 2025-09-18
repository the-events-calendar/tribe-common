import * as React from 'react';
import { render } from '@testing-library/react';
import { describe, expect, it, jest } from '@jest/globals';
import { LabeledInput } from '@tec/common/classy/components';

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
