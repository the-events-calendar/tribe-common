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
	transform: {
		'^.+\\.(js|jsx|ts|tsx)$': 'babel-jest',
	},
	transformIgnorePatterns: [
		'/node_modules/(?!(react-day-picker|@wordpress)/)',
	],
	testEnvironment: 'jest-environment-jsdom-global',
};