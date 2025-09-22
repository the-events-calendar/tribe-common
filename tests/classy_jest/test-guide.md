# Jest Integration Tests for Common Classy Components

This guide provides instructions for writing and maintaining Jest integration tests for the Common package's Classy components and utilities. These tests verify that shared components, functions, and store logic work correctly across The Events Calendar ecosystem.

## Test Philosophy

The Common Classy test suite focuses on testing reusable components and utilities that are shared across TEC plugins. Tests should verify that components work correctly in isolation and can be integrated into different contexts. Mock only what's necessary:

- **WordPress Data Module** (`@wordpress/data`) - Mock selectors and dispatch functions when testing components that use WordPress state
- **External dependencies** - Mock only when necessary to isolate the component under test
- **Browser APIs** - Mock browser-specific APIs that aren't available in the test environment

## Running Tests

### Run All Tests

```bash
nvm use && npm run common:classy:jest
```

### Run a Single Test File

```bash
nvm use && node node_modules/.bin/jest --config common/tests/classy_jest/jest.config.ts <test_file>

# Example
nvm use && node node_modules/.bin/jest --config common/tests/classy_jest/jest.config.ts common/tests/classy_jest/components/CurrencyInput.spec.tsx
```

### Run a Single Test Method

```bash
nvm use && node node_modules/.bin/jest --config common/tests/classy_jest/jest.config.ts <test_file> -t "<test_method>"

# Example
nvm use && node node_modules/.bin/jest --config common/tests/classy_jest/jest.config.ts common/tests/classy_jest/components/CurrencyInput.spec.tsx -t "formats value when not focused"
```

## Test Structure

```
common/tests/classy_jest/
├── _support/           # Test utilities and helpers
│   ├── TestProvider.tsx       # React context provider for tests
│   └── userEvents.ts          # User event simulation utilities
├── components/         # Component tests
├── fields/            # Field component tests
├── functions/         # Utility function tests
├── store/             # Store selector and action tests
├── jest.config.ts     # Jest configuration
├── jest.setup.ts      # Global test setup
└── test-guide.md      # This guide
```

### Test File Naming

- Component tests: `ComponentName.spec.tsx` (for React components)
- Function tests: `functionName.spec.ts` (for utility functions)
- Store tests: `selectors.spec.ts`, `actions.spec.ts` (for Redux/store tests)

## Writing Tests

### Test File Headers

Most test files in the Common Classy suite begin with these TypeScript compiler directives:

```tsx
// @ts-nocheck
// eslint-disable-next-line @typescript-eslint/triple-slash-reference
/// <reference types="jest" />
```

These headers serve important purposes:
- `@ts-nocheck`: Disables TypeScript type checking for the test file, useful when mocking complex types
- `/// <reference types="jest" />`: Ensures Jest global types are available in the file

### Component Tests

Component tests verify that reusable UI components render correctly and handle various props and interactions.

#### Basic Component Test Structure

```tsx
// common/tests/classy_jest/components/CurrencyInput.spec.tsx
// @ts-nocheck
// eslint-disable-next-line @typescript-eslint/triple-slash-reference
/// <reference types="jest" />
import React from 'react';
import { fireEvent, render, screen } from '@testing-library/react';
import '@testing-library/jest-dom';
import { describe, expect, it, jest } from '@jest/globals';
import CurrencyInput from '@tec/common/classy/components/CurrencyInput/CurrencyInput';

describe('CurrencyInput', () => {
	const mockDefaultCurrency = {
		code: 'USD',
		symbol: '$',
		position: 'prefix' as const,
		label: 'US Dollar',
	};

	const defaultProps = {
		value: '100',
		decimalPrecision: 2,
		decimalSeparator: '.',
		thousandSeparator: ',',
		defaultCurrency: mockDefaultCurrency,
	};

	it('renders with default label when no label provided', () => {
		render(<CurrencyInput {...defaultProps} />);
		expect(screen.getByLabelText('Price')).toBeInTheDocument();
	});

	it('formats value when not focused', () => {
		render(<CurrencyInput {...defaultProps} value="1000.50" />);
		const input = screen.getByRole('textbox');
		expect(input).toHaveValue('$1,000.50');
	});

	it('shows raw value when focused', () => {
		render(<CurrencyInput {...defaultProps} value="1000.50" />);
		const input = screen.getByRole('textbox');

		fireEvent.focus(input);
		expect(input).toHaveValue('1000.50');
	});

	it('calls onChange when value changes', () => {
		const mockOnChange = jest.fn();
		render(<CurrencyInput {...defaultProps} onChange={mockOnChange} />);
		const input = screen.getByRole('textbox');

		fireEvent.focus(input);
		fireEvent.change(input, { target: { value: '250.75' } });

		expect(mockOnChange).toHaveBeenCalledWith('250.75');
	});
});
```

### Testing Components with Context

For components that require WordPress data context, use the TestProvider:

