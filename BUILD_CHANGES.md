# Build System Changes - Tribe Common

## Summary
Removed `@the-events-calendar/product-taskmaster` dependency and replaced gulp-based build tasks with direct tool usage. This mirrors the changes made to the main TEC plugin.

## Changes Made

### 1. Removed Dependencies
- `@the-events-calendar/product-taskmaster` - no longer maintained, caused dependency conflicts
- `enzyme-adapter-react-16` - replaced with `@cfaester/enzyme-adapter-react-18`
- `redux-devtools-extension` - replaced with `@redux-devtools/extension`

### 2. Fixed React Dependencies
- Updated `react` from `^16.14.0` to `^18.3.1`
- Updated `react-dom` from `^16.14.0` to `^18.3.1`
- Updated `react-day-picker` from `^7.2.4` to `^9.3.0` (React 18 support)
- Removed `react-input-autosize` (replaced with `react-18-input-autosize`)

### 3. Updated Redux Dependencies
- Updated `redux` from `^4.0.0` to `^5.0.1`
- Updated `react-redux` from `^5.0.7` to `^9.1.2`
- Updated `redux-thunk` from `^2.3.0` to `^3.1.0`
- Updated `react-select` from `^2.1.2` to `^5.8.3`

### 4. Added Direct Tool Dependencies
- `@babel/eslint-parser@^7.25.1` - for parsing modern JavaScript in eslint
- `@babel/preset-react@^7.25.9` - for React JSX support in Babel
- `babel-plugin-lodash@^3.3.4` - for optimized lodash imports
- `eslint@^8.57.0` - for JavaScript linting
- `eslint-plugin-react@^7.37.2` - for React-specific linting rules
- `identity-obj-proxy@^3.0.0` - for mocking CSS modules in Jest
- `stylelint@^16.10.0` - for CSS/PostCSS linting
- `stylelint-config-standard@^36.0.1` - standard stylelint config

### 5. Replaced Configuration Files
All product-taskmaster references in configuration files were replaced with standalone configs:

**`.stylelintrc`** - Now uses `stylelint-config-standard` directly with custom rules

**`babel.config.json`** - Standalone Babel config with WordPress presets

**`jest.config.js`** - Complete Jest configuration with enzyme setup

**`webpack.config.js`** - Inlined common webpack config and all external mappings (wp, vendor, lodash, modules)

**`src/resources/js/.eslintrc`** - ES5 config for legacy JavaScript

**`src/modules/.eslintrc`** - React/JSX config for modern code

### 6. Updated npm Scripts
**Removed:**
- `bootstrap` - dependencies auto-install
- `build:gulp` - no longer needed without product-taskmaster
- `prebuild` - no longer needed
- `zip` - use pup for packaging
- `glotpress` - use pup i18n instead

**Updated:**
- `build` - now only runs webpack (no gulp)
- `lint:eslint` - calls eslint directly instead of gulp
- `lint:stylelint` - calls stylelint directly instead of gulp
- `jest` - calls jest directly instead of through gulp

### 7. Deleted Files
- `gulpfile.js` - only required product-taskmaster, no longer needed

## Installation

Standard npm install now works without any flags:
```bash
npm install
```

**Note:** The `--legacy-peer-deps` flag is no longer needed! Removing `product-taskmaster` resolved all peer dependency conflicts.

You may see deprecation warnings for older packages (webpack 4, etc.) but these are non-blocking and don't prevent installation or development.

## npm Scripts Reference

### Development
- `npm run dev` - Watch mode webpack build
- `npm run build` - Production webpack build
- `npm run rebuild` - Clean install and build

### Testing & Linting
- `npm run jest` - Run Jest tests
- `npm run lint` - Run both eslint and stylelint
- `npm run lint:eslint` - Lint JavaScript files
- `npm run lint:stylelint` - Lint PostCSS files

### Analysis
- `npm run analyze` - Analyze webpack bundle size

## Migration from TEC Main Plugin

These changes are identical to those made in the main TEC plugin. If you're working across both:
- Same React 18 version
- Same Redux 5 version
- Same build tools and configurations
- Compatible dependencies

This ensures consistency across the entire TEC ecosystem.
