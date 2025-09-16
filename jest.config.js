const pkg = require( './package.json' );

module.exports = {
	displayName: 'common',
	testMatch: pkg._filePath.jest.map( ( path ) => `<rootDir>/${ path }` ),
	verbose: true,
	setupFiles: [
		'<rootDir>/jest.setup.js',
	],
	moduleNameMapper: {
		'\\.(css|pcss)$': 'identity-obj-proxy',
		'\\.(svg)$': '<rootDir>/__mocks__/icons.js',
	},
	testEnvironment: 'jest-environment-jsdom-global',
};