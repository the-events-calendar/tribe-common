# Linting Recommendations for Classy

**Project:** Classy BEM Refactoring
**Version:** 1.0
**Last Updated:** 2025-10-25

---

## Overview

This document provides comprehensive linting recommendations to enforce BEM naming conventions, maintain code quality, and ensure consistency across the Classy component system.

---

## 1. Stylelint for CSS

### Purpose

Stylelint enforces BEM naming conventions in CSS/PCSS files, ensuring all class names follow the `.classy-block__element--modifier` pattern.

---

### Installation

```bash
npm install --save-dev stylelint stylelint-config-standard stylelint-selector-bem-pattern
```

---

### Configuration

Create or update `.stylelintrc.js` in the project root:

```javascript
module.exports = {
  extends: ['stylelint-config-standard'],
  plugins: ['stylelint-selector-bem-pattern'],
  rules: {
    // Enforce BEM pattern for Classy components
    'plugin/selector-bem-pattern': {
      preset: 'bem',
      componentName: 'classy',
      componentSelectors: {
        // Match: .classy, .classy-modal, .classy-field
        initial: '^\\.{componentName}(?:-[a-z0-9]+(?:-[a-z0-9]+)*)?$',
        // Match: .classy-modal__header, .classy-modal__header--large
        combined: '^\\.{componentName}(?:-[a-z0-9]+(?:-[a-z0-9]+)*)?(?:__[a-z0-9]+(?:-[a-z0-9]+)*)?(?:--[a-z0-9]+(?:-[a-z0-9]+)*)?$'
      },
      utilitySelectors: '^\\.u-[a-z]+$',
      ignoreSelectors: [
        // Allow WordPress core classes
        '^\\.wp-',
        '^\\.components-',
        '^\\.editor-',
        // Allow TEC legacy classes (during migration)
        '^\\.tribe-',
        // Allow pseudo-classes and pseudo-elements
        '^::?[a-z-]+',
        // Allow attribute selectors
        '^\\[',
        // Allow ID selectors for specific cases
        '^#'
      ]
    },
    // Additional useful rules
    'selector-class-pattern': null, // Let bem-pattern handle this
    'custom-property-pattern': '^classy-[a-z0-9]+(-[a-z0-9]+)*$',
    'selector-max-specificity': '0,3,0',
    'max-nesting-depth': 3,
    'no-descending-specificity': null // Too strict for BEM
  }
};
```

---

### Running Stylelint

Add scripts to `package.json`:

```json
{
  "scripts": {
    "lint:css": "stylelint 'src/resources/packages/classy/**/*.pcss'",
    "lint:css:fix": "stylelint 'src/resources/packages/classy/**/*.pcss' --fix",
    "lint:css:common": "stylelint 'common/src/resources/packages/classy/**/*.pcss'",
    "lint:css:all": "npm run lint:css && npm run lint:css:common"
  }
}
```

Run commands:

```bash
# Lint CSS in plugin
npm run lint:css

# Lint and auto-fix
npm run lint:css:fix

# Lint Common CSS
npm run lint:css:common

# Lint everything
npm run lint:css:all
```

---

### Example Violations and Fixes

**❌ Violation: Wrong prefix**
```css
.modal { }
.dialog__header { }
```

**✅ Fix: Use classy- prefix**
```css
.classy-modal { }
.classy-modal__header { }
```

---

**❌ Violation: Single underscore/hyphen**
```css
.classy-modal_header { }
.classy-modal-variant { }
```

**✅ Fix: Double underscore for elements, double hyphen for modifiers**
```css
.classy-modal__header { }
.classy-modal--large { }
```

---

**❌ Violation: Wrong modifier syntax**
```css
.classy-modal.large { }
.classy-modal_large { }
```

**✅ Fix: Use double hyphen**
```css
.classy-modal--large { }
```

---

## 2. ESLint for TypeScript/TSX

### Purpose

