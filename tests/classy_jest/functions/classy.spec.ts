import {
	getOrCreateElement,
	initApp,
	insertElement,
	toggleElementVisibility,
} from '../../../src/resources/packages/classy/functions/classy';
import { afterEach, beforeEach, describe, expect, it, jest } from '@jest/globals';
import { getElement } from '../../../src/resources/packages/classy/functions/visualEditor';
import { Classy } from '../../../src/resources/packages/classy/components/Classy';

jest.mock( '../../../src/resources/packages/classy/components/Classy', () => ( {
	Classy: jest.fn(),
} ) );

describe( 'classy', () => {
	beforeEach( () => {
		jest.resetModules();
	} );

	afterEach( () => {
		jest.resetAllMocks();
		jest.restoreAllMocks();
		jest.resetModules();
	} );

	it( 'should create the element if not already existing', () => {
		const mockDocument = new DOMParser().parseFromString( `<html><body></body></html>`, 'text/html' );

		const element = getOrCreateElement( mockDocument );

		expect( element ).not.toBeNull();
		expect( element.outerHTML ).toMatchSnapshot();

		const element2 = getOrCreateElement( mockDocument );

		expect( element2 ).toBe( element );
	} );

	it( 'should inject the element into the DOM', () => {
		const mockDocument = new DOMParser().parseFromString(
			`<html>
				<body>
					<div class="editor-visual-editor edit-post-visual-editor"></div>
				</body>
			</html>`,
			'text/html'
		);

		const inserted = insertElement( mockDocument );

		expect( inserted ).toBe( true );
		expect( getElement( mockDocument ).outerHTML ).toMatchSnapshot();
	} );

	it( 'should not inject the element into the DOM If visual editor is not present', () => {
		const mockDocument = new DOMParser().parseFromString(
			`<html>
				<body>
					<div class="not-the-visual-editor edit-post-visual-editor"></div>
				</body>
			</html>`,
			'text/html'
		);

		const inserted = insertElement( mockDocument );

		expect( inserted ).toBe( false );
	} );

	it( 'should toggle the visibility of the element', () => {
		const mockDocument = new DOMParser().parseFromString(
			`<html>
				<body>
					<div class="editor-visual-editor edit-post-visual-editor"></div>
				</body>
			</html>`,
			'text/html'
		);

		insertElement( mockDocument );

		expect( getOrCreateElement( mockDocument ).classList.contains( 'classy-root--hidden' ) ).toBe( false );

		toggleElementVisibility();

		expect( getOrCreateElement( mockDocument ).classList.contains( 'classy-root--hidden' ) ).toBe( true );

		toggleElementVisibility();

		expect( getOrCreateElement( mockDocument ).classList.contains( 'classy-root--hidden' ) ).toBe( false );
	} );

	it( 'should initialize the app correctly', async () => {
		const mockDocument = new DOMParser().parseFromString(
			`<html>
				<body>
					<div class="editor-visual-editor edit-post-visual-editor"></div>
				</body>
			</html>`,
			'text/html'
		);

		await initApp( mockDocument );

		expect( getOrCreateElement() ).not.toBeNull();
		expect( Classy as jest.Mock ).toHaveBeenCalledTimes( 1 );
	} );
} );