```tsx
// common/tests/classy_jest/components/TimeZone.spec.tsx
import React from 'react';
import { render, screen } from '@testing-library/react';
import '@testing-library/jest-dom';
import TestProvider from '../_support/TestProvider';
import TimeZone from '@tec/common/classy/components/TimeZone';

describe('TimeZone', () => {
	it('renders timezone selector with options', () => {
		render(
			<TestProvider>
				<TimeZone />
			</TestProvider>
		);

		expect(screen.getByLabelText('Timezone')).toBeInTheDocument();
	});
});
```

### Store Selector Tests

Test store selectors to ensure they correctly transform and return data:

```ts
// common/tests/classy_jest/store/selectors.spec.ts
// @ts-nocheck
// eslint-disable-next-line @typescript-eslint/triple-slash-reference
/// <reference types="jest" />
import { describe, expect, it, jest, beforeEach } from '@jest/globals';
import {
	getSettings,
	getDefaultCurrency,
	getCurrencyOptions,
} from '@tec/common/classy/store/selectors';
import { StoreState } from '@tec/common/classy/types/Store';
import { Currency } from '@tec/common/classy/types/Currency';

describe('Store Selectors', () => {
	const mockSettings = {
		defaultCurrency: {
			code: 'USD',
			symbol: '$',
			position: 'prefix' as const,
			label: 'US Dollar',
		},
		timezoneString: 'UTC',
		timeInterval: 30,
	};

	const mockCurrencyOptions: Currency[] = [
		{
			code: 'USD',
			symbol: '$',
			position: 'prefix' as const,
			label: 'US Dollar',
		},
		{
			code: 'EUR',
			symbol: '€',
			position: 'postfix' as const,
			label: 'Euro',
		},
	];

	const mockState: StoreState = {
		settings: mockSettings,
		options: {
			currencies: mockCurrencyOptions,
		},
	};

	it('getSettings should return settings from state', () => {
		const result = getSettings(mockState);
		expect(result).toEqual(mockSettings);
	});

	it('getDefaultCurrency should return default currency', () => {
		const result = getDefaultCurrency(mockState);
		expect(result).toEqual(mockSettings.defaultCurrency);
	});

	it('getCurrencyOptions should return currency options', () => {
		const result = getCurrencyOptions(mockState);
		expect(result).toEqual(mockCurrencyOptions);
	});
});
```

### Function Tests

Test utility functions with various inputs and edge cases:

```ts
// common/tests/classy_jest/functions/formatCurrency.spec.ts
import { describe, expect, it } from '@jest/globals';
import { formatCurrency } from '@tec/common/classy/functions';

describe('formatCurrency', () => {
	it('formats currency with prefix symbol', () => {
		const result = formatCurrency('1000.50', {
			symbol: '$',
			position: 'prefix',
		});
		expect(result).toBe('$1,000.50');
	});

	it('formats currency with postfix symbol', () => {
		const result = formatCurrency('1000.50', {
			symbol: '€',
			position: 'postfix',
		});
		expect(result).toBe('1,000.50€');
	});

	it('handles empty value', () => {
		const result = formatCurrency('', {
			symbol: '$',
			position: 'prefix',
		});
		expect(result).toBe('');
	});

	it('handles invalid number gracefully', () => {
		const result = formatCurrency('not-a-number', {
			symbol: '$',
			position: 'prefix',
		});
		expect(result).toBe('$0.00');
	});
});
```

## Testing Complex Components

### Components with External Dependencies

When testing components that depend on external libraries or WordPress components:

```tsx
// Mock external WordPress components
jest.mock('@wordpress/components', () => ({
	SelectControl: jest.fn(({ label, value, onChange, options }) => (
		<select aria-label={label} value={value} onChange={(e) => onChange(e.target.value)}>
			{options.map((opt) => (
				<option key={opt.value} value={opt.value}>
					{opt.label}
				</option>
			))}
		</select>
	)),
	CustomSelectControl: jest.fn(({ label, value, onChange, options }) => (
		<select aria-label={label} value={value} onChange={(e) => onChange(e.target.value)}>
			{options.map((opt) => (
				<option key={opt.key} value={opt.value}>
					{opt.name}
				</option>
			))}
		</select>
	)),
}));
```

### Testing Hooks

Test custom React hooks in isolation:

```tsx
// common/tests/classy_jest/functions/useDebounce.spec.ts
import { renderHook, act } from '@testing-library/react';
import { useDebounce } from '@tec/common/classy/hooks';

describe('useDebounce', () => {
	beforeEach(() => {
		jest.useFakeTimers();
	});

	afterEach(() => {
		jest.useRealTimers();
	});

	it('should debounce value changes', () => {
		const { result, rerender } = renderHook(
			({ value, delay }) => useDebounce(value, delay),
			{ initialProps: { value: 'initial', delay: 500 } }
		);

		expect(result.current).toBe('initial');

		// Update value
		rerender({ value: 'updated', delay: 500 });

		// Value should not change immediately
		expect(result.current).toBe('initial');

		// Fast-forward time
		act(() => {
			jest.advanceTimersByTime(500);
		});

		// Value should now be updated
		expect(result.current).toBe('updated');
	});
});
```