ESLint validates className attributes in React components to ensure they follow BEM conventions and prevents common mistakes.

---

### Installation

```bash
npm install --save-dev eslint @typescript-eslint/parser @typescript-eslint/eslint-plugin eslint-plugin-react eslint-plugin-react-hooks
```

---

### Configuration

Create or update `.eslintrc.js`:

```javascript
module.exports = {
  parser: '@typescript-eslint/parser',
  extends: [
    'eslint:recommended',
    'plugin:@typescript-eslint/recommended',
    'plugin:react/recommended',
    'plugin:react-hooks/recommended'
  ],
  plugins: ['@typescript-eslint', 'react', 'react-hooks'],
  parserOptions: {
    ecmaVersion: 2020,
    sourceType: 'module',
    ecmaFeatures: {
      jsx: true
    }
  },
  settings: {
    react: {
      version: 'detect'
    }
  },
  rules: {
    // Custom rule for BEM className validation (see below)
    'classy-bem-classname': 'error',

    // TypeScript rules
    '@typescript-eslint/explicit-module-boundary-types': 'off',
    '@typescript-eslint/no-explicit-any': 'warn',

    // React rules
    'react/prop-types': 'off', // Using TypeScript for prop validation
    'react/react-in-jsx-scope': 'off' // Not needed in modern React
  }
};
```

---

### Custom ESLint Rule: BEM className Validation

Create `eslint-rules/classy-bem-classname.js`:

```javascript
/**
 * ESLint rule to enforce BEM naming in Classy components
 *
 * Validates that className attributes follow the pattern:
 * .classy-block__element--modifier
 */
module.exports = {
  meta: {
    type: 'problem',
    docs: {
      description: 'Enforce BEM naming convention in Classy components',
      category: 'Best Practices',
      recommended: true
    },
    messages: {
      invalidBEM: 'className "{{className}}" does not follow BEM convention: classy-block__element--modifier',
      missingPrefix: 'className "{{className}}" must start with "classy-"',
      wrongSeparator: 'Use __ for elements and -- for modifiers, not single underscore/hyphen'
    },
    schema: []
  },

  create(context) {
    // BEM pattern for Classy components
    const bemPattern = /^classy-[a-z0-9]+(-[a-z0-9]+)*(__[a-z0-9]+(-[a-z0-9]+)*)?(--[a-z0-9]+(-[a-z0-9]+)*)?$/;

    // Patterns to allow (WordPress, utilities, etc.)
    const allowedPatterns = [
      /^wp-/,
      /^components-/,
      /^editor-/,
      /^tribe-/,  // Legacy during migration
      /^u-/       // Utility classes
    ];

    function isAllowedClass(className) {
      return allowedPatterns.some(pattern => pattern.test(className));
    }

    function validateClassName(className) {
      // Skip allowed patterns
      if (isAllowedClass(className)) {
        return;
      }

      // Must start with classy-
      if (!className.startsWith('classy-')) {
        return 'missingPrefix';
      }

      // Check for wrong separators
      if (className.includes('_') && !className.includes('__')) {
        return 'wrongSeparator';
      }
      if (className.match(/(?<!-)-(?!-)/) && !className.match(/^classy-[a-z]/)) {
        return 'wrongSeparator';
      }

      // Validate full BEM pattern
      if (!bemPattern.test(className)) {
        return 'invalidBEM';
      }
    }

    return {
      JSXAttribute(node) {
        // Only check className attributes
        if (node.name.name !== 'className') {
          return;
        }

        // Get the className value
        const value = node.value;
        if (!value) {
          return;
        }

        let classNames = [];

        // Handle string literals
        if (value.type === 'Literal' && typeof value.value === 'string') {
          classNames = value.value.split(/\s+/).filter(Boolean);
        }

        // Handle template literals
        if (value.type === 'JSXExpressionContainer' &&
            value.expression.type === 'TemplateLiteral') {
          // Extract static parts
          const quasis = value.expression.quasis;
          quasis.forEach(quasi => {
            const classes = quasi.value.cooked.split(/\s+/).filter(Boolean);
            classNames.push(...classes);
          });
        }

        // Validate each className
        classNames.forEach(className => {
          const error = validateClassName(className);
          if (error) {
            context.report({
              node,
              messageId: error,
              data: { className }
            });
          }
        });
      }
    };
  }
};
```

