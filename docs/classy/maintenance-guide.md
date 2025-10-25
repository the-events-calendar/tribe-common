# Maintenance Guide for Classy

**Project:** Classy BEM Refactoring
**Version:** 1.0
**Last Updated:** 2025-10-25

---

## Overview

This guide provides comprehensive procedures for maintaining, updating, and evolving the Classy component system over time. It covers component updates, adding new components, extending existing components, deprecation strategies, and handling breaking changes.

---

## Table of Contents

1. [Component Update Protocol](#1-component-update-protocol)
2. [Adding New Components](#2-adding-new-components)
3. [Extending Existing Components](#3-extending-existing-components)
4. [Deprecation Strategy](#4-deprecation-strategy)
5. [Breaking Change Process](#5-breaking-change-process)
6. [Version Management](#6-version-management)
7. [Documentation Updates](#7-documentation-updates)
8. [Testing Requirements](#8-testing-requirements)
9. [Communication Protocol](#9-communication-protocol)
10. [Maintenance Checklist](#10-maintenance-checklist)

---

## 1. Component Update Protocol

### When Updating a Common Component

Use this protocol when modifying any component in Common:

**Step 1: Assess Impact**
```bash
# Find all usages of the component
cd /Users/lucatume/work/tec/tec-dev/wp-content/plugins

# Search in TEC
grep -r "ClassyModal" the-events-calendar/src/ --include="*.tsx" --include="*.ts"

# Search in ECP
grep -r "ClassyModal" events-pro/src/ --include="*.tsx" --include="*.ts"

# Search in ET
grep -r "ClassyModal" event-tickets/src/ --include="*.tsx" --include="*.ts"

# Search in ETP
grep -r "ClassyModal" event-tickets-plus/src/ --include="*.tsx" --include="*.ts"
```

Document:
- Which plugins use this component?
- How many instances?
- Are they using the full API or subset?
- Will changes break existing usage?

---

**Step 2: Plan the Update**

Create an update plan:

```markdown
## ClassyModal Update Plan

**Component:** ClassyModal
**Type of change:** Enhancement
**Breaking change:** No

### Current API
- Props: isOpen, onClose, title, children
- Slots: None

### Proposed Changes
- Add prop: size ("small" | "medium" | "large")
- Add prop: closeOnOverlayClick (default: true)
- Add slot: tec.classy.modal.footer

### Impact Assessment
- Used in: TEC (3 places), ET (2 places)
- Breaking: No (new props are optional)
- Migration needed: No

### Implementation Steps
1. Update ClassyModal.tsx in TEC Common
2. Add size styles to style.pcss
3. Add tests for new props
4. Update component-guide.md
5. Sync to ET Common
6. Build both plugins
7. Test all existing usages
```

---

**Step 3: Update Component Code**

Make changes in TEC Common first:

```bash
cd /Users/lucatume/work/tec/tec-dev/wp-content/plugins/the-events-calendar

# Edit component
code common/src/resources/packages/classy/components/ClassyModal.tsx

# Edit styles if needed
code common/src/resources/packages/classy/style.pcss
```

Follow BEM conventions and existing patterns.

---

**Step 4: Update Tests**

Add tests for new functionality:

```bash
# Edit test file
code tests/classy_jest/ClassyModal.test.tsx
```

Ensure all tests pass:

```bash
nvm use
npm run classy:jest
```

---

**Step 5: Update Documentation**

Update component-guide.md:

```bash
code common/src/resources/packages/classy/component-guide.md
```

Add:
- New props to component API documentation
- Usage examples for new features
- Migration notes if needed

---

**Step 6: Sync to ET Common**

```bash
cd /Users/lucatume/work/tec/tec-dev/wp-content/plugins

# Sync
rsync -av --delete \
  the-events-calendar/common/src/resources/packages/classy/ \
  event-tickets/common/src/resources/packages/classy/

# Verify
diff -rq \
  the-events-calendar/common/src/resources/packages/classy/ \
  event-tickets/common/src/resources/packages/classy/
```

---

**Step 7: Build All Plugins**

```bash
# Build TEC
cd the-events-calendar
nvm use && npm run build

# Build ECP
cd ../events-pro
nvm use && npm run build

# Build ET
cd ../event-tickets
nvm use && npm run build

# Build ETP
cd ../event-tickets-plus
nvm use && npm run build
```

All builds must succeed.

---

**Step 8: Test All Affected Features**

Manual testing checklist:

- [ ] Test feature in TEC where component is used
- [ ] Test feature in ECP where component is used
- [ ] Test feature in ET where component is used
- [ ] Test feature in ETP where component is used
- [ ] Test new functionality specifically
- [ ] Test that existing usage still works
- [ ] Test edge cases
- [ ] Test in different browsers

---

**Step 9: Commit with Clear Message**

```bash
cd /Users/lucatume/work/tec/tec-dev

git add the-events-calendar/common/src/resources/packages/classy/
git add event-tickets/common/src/resources/packages/classy/

git commit -m "Update ClassyModal with size prop and footer slot

- Add size prop: small, medium, large
- Add closeOnOverlayClick prop
- Add footer slot for custom actions
- Update styles for size variants
- Add tests for new functionality
- Update component documentation
- Synced to both TEC and ET Common

🤖 Generated with [Claude Code](https://claude.com/claude-code)

Co-Authored-By: Claude <noreply@anthropic.com>"
```

---

### When Updating a Plugin-Specific Component

Simpler process for plugin-specific components:

**Step 1: Locate Component**

```bash
# Example: TEC-specific field
cd /Users/lucatume/work/tec/tec-dev/wp-content/plugins/the-events-calendar

# Component location
ls src/resources/packages/classy/fields/VenueField.tsx
```

---

**Step 2: Update Component Code**

```bash
# Edit component
code src/resources/packages/classy/fields/VenueField.tsx

# Edit styles if needed
code src/resources/packages/classy/style.pcss
```

---

**Step 3: Update Tests**

```bash
# Edit test
code tests/classy_jest/VenueField.test.tsx

# Run tests
nvm use
npm run classy:jest
```

---

**Step 4: Build Affected Plugin**

```bash
# Build TEC only
nvm use
npm run build
```

---

**Step 5: Test Affected Features**

- [ ] Test feature in browser
- [ ] Verify existing functionality still works
- [ ] Test new functionality
- [ ] Test edge cases

---

**Step 6: Update Documentation (If Significant)**

If the change is significant:

```bash
# Update plugin-specific documentation
code docs/classy-venue-field.md
```

Minor changes may not need documentation updates.

---

**Step 7: Commit**

```bash
git add src/resources/packages/classy/
git commit -m "Update VenueField validation logic

- Add email validation for venue contact
- Add error message display
- Update tests

🤖 Generated with [Claude Code](https://claude.com/claude-code)

Co-Authored-By: Claude <noreply@anthropic.com>"
```

No sync needed for plugin-specific components.

---

## 2. Adding New Components

### Decision Tree

Use this decision tree to determine where to add components:

```
Is this component used by multiple plugins?
├─ YES → Will it be used by both TEC and ET?
│   ├─ YES → Add to Common (requires sync)
│   │   └─ Example: DatePicker, TimePicker, Modal
│   └─ NO → Will it be shared within one product family?
│       ├─ YES (TEC+ECP) → Add to TEC Common
│       ├─ YES (ET+ETP) → Add to ET Common
│       └─ NO → Add to specific plugin
└─ NO → Is it a core UI pattern that might be reused?
    ├─ YES → Consider adding to Common for future
    └─ NO → Add to specific plugin
```

---

### Adding to Common

**When to add to Common:**
- Used by both TEC and ET
- Core UI pattern (modal, field, card)
- Reusable across features
- No plugin-specific logic

**Process:**

**Step 1: Create Component in TEC Common**

```bash
cd /Users/lucatume/work/tec/tec-dev/wp-content/plugins/the-events-calendar

# Create component file
touch common/src/resources/packages/classy/components/ClassyDateRangePicker.tsx

# Edit component
code common/src/resources/packages/classy/components/ClassyDateRangePicker.tsx
```

---

**Step 2: Follow Component Patterns**

Use existing components as templates:

```tsx
/**
 * ClassyDateRangePicker Component
 *
 * Reusable date range picker for TEC and ET plugins.
 *
 * @package TEC\Common\Classy
 */

import React from 'react';
import { useState } from '@wordpress/element';

/**
 * Props for ClassyDateRangePicker
 */
export interface ClassyDateRangePickerProps {
    /** Start date value */
    startDate?: Date;
    /** End date value */
    endDate?: Date;
    /** Callback when dates change */
    onChange: (start: Date | null, end: Date | null) => void;
    /** Minimum selectable date */
    minDate?: Date;
    /** Maximum selectable date */
    maxDate?: Date;
    /** Disabled state */
    disabled?: boolean;
    /** Additional CSS class */
    className?: string;
}

/**
 * ClassyDateRangePicker component
 */
export const ClassyDateRangePicker: React.FC<ClassyDateRangePickerProps> = ({
    startDate,
    endDate,
    onChange,
    minDate,
    maxDate,
    disabled = false,
    className = ''
}) => {
    // Implementation...

    return (
        <div className={`classy-date-range-picker ${className}`}>
            {/* Component JSX */}
        </div>
    );
};
```

---

**Step 3: Add Styles**

```bash
# Edit style.pcss
code common/src/resources/packages/classy/style.pcss
```

Add BEM-compliant styles:

```css
/* Date Range Picker */
.classy-date-range-picker {
    display: flex;
    gap: var(--classy-gap);
}

.classy-date-range-picker__input {
    flex: 1;
}

.classy-date-range-picker__separator {
    display: flex;
    align-items: center;
    color: var(--classy-text-secondary);
}

.classy-date-range-picker--disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
```

---

**Step 4: Export Component**

```bash
# Edit index.ts
code common/src/resources/packages/classy/components/index.ts
```

Add export:

```typescript
export { ClassyDateRangePicker } from './ClassyDateRangePicker';
export type { ClassyDateRangePickerProps } from './ClassyDateRangePicker';
```

---

**Step 5: Write Tests**

```bash
# Create test file
touch tests/classy_jest/ClassyDateRangePicker.test.tsx

# Edit test
code tests/classy_jest/ClassyDateRangePicker.test.tsx
```

Write comprehensive tests:

```tsx
import { render, screen, fireEvent } from '@testing-library/react';
import { ClassyDateRangePicker } from '@tec/common/classy/components';

describe('ClassyDateRangePicker', () => {
    it('renders with start and end dates', () => {
        const onChange = jest.fn();
        const startDate = new Date('2025-01-01');
        const endDate = new Date('2025-01-31');

        render(
            <ClassyDateRangePicker
                startDate={startDate}
                endDate={endDate}
                onChange={onChange}
            />
        );

        expect(screen.getByDisplayValue('01/01/2025')).toBeInTheDocument();
        expect(screen.getByDisplayValue('01/31/2025')).toBeInTheDocument();
    });

    it('calls onChange when dates are selected', () => {
        const onChange = jest.fn();

        render(<ClassyDateRangePicker onChange={onChange} />);

        // Test date selection...
    });

    // More tests...
});
```

Run tests:

```bash
nvm use
npm run classy:jest
```

---

**Step 6: Document in Component Catalog**

```bash
code common/src/resources/packages/classy/component-guide.md
```

Add to Component Catalog section:

```markdown
### ClassyDateRangePicker

**Location:** `common/src/resources/packages/classy/components/ClassyDateRangePicker.tsx`
**Type:** Input Component
**Used by:** TEC, ET

**Purpose:** Reusable date range picker for selecting start and end dates.

**Props:**

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| startDate | Date \| undefined | undefined | Start date value |
| endDate | Date \| undefined | undefined | End date value |
| onChange | (start, end) => void | required | Callback when dates change |
| minDate | Date \| undefined | undefined | Minimum selectable date |
| maxDate | Date \| undefined | undefined | Maximum selectable date |
| disabled | boolean | false | Disabled state |
| className | string | '' | Additional CSS class |

**Usage:**

\`\`\`tsx
import { ClassyDateRangePicker } from '@tec/common/classy/components';

const MyComponent = () => {
    const [startDate, setStartDate] = useState<Date | null>(null);
    const [endDate, setEndDate] = useState<Date | null>(null);

    return (
        <ClassyDateRangePicker
            startDate={startDate}
            endDate={endDate}
            onChange={(start, end) => {
                setStartDate(start);
                setEndDate(end);
            }}
        />
    );
};
\`\`\`

**CSS Classes:**

- `.classy-date-range-picker` - Root container
- `.classy-date-range-picker__input` - Date input field
- `.classy-date-range-picker__separator` - Separator between dates
- `.classy-date-range-picker--disabled` - Disabled state
```

---

**Step 7: Sync to ET Common**

```bash
cd /Users/lucatume/work/tec/tec-dev/wp-content/plugins

# Sync
rsync -av --delete \
  the-events-calendar/common/src/resources/packages/classy/ \
  event-tickets/common/src/resources/packages/classy/

# Verify
diff -rq \
  the-events-calendar/common/src/resources/packages/classy/ \
  event-tickets/common/src/resources/packages/classy/
```

---

**Step 8: Build and Test**

```bash
# Build TEC
cd the-events-calendar
nvm use && npm run build

# Build ET
cd ../event-tickets
nvm use && npm run build

# Test TEC
cd ../the-events-calendar
nvm use && npm run classy:jest

# Test ET
cd ../event-tickets
nvm use && npm run classy:jest
```

---

**Step 9: Commit**

```bash
cd /Users/lucatume/work/tec/tec-dev

git add the-events-calendar/common/src/resources/packages/classy/
git add event-tickets/common/src/resources/packages/classy/

git commit -m "Add ClassyDateRangePicker to Common

- Create ClassyDateRangePicker component
- Add date range picker styles
- Add comprehensive tests
- Update component catalog
- Export from components index
- Synced to both TEC and ET Common

🤖 Generated with [Claude Code](https://claude.com/claude-code)

Co-Authored-By: Claude <noreply@anthropic.com>"
```

---

### Adding to Plugin

**When to add to plugin:**
- Feature-specific component
- Contains plugin-specific logic
- Uses plugin-specific dependencies
- Not reusable across plugins

**Process:**

**Step 1: Create Component in Plugin**

```bash
cd /Users/lucatume/work/tec/tec-dev/wp-content/plugins/the-events-calendar

# Create in plugin directory
touch src/resources/packages/classy/fields/RecurrenceField.tsx

# Edit component
code src/resources/packages/classy/fields/RecurrenceField.tsx
```

---

**Step 2: Follow Component Patterns**

Use Common components as building blocks:

```tsx
/**
 * RecurrenceField Component
 *
 * TEC-specific field for event recurrence patterns.
 *
 * @package TEC\Classy
 */

import React from 'react';
import { ClassyField, DatePicker } from '@tec/common/classy/components';
import { FieldProps } from '@tec/common/classy/types';

export interface RecurrenceFieldProps extends FieldProps {
    pattern: string;
    onPatternChange: (pattern: string) => void;
}

export const RecurrenceField: React.FC<RecurrenceFieldProps> = ({
    label,
    value,
    onChange,
    pattern,
    onPatternChange,
    ...fieldProps
}) => {
    return (
        <ClassyField
            label={label}
            className="classy-recurrence-field"
            {...fieldProps}
        >
            {/* Use Common components */}
            <DatePicker value={value} onChange={onChange} />
            {/* Plugin-specific UI */}
            <div className="classy-recurrence-field__pattern">
                {/* Recurrence pattern UI */}
            </div>
        </ClassyField>
    );
};
```

---

**Step 3: Add Plugin-Specific Styles**

```bash
# Edit plugin style.pcss
code src/resources/packages/classy/style.pcss
```

Add styles:

```css
/* Recurrence Field (TEC-specific) */
.classy-recurrence-field {
    /* Extends base ClassyField styles */
}

.classy-recurrence-field__pattern {
    margin-top: var(--classy-gap);
    padding: var(--classy-padding);
    border: 1px solid var(--classy-border);
}
```

---

**Step 4: Write Tests**

```bash
# Create test
touch tests/classy_jest/RecurrenceField.test.tsx

# Edit test
code tests/classy_jest/RecurrenceField.test.tsx

# Run tests
nvm use
npm run classy:jest
```

---

**Step 5: Build and Test**

```bash
# Build TEC
nvm use
npm run build

# Manual testing
# Test in browser
```

---

**Step 6: Commit**

```bash
git add src/resources/packages/classy/
git commit -m "Add RecurrenceField for event recurrence

- Create RecurrenceField component
- Add recurrence pattern UI
- Add tests
- Add styles

🤖 Generated with [Claude Code](https://claude.com/claude-code)

Co-Authored-By: Claude <noreply@anthropic.com>"
```

No sync needed - plugin-specific only.

---

## 3. Extending Existing Components

### When to Extend vs Create New

**Extend existing component when:**
- Adding a variant of existing functionality
- Sharing most of the base behavior
- Slight visual or behavioral difference
- Semantically related to base component

**Create new component when:**
- Fundamentally different functionality
- Different data structure
- Different user interaction pattern
- Semantically distinct purpose

---

### Extension Method 1: BEM Modifiers

Best for: Visual variants

**Example: Large Modal Variant**

```css
/* Base in Common */
.classy-modal {
    width: 500px;
}

/* Variant in plugin */
.classy-modal--large {
    width: 800px;
}

.classy-modal--full-screen {
    width: 100vw;
    height: 100vh;
}
```

Usage:

```tsx
// Standard modal
<ClassyModal className="classy-modal">

// Large modal
<ClassyModal className="classy-modal--large">

// Full screen modal
<ClassyModal className="classy-modal--full-screen">
```

---

### Extension Method 2: CSS Variable Overrides

Best for: Theming and spacing adjustments

**Example: Tight Spacing Variant**

```css
/* Base in Common */
:root {
    --classy-padding: 16px;
    --classy-gap: 12px;
}

.classy-modal {
    padding: var(--classy-padding);
    gap: var(--classy-gap);
}

/* Override in plugin */
.classy-tickets-modal {
    --classy-padding: 8px;
    --classy-gap: 6px;
}
```

Usage:

```tsx
<div className="classy-tickets-modal">
    <ClassyModal>
        {/* Modal now uses tighter spacing */}
    </ClassyModal>
</div>
```

---

### Extension Method 3: Slot/Fill Pattern

Best for: Adding content to existing components

**Example: Add Footer to Modal**

```tsx
// Common component provides slot
import { Slot } from '@tec/common/packages/slot-fill';

export const ClassyModal = ({ children }) => {
    return (
        <div className="classy-modal">
            <div className="classy-modal__content">
                {children}
            </div>
            <Slot name="tec.classy.modal.footer" />
        </div>
    );
};

// Plugin fills the slot
import { Fill } from '@tec/common/packages/slot-fill';

export const TicketModalFooter = () => {
    return (
        <Fill name="tec.classy.modal.footer">
            <div className="classy-modal__footer">
                <Button>Save Ticket</Button>
            </div>
        </Fill>
    );
};
```

---

### Extension Method 4: Composition

Best for: Building complex components from simple ones

**Example: Complex Field from Simple Components**

```tsx
import { ClassyField, DatePicker, TimePicker } from '@tec/common/classy/components';

export const DateTimeField = ({ label, dateValue, timeValue, onChange }) => {
    return (
        <ClassyField label={label} className="classy-datetime-field">
            <div className="classy-datetime-field__inputs">
                <DatePicker
                    value={dateValue}
                    onChange={(date) => onChange({ date, time: timeValue })}
                />
                <TimePicker
                    value={timeValue}
                    onChange={(time) => onChange({ date: dateValue, time })}
                />
            </div>
        </ClassyField>
    );
};
```

---

### Extension Method 5: Props Extension

Best for: Adding optional functionality

**Example: Extended Modal Props**

```tsx
// In plugin, create wrapper with extended props
import { ClassyModal, ClassyModalProps } from '@tec/common/classy/components';

interface ExtendedModalProps extends ClassyModalProps {
    showTicketInfo?: boolean;
    ticketData?: TicketData;
}

export const TicketModal: React.FC<ExtendedModalProps> = ({
    showTicketInfo = false,
    ticketData,
    children,
    ...modalProps
}) => {
    return (
        <ClassyModal {...modalProps}>
            {showTicketInfo && ticketData && (
                <div className="classy-modal__ticket-info">
                    {/* Ticket-specific content */}
                </div>
            )}
            {children}
        </ClassyModal>
    );
};
```

---

## 4. Deprecation Strategy

### When to Deprecate

Deprecate a component or pattern when:

- Better alternative exists
- Pattern is outdated or problematic
- Causing maintenance burden
- Security or performance issues
- Standardizing on different approach

**Don't deprecate if:**
- Still widely used and working
- No clear alternative
- Would cause excessive migration work

---

### Deprecation Process

**Step 1: Mark as Deprecated in Code**

```tsx
/**
 * ClassyOldModal Component
 *
 * @deprecated Since 1.5.0 - Use ClassyModal instead
 * @see ClassyModal for the new API
 *
 * This component will be removed in version 2.0.0
 */
export const ClassyOldModal: React.FC<ClassyOldModalProps> = (props) => {
    // Log deprecation warning
    if (process.env.NODE_ENV === 'development') {
        console.warn(
            'ClassyOldModal is deprecated and will be removed in v2.0.0. ' +
            'Use ClassyModal instead. See component-guide.md for migration.'
        );
    }

    // Existing implementation
    return <div>...</div>;
};
```

---

**Step 2: Add Deprecation Notice to Documentation**

Update component-guide.md:

```markdown
### ClassyOldModal (DEPRECATED)

**Status:** ⚠️ **DEPRECATED** - Will be removed in v2.0.0
**Replacement:** Use `ClassyModal` instead

**Migration Guide:**

Old usage:
\`\`\`tsx
<ClassyOldModal open={true} onClose={handleClose}>
    <div>Content</div>
</ClassyOldModal>
\`\`\`

New usage:
\`\`\`tsx
<ClassyModal isOpen={true} onClose={handleClose}>
    <div>Content</div>
</ClassyModal>
\`\`\`

**Key differences:**
- Prop `open` renamed to `isOpen`
- Prop `onClose` remains the same
- Children structure unchanged

**Timeline:**
- Deprecated: v1.5.0 (2025-10-01)
- Removal: v2.0.0 (2026-04-01)
```

---

**Step 3: Provide Migration Path**

Create migration document:

```bash
touch common/docs/classy/migrations/old-modal-to-modal.md
```

Content:

```markdown
# Migration: ClassyOldModal → ClassyModal

## Why?

ClassyOldModal uses inconsistent API. ClassyModal standardizes prop names and improves accessibility.

## How to Migrate

### 1. Update Import

\`\`\`diff
- import { ClassyOldModal } from '@tec/common/classy/components';
+ import { ClassyModal } from '@tec/common/classy/components';
\`\`\`

### 2. Rename Props

| Old Prop | New Prop | Change |
|----------|----------|--------|
| open | isOpen | Renamed |
| onClose | onClose | No change |
| title | title | No change |

### 3. Update Usage

\`\`\`diff
- <ClassyOldModal
+ <ClassyModal
-     open={isOpen}
+     isOpen={isOpen}
      onClose={handleClose}
      title="My Modal"
  >
      <div>Content</div>
- </ClassyOldModal>
+ </ClassyModal>
\`\`\`

## Find All Usages

\`\`\`bash
grep -r "ClassyOldModal" src/ --include="*.tsx" --include="*.ts"
\`\`\`

## Testing

After migration, test:
- [ ] Modal opens correctly
- [ ] Modal closes correctly
- [ ] Content renders properly
- [ ] Accessibility (keyboard, screen reader)
```

---

**Step 4: Keep Working for 2+ Releases**

Maintain deprecated component for at least 2 major releases:

```
v1.5.0 - Deprecated
v1.6.0 - Still works with warning
v1.7.0 - Still works with warning
v2.0.0 - Removed
```

This gives users time to migrate.

---

**Step 5: Eventually Remove**

When removal time comes:

```bash
# Remove component file
rm common/src/resources/packages/classy/components/ClassyOldModal.tsx

# Remove from exports
# Edit common/src/resources/packages/classy/components/index.ts

# Remove tests
rm tests/classy_jest/ClassyOldModal.test.tsx

# Update documentation
# Mark as removed in component-guide.md
```

Add to changelog:

```markdown
## v2.0.0 - 2026-04-01

### BREAKING CHANGES

- **Removed**: ClassyOldModal (deprecated since v1.5.0)
  - Use ClassyModal instead
  - See migration guide: docs/classy/migrations/old-modal-to-modal.md
```

---

## 5. Breaking Change Process

### When Breaking Changes are Acceptable

Breaking changes should be rare and only in these situations:

**Acceptable:**
- Major version bumps (v1.x → v2.x)
- Security vulnerabilities
- Critical bugs that can't be fixed otherwise
- With clear migration path and advance notice

**Not acceptable:**
- Minor version bumps (v1.5 → v1.6)
- Convenience refactors
- Personal preferences
- Without deprecation period

---

### Breaking Change Process

**Step 1: Document What's Changing**

Create breaking change RFC:

```markdown
# RFC: ClassyModal API Redesign

## Status: Proposed

## Context

Current ClassyModal API has inconsistencies:
- Some props use `is` prefix, others don't
- Event handlers have inconsistent naming
- Missing important accessibility props

## Proposed Changes

### Breaking Changes

1. **Prop Renaming**
   - `open` → `isOpen`
   - `onRequestClose` → `onClose`

2. **Removed Props**
   - `legacy` prop (no longer needed)

3. **New Required Props**
   - `title` now required for accessibility

### Migration Path

Provide codemod script for automatic migration.

## Timeline

- RFC: 2025-11-01
- Deprecation: v1.6.0 (2025-12-01)
- Removal: v2.0.0 (2026-06-01)

## Impact

Affects ~50 usages across TEC, ECP, ET, ETP.
```

---

**Step 2: Explain Why**

Document justification:

```markdown
## Why This Breaking Change?

### Current Problems

1. **Inconsistent API**
   - `open` vs `isOpen` vs `visible`
   - `onRequestClose` vs `onClose` vs `handleClose`

2. **Accessibility Issues**
   - Missing required ARIA labels
   - No focus management

3. **Technical Debt**
   - Legacy props from old implementation
   - Confusing prop combinations

### Benefits of Change

1. **Consistency**
   - All boolean props use `is` prefix
   - All event handlers use `on` prefix

2. **Accessibility**
   - Required `title` ensures ARIA labels
   - Built-in focus management

3. **Maintainability**
   - Cleaner API
   - Easier to understand
   - Better TypeScript types
```

---

**Step 3: Provide Migration Guide**

Detailed migration instructions:

```markdown
## Migration Guide: ClassyModal v2

### Automated Migration

Use our codemod:

\`\`\`bash
npx @tec/classy-codemods modal-v2 src/
\`\`\`

### Manual Migration

#### 1. Update Props

\`\`\`diff
  <ClassyModal
-   open={isModalOpen}
+   isOpen={isModalOpen}
-   onRequestClose={closeModal}
+   onClose={closeModal}
+   title="Event Details"
  >
    {children}
  </ClassyModal>
\`\`\`

#### 2. Remove Legacy Props

\`\`\`diff
  <ClassyModal
    isOpen={isModalOpen}
    onClose={closeModal}
    title="Event Details"
-   legacy={false}
  >
    {children}
  </ClassyModal>
\`\`\`

#### 3. Add Required Title

\`\`\`diff
  <ClassyModal
    isOpen={isModalOpen}
    onClose={closeModal}
+   title="Event Details"
  >
    {children}
  </ClassyModal>
\`\`\`

### Testing After Migration

\`\`\`bash
# Run automated tests
npm run classy:jest

# Manual testing checklist
- [ ] Modal opens
- [ ] Modal closes
- [ ] Title displays
- [ ] Focus management works
- [ ] Keyboard navigation works
- [ ] Screen reader announces title
\`\`\`
```

---

**Step 4: Update Documentation**

Update component-guide.md with migration timeline:

```markdown
### ClassyModal

**Current Version:** v2.0.0
**Last Breaking Change:** v2.0.0 (2026-06-01)

⚠️ **BREAKING CHANGES in v2.0.0**

If upgrading from v1.x, see [Migration Guide: ClassyModal v2](migrations/modal-v2.md)

**Key changes:**
- `open` → `isOpen`
- `onRequestClose` → `onClose`
- `title` is now required
- `legacy` prop removed

**Props:**

| Prop | Type | Default | Required | Description |
|------|------|---------|----------|-------------|
| isOpen | boolean | false | Yes | Modal visibility |
| onClose | () => void | - | Yes | Close handler |
| title | string | - | Yes | Modal title (for ARIA) |
| children | ReactNode | - | Yes | Modal content |
```

---

**Step 5: Communicate to Team**

Before release:

1. **Slack announcement:**
   ```
   📢 BREAKING CHANGE in ClassyModal v2.0.0

   ClassyModal API has been redesigned for consistency and accessibility.

   Key changes:
   - open → isOpen
   - onRequestClose → onClose
   - title now required

   Migration guide: [link]
   Codemod available: npx @tec/classy-codemods modal-v2

   Questions? Ask in #classy
   ```

2. **Team meeting:**
   - Present changes
   - Demonstrate migration
   - Answer questions

3. **Documentation:**
   - Update component-guide.md
   - Create migration guide
   - Update examples

---

**Step 6: Test Thoroughly**

Extensive testing before release:

```bash
# Unit tests
npm run classy:jest

# Integration tests
npm run test:integration

# Build all plugins
cd the-events-calendar && npm run build
cd ../events-pro && npm run build
cd ../event-tickets && npm run build
cd ../event-tickets-plus && npm run build

# Manual testing
# Test every usage of ClassyModal
# Test in multiple browsers
# Test with screen readers
```

---

**Step 7: Version Bump**

Update version numbers:

```json
// package.json
{
  "version": "2.0.0"
}
```

Follow semantic versioning:
- Major: Breaking changes (1.0.0 → 2.0.0)
- Minor: New features (1.5.0 → 1.6.0)
- Patch: Bug fixes (1.5.0 → 1.5.1)

---

## 6. Version Management

### Semantic Versioning

Follow semantic versioning (semver):

```
MAJOR.MINOR.PATCH

Examples:
1.0.0 → Initial release
1.0.1 → Bug fix (patch)
1.1.0 → New feature (minor)
2.0.0 → Breaking change (major)
```

---

### When to Bump Versions

**Major (X.0.0):**
- Breaking changes
- Removing deprecated features
- API redesigns

**Minor (1.X.0):**
- New components
- New features
- New props (backward compatible)
- Deprecations

**Patch (1.0.X):**
- Bug fixes
- Style tweaks
- Documentation updates
- Performance improvements

---

### Version Documentation

Track versions in CHANGELOG.md:

```markdown
# Classy Changelog

## [2.0.0] - 2026-06-01

### BREAKING CHANGES
- ClassyModal API redesigned
  - `open` → `isOpen`
  - `onRequestClose` → `onClose`
  - `title` now required
  - See migration guide

### Added
- ClassyDateRangePicker component
- Accessibility improvements across all modals

### Fixed
- ClassyField validation timing
- TimePicker timezone handling

## [1.6.0] - 2026-03-01

### Added
- ClassyTimePicker component
- New slot in ClassyModal footer

### Deprecated
- ClassyOldModal (use ClassyModal)

### Fixed
- DatePicker month navigation
- ClassyCard hover state

## [1.5.0] - 2026-01-01

### Added
- ClassyModal component
- ClassyField component
- DatePicker component

### Fixed
- Initial release bugs
```

---

## 7. Documentation Updates

### When to Update Documentation

Update component-guide.md when:

- Adding new component (always)
- Changing component API (always)
- Deprecating component (always)
- Breaking changes (always)
- New patterns or best practices (recommended)
- Bug fixes affecting usage (sometimes)
- Internal refactors (rarely)

---

### Documentation Checklist

When updating documentation:

- [ ] Update component catalog
- [ ] Update props tables
- [ ] Update code examples
- [ ] Update migration guides if breaking changes
- [ ] Update quick reference if patterns changed
- [ ] Add deprecation notices if applicable
- [ ] Update changelog
- [ ] Sync to both TEC and ET Common

---

## 8. Testing Requirements

### Test Coverage Requirements

**Required for all changes:**
- Unit tests for new functionality
- Unit tests for changed functionality
- Update tests for breaking changes
- All existing tests pass

**Required for Common changes:**
- Tests pass in TEC
- Tests pass in ET
- Integration tests pass (if applicable)

**Required for breaking changes:**
- Migration path tested
- Backward compatibility tested (during deprecation)
- All affected features tested manually

---

### Test Quality Standards

All tests must:

- Have clear, descriptive names
- Test one thing per test
- Be deterministic (no flaky tests)
- Have proper setup and teardown
- Use appropriate matchers
- Include edge cases
- Mock external dependencies properly

---

## 9. Communication Protocol

### Internal Communication

**For minor changes:**
- Commit message is sufficient
- Optional Slack update in #classy

**For new components:**
- Commit message
- Slack announcement in #classy
- Demo in team meeting (optional)

**For breaking changes:**
- RFC document
- Team meeting discussion
- Slack announcement
- Migration guide
- Email to stakeholders

---

### External Communication

**For users/customers:**
- Changelog in release notes
- Migration guides
- Blog post for major changes
- Support documentation updates

---

## 10. Maintenance Checklist

### Before Making Changes

- [ ] Read relevant documentation
- [ ] Understand current implementation
- [ ] Assess impact (Common vs plugin-specific)
- [ ] Plan the change
- [ ] Identify affected components/features

---

### During Implementation

- [ ] Follow BEM naming conventions
- [ ] Follow existing patterns
- [ ] Write clean, documented code
- [ ] Add comprehensive tests
- [ ] Update documentation
- [ ] Sync Common if needed

---

### Before Committing

- [ ] All tests pass
- [ ] All builds succeed
- [ ] Code follows style guide
- [ ] Documentation updated
- [ ] Common synced and verified
- [ ] Manual testing complete
- [ ] Clear commit message

---

### After Committing

- [ ] Monitor for issues
- [ ] Respond to questions
- [ ] Fix bugs promptly
- [ ] Update documentation if needed

---

## Conclusion

Maintaining the Classy system requires:

- Following established processes
- Clear communication
- Thorough testing
- Comprehensive documentation
- Respect for backward compatibility
- Thoughtful deprecation

**Remember:**
- Changes to Common affect multiple plugins
- Always sync TEC and ET Common
- Deprecate before removing
- Provide migration paths
- Test thoroughly
- Document everything

By following this guide, you ensure the Classy system remains maintainable, reliable, and valuable for the team.

---

**End of Maintenance Guide**