## Best Practices

### 1. Test Component Behavior, Not Implementation

Focus on testing what users see and interact with:

```tsx
// Good: Test user-visible behavior
it('should display formatted currency value', () => {
	render(<CurrencyInput value="1000" />);
	expect(screen.getByRole('textbox')).toHaveValue('$1,000.00');
});

// Avoid: Testing internal state or methods
it('should update internal state', () => {
	// Don't test component internals
});
```

### 2. Use Appropriate Test Helpers

The `_support` directory contains utilities specific to common components:

- **TestProvider.tsx**: Provides WordPress data context for components
- **userEvents.ts**: Utilities for simulating user interactions

```tsx
import TestProvider from '../_support/TestProvider';
import { simulateUserTyping } from '../_support/userEvents';

it('handles user typing', async () => {
	render(
		<TestProvider>
			<InputComponent />
		</TestProvider>
	);

	await simulateUserTyping(screen.getByRole('textbox'), 'Hello World');
	expect(screen.getByRole('textbox')).toHaveValue('Hello World');
});
```

### 3. Test Edge Cases

Always test edge cases and error conditions:

```tsx
describe('Component edge cases', () => {
	it('handles null values gracefully', () => {
		render(<Component value={null} />);
		expect(screen.getByRole('textbox')).toHaveValue('');
	});

	it('handles undefined values', () => {
		render(<Component value={undefined} />);
		expect(screen.getByRole('textbox')).toHaveValue('');
	});

	it('handles empty arrays', () => {
		render(<Component options={[]} />);
		expect(screen.getByText('No options available')).toBeInTheDocument();
	});
});
```

### 4. Keep Tests Focused and Independent

Each test should test one specific behavior and not depend on other tests:

```tsx
describe('CurrencyInput', () => {
	// Each test is independent
	beforeEach(() => {
		jest.clearAllMocks();
	});

	it('formats value on blur', () => {
		// Test only blur behavior
	});

	it('validates input on change', () => {
		// Test only validation
	});

	it('handles decimal precision', () => {
		// Test only decimal handling
	});
});
```

## Common Patterns

### Testing Form Components

```tsx
it('should validate required fields', () => {
	const mockOnError = jest.fn();
	render(
		<FormField
			required={true}
			value=""
			onError={mockOnError}
		/>
	);

	const input = screen.getByRole('textbox');
	fireEvent.blur(input);

	expect(mockOnError).toHaveBeenCalledWith('This field is required');
});
```

### Testing Async Components

```tsx
it('should load data asynchronously', async () => {
	render(<AsyncComponent />);

	// Component shows loading state initially
	expect(screen.getByText('Loading...')).toBeInTheDocument();

	// Wait for data to load
	await waitFor(() => {
		expect(screen.getByText('Data loaded')).toBeInTheDocument();
	});

	// Loading indicator should be gone
	expect(screen.queryByText('Loading...')).not.toBeInTheDocument();
});
```

### Testing Components with Timers

```tsx
it('should auto-save after delay', () => {
	jest.useFakeTimers();
	const mockSave = jest.fn();

	render(<AutoSaveInput onSave={mockSave} delay={1000} />);

	const input = screen.getByRole('textbox');
	fireEvent.change(input, { target: { value: 'New value' } });

	// Save should not be called immediately
	expect(mockSave).not.toHaveBeenCalled();

	// Advance timers
	act(() => {
		jest.advanceTimersByTime(1000);
	});

	// Save should now be called
	expect(mockSave).toHaveBeenCalledWith('New value');

	jest.useRealTimers();
});
```

## Troubleshooting

### Common Issues

1. **Module not found errors**
   - Check that imports use the correct aliases (`@tec/common/...`)
   - Verify the moduleNameMapper in jest.config.ts

2. **Component not rendering**
   - Ensure TestProvider wraps components that need context
   - Check that all required props are provided

3. **Async test failures**
   - Use `waitFor` for async operations
   - Remember to use `async/await` in test functions

4. **Mock not working**
   - Ensure mocks are defined before imports
   - Clear mocks between tests with `jest.clearAllMocks()`

### Debug Tips

```tsx
// Debug the current DOM state
screen.debug();

// Debug a specific element
const element = screen.getByRole('textbox');
screen.debug(element);

// Log testing playground URL for interactive debugging
screen.logTestingPlaygroundURL();

// Check all available queries
const { container } = render(<Component />);
console.log('HTML:', container.innerHTML);
```

## Additional Resources

- [Jest Documentation](https://jestjs.io/docs/getting-started)
- [React Testing Library](https://testing-library.com/docs/react-testing-library/intro/)
- [Testing Library Queries](https://testing-library.com/docs/queries/about)
- [WordPress Components Reference](https://developer.wordpress.org/block-editor/reference-guides/components/)