---

### Loading Custom Rule

Update `.eslintrc.js`:

```javascript
module.exports = {
  // ... other config
  rules: {
    'classy-bem-classname': 'error'
  },
  // Load custom rules
  rulePaths: ['./eslint-rules']
};
```

---

### Running ESLint

Add scripts to `package.json`:

```json
{
  "scripts": {
    "lint:js": "eslint 'src/resources/packages/classy/**/*.{ts,tsx}'",
    "lint:js:fix": "eslint 'src/resources/packages/classy/**/*.{ts,tsx}' --fix",
    "lint:js:common": "eslint 'common/src/resources/packages/classy/**/*.{ts,tsx}'",
    "lint:js:all": "npm run lint:js && npm run lint:js:common"
  }
}
```

Run commands:

```bash
# Lint TypeScript/TSX
npm run lint:js

# Lint and auto-fix
npm run lint:js:fix

# Lint Common TypeScript
npm run lint:js:common

# Lint everything
npm run lint:js:all
```

---

### Example Violations and Fixes

**❌ Violation: Wrong className**
```tsx
<div className="modal">
  <div className="modal_header">
    <h2>Title</h2>
  </div>
</div>
```

**✅ Fix: Use BEM pattern**
```tsx
<div className="classy-modal">
  <div className="classy-modal__header">
    <h2>Title</h2>
  </div>
</div>
```

---

**❌ Violation: Missing prefix**
```tsx
<button className="submit-button">Submit</button>
```

**✅ Fix: Add classy- prefix**
```tsx
<button className="classy-button--submit">Submit</button>
```

---

## 3. Pre-commit Hooks

### Purpose

Run linters automatically before commits to catch issues early and maintain code quality.

---

### Installation

```bash
npm install --save-dev husky lint-staged
```

---

### Configuration

Initialize husky:

```bash
npx husky init
```

Create `.husky/pre-commit`:

```bash
#!/bin/sh
. "$(dirname "$0")/_/husky.sh"

npx lint-staged
```

Make it executable:

```bash
chmod +x .husky/pre-commit
```

---

### Lint-staged Configuration

Add to `package.json`:

```json
{
  "lint-staged": {
    "src/resources/packages/classy/**/*.pcss": [
      "stylelint --fix",
      "git add"
    ],
    "common/src/resources/packages/classy/**/*.pcss": [
      "stylelint --fix",
      "git add"
    ],
    "src/resources/packages/classy/**/*.{ts,tsx}": [
      "eslint --fix",
      "prettier --write",
      "git add"
    ],
    "common/src/resources/packages/classy/**/*.{ts,tsx}": [
      "eslint --fix",
      "prettier --write",
      "git add"
    ]
  }
}
```

---

### How It Works

When you commit:

1. Husky intercepts the commit
2. Lint-staged runs on staged files only
3. Linters run and auto-fix issues
4. Prettier formats code
5. Fixed files are re-staged
6. Commit proceeds (or fails if issues remain)

Example:

```bash
$ git commit -m "Add modal component"
✔ Preparing...
✔ Running tasks...
✔ Applying modifications...
✔ Cleaning up...
[main abc123] Add modal component
```

---

## 4. Build Integration

### Purpose

Integrate linting into the build process to ensure code quality in CI/CD pipelines.

---

### Package.json Scripts

Add comprehensive lint scripts:

```json
{
  "scripts": {
    "lint": "npm run lint:css && npm run lint:js",
    "lint:fix": "npm run lint:css:fix && npm run lint:js:fix",
    "lint:css": "stylelint 'src/resources/packages/classy/**/*.pcss' 'common/src/resources/packages/classy/**/*.pcss'",
    "lint:css:fix": "stylelint 'src/resources/packages/classy/**/*.pcss' 'common/src/resources/packages/classy/**/*.pcss' --fix",
    "lint:js": "eslint 'src/resources/packages/classy/**/*.{ts,tsx}' 'common/src/resources/packages/classy/**/*.{ts,tsx}'",
    "lint:js:fix": "eslint 'src/resources/packages/classy/**/*.{ts,tsx}' 'common/src/resources/packages/classy/**/*.{ts,tsx}' --fix",
    "prelint": "echo 'Running linters...'",
    "postlint": "echo 'Linting complete!'"
  }
}
```

---

### Build Process Integration

Update build script to include linting:

```json
{
  "scripts": {
    "prebuild": "npm run lint",
    "build": "webpack --config webpack.config.js",
    "build:dev": "webpack --config webpack.config.js --mode development",
    "build:prod": "webpack --config webpack.config.js --mode production"
  }
}
```

---

### CI/CD Integration

Add to `.github/workflows/ci.yml`:

```yaml
name: CI

on: [push, pull_request]

jobs:
  lint:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3

      - name: Setup Node.js
        uses: actions/setup-node@v3
        with:
          node-version: '18'
          cache: 'npm'

      - name: Install dependencies
        run: npm ci

      - name: Lint CSS
        run: npm run lint:css

      - name: Lint JavaScript/TypeScript
        run: npm run lint:js

      - name: Build
        run: npm run build

      - name: Test
        run: npm test

  test:
    needs: lint
    runs-on: ubuntu-latest
    # ... test configuration
```

---

### Fail on Violations

Configure webpack to fail on lint errors:

```javascript
// webpack.config.js
module.exports = {
  module: {
    rules: [
      {
        enforce: 'pre',
        test: /\.(ts|tsx)$/,
        exclude: /node_modules/,
        use: [
          {
            loader: 'eslint-loader',
            options: {
              failOnError: true,
              failOnWarning: false,
              emitError: true
            }
          }
        ]
      }
    ]
  }
};
```

---

## 5. IDE Integration

### VS Code

Install extensions:

- ESLint (dbaeumer.vscode-eslint)
- Stylelint (stylelint.vscode-stylelint)
- Prettier (esbenp.prettier-vscode)

Add to `.vscode/settings.json`:

```json
{
  "editor.formatOnSave": true,
  "editor.codeActionsOnSave": {
    "source.fixAll.eslint": true,
    "source.fixAll.stylelint": true
  },
  "css.validate": false,
  "stylelint.validate": ["css", "postcss", "pcss"],
  "eslint.validate": [
    "javascript",
    "javascriptreact",
    "typescript",
    "typescriptreact"
  ]
}
```

---

### PHPStorm/WebStorm

1. Open Settings/Preferences
2. Navigate to Languages & Frameworks > JavaScript > Code Quality Tools > ESLint
3. Enable ESLint
4. Navigate to Languages & Frameworks > Stylesheets > Stylelint
5. Enable Stylelint
6. Configure to run on save

---

## 6. Exceptions and Overrides

### When to Disable Rules

Linting rules should rarely be disabled, but sometimes it's necessary:

**Valid reasons:**
- Third-party component wrappers
- WordPress core component styling
- Legacy code during migration
- Utility classes

**Invalid reasons:**
- Laziness
- "It's too hard"
- Deadline pressure

---

### How to Disable Rules

**Disable for single line (CSS):**
```css
/* stylelint-disable-next-line plugin/selector-bem-pattern */
.tribe-legacy-class { }
```

**Disable for block (CSS):**
```css
/* stylelint-disable plugin/selector-bem-pattern */
.wp-block { }
.wp-block__element { }
/* stylelint-enable plugin/selector-bem-pattern */
```

