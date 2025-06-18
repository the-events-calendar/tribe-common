import { beforeEach, afterEach, describe, expect, it, jest } from '@jest/globals';
import { ComponentType } from 'react';

// Mock the @wordpress/data module withRegistry function.
// const withRegistryMock = jest.fn().mockImplementation(
// 	(Inner: ComponentType)=>(props:any): JSX.Element => <Inner {...props} registry={{mockRegistry: true}}/>
// );
// const dataModule = jest.mock('@wordpress/data', () => ({
// 	withRegistry: withRegistryMock,
// }));

import {
	getDefaultRegistry,
	setDefaultRegistry,
} from '../../../src/resources/packages/classy/functions/getDefaultRegistry.tsx';

describe( 'getDefaultRegistry', () => {
	beforeEach( () => {
		jest.resetModules();
	} );

	afterEach( () => {
		jest.resetModules();
		jest.restoreAllMocks();
	} );

	it( 'returns the registry provided by withRegistry and caches it', async () => {
		const registry1 = await getDefaultRegistry();

		// expect(withRegistryMock).toHaveBeenCalledTimes(1);
		expect( registry1 ).not.toBeNull();
		expect( typeof registry1.select ).toBe( 'function' );

		const registry2 = await getDefaultRegistry();

		// expect(withRegistryMock).toHaveBeenCalledTimes(1);
		expect( registry2 ).toBe( registry1 );
	} );

	it( 'returns the custom registry set by setDefaultRegistry', async () => {
		const customRegistry = { custom: true };
		// @ts-ignore
		setDefaultRegistry( customRegistry );

		const registry = await getDefaultRegistry();

		// expect(withRegistryMock).not.toHaveBeenCalled();
		expect( registry ).toBe( customRegistry );
	} );
} );