**Disable for single line (TypeScript):**
```tsx
// eslint-disable-next-line classy-bem-classname
<div className="legacy-component">
```

**Disable for file (TypeScript):**
```tsx
/* eslint-disable classy-bem-classname */
// ... file content
/* eslint-enable classy-bem-classname */
```

---

### Document Exceptions

When disabling rules, always add a comment explaining why:

```css
/* stylelint-disable-next-line plugin/selector-bem-pattern */
/* Exception: WordPress core modal requires this class */
.wp-core-ui .button-primary { }
```

```tsx
// eslint-disable-next-line classy-bem-classname
// Exception: Third-party library component wrapper
<ThirdPartyModal className="external-modal">
```

---

## 7. Enforcement Checklist

Use this checklist before committing:

- [ ] Run `npm run lint:css` - no CSS violations
- [ ] Run `npm run lint:js` - no TypeScript violations
- [ ] Pre-commit hooks pass automatically
- [ ] All auto-fixes applied
- [ ] Any disabled rules documented
- [ ] IDE shows no lint errors
- [ ] Build succeeds with no warnings

---

## 8. Team Adoption

### Onboarding New Developers

1. Share this document
2. Install IDE extensions
3. Run `npm install` to get husky hooks
4. Run `npm run lint` to verify setup
5. Make test commit to verify hooks work

---

### Gradual Adoption

If adopting linting on an existing project:

1. Start with warnings instead of errors
2. Fix violations in new code first
3. Gradually fix violations in touched files
4. Eventually make rules errors
5. Remove legacy exceptions over time

Update `.eslintrc.js`:

```javascript
module.exports = {
  rules: {
    // Start as warning
    'classy-bem-classname': 'warn',
    // Later change to error
    // 'classy-bem-classname': 'error'
  }
};
```

---

## 9. Maintenance

### Keeping Rules Updated

Review linting configuration quarterly:

- Are rules still relevant?
- Are exceptions still needed?
- Do patterns need updating?
- Are new rules available?

---

### Monitoring Compliance

Track linting metrics:

```bash
# Count violations
npm run lint:css 2>&1 | grep -c "error"
npm run lint:js 2>&1 | grep -c "error"

# Track over time
echo "$(date),$(npm run lint:css 2>&1 | grep -c 'error')" >> lint-metrics.csv
```

---

## 10. Troubleshooting

### Common Issues

**Issue: Pre-commit hooks not running**

Solution:
```bash
# Reinstall husky
npm install husky --save-dev
npx husky init
chmod +x .husky/pre-commit
```

---

**Issue: Stylelint not recognizing PCSS**

Solution: Add to `.stylelintrc.js`:
```javascript
module.exports = {
  customSyntax: 'postcss-scss'
};
```

---

**Issue: Too many violations to fix**

Solution: Start with new code only:
```json
{
  "lint-staged": {
    "*.pcss": ["stylelint"],
    "*.{ts,tsx}": ["eslint"]
  }
}
```

---

**Issue: ESLint rule not loading**

Solution: Check rule path:
```javascript
module.exports = {
  rulePaths: [path.resolve(__dirname, 'eslint-rules')]
};
```

---

## Conclusion

Linting is critical for maintaining BEM consistency and code quality in the Classy system. By following these recommendations, you ensure:

- Consistent BEM naming
- Early error detection
- Automated enforcement
- Team-wide compliance
- Long-term maintainability

**Remember:** Linters are helpers, not hindrances. They catch mistakes before they reach production.

---

## Resources

- **Stylelint:** https://stylelint.io/
- **ESLint:** https://eslint.org/
- **Husky:** https://typicode.github.io/husky/
- **Lint-staged:** https://github.com/okonet/lint-staged
- **BEM:** https://getbem.com/

---

**End of Linting Recommendations**
