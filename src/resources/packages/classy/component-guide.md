# Classy Component & Style Guide

**Project:** Classy BEM Refactoring
**Version:** 1.0
**Last Updated:** 2025-10-25
**Status:** Complete

---

## About This Guide

This is the **primary reference documentation** for developing with the Classy component system. It provides comprehensive guidance on:

- BEM naming conventions and CSS architecture
- Component patterns and best practices
- CSS variable system and theming
- Slot/Fill extensibility patterns
- Redux store integration
- Build system and development workflow
- Testing strategies
- Maintenance and enforcement

**Who should use this guide:**
- Developers working on TEC, ECP, ET, or ETP plugins
- Anyone creating or modifying Classy components
- Team members maintaining the Classy architecture

**How to use this guide:**
- Start with [BEM Naming Conventions](#1-bem-naming-conventions) to understand the CSS foundation
- Review [Component Architecture Patterns](#2-component-architecture-patterns) for implementation guidance
- Reference [Component Catalog](#6-component-catalog) for available components and their APIs
- Use [Build System & Development](#8-build-system--development) for daily development workflow

---

## Table of Contents

1. [BEM Naming Conventions](#1-bem-naming-conventions)
   - Pattern Explanation
   - Complete Block Reference
   - Guidelines for New Classes
   - Common Pitfalls
   - Migration Guide

2. [Component Architecture Patterns](#2-component-architecture-patterns)
   - When to Use Common vs Plugin-Specific
   - Modal Pattern
   - Card Pattern
   - Field Pattern
   - Upsert Pattern

3. [CSS Variable System](#3-css-variable-system)
   - Complete Variable Inventory
   - Variable Naming Conventions
   - Theming Guidelines
   - Scoping Rules
   - TEC Design Token Integration

4. [Slot/Fill Extensibility](#4-slotfill-extensibility)
   - Available Slots Reference
   - Slot Naming Convention
   - Fill Registration Examples
   - How to Discover Slots
   - Best Practices

5. [Redux Store Patterns](#5-redux-store-patterns)
   - Store Naming and Registration
   - State Shape Conventions
   - Selector Patterns
   - Action Creator Patterns
   - Cross-Store Access

6. [Component Catalog](#6-component-catalog)
   - Layout Components
   - Input Components
   - UI Components
   - Icon Components
   - Card Components
   - Button Components
   - Field Components

7. [Import Path & Module Resolution](#7-import-path--module-resolution)
   - Webpack Configuration
   - Import Conventions
   - Dependency Graph
   - External Resolution Pattern

8. [Build System & Development](#8-build-system--development)
   - Build Process Overview
   - Build Commands
   - Build Order Dependencies
   - Development Workflow
   - Common Sync in Builds

9. [Testing Patterns](#9-testing-patterns)
   - Jest Configuration
   - Test Utilities from Common
   - Mocking WordPress Dependencies
   - Testing Slot/Fill Interactions
   - Store Testing Patterns
   - Component Testing Examples

10. [Enforcement & Maintenance](#10-enforcement--maintenance)
    - Linting Recommendations
    - Common Sync Procedures
    - Component Update Protocol
    - Adding New Components
    - Extending Existing Components
    - Deprecation Strategy
    - Breaking Change Process

---

## 1. BEM Naming Conventions

### Overview

The Classy architecture uses **BEM (Block Element Modifier)** methodology for all CSS class names. This provides a consistent, scalable naming system that prevents specificity conflicts and makes component relationships clear.

**Core BEM Pattern:**
```
.classy-block__element--modifier
```

**Key Rules:**
- All classes start with `classy-` prefix
- Double underscore (`__`) separates elements from blocks
- Double hyphen (`--`) separates modifiers from blocks/elements
- Single hyphens (`-`) for multi-word names

---

### Pattern Explanation

#### Block

A **block** is a standalone component that can exist independently.

```css
/* Block: Modal component */
.classy-modal { }

/* Block: Field component */
.classy-field { }

/* Block: Button component */
.classy-button { }
```

**When to create a block:**
- Component can exist independently
- Represents a complete UI pattern
- Not dependent on parent context

---

#### Element

An **element** is a part of a block that has no standalone meaning.

```css
/* Element: Modal header (part of modal) */
.classy-modal__header { }

/* Element: Modal content (part of modal) */
.classy-modal__content { }

/* Element: Field label (part of field) */
.classy-field__label { }
```

**When to create an element:**
- Part of a larger component
- Cannot exist independently
- Semantically tied to parent block

**Important:** Elements are always written relative to the block, never nested:

```css
/* CORRECT */
.classy-modal__header { }
.classy-modal__header-title { }

/* INCORRECT - Don't nest elements */
.classy-modal__header__title { }
```

---

#### Modifier

A **modifier** changes the appearance or behavior of a block or element.

```css
/* Modifier: Virtual location variant of modal */
.classy-modal--virtual-location { }

/* Modifier: Ticket overlay variant of modal */
.classy-modal__overlay--ticket { }

/* Modifier: Invalid state of field control */
.classy-field__control--invalid { }

/* Modifier: Selected state of weekday button */
.classy-field__weekday-button--selected { }
```

**When to create a modifier:**
- Variant appearance of a component
- Different state (active, disabled, invalid)
- Different context (small, large)
- Special behavior (expanded, collapsed)

---

### Complete Block Reference

Here are all BEM blocks in the Classy architecture:

#### Core Container Blocks

| Block | Purpose | Plugin | Example |
|-------|---------|--------|---------|
| `.classy-root` | Main wrapper container | Common | Used in all modal content |
| `.classy-container` | Content container | Common | Main field container |
| `.classy-section-separator` | Visual separator | Common | Between sections |

---

#### Field Blocks

| Block | Purpose | Plugin | Example |
|-------|---------|--------|---------|
| `.classy-field` | Base field component | Common | All fields use this |
| `.classy-field__label` | Field label | Common | Input labels |
| `.classy-field__control` | Input control wrapper | Common | Wraps inputs |
| `.classy-field__input` | Text input | Common | Name, description inputs |
| `.classy-field__input-note` | Help text | Common | Validation messages |
| `.classy-field__group` | Field grouping | Common | Related fields |
| `.classy-field__ticket-table` | Ticket list container | ET | Ticket management |
| `.classy-field__ticket-row` | Individual ticket row | ET | Each ticket |
| `.classy-field__weekdays` | Weekday selector | ECP | Recurrence weekdays |
| `.classy-field__weekday-button` | Weekday button | ECP | Sun, Mon, etc. |
| `.classy-field__event-cost` | Event cost field | TEC | Cost input |
| `.classy-field__currency` | Currency selector | Common | Currency dropdown |

---

#### Modal Blocks

| Block | Purpose | Plugin | Example |
|-------|---------|--------|---------|
| `.classy-modal` | Base modal structure | Common | All modals |
| `.classy-modal__overlay` | Modal overlay wrapper | Common | Overlay container |
| `.classy-modal__header` | Modal header | Common | Title area |
| `.classy-modal__content` | Modal content | Common | Form content |
| `.classy-modal__footer` | Modal footer | Common | Action buttons |
| `.classy-modal__actions` | Action button container | Common | Save/Cancel buttons |

**Modal Modifiers:**
```css
.classy-modal--venue                    /* Venue modal variant */
.classy-modal--organizer                /* Organizer modal variant */
.classy-modal--virtual-location         /* Virtual location modal (ECP) */
.classy-modal__overlay--ticket          /* Ticket modal overlay (ET) */
```

---

#### Button Blocks

| Block | Purpose | Plugin | Example |
|-------|---------|--------|---------|
| `.classy-button` | Base button | Common | Generic button |
| `.classy-button__destructive` | Delete button | ET | Remove ticket |

---

#### Icon Blocks

| Block | Purpose | Plugin | Example |
|-------|---------|--------|---------|
| `.classy-icon` | Base icon wrapper | Common | All icons |
| `.classy-icon__add` | Add icon | Common | Create new |
| `.classy-icon__edit` | Edit icon | Common | Edit item |
| `.classy-icon__trash` | Delete icon | Common | Remove item |
| `.classy-icon__calendar` | Calendar icon | Common | Date picker |
| `.classy-icon__video-camera` | Video icon | Common | Virtual location |
| `.classy-icon__ticket` | Ticket icon | Common | Tickets |
| `.classy-icon__cog` | Settings icon | Common | Configuration |
| `.classy-icon__close` | Close icon | Common | Close modal |

---

#### Card Blocks

| Block | Purpose | Plugin | Example |
|-------|---------|--------|---------|
| `.classy__linked-post-card` | Base card component | Common | All entity cards |
| `.classy-linked-post-card__title` | Card title | Common | Entity name |
| `.classy-linked-post-card__detail` | Card detail row | Common | Address, phone, etc. |
| `.classy-linked-post-card__section` | Card section | Common | Grouped content |
| `.classy-linked-post-card__actions` | Card actions | Common | Edit/Remove buttons |

**Card Modifiers:**
```css
.classy__linked-post-card--venue              /* Venue card */
.classy__linked-post-card--organizer          /* Organizer card */
.classy__linked-post-card--virtual-location   /* Virtual location card (ECP) */
```

---

#### Component Blocks

| Block | Purpose | Plugin | Example |
|-------|---------|--------|---------|
| `.classy-component__input-label` | Input label | Common | Form labels |
| `.classy-component__labeled-input` | Labeled input wrapper | Common | Label + input |
| `.classy-component__error-display` | Error message | Common | Validation errors |
| `.classy-component__centered-spinner` | Loading spinner | Common | Loading state |
| `.classy-component__currency-input` | Currency input | Common | Price inputs |
| `.classy-component__currency-selector` | Currency dropdown | Common | Currency picker |
| `.classy-component__date-picker` | Date picker | Common | Date selection |
| `.classy-component__time-picker` | Time picker | Common | Time selection |
| `.classy-component__timezone` | Timezone selector | Common | Timezone picker |

---

### Real-World Examples

#### Example 1: Modal Structure

```css
/* Block */
.classy-modal {
    /* Base modal styles */
}

/* Element */
.classy-modal__header {
    align-items: center;
    display: flex;
    padding: var(--classy-padding-vertical) var(--classy-padding-horizontal);
}

.classy-modal__content {
    display: flex;
    flex-direction: column;
    gap: var(--tec-spacer-4);
}

/* Modifier */
.classy-modal--virtual-location {
    .classy-modal__footer {
        background-color: var(--classy-color-footer);
        /* Virtual location specific footer */
    }
}
```

**Usage in TSX:**
```tsx
<Modal className="classy-modal classy-modal--virtual-location">
    <div className="classy-root">
        <header className="classy-modal__header">
            <IconNew />
            <h4>New Virtual Location</h4>
        </header>
        <section className="classy-modal__content">
            {/* Form content */}
        </section>
        <footer className="classy-modal__footer">
            {/* Action buttons */}
        </footer>
    </div>
</Modal>
```

---

#### Example 2: Field with Elements

```css
/* Block */
.classy-field {
    display: flex;
    flex-direction: column;
    gap: var(--tec-spacer-2);
}

/* Elements */
.classy-field__label {
    color: var(--tec-color-text-primary);
    font-size: var(--classy-font-size-13);
    font-weight: var(--tec-font-weight-bold);
}

.classy-field__control {
    width: 100%;
}

/* Element with modifier */
.classy-field__control--input {
    width: var(--classy-input-width);
}

.classy-field__control--invalid {
    border-color: var(--tec-color-icon-error);
}
```

**Usage in TSX:**
```tsx
<ClassyField title="Venue Name">
    <InputControl
        className="classy-field__control classy-field__control--input"
        value={name}
        onChange={setName}
    />
</ClassyField>
```

---

#### Example 3: Card with Actions

```css
/* Block with modifier */
.classy__linked-post-card--venue {
    border: 1px solid var(--classy-color-border);
    padding: var(--tec-spacer-4);
}

/* Elements */
.classy-linked-post-card__title {
    font-size: var(--classy-font-size-16);
    font-weight: var(--tec-font-weight-bold);
    margin-bottom: var(--tec-spacer-2);
}

.classy-linked-post-card__detail {
    color: var(--tec-color-text-secondary);
    margin-bottom: var(--tec-spacer-1);
}

.classy-linked-post-card__actions {
    display: flex;
    gap: var(--tec-spacer-2);
    margin-top: var(--tec-spacer-3);
}
```

**Usage in TSX:**
```tsx
<div className="classy__linked-post-card classy__linked-post-card--venue">
    <h4 className="classy-linked-post-card__title">{venueName}</h4>
    <span className="classy-linked-post-card__detail">{address}</span>
    <span className="classy-linked-post-card__detail">{phone}</span>
    <div className="classy-linked-post-card__actions">
        <Button onClick={onEdit}><IconEdit /></Button>
        <Button onClick={onRemove}><IconTrash /></Button>
    </div>
</div>
```

---

### Guidelines for New Classes

#### When to Create a New Block

**Create a new block when:**
1. **Component is standalone**
   ```css
   /* New standalone component */
   .classy-status-indicator { }
   ```

2. **Component is reusable across contexts**
   ```css
   /* Reusable UI pattern */
   .classy-dropdown { }
   ```

3. **Component has independent meaning**
   ```css
   /* Independent entity */
   .classy-notification { }
   ```

---

#### When to Create a New Element

**Create an element when:**
1. **Part of a larger block**
   ```css
   /* Part of modal */
   .classy-modal__header { }
   ```

2. **Cannot exist independently**
   ```css
   /* Requires parent field */
   .classy-field__label { }
   ```

3. **Semantically tied to parent**
   ```css
   /* Only meaningful in card context */
   .classy-linked-post-card__actions { }
   ```

---

#### When to Use a Modifier

**Use a modifier for:**
1. **Visual variants**
   ```css
   .classy-button--primary { }
   .classy-button--secondary { }
   ```

2. **State changes**
   ```css
   .classy-field__control--invalid { }
   .classy-field__weekday-button--selected { }
   ```

3. **Context-specific styling**
   ```css
   .classy-modal--virtual-location { }
   .classy-modal__overlay--ticket { }
   ```

4. **Size variants**
   ```css
   .classy-field--small { }
   .classy-field--large { }
   ```

---

### Common Pitfalls

#### Pitfall 1: Using Single Underscore or Hyphen

**Wrong:**
```css
/* ❌ Single underscore (not BEM) */
.classy_field_control { }

/* ❌ Single hyphen for element (should be __) */
.classy-modal-header { }
```

**Correct:**
```css
/* ✅ Double underscore for elements */
.classy-field__control { }

/* ✅ Double hyphen for modifiers */
.classy-modal--venue { }
```

---

#### Pitfall 2: Typos in Prefix

**Wrong:**
```css
/* ❌ Typo: 'class' instead of 'classy' */
.class-field { }

/* ❌ Missing prefix */
.modal__header { }
```

**Correct:**
```css
/* ✅ Always start with 'classy-' */
.classy-field { }
.classy-modal__header { }
```

---

#### Pitfall 3: Nesting Elements

**Wrong:**
```css
/* ❌ Nested elements (don't do this) */
.classy-modal__header__title { }
.classy-field__control__input { }
```

**Correct:**
```css
/* ✅ Elements are flat, not nested */
.classy-modal__header-title { }
.classy-field__control-input { }

/* OR use modifier */
.classy-field__control--input { }
```

---

#### Pitfall 4: Mixing BEM with Non-BEM

**Wrong:**
```css
/* ❌ Mixing conventions */
.classy-modal .header { }
.classy-field > input { }
```

**Correct:**
```css
/* ✅ Always use full BEM classes */
.classy-modal__header { }
.classy-field__input { }
```

---

#### Pitfall 5: Generic Modifier Names

**Wrong:**
```css
/* ❌ Too generic */
.classy-field--special { }
.classy-modal--custom { }
.classy-button--new { }
```

**Correct:**
```css
/* ✅ Descriptive modifiers */
.classy-field--currency { }
.classy-modal--virtual-location { }
.classy-button--destructive { }
```

---

### Migration Guide

#### Finding BEM Violations

**Step 1: Search for common patterns**

```bash
# Find single underscores (should be double)
grep -r "classy_" --include="*.pcss" --include="*.css"

# Find typos
grep -r "\.class-" --include="*.pcss" --include="*.css"

# Find nested elements (three underscores)
grep -r "___" --include="*.pcss" --include="*.css"
```

---

**Step 2: Search in TSX files**

```bash
# Find className violations in TypeScript
grep -r 'className="classy_' --include="*.tsx" --include="*.ts"

# Find typos in components
grep -r 'className="class-' --include="*.tsx" --include="*.ts"
```

---

#### Fixing Violations

**Example 1: Fix Underscore**

Before:
```css
.classy_field_control {
    width: 100%;
}
```

After:
```css
.classy-field__control {
    width: 100%;
}
```

Update in TSX:
```tsx
// Before
<input className="classy_field_control" />

// After
<input className="classy-field__control" />
```

---

**Example 2: Fix Nested Elements**

Before:
```css
.classy-modal__header__title {
    font-size: 18px;
}
```

After:
```css
.classy-modal__header-title {
    font-size: 18px;
}
```

---

**Example 3: Fix Typo**

Before:
```css
.class-field {
    display: flex;
}
```

After:
```css
.classy-field {
    display: flex;
}
```

---

#### Testing After Fixes

**Step 1: Build the project**
```bash
nvm use && npm run build
```

**Step 2: Check for errors**
- Look for console errors in browser
- Verify components still render correctly
- Test component interactions

**Step 3: Visual regression**
- Compare before/after screenshots
- Check all component states (hover, active, invalid)
- Test responsive layouts

---

### BEM Best Practices Summary

**DO:**
- ✅ Always use `classy-` prefix
- ✅ Use double underscore (`__`) for elements
- ✅ Use double hyphen (`--`) for modifiers
- ✅ Keep elements flat (no nesting)
- ✅ Use descriptive modifier names
- ✅ Be consistent across all files

**DON'T:**
- ❌ Mix BEM with other methodologies
- ❌ Use single underscores or hyphens
- ❌ Nest elements (no `block__element__child`)
- ❌ Use generic names (special, custom, new)
- ❌ Forget the `classy-` prefix
- ❌ Mix case styles (always lowercase with hyphens)

---

## 2. Component Architecture Patterns

### Overview

The Classy architecture defines four core component patterns that cover all use cases in TEC, ECP, ET, and ETP. Each pattern has specific structure, state management, and usage conventions.

**The Four Patterns:**
1. **Modal Pattern** - For creating/editing entities in dialogs
2. **Card Pattern** - For displaying entity data with actions
3. **Field Pattern** - For form inputs and controls
4. **Upsert Pattern** - For create/update forms (combines Modal + validation + API)

---

### When to Use Common vs Plugin-Specific

#### Decision Tree

```
Is this component used by multiple plugins?
├─ YES → Create in Common
└─ NO  → Is it a generic UI pattern?
    ├─ YES → Create in Common
    └─ NO  → Is it feature-specific?
        ├─ YES → Create in plugin
        └─ NO  → Reconsider (might belong in Common)
```

---

#### Common Components

**Put in Common when:**
- Component is used by multiple plugins (TEC + ET, TEC + ECP)
- Component is a generic UI pattern (modal, field, button)
- Component has no domain logic (pure presentation)
- Component could be reused in future plugins

**Examples of Common components:**
```typescript
// Base patterns - always Common
ClassyModal        // Used by all plugins
ClassyField        // Used by all plugins
DatePicker         // Used by TEC, ECP
TimePicker         // Used by TEC, ECP
CurrencyInput      // Used by TEC, ET
ErrorBoundary      // Used by all plugins
CenteredSpinner    // Used by all plugins
```

---

#### Plugin-Specific Components

**Put in plugin when:**
- Component implements feature-specific logic
- Component is tied to domain concepts (tickets, recurrence)
- Component only used by one plugin
- Component has no clear reuse potential

**Examples of plugin-specific components:**
```typescript
// TEC Plugin
VenueCard          // Venue-specific
VenueUpsert        // Venue logic
OrganizerCard      // Organizer-specific

// ECP Plugin
ZoomCard           // Zoom-specific
RecurrencePreview  // Recurrence-specific
WeekdayButtons     // Recurrence UI

// ET Plugin
TicketRow          // Ticket-specific
CapacityField      // Ticket capacity logic
```

---

### Pattern 1: Modal Pattern

Modals follow a **two-component structure**: wrapper + content.

#### Structure Overview

```
VenueUpsertModal.tsx (Wrapper)
└─> WordPress Modal component
    └─> VenueUpsert.tsx (Content)
        ├─> Header (classy-modal__header)
        ├─> Separator (classy-section-separator)
        ├─> Content (classy-modal__content)
        └─> Footer (classy-modal__footer)
            └─> Actions (classy-modal__actions)
```

---

#### Wrapper Component

**Purpose:** Provides WordPress Modal integration and BEM classes

**Location:** Same directory as content component

**File:** `VenueUpsertModal.tsx`

```typescript
import * as React from 'react';
import { Modal } from '@wordpress/components';
import VenueUpsert from './VenueUpsert';
import { VenueData } from '../../types/VenueData';

export default function VenueUpsertModal(props: {
    isUpdate: boolean;
    onCancel: () => void;
    onClose: () => void;
    onSave: (venueData: VenueData) => void;
    values: VenueData;
}) {
    const { isUpdate, onCancel, onClose, onSave, values } = props;

    return (
        <Modal
            __experimentalHideHeader={true}
            className="classy-modal classy-modal--venue"
            onRequestClose={onClose}
            overlayClassName="classy-modal__overlay classy-modal__overlay--venue"
        >
            <VenueUpsert
                isUpdate={isUpdate}
                onCancel={onCancel}
                onSave={onSave}
                values={values}
            />
        </Modal>
    );
}
```

**Key points:**
- `__experimentalHideHeader={true}` - Use custom header in content
- `className` - BEM block with modifier
- `overlayClassName` - Separate overlay styling
- Passes all props to content component

---

#### Content Component

**Purpose:** Contains form logic, validation, and UI

**Location:** Same directory as wrapper

**File:** `VenueUpsert.tsx`

```typescript
import * as React from 'react';
import { useState, useCallback } from 'react';
import { __experimentalInputControl as InputControl, Button } from '@wordpress/components';
import { IconNew, LabeledInput } from '@tec/common/classy/components';
import { isValidUrl } from '@tec/common/classy/functions';
import { VenueData } from '../../types/VenueData';

const defaultValues: VenueData = {
    id: 0,
    name: '',
    address: '',
    city: '',
    state: '',
    zip: '',
    phone: '',
    website: ''
};

export default function VenueUpsert(props: {
    isUpdate: boolean;
    onCancel: () => void;
    onSave: (data: VenueData) => void;
    values: VenueData;
}) {
    const { isUpdate, onCancel, onSave, values } = props;

    // State management
    const [currentValues, setCurrentValues] = useState({
        ...defaultValues,
        ...values,
    });

    // Validation state
    const [confirmEnabled, setConfirmEnabled] = useState(currentValues.name !== '');
    const [hasValidUrl, setHasValidUrl] = useState<boolean>(true);

    // Save handler
    const invokeSaveWithData = useCallback((): void => {
        if (!confirmEnabled) return;
        onSave(currentValues);
    }, [currentValues, confirmEnabled, onSave]);

    // URL validation
    const handleWebsiteChange = useCallback((value: string) => {
        if (value && !isValidUrl(value)) {
            setHasValidUrl(false);
            return;
        }
        setHasValidUrl(true);
        setCurrentValues({ ...currentValues, website: value });
    }, [currentValues]);

    return (
        <div className="classy-root">
            {/* HEADER */}
            <header className="classy-modal__header classy-modal__header--venue">
                <IconNew />
                <h4 className="classy-modal__header-title">
                    {isUpdate ? 'Update Venue' : 'New Venue'}
                </h4>
            </header>

            {/* SEPARATOR */}
            <span className="classy-section-separator"></span>

            {/* CONTENT */}
            <section className="classy-modal__content classy-modal__content--venue">
                <LabeledInput label="Name">
                    <InputControl
                        value={currentValues.name}
                        onChange={(value) => {
                            const newValue = value || '';
                            setConfirmEnabled(newValue.trim() !== '');
                            setCurrentValues({ ...currentValues, name: newValue });
                        }}
                        required
                    />
                </LabeledInput>

                <LabeledInput label="Address">
                    <InputControl
                        value={currentValues.address}
                        onChange={(value) =>
                            setCurrentValues({ ...currentValues, address: value || '' })
                        }
                    />
                </LabeledInput>

                <LabeledInput label="Website">
                    <InputControl
                        className={!hasValidUrl ? 'classy-field__control--invalid' : ''}
                        value={currentValues.website}
                        onChange={handleWebsiteChange}
                    />
                    {!hasValidUrl && (
                        <div className="classy-field__input-note classy-field__input-note--error">
                            Must be a valid URL
                        </div>
                    )}
                </LabeledInput>

                {/* More inputs... */}
            </section>

            {/* FOOTER */}
            <footer className="classy-modal__footer classy-modal__footer--venue">
                <div className="classy-modal__actions classy-modal__actions--venue">
                    <Button
                        aria-disabled={!confirmEnabled || !hasValidUrl}
                        onClick={invokeSaveWithData}
                        variant="primary"
                    >
                        {isUpdate ? 'Update Venue' : 'Create Venue'}
                    </Button>
                    <Button onClick={onCancel} variant="link">
                        Cancel
                    </Button>
                </div>
            </footer>
        </div>
    );
}
```

---

#### Validation Pattern

**State-based validation:**

```typescript
// Validation state
const [confirmEnabled, setConfirmEnabled] = useState(false);
const [hasValidUrl, setHasValidUrl] = useState(true);
const [hasValidEmail, setHasValidEmail] = useState(true);

// Multiple validation checks
const isValid = confirmEnabled && hasValidUrl && hasValidEmail;

// Update validation on input change
onChange={(value) => {
    if (!isValidUrl(value)) {
        setHasValidUrl(false);
        return;
    }
    setHasValidUrl(true);
    setCurrentValues({ ...currentValues, website: value });
}}

// Button uses validation state
<Button
    aria-disabled={!isValid}
    onClick={invokeSaveWithData}
>
    Save
</Button>
```

**Common validation states:**
- `confirmEnabled` - Primary action can be performed
- `hasValidUrl` - URL format is valid
- `hasValidEmail` - Email format is valid
- `hasRequiredFields` - All required fields filled

---

#### Error Handling Pattern

**Display errors inline:**

```typescript
// Error state
const [urlError, setUrlError] = useState<string | null>(null);

// Validation
if (!isValidUrl(value)) {
    setUrlError('Must be a valid URL');
    return;
}
setUrlError(null);

// Display
<InputControl
    className={urlError ? 'classy-field__control--invalid' : ''}
    value={website}
    onChange={onWebsiteChange}
/>
{urlError && (
    <div className="classy-field__input-note classy-field__input-note--error">
        {urlError}
    </div>
)}
```

---

#### API Integration Pattern

**Async API calls with loading state:**

```typescript
// Loading state
const [isSaving, setIsSaving] = useState(false);
const [error, setError] = useState<string | null>(null);

// Save handler
const invokeSaveWithData = useCallback(async (): Promise<void> => {
    if (!confirmEnabled) return;

    setIsSaving(true);
    setError(null);

    try {
        const response = await createVenue(currentValues);

        if (response.status === 200) {
            onSave(response.data);
        } else {
            setError('Failed to save venue');
        }
    } catch (error) {
        setError(error.message);
    } finally {
        setIsSaving(false);
    }
}, [currentValues, confirmEnabled]);

// Button shows loading state
<Button
    aria-disabled={!confirmEnabled || isSaving}
    onClick={invokeSaveWithData}
>
    {isSaving ? 'Saving...' : 'Save Venue'}
</Button>

// Error display
{error && (
    <ErrorDisplay message={error} />
)}
```

---

#### Modal Pattern Checklist

When creating a modal:

- [ ] Create wrapper component with `Modal` from `@wordpress/components`
- [ ] Use `__experimentalHideHeader={true}` for custom headers
- [ ] Apply BEM classes: `classy-modal classy-modal--{type}`
- [ ] Apply overlay class: `classy-modal__overlay classy-modal__overlay--{type}`
- [ ] Create content component with validation logic
- [ ] Use `classy-root` wrapper in content
- [ ] Include header with `classy-modal__header`
- [ ] Add separator with `classy-section-separator`
- [ ] Wrap inputs in `classy-modal__content`
- [ ] Add footer with `classy-modal__footer`
- [ ] Include actions in `classy-modal__actions`
- [ ] Implement validation state (`confirmEnabled`, etc.)
- [ ] Handle API calls asynchronously
- [ ] Show loading states during API calls
- [ ] Display errors inline
- [ ] Use `aria-disabled` for buttons (not `disabled`)

---

### Pattern 2: Card Pattern

Cards display entity data with actions (edit, remove, settings).

#### Base Structure

```typescript
interface BaseCardProps {
    data: EntityData;
    onEdit?: (id: number) => void;
    onRemove: (id: number) => void;
    onSettingsClick?: () => void;
}
```

---

#### Simple Card Example: VenueCard

**File:** `/the-events-calendar/src/resources/packages/classy/fields/EventLocation/VenueCard.tsx`

```typescript
import * as React from 'react';
import { Button } from '@wordpress/components';
import { decodeEntities } from '@wordpress/html-entities';
import { IconEdit, IconTrash } from '@tec/common/classy/components';
import { FetchedVenue } from '../../types/VenueData';

export default function VenueCard(
    props: FetchedVenue & {
        onEdit: (id: number) => void;
        onRemove: (id: number) => void;
        addressSeparator: string;
    }
) {
    const {
        id: venueId,
        venue: name,
        address,
        city,
        stateprovince,
        zip,
        phone,
        website,
        addressSeparator
    } = props;

    // Build full address
    const addressParts = [address, city, stateprovince, zip].filter(Boolean);
    const fullAddress = addressParts.join(addressSeparator);

    return (
        <div
            className="classy__linked-post-card classy__linked-post-card--venue"
            data-object-id={venueId}
        >
            {/* Title */}
            <h4 className="classy-linked-post-card__title">
                {decodeEntities(name)}
            </h4>

            {/* Details */}
            {fullAddress && (
                <span className="classy-linked-post-card__detail">
                    {decodeEntities(fullAddress)}
                </span>
            )}

            {phone && (
                <span className="classy-linked-post-card__detail">
                    {decodeEntities(phone)}
                </span>
            )}

            {website && (
                <Button
                    variant="link"
                    className="classy-linked-post-card__detail"
                    href={website}
                    target="_blank"
                >
                    {decodeEntities(website)}
                </Button>
            )}

            {/* Actions */}
            <div className="classy-linked-post-card__actions">
                <Button
                    variant="link"
                    onClick={() => props.onEdit(venueId)}
                    className="classy-linked-post-card__action"
                >
                    <IconEdit />
                </Button>
                <Button
                    variant="link"
                    onClick={() => props.onRemove(venueId)}
                    className="classy-linked-post-card__action"
                >
                    <IconTrash />
                </Button>
            </div>
        </div>
    );
}
```

**Key Points:**
- Simple data display, no API state
- Uses `decodeEntities` for all text content
- Actions at bottom (edit, remove)
- BEM classes with modifier: `classy__linked-post-card--venue`

---

#### Complex Card Example: ZoomCard

**File:** `/events-pro/src/resources/packages/classy/fields/VirtualLocation/Cards/ZoomCard.tsx`

```typescript
import * as React from 'react';
import { useCallback, useState } from 'react';
import { useSelect } from '@wordpress/data';
import { Button, FormTokenField } from '@wordpress/components';
import { decodeEntities } from '@wordpress/html-entities';
import { IconVideoCamera, IconTrash, IconCog, CenteredSpinner } from '@tec/common/classy/components';
import { ZoomVirtualLocationData } from '../../types/VirtualLocation';
import { removeMeeting } from '../../api/zoom';

interface ZoomCardProps {
    postId: number;
    data: { id: string; url: string };
    onRemove: () => void;
    onSettingsClick?: () => void;
}

export default function ZoomCard(props: ZoomCardProps): JSX.Element {
    const { postId, data, onRemove, onSettingsClick } = props;

    // Fetch meeting data from store
    const { meetingType, meetingName, hostEmail, id, alternativeHosts, removeLink } =
        useSelect((select) => {
            const store = select('tec/classy/events-pro');
            const meetingId = data?.id || 0;
            return store.getZoomMeetingData(postId, meetingId);
        }, [data]);

    // Local state for alternative hosts
    const [selectedAlternativeHosts, setSelectedAlternativeHosts] =
        useState<string[]>([]);

    // API integration for removal
    const confirmRemove = useCallback(async () => {
        if (!confirm('Are you sure you want to remove this Zoom meeting?')) return;

        await removeMeeting({ removeLink, postId });
        onRemove();
    }, [removeLink, postId, onRemove]);

    // Loading state
    if (!id) {
        return <CenteredSpinner className="classy-full-width" />;
    }

    const isMeeting = meetingType === 'meeting';
    const title = isMeeting ? 'Zoom Meeting' : 'Zoom Webinar';
    const urlLabel = isMeeting ? 'Join Meeting URL' : 'Join Webinar URL';

    return (
        <div className="classy__linked-post-cards classy__linked-post-card--virtual-location">
            <div className="classy__linked-post-card classy__linked-post-card--virtual-location">
                {/* Title with icon */}
                <h4 className="classy-linked-post-card__title classy-aligned-row">
                    <IconVideoCamera className="classy-accent-color" />
                    {title}
                </h4>

                {/* Meeting details */}
                <div className="classy-linked-post-card__detail">
                    {isMeeting ? 'Meeting Name: ' : 'Webinar Name: '}
                    {decodeEntities(meetingName)}
                </div>

                <div className="classy-linked-post-card__detail">
                    Host: {decodeEntities(hostEmail)}
                </div>

                {/* URL */}
                <div className="classy-linked-post-card__detail">
                    <a href={data.url} target="_blank" rel="noopener noreferrer">
                        {urlLabel}
                    </a>
                </div>

                {/* Alternative hosts section */}
                {alternativeHosts.length > 0 && (
                    <div className="classy-linked-post-card__section">
                        <FormTokenField
                            label="Add additional hosts"
                            onChange={setSelectedAlternativeHosts}
                            suggestions={alternativeHosts}
                            value={selectedAlternativeHosts}
                        />
                    </div>
                )}

                {/* Settings section */}
                {onSettingsClick && (
                    <div className="classy-linked-post-card__section">
                        <Button
                            variant="link"
                            onClick={onSettingsClick}
                            className="classy-linked-post-card__settings-button"
                        >
                            <IconCog /> Virtual event settings
                        </Button>
                    </div>
                )}

                {/* Actions */}
                <div className="classy-linked-post-card__actions">
                    <Button
                        variant="link"
                        onClick={confirmRemove}
                        className="classy-linked-post-card__action"
                    >
                        <IconTrash />
                    </Button>
                </div>
            </div>
        </div>
    );
}
```

**Key Points:**
- Fetches data from Redux store
- Shows loading spinner while fetching
- Handles async API calls (removal)
- Complex sections (alternative hosts, settings)
- Confirmation before destructive actions

---

#### Card Pattern Comparison

| Feature | Simple Card (Venue) | Complex Card (Zoom) |
|---------|-------------------|---------------------|
| Data Source | Props only | Props + Redux store |
| Loading State | No | Yes (spinner) |
| API Calls | No | Yes (removal) |
| Sections | Single detail section | Multiple sections |
| Interactive Elements | Edit/Remove buttons | Buttons + TokenField |
| Confirmation | No | Yes (on remove) |

---

#### Card Structure Template

```tsx
<div className="classy__linked-post-card classy__linked-post-card--{type}">
    {/* TITLE */}
    <h4 className="classy-linked-post-card__title">
        {/* Optional icon */}
        <IconType />
        {title}
    </h4>

    {/* DETAILS (repeat as needed) */}
    <div className="classy-linked-post-card__detail">
        {detail}
    </div>

    {/* OPTIONAL SECTIONS */}
    {hasSection && (
        <div className="classy-linked-post-card__section">
            {sectionContent}
        </div>
    )}

    {/* ACTIONS (always last) */}
    <div className="classy-linked-post-card__actions">
        {actions.map(action => (
            <Button
                variant="link"
                onClick={action.onClick}
                className="classy-linked-post-card__action"
            >
                <ActionIcon />
            </Button>
        ))}
    </div>
</div>
```

---

#### Card Pattern Best Practices

**DO: Use Loading States**
```typescript
if (!data || isLoading) {
    return <CenteredSpinner className="classy-full-width" />;
}
```

**DO: Decode Entities**
```typescript
<h4>{decodeEntities(name)}</h4>
```

**DO: Confirm Destructive Actions**
```typescript
const confirmRemove = useCallback(async () => {
    if (!confirm('Are you sure?')) return;
    await removeItem(id);
    onRemove();
}, [id, onRemove]);
```

**DO: Use Semantic HTML**
```typescript
// Links for external URLs
<a href={url} target="_blank" rel="noopener noreferrer">{url}</a>

// Buttons for actions
<Button onClick={onEdit}><IconEdit /></Button>
```

**DON'T: Hardcode Strings**
```typescript
// Bad
<div>Host: {email}</div>

// Good
import { _x } from '@wordpress/i18n';
<div>{_x('Host:', 'Virtual meeting host label', 'tribe-events-calendar-pro')} {email}</div>
```

---

### Pattern 3: Field Pattern

Fields wrap input controls with labels and follow three complexity levels.

#### Simple Field Pattern

**Single input with label**

```typescript
import { ClassyField } from '@tec/common/classy/components';
import { __experimentalInputControl as InputControl } from '@wordpress/components';

// Example: TicketName field
<ClassyField title="Ticket Name">
    <InputControl
        value={name}
        onChange={setName}
        className="classy-field__control classy-field__control--input"
    />
</ClassyField>
```

**Structure:**
- `ClassyField` wrapper provides label
- Single input control
- Direct state binding

---

#### Complex Field Pattern

**Multiple components (cards + select + modal)**

**Example: EventLocation field**

```typescript
import * as React from 'react';
import { useState } from 'react';
import { useSelect } from '@wordpress/data';
import { Button, CustomSelectControl } from '@wordpress/components';
import { Slot } from '@wordpress/components';
import { ClassyField, IconAdd } from '@tec/common/classy/components';
import { FieldProps } from '@tec/common/classy/types';
import VenueCards from './VenueCards';
import VenueUpsertModal from './VenueUpsertModal';

export default function EventLocation(props: FieldProps) {
    const { title } = props;

    // Fetch venues from store
    const { venues, selectedVenues } = useSelect((select) => {
        const store = select('tec/classy/events');
        return {
            venues: store.getVenues(),
            selectedVenues: store.getSelectedVenueIds()
        };
    }, []);

    // Modal state
    const [showModal, setShowModal] = useState(false);
    const [editingVenue, setEditingVenue] = useState(null);

    // Handlers
    const handleEdit = (venueId) => {
        setEditingVenue(venues.find(v => v.id === venueId));
        setShowModal(true);
    };

    const handleRemove = (venueId) => {
        // Remove venue logic
    };

    const handleSave = (venueData) => {
        // Save venue logic
        setShowModal(false);
    };

    const handleVenueSelect = (selected) => {
        // Add existing venue
    };

    return (
        <ClassyField title={title}>
            {/* Cards for selected venues */}
            <VenueCards
                venues={selectedVenues.map(id => venues.find(v => v.id === id))}
                onEdit={handleEdit}
                onRemove={handleRemove}
            />

            {/* Separator */}
            <span className="classy-section-separator"></span>

            {/* Dropdown to add existing venue */}
            <CustomSelectControl
                label="Add existing venue"
                options={venues.map(v => ({ label: v.name, value: v.id }))}
                onChange={handleVenueSelect}
            />

            {/* Button to create new venue */}
            <Button onClick={() => setShowModal(true)}>
                <IconAdd /> New Venue
            </Button>

            {/* Modal for creating/editing */}
            {showModal && (
                <VenueUpsertModal
                    isUpdate={!!editingVenue}
                    values={editingVenue || defaultVenue}
                    onSave={handleSave}
                    onCancel={() => setShowModal(false)}
                    onClose={() => setShowModal(false)}
                />
            )}

            {/* Extension point for virtual locations */}
            <Slot name="tec.classy.events.event-location.after" />
        </ClassyField>
    );
}
```

**Key Components:**
1. **Cards** - Display selected items
2. **Select** - Choose from existing items
3. **Button** - Add new item
4. **Modal** - Create/edit form
5. **Slot** - Extension point for other plugins

---

#### Hybrid Field Pattern

**Conditional rendering based on state**

**Example: VirtualLocation field (simplified)**

```typescript
import * as React from 'react';
import { useState } from 'react';
import { Button } from '@wordpress/components';
import { ClassyField } from '@tec/common/classy/components';
import { ZoomIcon, GoogleMeetIcon, MicrosoftTeamsIcon } from '../Icons';
import ZoomCard from './Cards/ZoomCard';
import GoogleCard from './Cards/GoogleCard';

export default function VirtualLocation() {
    const [provider, setProvider] = useState<string | null>(null);
    const [connectionData, setConnectionData] = useState(null);

    if (!connectionData) {
        // Show provider selection
        return (
            <ClassyField title="Virtual Location">
                <div className="classy-field__provider-buttons">
                    <Button onClick={() => setProvider('zoom')}>
                        <ZoomIcon /> Zoom
                    </Button>
                    <Button onClick={() => setProvider('google')}>
                        <GoogleMeetIcon /> Google Meet
                    </Button>
                    <Button onClick={() => setProvider('microsoft')}>
                        <MicrosoftTeamsIcon /> Microsoft Teams
                    </Button>
                    {/* More providers... */}
                </div>
            </ClassyField>
        );
    }

    // Show provider-specific card
    return (
        <ClassyField title="Virtual Location">
            {provider === 'zoom' && (
                <ZoomCard
                    data={connectionData}
                    onRemove={() => setConnectionData(null)}
                />
            )}
            {provider === 'google' && (
                <GoogleCard
                    data={connectionData}
                    onRemove={() => setConnectionData(null)}
                />
            )}
            {/* More provider cards... */}
        </ClassyField>
    );
}
```

**Key Characteristics:**
- Conditional rendering based on state
- Different UI for different states
- State transitions (selection → connected)

---

#### Field with State Separation

**Pattern: Keep state in parent, presentation in child**

```typescript
// Parent component (EventDateTime/index.tsx)
export default function EventDateTime() {
    // State management
    const [startDate, setStartDate] = useState(new Date());
    const [endDate, setEndDate] = useState(new Date());
    const [allDay, setAllDay] = useState(false);

    // Pass to presentation component
    return (
        <EventDateTimeField
            startDate={startDate}
            endDate={endDate}
            allDay={allDay}
            onStartDateChange={setStartDate}
            onEndDateChange={setEndDate}
            onAllDayChange={setAllDay}
        />
    );
}

// Child component (EventDateTime/EventDateTime.tsx)
export default function EventDateTimeField(props) {
    const {
        startDate,
        endDate,
        allDay,
        onStartDateChange,
        onEndDateChange,
        onAllDayChange
    } = props;

    // Only presentation logic
    return (
        <ClassyField title="Event Date & Time">
            <DatePicker
                date={startDate}
                onChange={onStartDateChange}
            />
            {/* More inputs... */}
        </ClassyField>
    );
}
```

**Benefits:**
- Clear separation of concerns
- Easy to test
- Reusable presentation component

---

### Pattern 4: Upsert Pattern

**Upsert** = Create or Update. This pattern combines Modal + Form + Validation + API.

#### Structure

```
UpsertModal (Wrapper)
└─> Upsert (Content)
    ├─> Form state
    ├─> Validation logic
    ├─> API integration
    └─> UI rendering
```

---

#### Full Upsert Example

See the detailed VenueUpsert example in the [Modal Pattern](#pattern-1-modal-pattern) section above. The Upsert pattern is essentially the content component of a modal with these additional concerns:

1. **Default Values**
```typescript
const defaultValues: VenueData = {
    id: 0,
    name: '',
    address: '',
    // ... more defaults
};
```

2. **Merge Props with Defaults**
```typescript
const [currentValues, setCurrentValues] = useState({
    ...defaultValues,
    ...values,
});
```

3. **Validation State**
```typescript
const [confirmEnabled, setConfirmEnabled] = useState(false);
const [hasValidUrl, setHasValidUrl] = useState(true);
```

4. **API Integration**
```typescript
const invokeSaveWithData = useCallback(async () => {
    setIsSaving(true);
    try {
        const response = await saveVenue(currentValues);
        onSave(response.data);
    } catch (error) {
        setError(error.message);
    } finally {
        setIsSaving(false);
    }
}, [currentValues]);
```

5. **Update vs Create Logic**
```typescript
const apiCall = isUpdate ? updateVenue : createVenue;
const buttonText = isUpdate ? 'Update Venue' : 'Create Venue';
const headerText = isUpdate ? 'Update Venue' : 'New Venue';
```

---

### Component Architecture Best Practices

**DO: Follow Established Patterns**
- Use Modal pattern for create/edit dialogs
- Use Card pattern for entity display
- Use Field pattern for form inputs
- Use Upsert pattern for save operations

**DO: Separate Concerns**
- Wrapper handles WordPress integration
- Content handles business logic
- State management in parent when possible
- Presentation in child components

**DO: Use Common Components**
- Import from `@tec/common/classy/components`
- Don't recreate what exists in Common
- Extend with modifiers when needed

**DON'T: Mix Patterns**
- Don't put API calls in Card components
- Don't put complex state in wrapper components
- Don't bypass Field wrappers for inputs

**DON'T: Duplicate Code**
- Extract reusable components to Common
- Share validation logic
- Reuse API functions

---

## 3. CSS Variable System

### Overview

The Classy CSS architecture uses **CSS Custom Properties (variables)** extensively for:
- Consistent spacing and sizing
- Themeable colors
- Reusable dimensions
- Contextual overrides

Variables are defined at `:root` (global) level and can be overridden at any scope level (component, modifier, element).

---

### Complete Variable Inventory

#### Layout Variables (Common)

| Variable | Purpose | Default Value | Overridable |
|----------|---------|---------------|-------------|
| `--classy-padding-horizontal` | Horizontal padding | `var(--tec-spacer-6)` (24px) | Yes |
| `--classy-padding-vertical` | Vertical padding | `var(--tec-spacer-7)` (28px) | Yes |
| `--classy-height-100` | Standard height | `32px` | Yes |
| `--classy-height-75` | Smaller height | `24px` | Yes |
| `--classy-line-height` | Line height | `18.2px` | No |

**Usage Example:**
```css
.classy-container {
    padding: 26px 74px 26px;
}

.classy-field {
    gap: var(--classy-padding-vertical);
}

.classy-field__ticket-row {
    --classy-padding-horizontal: var(--tec-spacer-5);  /* Override */
    padding: var(--classy-padding-vertical) 0;
}
```

---

#### Input Variables (Common)

| Variable | Purpose | Default Value | Overridable |
|----------|---------|---------------|-------------|
| `--classy-input-width` | Standard input width | `375px` | Yes |
| `--classy-input-padding-top-bottom` | Vertical input padding | `var(--tec-spacer-2)` (8px) | Yes |
| `--classy-input-padding-left-right` | Horizontal input padding | `var(--tec-spacer-3)` (12px) | Yes |

**ET-Specific Input Variables:**

| Variable | Purpose | Default Value | Plugin |
|----------|---------|---------------|--------|
| `--classy-date-picker-width` | Date picker width | `320px` | ET |

**Usage Example:**
```css
/* Common default */
:root {
    --classy-input-width: 375px;
}

.classy-field__input {
    width: var(--classy-input-width);
}

/* ET override for date pickers */
.classy-field__input--sale-duration {
    --classy-input-width: var(--classy-date-picker-width);
}
```

---

#### Color Variables (Common)

| Variable | Purpose | Value | Notes |
|----------|---------|-------|-------|
| `--classy-color-footer` | Footer background | `rgba(30, 30, 30, 0.03)` | Light gray |
| `--classy-color-highlight` | Highlight overlay | `rgb(from var(--tec-color-accent-primary) r g b / 0.5)` | 50% lighter |
| `--classy-color-required-asterisk` | Required marker | `var(--tec-color-icon-error)` | Red |
| `--classy-highlight-color` | Alternative highlight | `rgb(from var(--tec-color-accent-primary) r g b / 0.5)` | Same as above |
| `--classy-accent-color` | Primary accent | `var(--wp-components-color-accent, var(--wp-admin-theme-color, #3858e9))` | WP theme |
| `--classy-icon-default-color` | Default icon color | `#1E1E1E` | Dark gray |

---

#### Color Variables (ECP)

| Variable | Purpose | Value | Notes |
|----------|---------|-------|-------|
| `--classy-color-gutenberg-blueberry` | Button accent | `var(--classy-accent-color)` | Alias |
| `--classy-color-button-border` | Button border | `#949494` | Gray |
| `--classy-color-button-text` | Button text | `var(--classy-icon-default-color)` | Dark gray |
| `--classy-color-button-bg` | Button background | `#fff` | White |
| `--classy-color-button-selected-bg` | Selected button bg | `var(--classy-accent-color)` | Blue |
| `--classy-color-button-selected-text` | Selected button text | `#fff` | White |

---

#### Color Variables (ET)

| Variable | Purpose | Value | Notes |
|----------|---------|-------|-------|
| `--classy-color-border` | Border color | `#e4e4e4` | Light gray |
| `--classy-color-destructive` | Destructive action | `var(--tec-color-gutenberg-alert-red)` | Delete button |

**Usage Example:**
```css
/* Common defines base */
:root {
    --classy-accent-color: var(--wp-components-color-accent, #3858e9);
}

/* ECP references Common variable */
:root {
    --classy-color-button-selected-bg: var(--classy-accent-color);
}

.classy-field__weekday-button--selected {
    background: var(--classy-color-button-selected-bg);
}
```

---

#### Typography Variables (Common)

| Variable | Purpose | Value | Notes |
|----------|---------|-------|-------|
| `--classy-font-size-13` | Small text | `calc(var(--tec-font-size-1) + 1px)` | ~13px |
| `--classy-font-size-15` | Medium text | `calc(var(--tec-font-size-2) + 1px)` | ~15px |
| `--classy-font-size-16` | Standard text | `var(--tec-font-size-3)` | ~16px |

**Usage Example:**
```css
.classy-field__label {
    font-size: var(--classy-font-size-13);
}

.classy-field__ticket-row {
    font-size: var(--classy-font-size-16);
}

.classy-field__ticket-row__description {
    font-size: var(--classy-font-size-13);
}
```

---

#### Domain-Specific Variables (ET)

| Variable | Purpose | Value | Notes |
|----------|---------|-------|-------|
| `--classy-icon-padding` | Icon spacing | `var(--tec-spacer-0)` | Minimal padding |

---

### TEC Spacer System

The Classy architecture builds on **TEC's spacer system** for consistent spacing:

```css
/* TEC defines spacers (from TEC common) */
--tec-spacer-0: 0px
--tec-spacer-1: 4px
--tec-spacer-2: 8px
--tec-spacer-3: 12px
--tec-spacer-4: 16px
--tec-spacer-5: 20px
--tec-spacer-6: 24px
--tec-spacer-7: 28px
--tec-spacer-8: 32px
--tec-spacer-9: 36px
```

**Classy uses these extensively:**
```css
:root {
    --classy-padding-horizontal: var(--tec-spacer-6);  /* 24px */
    --classy-padding-vertical: var(--tec-spacer-7);    /* 28px */
}

.classy-container {
    gap: var(--tec-spacer-8);  /* 32px between field groups */
}

.classy-field {
    gap: var(--tec-spacer-2);  /* 8px between label and input */
}
```

**When to use TEC spacers:**
- For ANY spacing or gap value
- For padding/margin values
- For consistent visual rhythm

**When to use custom values:**
- When TEC spacers don't fit (rarely)
- For fixed dimensions like input width
- For specific design requirements

---

### TEC Color System

The Classy architecture also references **TEC's color system**:

```css
/* Common color references from TEC */
--tec-color-accent-primary
--tec-color-icon-error
--tec-color-gutenberg-grey-300
--tec-color-gutenberg-grey-900
--tec-color-gutenberg-alert-red
--tec-color-text-primary
--tec-color-text-secondary
```

**Classy variables reference TEC colors:**
```css
:root {
    --classy-color-required-asterisk: var(--tec-color-icon-error);
    --classy-color-highlight: rgb(from var(--tec-color-accent-primary) r g b / 0.5);
}

/* ET adds ticket-specific colors */
:root {
    --classy-color-destructive: var(--tec-color-gutenberg-alert-red);
}
```

**Relationship:**
```
TEC Color Variables (base theme colors)
    ↓ Referenced by
Classy Color Variables (component-specific)
    ↓ Used in
Component Styles (actual UI)
```

---

### Variable Scoping Patterns

#### Pattern 1: Global Default at Root

```css
/* Define at :root for global default */
:root {
    --classy-padding-horizontal: var(--tec-spacer-6);  /* 24px globally */
}

/* All components inherit this */
.classy-field {
    padding: 0 var(--classy-padding-horizontal);  /* Uses 24px */
}
```

---

#### Pattern 2: Component-Level Override

```css
/* Global default */
:root {
    --classy-padding-horizontal: var(--tec-spacer-6);  /* 24px */
}

/* Component overrides for its scope */
.classy-field__ticket-row {
    --classy-padding-horizontal: var(--tec-spacer-5);  /* 20px in this component */

    .classy-field__ticket-row__section:first-child {
        margin-left: var(--classy-padding-horizontal);  /* Uses 20px */
    }
}

/* Other components still use 24px */
.classy-modal__content {
    padding: 0 var(--classy-padding-horizontal);  /* Still 24px */
}
```

---

#### Pattern 3: Context-Specific Override

```css
/* Modal overlay changes variables for content inside */
.classy-modal__overlay--ticket {
    .classy-field__input--sale-duration {
        --classy-input-width: var(--classy-date-picker-width);
        /* This input uses different width */
    }
}
```

---

#### Pattern 4: Plugin Extends Root Variables

```css
/* Common defines base set */
:root {
    --classy-accent-color: var(--wp-admin-theme-color);
}

/* ECP adds more variables */
:root {
    --classy-color-button-border: #949494;
    --classy-color-button-selected-bg: var(--classy-accent-color);
}

/* ET adds different variables */
:root {
    --classy-color-border: #e4e4e4;
    --classy-color-destructive: var(--tec-color-gutenberg-alert-red);
}
```

---

### Variable Naming Conventions

Follow these naming patterns:

```css
/* Layout/Spacing */
--classy-padding-{direction}
--classy-margin-{direction}
--classy-height-{size}
--classy-width-{context}

/* Colors */
--classy-color-{semantic-name}
--classy-color-{component}-{property}

/* Typography */
--classy-font-size-{number}
--classy-font-weight-{weight}
--classy-line-height

/* Component-specific */
--classy-{component}-{property}
```

**Examples:**
```css
--classy-padding-horizontal     /* Layout */
--classy-color-destructive      /* Semantic color */
--classy-color-button-border    /* Component color */
--classy-font-size-13           /* Typography */
--classy-date-picker-width      /* Component dimension */
```

---

### When to Create New Variables

#### Create a new variable when:

1. **Value is used in multiple places**
   ```css
   /* Good - used in multiple components */
   :root {
       --classy-padding-horizontal: var(--tec-spacer-6);
   }

   .classy-container { padding: 0 var(--classy-padding-horizontal); }
   .classy-modal { padding: 0 var(--classy-padding-horizontal); }
   .classy-field { padding: 0 var(--classy-padding-horizontal); }
   ```

2. **Value refers to a design token**
   ```css
   /* Good - semantic color from theme */
   :root {
       --classy-accent-color: var(--wp-admin-theme-color);
   }
   ```

3. **Value should be overridable by context**
   ```css
   /* Good - components can override */
   :root {
       --classy-input-width: 375px;
   }

   .classy-field__custom {
       --classy-input-width: 500px;  /* Override for this context */
   }
   ```

4. **Value has semantic meaning**
   ```css
   /* Good - "destructive" is more meaningful than "red" */
   :root {
       --classy-color-destructive: var(--tec-color-gutenberg-alert-red);
   }
   ```

---

#### Use literal values when:

1. **Value is truly one-off**
   ```css
   /* OK - only used here, specific to this layout */
   .classy-container {
       padding: 26px 74px 26px;
   }
   ```

2. **Value is magic number with specific reason**
   ```css
   /* OK - specific to component's internal layout */
   .classy-field__ticket-row__section:last-child {
       margin-right: 8px;  /* Aligns with icon padding */
   }
   ```

3. **Creating variable would be over-engineering**
   ```css
   /* OK - too specific for a variable */
   .classy-modal__footer-icons {
       height: auto;
       width: auto;
   }
   ```

---

### Theming Guidelines

#### Using TEC Design Tokens

Always prefer TEC design tokens over hardcoded values:

```css
/* DO: Use TEC spacers */
.classy-field {
    gap: var(--tec-spacer-2);
    padding: var(--tec-spacer-4);
}

/* DON'T: Hardcode spacing */
.classy-field {
    gap: 8px;
    padding: 16px;
}

/* DO: Use TEC colors */
.classy-field__label {
    color: var(--tec-color-text-primary);
}

/* DON'T: Hardcode colors */
.classy-field__label {
    color: #1E1E1E;
}
```

---

#### Override Patterns for Plugins

Plugins can override Common variables:

```css
/* Common defines default */
:root {
    --classy-padding-horizontal: var(--tec-spacer-6);
}

/* ET overrides for ticket row context */
.classy-field__ticket-row {
    --classy-padding-horizontal: var(--tec-spacer-5);
    /* All children use 20px instead of 24px */
}

/* ECP overrides for event cost field */
.classy-field__event-cost {
    --classy-height-100: 40px;
    /* Taller controls for cost inputs */
}
```

---

### CSS Variable System Best Practices

**DO:**
- ✅ Use TEC spacers for all spacing values
- ✅ Use TEC colors for all color values
- ✅ Create semantic variable names
- ✅ Override at appropriate scope levels
- ✅ Document custom variables
- ✅ Prefer variables over literals

**DON'T:**
- ❌ Hardcode spacing values
- ❌ Hardcode color values
- ❌ Create variables for one-off values
- ❌ Use generic variable names
- ❌ Override global variables in plugins (create new ones)
- ❌ Forget to use `var()` function

---

## 4. Slot/Fill Extensibility

### Overview

The Classy architecture uses WordPress's **Slot/Fill** pattern for extensibility. This allows plugins to inject content into predefined extension points without modifying parent components.

**Key Concept:**
- **Slot** = Placeholder in parent component ("you can add content here")
- **Fill** = Content provided by child/extending plugin ("here's my content")

**Use Cases:**
- ET adds tickets field to TEC events
- ECP adds virtual location to TEC event location
- ECP adds recurrence fields to TEC date/time
- Third-party plugins extend Classy UI

---

### Available Slots Reference

#### Global Slots (Common)

| Slot Name | Location | Purpose | Plugin |
|-----------|----------|---------|--------|
| `tec.classy.before` | Before main container | Add content before all fields | Common |
| `tec.classy.fields.before` | Before field groups | Add content before fields | Common |
| `tec.classy.fields` | Main fields area | Add custom fields | Common |
| `tec.classy.fields.after` | After field groups | Add content after fields | Common |
| `tec.classy.after` | After main container | Add content after all fields | Common |

**Usage:**
```tsx
// In Common Classy.tsx
<div className="classy-root">
    <Slot name="tec.classy.before" />

    <div className="classy-container">
        <Slot name="tec.classy.fields.before" />

        {/* Field groups */}
        <Slot name="tec.classy.fields" />

        <Slot name="tec.classy.fields.after" />
    </div>

    <Slot name="tec.classy.after" />
</div>
```

---

#### TEC Event Slots

| Slot Name | Location | Purpose | Plugin |
|-----------|----------|---------|--------|
| `tec.classy.fields.event-admission.buttons` | Event Admission field | Add custom admission buttons | TEC |
| `tec.classy.fields.tickets` | Event Admission field | Add tickets UI (filled by ET) | TEC |
| `tec.classy.fields.event-date-time.before` | Event Date/Time field | Add content before date/time | TEC |
| `tec.classy.fields.event-date-time.after` | Event Date/Time field | Add content after date/time (ECP fills with recurrence) | TEC |
| `tec.classy.events.event-location.after` | Event Location field | Add virtual location (ECP fills) | TEC |

---

#### ECP Virtual Location Slots

| Slot Name | Location | Purpose | Plugin |
|-----------|----------|---------|--------|
| `tec.classy.virtual-location.settings.viewing-permissions.after` | Virtual Location settings | Add content after viewing permissions | ECP |
| `tec.classy.virtual-location.settings.after` | Virtual Location settings | Add content after all settings | ECP |

---

#### ET Ticket Slots

| Slot Name | Location | Purpose | Plugin |
|-----------|----------|---------|--------|
| `tec.tickets.classy.ticketRow.icons` | Ticket row | Add custom icons/actions to ticket row | ET |

**Usage with Fill Props:**
```tsx
// In TicketRow component
<Slot
    name="tec.tickets.classy.ticketRow.icons"
    fillProps={{ ticket }}
/>

// Extension plugin can access ticket data
<Fill name="tec.tickets.classy.ticketRow.icons">
    {({ ticket }) => (
        <IconCustom ticketId={ticket.id} />
    )}
</Fill>
```

---

### Slot Naming Convention

**Pattern:** `tec.classy.[plugin].[component].[position]`

**Components:**
- `tec` - The Events Calendar namespace
- `classy` - Classy architecture
- `[plugin]` - events, tickets, events-pro, etc.
- `[component]` - event-location, event-date-time, tickets, etc.
- `[position]` - before, after, buttons, icons, etc.

**Examples:**
```
tec.classy.events.event-location.after
tec.classy.fields.event-date-time.before
tec.classy.virtual-location.settings.after
tec.tickets.classy.ticketRow.icons
```

---

### Fill Registration Examples

#### Example 1: ET Fills TEC Tickets Slot

**TEC provides slot:**
```tsx
// File: /the-events-calendar/src/resources/packages/classy/fields/EventAdmission/EventAdmission.tsx

export default function EventAdmission() {
    return (
        <ClassyField title="Event Admission">
            {/* Buttons for free/paid selection */}
            <Slot name="tec.classy.fields.event-admission.buttons" />

            {/* ET fills this with Tickets component */}
            <Slot name="tec.classy.fields.tickets" />
        </ClassyField>
    );
}
```

**ET fills slot:**
```tsx
// File: /event-tickets/src/resources/packages/classy/fields/Tickets/index.tsx

import { Fill } from '@wordpress/components';
import Tickets from './Tickets';

export default function TicketsFill() {
    return (
        <Fill name="tec.classy.fields.tickets">
            <Tickets />
        </Fill>
    );
}
```

---

#### Example 2: ECP Fills TEC Virtual Location Slot

**TEC provides slot:**
```tsx
// File: /the-events-calendar/src/resources/packages/classy/fields/EventLocation/EventLocation.tsx

export default function EventLocation() {
    return (
        <ClassyField title="Event Location">
            {/* Venue cards and controls */}
            <VenueCards venues={venues} />

            {/* ECP fills this with Virtual Location */}
            <Slot name="tec.classy.events.event-location.after" />
        </ClassyField>
    );
}
```

**ECP fills slot:**
```tsx
// File: /events-pro/src/resources/packages/classy/fields/VirtualLocation/index.tsx

import { Fill } from '@wordpress/components';
import VirtualLocation from './VirtualLocation';

export default function VirtualLocationFill() {
    return (
        <Fill name="tec.classy.events.event-location.after">
            <VirtualLocation />
        </Fill>
    );
}
```

---

#### Example 3: Accessing Fill Props

**Component provides data to fills:**
```tsx
// File: /event-tickets/src/resources/packages/classy/components/TicketRow/TicketRow.tsx

export default function TicketRow({ ticket }) {
    return (
        <div className="classy-field__ticket-row">
            {/* Ticket details */}
            <span>{ticket.name}</span>
            <span>${ticket.price}</span>

            {/* Icons with ticket data available to fills */}
            <Slot
                name="tec.tickets.classy.ticketRow.icons"
                fillProps={{ ticket }}
            />
        </div>
    );
}
```

**Extension uses fill props:**
```tsx
// Third-party plugin
import { Fill } from '@wordpress/components';

function CustomTicketIcon() {
    return (
        <Fill name="tec.tickets.classy.ticketRow.icons">
            {({ ticket }) => (
                <Button onClick={() => customAction(ticket.id)}>
                    <IconCustom />
                </Button>
            )}
        </Fill>
    );
}
```

---

### How to Discover Slots

#### Method 1: Search Codebase

```bash
# Find all Slot definitions
grep -r "Slot name=" --include="*.tsx" --include="*.ts"

# Find slots in specific plugin
grep -r "Slot name=" /path/to/plugin/src/ --include="*.tsx"

# Find slots with specific prefix
grep -r 'Slot name="tec.classy.events' --include="*.tsx"
```

---

#### Method 2: Check Component Files

Look for `Slot` components in:
- `/the-events-calendar/common/src/resources/packages/classy/components/Classy.tsx`
- `/the-events-calendar/src/resources/packages/classy/fields/**/*.tsx`
- `/events-pro/src/resources/packages/classy/fields/**/*.tsx`
- `/event-tickets/src/resources/packages/classy/fields/**/*.tsx`

---

#### Method 3: Browser DevTools

Slots are rendered as `<wordpress-slot-fill>` elements:

```html
<wordpress-slot-fill name="tec.classy.events.event-location.after">
    <!-- Fill content renders here -->
</wordpress-slot-fill>
```

Use browser inspector to find slot names in rendered HTML.

---

#### Method 4: Check This Documentation

Refer to the [Available Slots Reference](#available-slots-reference) section above.

---

### Best Practices

#### DO: Always Render Existing Fields

When filling a slot, don't replace existing content:

```tsx
// GOOD: Add to existing content
<Fill name="tec.classy.events.event-location.after">
    <VirtualLocation />
</Fill>

// BAD: Don't try to replace existing content
// (You can't - fills are additive)
```

---

#### DO: Use Fragment When Possible

If adding multiple elements, wrap in Fragment:

```tsx
import { Fill } from '@wordpress/components';
import { Fragment } from '@wordpress/element';

<Fill name="tec.classy.fields.after">
    <Fragment>
        <CustomField1 />
        <CustomField2 />
    </Fragment>
</Fill>
```

---

#### DO: Handle Priorities

Use `fillProps` and conditional rendering if needed:

```tsx
<Fill name="tec.classy.fields">
    {({ priority }) => {
        if (priority === 'high') {
            return <HighPriorityField />;
        }
        return <NormalField />;
    }}
</Fill>
```

---

#### DO: Test With/Without Extensions

Always test:
1. Without your plugin (slot should render empty)
2. With your plugin (fill should appear)
3. With multiple fills (all should render)

---

#### DON'T: Break Existing Functionality

**Bad:**
```tsx
// Don't hide or break parent component
<Fill name="tec.classy.events.event-location.after">
    <div style={{ display: 'none' }}>
        {/* Hiding parent content */}
    </div>
</Fill>
```

**Good:**
```tsx
// Add your content, leave parent alone
<Fill name="tec.classy.events.event-location.after">
    <MyCustomField />
</Fill>
```

---

#### DON'T: Create Slot Name Conflicts

Use descriptive, unique names:

```tsx
// BAD: Generic name
<Slot name="tec.classy.field.after" />

// GOOD: Specific name
<Slot name="tec.classy.events.event-location.after" />
```

---

### Slot/Fill Pattern Summary

**When to use Slot:**
- You're creating a component that others should extend
- You want to provide extension points
- Component is in Common or base plugin

**When to use Fill:**
- You're extending another plugin's component
- You want to add content to a slot
- You're in a child/extending plugin

**Key Points:**
- Slots are placeholders
- Fills provide content
- Multiple fills can target same slot
- Fills are additive (don't replace)
- Use `fillProps` to pass data to fills

---

## 5. Redux Store Patterns

### Overview

The Classy architecture uses **Redux** (via `@wordpress/data`) for state management. Each plugin registers its own Redux store with selectors, actions, and reducers.

**Store Architecture:**
- **Common** - Base store utilities and registry
- **TEC** - `tec/classy/events` store
- **ET** - `tec/classy/tickets` store
- **ECP** - `tec/classy/events-pro` store
- **ETP** - `tec/classy/tickets-plus` store

---

### Store Naming and Registration

#### Store Names

Follow this naming pattern:

```
tec/classy/[plugin]
```

**Examples:**
```typescript
'tec/classy/events'       // TEC store
'tec/classy/tickets'      // ET store
'tec/classy/events-pro'   // ECP store
'tec/classy/tickets-plus' // ETP store
```

---

#### Store Registration

**File:** `/[plugin]/src/resources/packages/classy/store/registry.ts`

```typescript
import { createReduxStore, register } from '@wordpress/data';
import reducer from './reducer';
import * as actions from './actions';
import * as selectors from './selectors';

const STORE_NAME = 'tec/classy/events';

const store = createReduxStore(STORE_NAME, {
    reducer,
    actions,
    selectors,
});

register(store);

export { STORE_NAME };
export default store;
```

---

#### Registration Timing

Register stores after Common is initialized:

```typescript
import { didAction, addAction } from '@wordpress/hooks';
import store, { STORE_NAME } from './store/registry';

// Wait for Common to be ready
if (didAction('tec.classy.initialized')) {
    // Common already loaded, register immediately
    // (registration happens in import)
} else {
    // Wait for Common
    addAction('tec.classy.initialized', 'tec.classy.events', () => {
        // Store registered on import
    });
}
```

---

### State Shape Conventions

#### Normalization Pattern

**DO: Normalize data by ID**

```typescript
// GOOD: Normalized state
interface StoreState {
    venues: {
        byId: {
            [id: number]: VenueData;
        };
        allIds: number[];
    };
    selectedVenueIds: number[];
}
```

**DON'T: Store arrays directly**

```typescript
// BAD: Un-normalized state
interface StoreState {
    venues: VenueData[];  // Hard to update individual items
    selectedVenues: VenueData[];  // Duplicated data
}
```

---

#### Example State Shapes

**TEC Store:**
```typescript
interface TecStoreState {
    // Event metadata
    eventMeta: EventMeta;

    // Venues
    venues: {
        byId: { [id: number]: VenueData };
        allIds: number[];
    };

    // Organizers
    organizers: {
        byId: { [id: number]: OrganizerData };
        allIds: number[];
    };

    // Selected entities
    selectedVenueIds: number[];
    selectedOrganizerIds: number[];

    // Settings
    settings: Settings;
}
```

**ET Store:**
```typescript
interface EtStoreState {
    // Tickets
    tickets: {
        byId: { [id: number]: Ticket };
        allIds: number[];
    };

    // Capacity
    capacity: CapacityData;

    // UI state
    editingTicketId: number | null;
}
```

**ECP Store:**
```typescript
interface EcpStoreState {
    // Recurrence
    recurrence: RecurrenceData | null;

    // Virtual location
    virtualLocation: VirtualLocationData | null;

    // Provider-specific data
    zoomMeetings: {
        byId: { [id: string]: ZoomVirtualLocationData };
        allIds: string[];
    };
}
```

---

### Selector Patterns

#### Basic Selector

```typescript
// File: /[plugin]/src/resources/packages/classy/store/selectors.ts

/**
 * Get all venues
 */
export function getVenues(state) {
    return state.venues.allIds.map(id => state.venues.byId[id]);
}

/**
 * Get venue by ID
 */
export function getVenue(state, venueId) {
    return state.venues.byId[venueId];
}

/**
 * Get selected venue IDs
 */
export function getSelectedVenueIds(state) {
    return state.selectedVenueIds;
}
```

---

#### Computed Selector

```typescript
/**
 * Get selected venues (computed from IDs)
 */
export function getSelectedVenues(state) {
    return state.selectedVenueIds.map(id => state.venues.byId[id]);
}

/**
 * Get venue by ID with fallback
 */
export function getVenueOrDefault(state, venueId) {
    return state.venues.byId[venueId] || getDefaultVenue();
}
```

---

#### Selector Usage in Components

```typescript
import { useSelect } from '@wordpress/data';

export default function EventLocation() {
    // Single value
    const venues = useSelect((select) => {
        return select('tec/classy/events').getVenues();
    }, []);

    // Multiple values
    const { venues, selectedIds, settings } = useSelect((select) => {
        const store = select('tec/classy/events');
        return {
            venues: store.getVenues(),
            selectedIds: store.getSelectedVenueIds(),
            settings: store.getSettings()
        };
    }, []);

    // With dependencies
    const venue = useSelect((select) => {
        return select('tec/classy/events').getVenue(venueId);
    }, [venueId]);  // Re-run when venueId changes

    return (
        // Use selected data
    );
}
```

---

### Action Creator Patterns

#### Sync Actions

```typescript
// File: /[plugin]/src/resources/packages/classy/store/actions.ts

/**
 * Set venues
 */
export function setVenues(venues) {
    return {
        type: 'SET_VENUES',
        venues
    };
}

/**
 * Add venue
 */
export function addVenue(venue) {
    return {
        type: 'ADD_VENUE',
        venue
    };
}

/**
 * Update venue
 */
export function updateVenue(venueId, updates) {
    return {
        type: 'UPDATE_VENUE',
        venueId,
        updates
    };
}

/**
 * Remove venue
 */
export function removeVenue(venueId) {
    return {
        type: 'REMOVE_VENUE',
        venueId
    };
}

/**
 * Set selected venue IDs
 */
export function setSelectedVenueIds(venueIds) {
    return {
        type: 'SET_SELECTED_VENUE_IDS',
        venueIds
    };
}
```

---

#### Async Actions (Generator Functions)

```typescript
import { apiFetch } from '@wordpress/api-fetch';

/**
 * Fetch venues from API
 */
export function* fetchVenues() {
    try {
        // Fetch from API
        const venues = yield apiFetch({ path: '/tec/v1/venues' });

        // Dispatch sync action
        yield setVenues(venues);

        return venues;
    } catch (error) {
        console.error('Failed to fetch venues:', error);
        throw error;
    }
}

/**
 * Save venue
 */
export function* saveVenue(venueData) {
    try {
        const response = yield apiFetch({
            path: '/tec/v1/venues',
            method: 'POST',
            data: venueData
        });

        // Add to store
        yield addVenue(response);

        return response;
    } catch (error) {
        console.error('Failed to save venue:', error);
        throw error;
    }
}

/**
 * Delete venue
 */
export function* deleteVenue(venueId) {
    try {
        yield apiFetch({
            path: `/tec/v1/venues/${venueId}`,
            method: 'DELETE'
        });

        // Remove from store
        yield removeVenue(venueId);
    } catch (error) {
        console.error('Failed to delete venue:', error);
        throw error;
    }
}
```

---

#### Action Usage in Components

```typescript
import { useDispatch } from '@wordpress/data';

export default function VenueUpsert({ venueData, onSave }) {
    const { saveVenue } = useDispatch('tec/classy/events');
    const [isSaving, setIsSaving] = useState(false);

    const handleSave = useCallback(async () => {
        setIsSaving(true);
        try {
            // Call async action
            const saved = await saveVenue(venueData);
            onSave(saved);
        } catch (error) {
            alert('Failed to save venue');
        } finally {
            setIsSaving(false);
        }
    }, [venueData, saveVenue, onSave]);

    return (
        <Button
            onClick={handleSave}
            aria-disabled={isSaving}
        >
            {isSaving ? 'Saving...' : 'Save Venue'}
        </Button>
    );
}
```

---

### Reducer Pattern

```typescript
// File: /[plugin]/src/resources/packages/classy/store/reducer.ts

const DEFAULT_STATE = {
    venues: {
        byId: {},
        allIds: []
    },
    selectedVenueIds: []
};

export default function reducer(state = DEFAULT_STATE, action) {
    switch (action.type) {
        case 'SET_VENUES':
            return {
                ...state,
                venues: {
                    byId: action.venues.reduce((acc, venue) => {
                        acc[venue.id] = venue;
                        return acc;
                    }, {}),
                    allIds: action.venues.map(v => v.id)
                }
            };

        case 'ADD_VENUE':
            return {
                ...state,
                venues: {
                    byId: {
                        ...state.venues.byId,
                        [action.venue.id]: action.venue
                    },
                    allIds: [...state.venues.allIds, action.venue.id]
                }
            };

        case 'UPDATE_VENUE':
            return {
                ...state,
                venues: {
                    ...state.venues,
                    byId: {
                        ...state.venues.byId,
                        [action.venueId]: {
                            ...state.venues.byId[action.venueId],
                            ...action.updates
                        }
                    }
                }
            };

        case 'REMOVE_VENUE':
            const { [action.venueId]: removed, ...restById } = state.venues.byId;
            return {
                ...state,
                venues: {
                    byId: restById,
                    allIds: state.venues.allIds.filter(id => id !== action.venueId)
                },
                selectedVenueIds: state.selectedVenueIds.filter(id => id !== action.venueId)
            };

        case 'SET_SELECTED_VENUE_IDS':
            return {
                ...state,
                selectedVenueIds: action.venueIds
            };

        default:
            return state;
    }
}
```

---

### Cross-Store Access

#### How ET Accesses TEC Data

```typescript
// In ET component
import { useSelect } from '@wordpress/data';

export default function TicketCapacity() {
    // Access TEC store from ET component
    const { isUsingTickets, areTicketsSupported } = useSelect((select) => {
        const tecStore = select('tec/classy/events');
        return {
            isUsingTickets: tecStore.isUsingTickets(),
            areTicketsSupported: tecStore.areTicketsSupported()
        };
    }, []);

    if (!areTicketsSupported) {
        return null;
    }

    // Render ticket capacity UI
}
```

---

#### How ECP Extends Stores

```typescript
// In ECP component
import { useSelect, useDispatch } from '@wordpress/data';

export default function VirtualLocation() {
    // Access both TEC and ECP stores
    const { eventId, virtualLocation } = useSelect((select) => {
        return {
            eventId: select('tec/classy/events').getEventId(),
            virtualLocation: select('tec/classy/events-pro').getVirtualLocationData()
        };
    }, []);

    // Dispatch to ECP store
    const { setVirtualLocation } = useDispatch('tec/classy/events-pro');

    // Component logic
}
```

---

### Redux Store Best Practices

**DO: Normalize State**
```typescript
// GOOD
{
    byId: { 1: {...}, 2: {...} },
    allIds: [1, 2]
}

// BAD
[{...}, {...}]
```

**DO: Use Selectors**
```typescript
// GOOD
const venue = useSelect(select =>
    select('tec/classy/events').getVenue(venueId)
);

// BAD
const venue = useSelect(select =>
    select('tec/classy/events').getState().venues.byId[venueId]
);
```

**DO: Use Dependencies in useSelect**
```typescript
// GOOD
useSelect(select => select(STORE).getData(id), [id]);

// BAD
useSelect(select => select(STORE).getData(id));  // Runs every render
```

**DON'T: Mutate State**
```typescript
// BAD
state.venues.byId[id] = newVenue;

// GOOD
{
    ...state,
    venues: {
        ...state.venues,
        byId: {
            ...state.venues.byId,
            [id]: newVenue
        }
    }
}
```

**DON'T: Call Actions in Selectors**
```typescript
// BAD
export function getVenues(state) {
    // Never call actions in selectors!
    dispatch(fetchVenues());
    return state.venues;
}

// GOOD: Separate concerns
export function getVenues(state) {
    return state.venues;
}

// Call action from component
const venues = useSelect(select => select(STORE).getVenues());
const { fetchVenues } = useDispatch(STORE);
useEffect(() => { fetchVenues(); }, []);
```

---

## 6. Component Catalog

### Overview

This section provides a comprehensive catalog of all Classy components organized by category. Each component includes:
- Purpose and use cases
- Props/API
- Code example
- Import path
- Availability (which plugins include it)

---

### Layout Components

#### ClassyField

**Purpose:** Base wrapper for all form fields. Provides consistent label styling and layout.

**Props:**
```typescript
interface FieldProps {
    title: string;           // Field label
    children: ReactNode;     // Field content
}
```

**Example:**
```tsx
import { ClassyField } from '@tec/common/classy/components';

<ClassyField title="Venue Name">
    <InputControl value={name} onChange={setName} />
</ClassyField>
```

**Import:** `@tec/common/classy/components`
**Available in:** Common (all plugins)

---

#### FieldGroup

**Purpose:** Groups related fields with consistent spacing

**Props:**
```typescript
interface FieldGroupProps {
    children: ReactNode;     // Fields to group
    className?: string;      // Additional CSS classes
}
```

**Example:**
```tsx
import { FieldGroup } from '@tec/common/classy/components';

<FieldGroup>
    <ClassyField title="First Name">
        <InputControl value={firstName} onChange={setFirstName} />
    </ClassyField>
    <ClassyField title="Last Name">
        <InputControl value={lastName} onChange={setLastName} />
    </ClassyField>
</FieldGroup>
```

**Import:** `@tec/common/classy/components`
**Available in:** Common (all plugins)

---

#### ClassyModalRoot

**Purpose:** Root wrapper for modal content with header

**Props:**
```typescript
interface ClassyModalRootProps {
    children: ReactNode;
    icon?: ReactNode;         // Optional icon for header
    title: string;            // Modal title
}
```

**Example:**
```tsx
import { ClassyModalRoot, IconNew } from '@tec/common/classy/components';

<ClassyModalRoot icon={<IconNew />} title="New Venue">
    {/* Modal content */}
</ClassyModalRoot>
```

**Import:** `@tec/common/classy/components`
**Available in:** Common (all plugins)

---

#### ClassyModalSection

**Purpose:** Content section within modal

**Props:**
```typescript
interface ClassyModalSectionProps {
    children: ReactNode;
    className?: string;
}
```

**Example:**
```tsx
import { ClassyModalSection } from '@tec/common/classy/components';

<ClassyModalSection>
    <LabeledInput label="Name">
        <InputControl value={name} onChange={setName} />
    </LabeledInput>
</ClassyModalSection>
```

**Import:** `@tec/common/classy/components`
**Available in:** Common (all plugins)

---

#### ClassyModalFooter

**Purpose:** Footer section for modal actions

**Props:**
```typescript
interface ClassyModalFooterProps {
    children: ReactNode;
}
```

**Example:**
```tsx
import { ClassyModalFooter, ClassyModalActions } from '@tec/common/classy/components';

<ClassyModalFooter>
    <ClassyModalActions>
        <Button variant="primary" onClick={onSave}>Save</Button>
        <Button variant="link" onClick={onCancel}>Cancel</Button>
    </ClassyModalActions>
</ClassyModalFooter>
```

**Import:** `@tec/common/classy/components`
**Available in:** Common (all plugins)

---

#### ClassyModalActions

**Purpose:** Wrapper for action buttons in modal footer

**Props:**
```typescript
interface ClassyModalActionsProps {
    children: ReactNode;
}
```

**Import:** `@tec/common/classy/components`
**Available in:** Common (all plugins)

---

### Input Components

#### DatePicker

**Purpose:** Date selection component with calendar popover

**Props:**
```typescript
interface DatePickerProps {
    date: Date;                     // Selected date
    onChange: (date: Date) => void; // Change handler
    label?: string;                 // Optional label
    className?: string;             // Additional CSS classes
}
```

**Example:**
```tsx
import { DatePicker } from '@tec/common/classy/components';

<DatePicker
    date={startDate}
    onChange={setStartDate}
    label="Event Start Date"
/>
```

**Import:** `@tec/common/classy/components`
**Available in:** Common (all plugins)

---

#### TimePicker

**Purpose:** Time selection component with hour/minute dropdowns

**Props:**
```typescript
interface TimePickerProps {
    time: string;                   // Time in HH:MM format
    onChange: (time: string) => void;
    label?: string;
}
```

**Example:**
```tsx
import { TimePicker } from '@tec/common/classy/components';

<TimePicker
    time="14:30"
    onChange={setStartTime}
    label="Start Time"
/>
```

**Import:** `@tec/common/classy/components`
**Available in:** Common (all plugins)

---

#### TimeZone

**Purpose:** Timezone selector with search

**Props:**
```typescript
interface TimeZoneProps {
    timezone: string;                    // IANA timezone string
    onChange: (timezone: string) => void;
    label?: string;
}
```

**Example:**
```tsx
import { TimeZone } from '@tec/common/classy/components';

<TimeZone
    timezone="America/New_York"
    onChange={setTimezone}
    label="Event Timezone"
/>
```

**Import:** `@tec/common/classy/components`
**Available in:** Common (all plugins)

---

#### StartSelector

**Purpose:** Combined date/time picker for start date/time

**Props:**
```typescript
interface StartSelectorProps {
    date: Date;
    time: string;
    onDateChange: (date: Date) => void;
    onTimeChange: (time: string) => void;
    label?: string;
}
```

**Example:**
```tsx
import { StartSelector } from '@tec/common/classy/components';

<StartSelector
    date={startDate}
    time={startTime}
    onDateChange={setStartDate}
    onTimeChange={setStartTime}
    label="Event Starts"
/>
```

**Import:** `@tec/common/classy/components`
**Available in:** Common (all plugins)

---

#### EndSelector

**Purpose:** Combined date/time picker for end date/time

**Props:**
```typescript
interface EndSelectorProps {
    date: Date;
    time: string;
    onDateChange: (date: Date) => void;
    onTimeChange: (time: string) => void;
    label?: string;
}
```

**Import:** `@tec/common/classy/components`
**Available in:** Common (all plugins)

---

#### CurrencyInput

**Purpose:** Input for currency amounts with formatting

**Props:**
```typescript
interface CurrencyInputProps {
    value: number;
    onChange: (value: number) => void;
    currency: CurrencyData;
    label?: string;
}
```

**Example:**
```tsx
import { CurrencyInput } from '@tec/common/classy/components';

<CurrencyInput
    value={price}
    onChange={setPrice}
    currency={currencyData}
    label="Ticket Price"
/>
```

**Import:** `@tec/common/classy/components`
**Available in:** Common (all plugins)

---

#### CurrencySelector

**Purpose:** Dropdown to select currency

**Props:**
```typescript
interface CurrencySelectorProps {
    currency: string;                      // Currency code (USD, EUR, etc.)
    onChange: (currency: string) => void;
    label?: string;
}
```

**Example:**
```tsx
import { CurrencySelector } from '@tec/common/classy/components';

<CurrencySelector
    currency="USD"
    onChange={setCurrency}
    label="Currency"
/>
```

**Import:** `@tec/common/classy/components`
**Available in:** Common (all plugins)

---

#### TinyMceEditor

**Purpose:** WordPress TinyMCE rich text editor integration

**Props:**
```typescript
interface TinyMceEditorProps {
    content: string;
    onChange: (content: string) => void;
    editorId: string;              // Unique ID for editor instance
    label?: string;
}
```

**Example:**
```tsx
import { TinyMceEditor } from '@tec/common/classy/components';

<TinyMceEditor
    content={description}
    onChange={setDescription}
    editorId="venue-description"
    label="Venue Description"
/>
```

**Import:** `@tec/common/classy/components`
**Available in:** Common (all plugins)

---

#### LabeledInput

**Purpose:** Wrapper that adds label to any input component

**Props:**
```typescript
interface LabeledInputProps {
    label: string;
    required?: boolean;
    children: ReactNode;
}
```

**Example:**
```tsx
import { LabeledInput } from '@tec/common/classy/components';
import { __experimentalInputControl as InputControl } from '@wordpress/components';

<LabeledInput label="Venue Name" required>
    <InputControl value={name} onChange={setName} />
</LabeledInput>
```

**Import:** `@tec/common/classy/components`
**Available in:** Common (all plugins)

---

#### InputLabel

**Purpose:** Standalone label component with required indicator

**Props:**
```typescript
interface InputLabelProps {
    label: string;
    required?: boolean;
    htmlFor?: string;
}
```

**Example:**
```tsx
import { InputLabel } from '@tec/common/classy/components';

<InputLabel label="Venue Name" required htmlFor="venue-name" />
<input id="venue-name" value={name} onChange={e => setName(e.target.value)} />
```

**Import:** `@tec/common/classy/components`
**Available in:** Common (all plugins)

---

### UI Components

#### ClassyModal

**Purpose:** Base modal wrapper using WordPress Modal component

**Props:**
```typescript
interface ClassyModalProps {
    isOpen: boolean;
    onClose: () => void;
    title?: string;
    children: ReactNode;
    className?: string;
}
```

**Example:**
```tsx
import { Modal } from '@wordpress/components';
import VenueUpsert from './VenueUpsert';

<Modal
    __experimentalHideHeader={true}
    className="classy-modal classy-modal--venue"
    onRequestClose={onClose}
>
    <VenueUpsert {...props} />
</Modal>
```

**Note:** Typically use WordPress `Modal` directly, not a wrapper component

**Import:** `@wordpress/components`
**Available in:** All plugins (WordPress component)

---

#### CenteredSpinner

**Purpose:** Loading spinner component

**Props:**
```typescript
interface CenteredSpinnerProps {
    className?: string;
}
```

**Example:**
```tsx
import { CenteredSpinner } from '@tec/common/classy/components';

{isLoading && <CenteredSpinner />}

{!data && <CenteredSpinner className="classy-full-width" />}
```

**Import:** `@tec/common/classy/components`
**Available in:** Common (all plugins)

---

#### ErrorBoundary

**Purpose:** Catches React errors and displays fallback UI

**Props:**
```typescript
interface ErrorBoundaryProps {
    children: ReactNode;
    fallback?: ReactNode;  // Custom error UI
}
```

**Example:**
```tsx
import { ErrorBoundary } from '@tec/common/classy/components';

<ErrorBoundary fallback={<div>Something went wrong</div>}>
    <MyComponent />
</ErrorBoundary>
```

**Import:** `@tec/common/classy/components`
**Available in:** Common (all plugins)

---

#### ErrorDisplay

**Purpose:** Displays error messages consistently

**Props:**
```typescript
interface ErrorDisplayProps {
    message: string;
    type?: 'error' | 'warning' | 'info';
}
```

**Example:**
```tsx
import { ErrorDisplay } from '@tec/common/classy/components';

{error && <ErrorDisplay message={error} type="error" />}
```

**Import:** `@tec/common/classy/components`
**Available in:** Common (all plugins)

---

### Icon Components (Common)

All Common icons follow the same pattern:

**Import:** `@tec/common/classy/components`
**Available in:** Common (all plugins)

**Props:** All icons accept:
```typescript
interface IconProps {
    className?: string;
    size?: number;
}
```

#### IconAdd

**Purpose:** Add/create new entity icon

```tsx
import { IconAdd } from '@tec/common/classy/components';

<Button onClick={onCreate}>
    <IconAdd /> New Venue
</Button>
```

---

#### IconEdit

**Purpose:** Edit entity icon

```tsx
import { IconEdit } from '@tec/common/classy/components';

<Button onClick={onEdit}>
    <IconEdit />
</Button>
```

---

#### IconTrash

**Purpose:** Delete entity icon

```tsx
import { IconTrash } from '@tec/common/classy/components';

<Button onClick={onRemove}>
    <IconTrash />
</Button>
```

---

#### IconClose

**Purpose:** Close/dismiss icon

```tsx
import { IconClose } from '@tec/common/classy/components';

<Button onClick={onClose}>
    <IconClose />
</Button>
```

---

#### IconNew

**Purpose:** New entity modal header icon

```tsx
import { IconNew } from '@tec/common/classy/components';

<header className="classy-modal__header">
    <IconNew />
    <h4>New Venue</h4>
</header>
```

---

#### IconCalendar

**Purpose:** Calendar/date icon

```tsx
import { IconCalendar } from '@tec/common/classy/components';

<Button>
    <IconCalendar /> Select Date
</Button>
```

---

#### IconTicket

**Purpose:** Ticket icon

```tsx
import { IconTicket } from '@tec/common/classy/components';

<h3><IconTicket /> Tickets</h3>
```

---

#### IconVideoCamera

**Purpose:** Video/virtual location icon

```tsx
import { IconVideoCamera } from '@tec/common/classy/components';

<h3><IconVideoCamera /> Virtual Location</h3>
```

---

#### IconCog

**Purpose:** Settings/configuration icon

```tsx
import { IconCog } from '@tec/common/classy/components';

<Button onClick={onSettings}>
    <IconCog /> Settings
</Button>
```

---

### Icon Components (ET)

**Import:** `@tec/tickets/classy/components`
**Available in:** ET plugin only

#### IconChevronDown
**Purpose:** Expand/show more icon

#### IconChevronUp
**Purpose:** Collapse/show less icon

#### IconClipboard
**Purpose:** Copy to clipboard icon

#### IconClock
**Purpose:** Time/duration icon

#### IconTimer
**Purpose:** Countdown timer icon

---

### Icon Components (ECP)

**Import:** `@tec/events-pro/classy/components`
**Available in:** ECP plugin only

#### Provider Icons

- **ZoomIcon** - Zoom logo
- **GoogleMeetIcon** - Google Meet logo
- **MicrosoftTeamsIcon** - Microsoft Teams logo
- **WebexIcon** - Webex logo
- **YouTubeIcon** - YouTube logo

**Example:**
```tsx
import { ZoomIcon } from '@tec/events-pro/classy/components';

<Button onClick={() => connectZoom()}>
    <ZoomIcon /> Connect Zoom
</Button>
```

#### Recurrence Icons

- **RecurringIcon** - Recurring event indicator
- **CalendarToggleIcon** - Calendar view toggle

---

### Card Components

#### Base Card Pattern

All cards follow this structure. See [Card Pattern](#pattern-2-card-pattern) for detailed examples.

**Base Props:**
```typescript
interface BaseCardProps {
    data: EntityData;
    onEdit?: (id: number) => void;
    onRemove: (id: number) => void;
}
```

---

#### VenueCard (TEC)

**Purpose:** Displays venue information

**File:** `/the-events-calendar/src/resources/packages/classy/fields/EventLocation/VenueCard.tsx`

**Import:** Local to TEC
**Available in:** TEC only

See [Simple Card Example](#simple-card-example-venuecard) for full code.

---

#### OrganizerCard (TEC)

**Purpose:** Displays organizer information

**File:** `/the-events-calendar/src/resources/packages/classy/fields/EventOrganizer/OrganizerCard.tsx`

**Import:** Local to TEC
**Available in:** TEC only

---

#### ZoomCard (ECP)

**Purpose:** Displays Zoom meeting information

**File:** `/events-pro/src/resources/packages/classy/fields/VirtualLocation/Cards/ZoomCard.tsx`

**Import:** Local to ECP
**Available in:** ECP only

See [Complex Card Example](#complex-card-example-zoomcard) for full code.

---

#### Virtual Location Provider Cards (ECP)

- **GoogleCard** - Google Meet connection
- **MicrosoftCard** - Microsoft Teams connection
- **WebexCard** - Webex meeting
- **YouTubeCard** - YouTube livestream
- **UrlCard** - Generic URL

**Import:** Local to ECP
**Available in:** ECP only

---

### Field Components

Field components are complete field implementations including labels, inputs, validation, and state management.

#### TEC Fields

**Available in:** TEC plugin
**Location:** `/the-events-calendar/src/resources/packages/classy/fields/`

- **EventAdmission** - Free/paid admission toggle
- **EventDateTime** - Start/end date and time
- **EventCost** - Event cost with currency
- **EventLocation** - Venue selection and management
- **EventOrganizer** - Organizer selection and management
- **EventDetails** - Additional event details

---

#### ECP Fields

**Available in:** ECP plugin
**Location:** `/events-pro/src/resources/packages/classy/fields/`

- **VirtualLocation** - Virtual location provider connection
- **Recurrence** - Recurring event pattern configuration

---

#### ET Fields

**Available in:** ET plugin
**Location:** `/event-tickets/src/resources/packages/classy/fields/`

- **Tickets** - Ticket list and management
- **Capacity** - Event capacity configuration

---

### Using Components

#### Import Examples

```typescript
// Common components
import {
    ClassyField,
    ClassyModal,
    DatePicker,
    TimePicker,
    IconAdd,
    IconEdit,
    CenteredSpinner
} from '@tec/common/classy/components';

// TEC types
import { VenueData } from '@tec/events/classy/types';

// ET components (when extending ET)
import { Ticket } from '@tec/tickets/classy/types';

// ECP components (when extending ECP)
import { RecurrenceData } from '@tec/events-pro/classy/types';
```

---

#### Component Composition Example

```tsx
import * as React from 'react';
import { useState } from 'react';
import { Modal, Button } from '@wordpress/components';
import {
    ClassyField,
    LabeledInput,
    DatePicker,
    IconAdd,
    IconEdit,
    CenteredSpinner
} from '@tec/common/classy/components';

export default function MyField() {
    const [showModal, setShowModal] = useState(false);
    const [isLoading, setIsLoading] = useState(false);

    if (isLoading) {
        return <CenteredSpinner />;
    }

    return (
        <ClassyField title="My Field">
            <DatePicker
                date={date}
                onChange={setDate}
            />

            <Button onClick={() => setShowModal(true)}>
                <IconAdd /> Add Item
            </Button>

            {showModal && (
                <Modal onRequestClose={() => setShowModal(false)}>
                    <LabeledInput label="Name">
                        {/* Input here */}
                    </LabeledInput>
                </Modal>
            )}
        </ClassyField>
    );
}
```

---

## 7. Import Path & Module Resolution

### Overview

The Classy architecture uses **webpack externals** to resolve module imports at runtime. This allows plugins to share code without bundling duplicates and enables cross-plugin dependencies.

---

### Webpack Configuration

#### External Resolution Pattern

**File:** `webpack.config.js` (in each plugin)

```javascript
module.exports = {
    externals: {
        // Common
        '@tec/common': 'window.tec.common',

        // Plugins
        '@tec/events': 'window.tec.events',
        '@tec/tickets': 'window.tec.tickets',
        '@tec/events-pro': 'window.tec.eventsPro',
        '@tec/tickets-plus': 'window.tec.ticketsPlus',

        // WordPress
        '@wordpress/data': 'window.wp.data',
        '@wordpress/element': 'window.wp.element',
        '@wordpress/components': 'window.wp.components',
        // ... more WordPress packages
    }
};
```

---

#### What This Means

**At Build Time:**
```typescript
// Your code
import { ClassyModal } from '@tec/common/classy/components';
```

**After webpack build:**
```javascript
// Bundled code (simplified)
const ClassyModal = window.tec.common.classy.components.ClassyModal;
```

**Result:**
- Import statements are NOT bundled into your plugin's JavaScript
- At runtime, webpack resolves to `window` globals
- Shared code is loaded once, used by all plugins
- Reduces bundle size significantly

---

### Import Conventions

#### DO: Use Namespaced Imports

**From Common:**
```typescript
// Components
import {
    ClassyModal,
    ClassyField,
    DatePicker,
    IconAdd
} from '@tec/common/classy/components';

// Types
import { FieldProps } from '@tec/common/classy/types';

// Functions
import { isValidUrl } from '@tec/common/classy/functions';

// Store
import { getRegistry } from '@tec/common/classy/store';
```

**Cross-Plugin:**
```typescript
// TEC types in ECP
import { EventData } from '@tec/events/classy/types';

// TEC store in ECP
import { useSelect } from '@wordpress/data';
const eventId = useSelect(select =>
    select('tec/classy/events').getEventId()
);
```

---

#### DON'T: Use Relative Paths for Cross-Plugin

**Wrong:**
```typescript
// ❌ DON'T: Relative paths for Common
import ClassyModal from '../../common/classy/components/ClassyModal';

// ❌ DON'T: Relative paths for cross-plugin
import { EventData } from '../../../events/classy/types/EventData';
```

**Correct:**
```typescript
// ✅ DO: Use namespaced imports
import { ClassyModal } from '@tec/common/classy/components';
import { EventData } from '@tec/events/classy/types';
```

**Why:**
- Webpack externals only work with namespaced imports
- Relative paths will try to bundle the code
- Creates duplication and increases bundle size

---

#### DO: Use Relative Paths Within Same Plugin

**Correct for same-plugin imports:**
```typescript
// Within TEC plugin - GOOD
import { VenueData } from '../../types/VenueData';
import { fetchVenues } from '../api/venues';
import VenueCard from './VenueCard';
```

**Why:**
- These are local to the plugin
- Should be bundled with the plugin
- Not shared across plugins

---

### Dependency Graph

```
Common (Base Layer)
├─> window.tec.common
├─> Provides: Base components, utilities, types
└─> No dependencies

TEC (Events Layer)
├─> window.tec.events
├─> Depends on: Common
├─> Provides: Event-specific functionality
└─> Imports: @tec/common/classy/*

ECP (Events Pro Layer)
├─> window.tec.eventsPro
├─> Depends on: TEC + Common
├─> Provides: Premium event features
└─> Imports: @tec/common/classy/*, @tec/events/classy/*

ET (Tickets Layer)
├─> window.tec.tickets
├─> Depends on: Common
├─> Provides: Ticket-specific functionality
└─> Imports: @tec/common/classy/*

ETP (Tickets Plus Layer)
├─> window.tec.ticketsPlus
├─> Depends on: ET + Common
├─> Provides: Premium ticket features
└─> Imports: @tec/common/classy/*, @tec/tickets/classy/*
```

---

### External Resolution Mapping

| Import | Resolves To | Available When |
|--------|-------------|----------------|
| `@tec/common/classy/components` | `window.tec.common.classy.components` | Common loaded |
| `@tec/events/classy/types` | `window.tec.events.classy.types` | TEC loaded |
| `@tec/tickets/classy/types` | `window.tec.tickets.classy.types` | ET loaded |
| `@tec/events-pro/classy/types` | `window.tec.eventsPro.classy.types` | ECP loaded |
| `@wordpress/data` | `window.wp.data` | WordPress core |
| `@wordpress/element` | `window.wp.element` | WordPress core |
| `@wordpress/components` | `window.wp.components` | WordPress core |

---

### Load Order & Dependencies

#### Plugin Load Order

```
1. Common (TEC or ET)  - Always first
2. TEC Plugin          - After TEC Common
3. ECP Plugin          - After TEC Plugin
4. ET Plugin           - After ET Common
5. ETP Plugin          - After ET Plugin
```

---

#### Runtime Sequence

```
1. Common loads → Sets window.tec.common
2. TEC loads    → Sets window.tec.events (can access window.tec.common)
3. ECP loads    → Sets window.tec.eventsPro (can access both)
4. ET loads     → Sets window.tec.tickets (can access window.tec.common)
5. ETP loads    → Sets window.tec.ticketsPlus (can access ET + Common)
```

---

#### Initialization Hooks

```typescript
// TEC Common: Fires initialization
doAction('tec.classy.initialized');

// TEC Plugin: Waits for Common
if (didAction('tec.classy.initialized')) {
    registerStore();
} else {
    addAction('tec.classy.initialized', 'tec.classy.events', registerStore);
}

// ECP Plugin: Waits for TEC
if (didAction('tec.classy.events.initialized')) {
    registerStore();
} else {
    addAction('tec.classy.events.initialized', 'tec.classy.eventsPro', registerStore);
}
```

---

### Import Anti-Patterns

#### DON'T: Import from Plugin That May Not Exist

```typescript
// In ET plugin - BAD
import { VenueData } from '@tec/events/classy/types';
// ET can run without TEC - this will fail!
```

---

#### DON'T: Import from Child Plugin

```typescript
// In TEC plugin - BAD
import { RecurrenceData } from '@tec/events-pro/classy/types';
// TEC doesn't know if ECP is installed - this will fail!
```

---

#### DON'T: Create Circular Dependencies

```typescript
// In Common - BAD
import { EventData } from '@tec/events/classy/types';
// Common is base layer, can't depend on plugins!
```

---

#### DO: Follow Dependency Hierarchy

```typescript
// In ECP plugin - GOOD
import { ClassyModal } from '@tec/common/classy/components'; // Base layer
import { EventData } from '@tec/events/classy/types';        // Parent layer
import { RecurrenceData } from '../../types/Recurrence';     // Local

// Dependencies flow upward: ECP → TEC → Common
```

---

### Best Practices Summary

**DO:**
- ✅ Use `@tec/common/classy/*` for all Common imports
- ✅ Use `@tec/events/classy/*` for TEC imports (only if depending on TEC)
- ✅ Use relative paths for same-plugin imports
- ✅ Follow dependency hierarchy (always import from parent/base layers)
- ✅ Check plugin availability before importing cross-plugin

**DON'T:**
- ❌ Use relative paths for cross-plugin imports
- ❌ Import from plugins that may not be installed
- ❌ Create circular dependencies
- ❌ Import from child plugins (parent can't depend on child)
- ❌ Bundle Common code in plugins (use externals)

---

## 8. Build System & Development

### Overview

The Classy architecture uses **webpack** and **PostCSS** to build JavaScript and CSS assets. Each plugin has its own build process, but Common components must be built in both TEC and ET.

---

### Build Process Overview

#### Build Steps

1. **TypeScript Compilation** - TSX/TS files → JavaScript
2. **Webpack Bundling** - Module resolution and external mapping
3. **PostCSS Processing** - PCSS files → CSS with transformations
4. **Asset Output** - Compiled files to `build/` directory

---

#### Output Locations

```
Plugin Root
└─> src/resources/packages/classy/
    ├─> components/         (Source TSX/PCSS)
    ├─> fields/             (Source TSX/PCSS)
    ├─> types/              (Source TS)
    └─> style.pcss          (Main stylesheet)

Plugin Root
└─> build/classy/
    ├─> index.js            (Bundled JavaScript)
    ├─> index.asset.php     (Dependency manifest)
    └─> style.css           (Compiled stylesheet)
```

---

### Build Commands

#### Development Build

```bash
# From plugin root directory
nvm use && npm run build
```

**What it does:**
- Compiles TypeScript
- Bundles JavaScript with webpack
- Processes CSS with PostCSS
- Outputs to `build/` directory
- **Does NOT** watch for changes

---

#### Watch Mode

```bash
# From plugin root directory
nvm use && npm run build:watch
```

**What it does:**
- Same as `npm run build`
- Watches source files for changes
- Automatically rebuilds on save
- **Recommended for development**

---

#### Production Build

```bash
# From plugin root directory
nvm use && npm run build
```

**Note:** Same command, but typically run in CI/CD with production environment variables set.

---

### Build Order Dependencies

#### Rule: Common Must Build First (or in parallel)

```
Option 1: Sequential
1. Build TEC Common
2. Build TEC Plugin
3. Build ET Common
4. Build ET Plugin
5. Build ECP Plugin (if installed)
6. Build ETP Plugin (if installed)

Option 2: Parallel (Faster)
1. Build TEC Common + ET Common in parallel
2. Build TEC + ET plugins in parallel
3. Build ECP + ETP plugins in parallel
```

---

#### Why Order Matters

**For Type Checking:**
- Plugins import types from Common
- TypeScript needs Common types to be available
- If Common not built, type imports fail

**For External Resolution:**
- Plugins expect `window.tec.common` to exist
- Common must load before plugins
- WordPress plugin dependencies enforce this at runtime

---

#### Building All Plugins

```bash
# From workspace root (if using monorepo)
cd /path/to/plugins

# Build TEC Common
cd the-events-calendar && nvm use && npm run build && cd ..

# Build ET Common
cd event-tickets && nvm use && npm run build && cd ..

# Build ECP (if installed)
cd events-pro && nvm use && npm run build && cd ..

# Build ETP (if installed)
cd event-tickets-plus && nvm use && npm run build && cd ..
```

---

### Development Workflow

#### Recommended Workflow

1. **Start Watch Mode in Each Plugin**
   ```bash
   # Terminal 1: TEC Common
   cd the-events-calendar && nvm use && npm run build:watch

   # Terminal 2: ECP
   cd events-pro && nvm use && npm run build:watch

   # Terminal 3: ET
   cd event-tickets && nvm use && npm run build:watch
   ```

2. **Make Changes to Source Files**
   - Edit TSX, TS, or PCSS files
   - Watch mode automatically rebuilds
   - Changes appear in browser after reload

3. **Refresh Browser**
   - Reload WordPress admin
   - Test your changes
   - Repeat

---

#### Hot Reload Limitations

**Note:** Classy does NOT support hot module replacement (HMR).

- Changes require full page reload
- Watch mode only rebuilds files
- No automatic browser refresh
- Use a browser extension for auto-reload (LiveReload, etc.)

---

#### Debugging Tips

**Source Maps:**
- Enabled by default in development
- View original TypeScript in browser DevTools
- Set breakpoints in source files

**Console Logging:**
```typescript
// Add debug logs
console.log('Component rendering:', { props, state });

// Use React DevTools
// Install browser extension
// Inspect component props/state
```

**Build Errors:**
```bash
# Clear build cache
rm -rf node_modules/.cache

# Rebuild
npm run build

# Check for errors in output
# Look for TypeScript errors, webpack warnings
```

---

### Asset Handling

#### Images

**Location:** `/src/resources/packages/classy/images/`

**Import:**
```typescript
import logoSrc from '../images/logo.png';

<img src={logoSrc} alt="Logo" />
```

**Build:** Webpack copies to `build/` and returns URL

---

#### SVGs

**Option 1: Inline SVG (Preferred)**
```typescript
export default function IconAdd() {
    return (
        <svg width="24" height="24">
            <path d="..." />
        </svg>
    );
}
```

**Option 2: SVG as Image**
```typescript
import iconSrc from '../images/icon.svg';

<img src={iconSrc} alt="Icon" />
```

---

#### Fonts

**Not typically used in Classy**
- Rely on WordPress admin fonts
- Use TEC design token font families

---

#### Icons

**Create as React components:**
```typescript
// File: /common/src/resources/packages/classy/components/Icons/MyIcon.tsx

export default function IconMyIcon({ className = '', size = 24 }) {
    return (
        <svg
            className={`classy-icon ${className}`}
            width={size}
            height={size}
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path d="..." fill="currentColor" />
        </svg>
    );
}
```

---

### Common Sync in Builds

**CRITICAL:** Remember TEC and ET Common are separate!

#### When Building Common Changes

```bash
# 1. Build TEC Common
cd the-events-calendar
nvm use && npm run build

# 2. Build ET Common (MUST also build)
cd ../event-tickets
nvm use && npm run build

# 3. Verify both built successfully
# Check for build errors in both
```

---

#### Common Files Must Stay Identical

After building both:

```bash
# Verify style.css is identical
diff \
  the-events-calendar/build/classy/style.css \
  event-tickets/build/classy/style.css

# Should output: no differences

# Verify index.js is similar (won't be byte-identical due to paths)
# But check for major differences
```

---

### Build Troubleshooting

#### Error: Cannot find module '@tec/common/classy/components'

**Cause:** Common not built yet

**Fix:**
```bash
cd /path/to/common
npm run build
```

---

#### Error: TypeScript compilation failed

**Cause:** Type errors in source files

**Fix:**
1. Read error output carefully
2. Fix type errors in source
3. Rebuild

---

#### Error: PostCSS processing failed

**Cause:** CSS syntax error in PCSS files

**Fix:**
1. Check PCSS file mentioned in error
2. Fix CSS syntax
3. Rebuild

---

#### Styles Not Updating

**Cause:** Browser cache or build cache

**Fix:**
```bash
# Clear browser cache (hard reload)
# Chrome: Cmd+Shift+R (Mac) or Ctrl+Shift+R (Windows)

# Clear build cache
rm -rf node_modules/.cache
npm run build
```

---

#### JavaScript Not Updating

**Cause:** WordPress asset caching

**Fix:**
1. Clear WordPress cache (if using caching plugin)
2. Hard reload browser
3. Check Network tab in DevTools for 304 vs 200 responses

---

### Build System Best Practices

**DO:**
- ✅ Use watch mode during development
- ✅ Build Common before plugins
- ✅ Build both TEC and ET Common for Common changes
- ✅ Check build output for errors
- ✅ Test in browser after building

**DON'T:**
- ❌ Skip building Common
- ❌ Build only TEC Common (must build ET too)
- ❌ Ignore build warnings
- ❌ Commit `build/` directory to git
- ❌ Assume HMR works (it doesn't)

---

## 9. Testing Patterns

### Overview

The Classy architecture uses **Jest** for unit and integration testing. Tests ensure components, functions, and stores work correctly in isolation and together.

---

### Jest Configuration

#### Test Location

```
Plugin Root
└─> tests/classy_jest/
    ├─> components/      (Component tests)
    ├─> functions/       (Function tests)
    ├─> store/           (Store tests)
    └─> types/           (Type tests)
```

---

#### Running Tests

```bash
# From plugin root directory
nvm use && npm run classy:jest

# Run specific test file
nvm use && npm run classy:jest -- ComponentName.spec.tsx

# Run in watch mode
nvm use && npm run classy:jest -- --watch

# Run with coverage
nvm use && npm run classy:jest -- --coverage
```

---

### Test Utilities from Common

Common provides shared test utilities:

**Location:** `/the-events-calendar/common/src/resources/packages/classy/test-utils/`

#### Mock Utilities

```typescript
// Mock WordPress hooks
import { mockDoAction, mockAddAction } from '@tec/common/classy/test-utils';

// Mock WordPress data store
import { mockSelect, mockDispatch } from '@tec/common/classy/test-utils';

// Mock API calls
import { mockApiFetch } from '@tec/common/classy/test-utils';
```

---

#### Test Helpers

```typescript
// Render with providers
import { renderWithProviders } from '@tec/common/classy/test-utils';

// Create mock store
import { createMockStore } from '@tec/common/classy/test-utils';

// Mock data factories
import { createMockVenue, createMockTicket } from '@tec/common/classy/test-utils';
```

---

### Mocking WordPress Dependencies

#### Mocking @wordpress/data

```typescript
import { useSelect, useDispatch } from '@wordpress/data';

// Mock useSelect
jest.mock('@wordpress/data', () => ({
    useSelect: jest.fn(),
    useDispatch: jest.fn(),
}));

// In test
beforeEach(() => {
    (useSelect as jest.Mock).mockImplementation((selector) => {
        return selector({
            'tec/classy/events': {
                getVenues: () => [mockVenue1, mockVenue2],
                getSelectedVenueIds: () => [1]
            }
        });
    });

    (useDispatch as jest.Mock).mockReturnValue({
        'tec/classy/events': {
            addVenue: jest.fn(),
            removeVenue: jest.fn()
        }
    });
});
```

---

#### Mocking @wordpress/components

```typescript
jest.mock('@wordpress/components', () => ({
    Button: ({ children, onClick }) => (
        <button onClick={onClick}>{children}</button>
    ),
    Modal: ({ children, onRequestClose }) => (
        <div data-testid="modal">
            <button onClick={onRequestClose}>Close</button>
            {children}
        </div>
    ),
    __experimentalInputControl: ({ value, onChange }) => (
        <input value={value} onChange={(e) => onChange(e.target.value)} />
    )
}));
```

---

#### Mocking @wordpress/element

```typescript
// Usually not needed - Jest handles React automatically
// But if needed:
jest.mock('@wordpress/element', () => require('react'));
```

---

### Testing Slot/Fill Interactions

#### Testing Slots

```typescript
import { render, screen } from '@testing-library/react';
import { SlotFillProvider } from '@wordpress/components';

test('renders slot', () => {
    render(
        <SlotFillProvider>
            <ComponentWithSlot />
        </SlotFillProvider>
    );

    // Slot should be in DOM
    expect(screen.getByRole('region', { name: 'slot-name' })).toBeInTheDocument();
});
```

---

#### Testing Fills

```typescript
import { render, screen } from '@testing-library/react';
import { SlotFillProvider, Slot, Fill } from '@wordpress/components';

test('fill renders in slot', () => {
    render(
        <SlotFillProvider>
            <Slot name="test-slot" />
            <Fill name="test-slot">
                <div>Fill Content</div>
            </Fill>
        </SlotFillProvider>
    );

    expect(screen.getByText('Fill Content')).toBeInTheDocument();
});
```

---

#### Testing Integration

```typescript
import { render, screen } from '@testing-library/react';
import { SlotFillProvider } from '@wordpress/components';
import EventLocation from '../fields/EventLocation';
import VirtualLocation from '../fields/VirtualLocation';

test('virtual location fills event location slot', () => {
    render(
        <SlotFillProvider>
            <EventLocation />
            <VirtualLocation />
        </SlotFillProvider>
    );

    // Both components should render
    expect(screen.getByText('Event Location')).toBeInTheDocument();
    expect(screen.getByText('Virtual Location')).toBeInTheDocument();
});
```

---

### Store Testing Patterns

#### Testing Selectors

```typescript
import { getVenues, getVenue } from '../store/selectors';

describe('Venue Selectors', () => {
    const mockState = {
        venues: {
            byId: {
                1: { id: 1, name: 'Venue 1' },
                2: { id: 2, name: 'Venue 2' }
            },
            allIds: [1, 2]
        }
    };

    test('getVenues returns all venues', () => {
        const venues = getVenues(mockState);

        expect(venues).toHaveLength(2);
        expect(venues[0].name).toBe('Venue 1');
    });

    test('getVenue returns specific venue', () => {
        const venue = getVenue(mockState, 1);

        expect(venue.name).toBe('Venue 1');
    });

    test('getVenue returns undefined for non-existent venue', () => {
        const venue = getVenue(mockState, 999);

        expect(venue).toBeUndefined();
    });
});
```

---

#### Testing Actions

```typescript
import { setVenues, addVenue, removeVenue } from '../store/actions';

describe('Venue Actions', () => {
    test('setVenues creates correct action', () => {
        const venues = [{ id: 1, name: 'Venue 1' }];
        const action = setVenues(venues);

        expect(action).toEqual({
            type: 'SET_VENUES',
            venues
        });
    });

    test('addVenue creates correct action', () => {
        const venue = { id: 1, name: 'Venue 1' };
        const action = addVenue(venue);

        expect(action).toEqual({
            type: 'ADD_VENUE',
            venue
        });
    });
});
```

---

#### Testing Reducers

```typescript
import reducer from '../store/reducer';
import { setVenues, addVenue, removeVenue } from '../store/actions';

describe('Venue Reducer', () => {
    const initialState = {
        venues: {
            byId: {},
            allIds: []
        }
    };

    test('SET_VENUES updates state', () => {
        const venues = [
            { id: 1, name: 'Venue 1' },
            { id: 2, name: 'Venue 2' }
        ];

        const newState = reducer(initialState, setVenues(venues));

        expect(newState.venues.allIds).toEqual([1, 2]);
        expect(newState.venues.byId[1].name).toBe('Venue 1');
    });

    test('ADD_VENUE adds venue', () => {
        const venue = { id: 3, name: 'Venue 3' };

        const newState = reducer(initialState, addVenue(venue));

        expect(newState.venues.allIds).toContain(3);
        expect(newState.venues.byId[3]).toEqual(venue);
    });

    test('REMOVE_VENUE removes venue', () => {
        const stateWithVenues = {
            venues: {
                byId: {
                    1: { id: 1, name: 'Venue 1' },
                    2: { id: 2, name: 'Venue 2' }
                },
                allIds: [1, 2]
            }
        };

        const newState = reducer(stateWithVenues, removeVenue(1));

        expect(newState.venues.allIds).not.toContain(1);
        expect(newState.venues.byId[1]).toBeUndefined();
    });
});
```

---

#### Testing Async Actions

```typescript
import { fetchVenues, saveVenue } from '../store/actions';
import { apiFetch } from '@wordpress/api-fetch';

jest.mock('@wordpress/api-fetch');

describe('Async Venue Actions', () => {
    test('fetchVenues calls API and dispatches result', async () => {
        const mockVenues = [{ id: 1, name: 'Venue 1' }];
        (apiFetch as jest.Mock).mockResolvedValue(mockVenues);

        const dispatch = jest.fn();
        const generator = fetchVenues();

        // First yield: API call
        let next = generator.next();
        expect(next.value).toEqual(apiFetch({ path: '/tec/v1/venues' }));

        // Second yield: dispatch action
        next = generator.next(mockVenues);
        // Generator yields setVenues action

        // Complete
        next = generator.next();
        expect(next.done).toBe(true);
    });

    test('saveVenue handles errors', async () => {
        const error = new Error('API Error');
        (apiFetch as jest.Mock).mockRejectedValue(error);

        const generator = saveVenue({ name: 'Test Venue' });

        try {
            let next = generator.next();
            next = generator.throw(error);
        } catch (e) {
            expect(e).toBe(error);
        }
    });
});
```

---

### Component Testing Examples

#### Simple Component Test

```typescript
import { render, screen } from '@testing-library/react';
import { ClassyField } from '../ClassyField';

describe('ClassyField', () => {
    test('renders field with title', () => {
        render(
            <ClassyField title="Test Field">
                <input type="text" />
            </ClassyField>
        );

        expect(screen.getByText('Test Field')).toBeInTheDocument();
        expect(screen.getByRole('textbox')).toBeInTheDocument();
    });

    test('renders children', () => {
        render(
            <ClassyField title="Test Field">
                <div data-testid="child">Child Content</div>
            </ClassyField>
        );

        expect(screen.getByTestId('child')).toBeInTheDocument();
    });
});
```

---

#### Component with State Test

```typescript
import { render, screen, fireEvent } from '@testing-library/react';
import userEvent from '@testing-library/user-event';
import VenueUpsert from '../VenueUpsert';

describe('VenueUpsert', () => {
    const mockOnSave = jest.fn();
    const mockOnCancel = jest.fn();

    test('enables save button when name is entered', async () => {
        render(
            <VenueUpsert
                values={{ name: '' }}
                onSave={mockOnSave}
                onCancel={mockOnCancel}
                isUpdate={false}
            />
        );

        const nameInput = screen.getByLabelText('Name');
        const saveButton = screen.getByRole('button', { name: /save/i });

        // Initially disabled
        expect(saveButton).toHaveAttribute('aria-disabled', 'true');

        // Type name
        await userEvent.type(nameInput, 'Test Venue');

        // Now enabled
        expect(saveButton).toHaveAttribute('aria-disabled', 'false');
    });

    test('calls onSave when save button clicked', async () => {
        render(
            <VenueUpsert
                values={{ name: 'Test Venue' }}
                onSave={mockOnSave}
                onCancel={mockOnCancel}
                isUpdate={false}
            />
        );

        const saveButton = screen.getByRole('button', { name: /save/i });
        fireEvent.click(saveButton);

        expect(mockOnSave).toHaveBeenCalledWith(
            expect.objectContaining({ name: 'Test Venue' })
        );
    });
});
```

---

#### Component with Store Integration Test

```typescript
import { render, screen } from '@testing-library/react';
import { useSelect } from '@wordpress/data';
import EventLocation from '../EventLocation';

jest.mock('@wordpress/data');

describe('EventLocation', () => {
    test('displays venues from store', () => {
        const mockVenues = [
            { id: 1, name: 'Venue 1' },
            { id: 2, name: 'Venue 2' }
        ];

        (useSelect as jest.Mock).mockReturnValue({
            venues: mockVenues,
            selectedVenueIds: [1, 2]
        });

        render(<EventLocation title="Event Location" />);

        expect(screen.getByText('Venue 1')).toBeInTheDocument();
        expect(screen.getByText('Venue 2')).toBeInTheDocument();
    });

    test('shows loading spinner when no data', () => {
        (useSelect as jest.Mock).mockReturnValue({
            venues: null,
            selectedVenueIds: []
        });

        render(<EventLocation title="Event Location" />);

        expect(screen.getByTestId('spinner')).toBeInTheDocument();
    });
});
```

---

### Testing Best Practices

**DO: Test User Interactions**
```typescript
// GOOD
test('user can enter venue name', async () => {
    render(<VenueUpsert {...props} />);
    await userEvent.type(screen.getByLabelText('Name'), 'Test Venue');
    expect(screen.getByDisplayValue('Test Venue')).toBeInTheDocument();
});
```

**DO: Test Edge Cases**
```typescript
test('handles empty venue name', () => {
    const venue = getVenue(state, '');
    expect(venue).toBeUndefined();
});

test('handles non-existent venue ID', () => {
    const venue = getVenue(state, 9999);
    expect(venue).toBeUndefined();
});
```

**DO: Use Testing Library Queries**
```typescript
// GOOD: Semantic queries
screen.getByRole('button', { name: /save/i });
screen.getByLabelText('Venue Name');
screen.getByText('Error message');

// AVOID: Test IDs (use only when necessary)
screen.getByTestId('venue-card');
```

**DON'T: Test Implementation Details**
```typescript
// BAD: Testing state directly
expect(component.state.venues).toEqual([...]);

// GOOD: Test behavior
expect(screen.getByText('Venue 1')).toBeInTheDocument();
```

**DON'T: Test WordPress Components**
```typescript
// BAD: Testing that Button works
test('Button renders', () => {
    render(<Button>Click</Button>);
    expect(screen.getByText('Click')).toBeInTheDocument();
});

// GOOD: Test YOUR component using Button
test('save button triggers onSave', () => {
    render(<VenueUpsert {...props} />);
    fireEvent.click(screen.getByRole('button', { name: /save/i }));
    expect(mockOnSave).toHaveBeenCalled();
});
```

---

## 10. Enforcement & Maintenance

### Overview

This section covers procedures for maintaining code quality, enforcing conventions, and managing changes to the Classy architecture.

**Quick Reference:** For everyday development, use the quick reference sections below.

**Detailed Guides:** For comprehensive procedures, see:
- **[Linting Recommendations](../../../docs/classy/linting-recommendations.md)** - Complete linting setup with Stylelint, ESLint, pre-commit hooks, and CI/CD integration
- **[Common Sync Procedures](../../../docs/classy/common-sync-procedures.md)** - Step-by-step procedures for keeping TEC and ET Common synchronized
- **[Maintenance Guide](../../../docs/classy/maintenance-guide.md)** - Comprehensive guide for updates, deprecation, breaking changes, and version management

---

### Linting Recommendations

#### Stylelint for CSS BEM Enforcement

**Install:**
```bash
npm install --save-dev stylelint stylelint-selector-bem-pattern
```

**Configuration:** `.stylelintrc.json`
```json
{
    "plugins": [
        "stylelint-selector-bem-pattern"
    ],
    "rules": {
        "plugin/selector-bem-pattern": {
            "preset": "bem",
            "componentName": "classy-[a-z]+",
            "componentSelectors": {
                "initial": "^\\.{componentName}(?:__[a-z]+)?(?:--[a-z]+)?$"
            }
        }
    }
}
```

**Run:**
```bash
npx stylelint "**/*.pcss"
```

---

#### ESLint for Class Name Validation

**Configuration:** `.eslintrc.json`
```json
{
    "rules": {
        "no-restricted-syntax": [
            "error",
            {
                "selector": "Literal[value=/classy_/]",
                "message": "Use double underscore for BEM elements: classy__element"
            },
            {
                "selector": "Literal[value=/\\.class-/]",
                "message": "Use 'classy-' prefix, not 'class-'"
            }
        ]
    }
}
```

---

#### Pre-commit Hooks

**Install Husky:**
```bash
npm install --save-dev husky lint-staged
```

**Configuration:** `package.json`
```json
{
    "lint-staged": {
        "*.pcss": ["stylelint --fix"],
        "*.{ts,tsx}": ["eslint --fix"]
    },
    "husky": {
        "hooks": {
            "pre-commit": "lint-staged"
        }
    }
}
```

---

### Common Sync Procedures

**CRITICAL:** TEC and ET Common must stay 100% identical.

#### Sync Checklist

When updating Common components:

- [ ] **1. Make change in TEC Common**
  ```bash
  # Edit file in TEC Common
  vim the-events-calendar/common/src/resources/packages/classy/components/ClassyField.tsx
  ```

- [ ] **2. Test the change in TEC**
  ```bash
  cd the-events-calendar
  npm run build
  # Test in browser
  ```

- [ ] **3. Copy to ET Common**
  ```bash
  cp the-events-calendar/common/src/resources/packages/classy/components/ClassyField.tsx \
     event-tickets/common/src/resources/packages/classy/components/ClassyField.tsx
  ```

- [ ] **4. Verify files are identical**
  ```bash
  diff \
    the-events-calendar/common/src/resources/packages/classy/components/ClassyField.tsx \
    event-tickets/common/src/resources/packages/classy/components/ClassyField.tsx
  # Should output: no differences
  ```

- [ ] **5. Build ET Common**
  ```bash
  cd event-tickets
  npm run build
  # Check for errors
  ```

- [ ] **6. Test ET**
  - Test in browser with ET active (TEC inactive)
  - Verify component works correctly

- [ ] **7. Commit both changes together**
  ```bash
  git add the-events-calendar/common/src/resources/packages/classy/components/ClassyField.tsx
  git add event-tickets/common/src/resources/packages/classy/components/ClassyField.tsx
  git commit -m "Update Common: Improve ClassyField validation"
  ```

---

#### What Requires Sync

**ALWAYS sync:**
- All files in `/common/src/resources/packages/classy/`
- Components
- Types
- Functions
- Store files
- `style.pcss`

**NEVER sync:**
- Plugin-specific files (in `/src/resources/packages/classy/`)
- Field implementations (outside Common)
- Plugin stores

---

#### Verification Commands

```bash
# Verify entire Common directory is identical
diff -rq \
  --exclude="node_modules" \
  --exclude="build" \
  the-events-calendar/common/src/resources/packages/classy/ \
  event-tickets/common/src/resources/packages/classy/

# Verify specific file
diff \
  the-events-calendar/common/src/resources/packages/classy/style.pcss \
  event-tickets/common/src/resources/packages/classy/style.pcss

# Count files (should match)
find the-events-calendar/common/src/resources/packages/classy/ -type f | wc -l
find event-tickets/common/src/resources/packages/classy/ -type f | wc -l
```

---

### Component Update Protocol

#### When Updating Common Components

1. **Assess Impact**
   - Which plugins use this component?
   - Are there breaking changes?
   - Do plugin-specific styles need updates?

2. **Update Documentation**
   - Update this guide if API changes
   - Update component comments
   - Add migration notes if breaking

3. **Test in All Plugins**
   - Test in TEC
   - Test in ET
   - Test in ECP (if relevant)
   - Test in ETP (if relevant)

4. **Update Dependents**
   - Fix plugin-specific code that breaks
   - Update CSS if needed
   - Update tests

5. **Communicate Changes**
   - Document in changelog
   - Notify team of breaking changes
   - Provide migration path

---

### Adding New Components

#### Decision Tree

```
Where should new component go?

Is it used by multiple plugins?
├─ YES → Common
└─ NO  → Is it generic UI pattern?
    ├─ YES → Common
    └─ NO  → Plugin-specific
```

---

#### Adding to Common

**Checklist:**

- [ ] **1. Create component in TEC Common**
  ```bash
  # File: the-events-calendar/common/src/resources/packages/classy/components/NewComponent.tsx
  ```

- [ ] **2. Export from index**
  ```typescript
  // File: the-events-calendar/common/src/resources/packages/classy/components/index.ts
  export { default as NewComponent } from './NewComponent';
  ```

- [ ] **3. Add TypeScript types if needed**
  ```typescript
  // File: the-events-calendar/common/src/resources/packages/classy/types/NewComponentProps.d.ts
  export interface NewComponentProps {
      // Props here
  }
  ```

- [ ] **4. Add styles if needed**
  ```css
  /* File: the-events-calendar/common/src/resources/packages/classy/style.pcss */
  .classy-new-component {
      /* Styles here */
  }
  ```

- [ ] **5. Copy everything to ET Common**
  ```bash
  # Copy component
  cp the-events-calendar/common/.../NewComponent.tsx \
     event-tickets/common/.../NewComponent.tsx

  # Copy types
  cp the-events-calendar/common/.../NewComponentProps.d.ts \
     event-tickets/common/.../NewComponentProps.d.ts

  # Update both index.ts files
  # Update both style.pcss files
  ```

- [ ] **6. Build both**
  ```bash
  cd the-events-calendar && npm run build
  cd event-tickets && npm run build
  ```

- [ ] **7. Test in both**
  - Import and use in TEC
  - Import and use in ET
  - Verify styling

- [ ] **8. Add tests**
  ```typescript
  // File: the-events-calendar/tests/classy_jest/components/NewComponent.spec.tsx
  ```

- [ ] **9. Update this guide**
  - Add to [Component Catalog](#6-component-catalog)
  - Document props and usage

- [ ] **10. Commit**
  ```bash
  git add the-events-calendar/common/.../NewComponent.*
  git add event-tickets/common/.../NewComponent.*
  git commit -m "Add Common: NewComponent"
  ```

---

#### Adding to Plugin

**Checklist:**

- [ ] **1. Create component in plugin**
  ```bash
  # File: /plugin/src/resources/packages/classy/components/NewComponent.tsx
  ```

- [ ] **2. Export if reusable within plugin**
  ```typescript
  // File: /plugin/src/resources/packages/classy/components/index.ts
  export { default as NewComponent } from './NewComponent';
  ```

- [ ] **3. Add styles**
  ```css
  /* File: /plugin/src/resources/packages/classy/style.pcss */
  .classy-new-component {
      /* Plugin-specific styles */
  }
  ```

- [ ] **4. Build**
  ```bash
  npm run build
  ```

- [ ] **5. Test**
  - Use component
  - Verify styling
  - Test interactions

- [ ] **6. Add tests**
  ```typescript
  // File: /plugin/tests/classy_jest/components/NewComponent.spec.tsx
  ```

- [ ] **7. Commit**
  ```bash
  git add plugin/src/.../NewComponent.*
  git commit -m "Add plugin component: NewComponent"
  ```

---

### Extending Existing Components

#### When to Extend vs Create New

**Extend with modifier when:**
- Base structure is the same
- Only visual variations differ
- Want to inherit base behavior

**Create new component when:**
- Structure is completely different
- Purpose is unrelated
- Would create confusion to extend

---

#### Extension Patterns

**Pattern 1: CSS Modifier**

```css
/* Common base */
.classy-modal {
    /* Base styles */
}

/* Plugin extends with modifier */
.classy-modal--virtual-location {
    .classy-modal__footer {
        /* Only override what's different */
    }
}
```

**Usage:**
```tsx
<Modal className="classy-modal classy-modal--virtual-location">
    {/* Content */}
</Modal>
```

---

**Pattern 2: CSS Variable Override**

```css
/* Common default */
:root {
    --classy-padding-horizontal: var(--tec-spacer-6);
}

/* Plugin overrides for specific context */
.classy-field__custom {
    --classy-padding-horizontal: var(--tec-spacer-3);
    /* Now uses 12px instead of 24px */
}
```

---

**Pattern 3: Component Composition**

```tsx
// Common provides base
import { ClassyField } from '@tec/common/classy/components';

// Plugin composes
export default function CustomField() {
    return (
        <ClassyField title="Custom Field">
            {/* Custom implementation */}
        </ClassyField>
    );
}
```

---

**Pattern 4: Slot/Fill Extension**

```tsx
// Common provides slot
<Slot name="tec.classy.events.event-location.after" />

// Plugin fills slot
<Fill name="tec.classy.events.event-location.after">
    <VirtualLocation />
</Fill>
```

---

### Deprecation Strategy

#### When to Deprecate

- Component is no longer used
- Better alternative exists
- Architecture has changed
- Security or performance issue

---

#### How to Deprecate

**Step 1: Add deprecation warning**

```typescript
/**
 * @deprecated Use NewComponent instead. Will be removed in version X.0.0
 */
export default function OldComponent() {
    console.warn('OldComponent is deprecated. Use NewComponent instead.');

    // Keep existing implementation
}
```

---

**Step 2: Provide migration path**

```markdown
# Migration Guide: OldComponent → NewComponent

## Before
\`\`\`tsx
import { OldComponent } from '@tec/common/classy/components';

<OldComponent prop1="value" prop2="value" />
\`\`\`

## After
\`\`\`tsx
import { NewComponent } from '@tec/common/classy/components';

<NewComponent newProp="value" />
\`\`\`

## Changes
- `prop1` is now `newProp`
- `prop2` is removed (no longer needed)
\`\`\`
```

---

**Step 3: Update all usages**

- Find all usages: `grep -r "OldComponent" --include="*.tsx"`
- Update to NewComponent
- Test thoroughly

---

**Step 4: Remove after deprecation period**

- Wait at least 1 major version
- Ensure all plugins updated
- Remove deprecated component
- Update documentation

---

### Breaking Change Process

#### When Breaking Changes Are Acceptable

- Major version update (X.0.0)
- Security fix requires it
- Architecture improvement requires it
- No feasible backward-compatible solution

---

#### How to Implement Breaking Changes

**Step 1: Plan the change**

- Document what's breaking
- Why it's necessary
- Migration path
- Timeline

---

**Step 2: Communicate early**

- Team meeting
- Slack announcement
- Documentation update
- Changelog entry

---

**Step 3: Provide migration guide**

```markdown
# Breaking Change: ClassyModal API Update

## What's Changing
The `ClassyModal` component API is changing in version 3.0.0.

## Before (v2.x)
\`\`\`tsx
<ClassyModal
    open={isOpen}
    close={onClose}
    title="My Modal"
>
    Content
</ClassyModal>
\`\`\`

## After (v3.0.0)
\`\`\`tsx
<ClassyModal
    isOpen={isOpen}
    onClose={onClose}
>
    <ClassyModalRoot title="My Modal">
        Content
    </ClassyModalRoot>
</ClassyModal>
\`\`\`

## Why
- More consistent with WordPress patterns
- Better separation of concerns
- Enables more flexible composition

## Migration Steps
1. Rename `open` prop to `isOpen`
2. Rename `close` prop to `onClose`
3. Wrap content with `ClassyModalRoot`
4. Move `title` to `ClassyModalRoot`

## Timeline
- v2.9.0 (Current): Old API works, deprecation warnings
- v3.0.0 (Next major): New API required, old API removed
\`\`\`
```

---

**Step 4: Implement with deprecation period**

```typescript
// v2.9.0: Support both APIs
export default function ClassyModal(props) {
    // Detect old API
    if ('open' in props || 'close' in props) {
        console.warn('ClassyModal: old API is deprecated. See migration guide.');

        // Support old API
        return <OldImplementation {...props} />;
    }

    // New API
    return <NewImplementation {...props} />;
}
```

---

**Step 5: Remove old implementation**

```typescript
// v3.0.0: Only new API
export default function ClassyModal(props) {
    // Old API no longer supported
    return <NewImplementation {...props} />;
}
```

---

### Version Coordination

#### When Releasing

**Coordinate versions across plugins:**

1. **Common changes affect all**
   - Update both TEC and ET Common
   - Both get same version bump

2. **Breaking changes cascade**
   - If Common has breaking change
   - All plugins must update

3. **Test matrix**
   - Test all plugin combinations
   - Ensure backward compatibility where needed

---

#### Version Compatibility

**Maintain compatibility matrix:**

| Common | TEC | ET | ECP | ETP |
|--------|-----|----|----|-----|
| 2.0.x | 2.0.x | 2.0.x | 2.0.x | 2.0.x |
| 2.1.x | 2.1.x | 2.0.x | 2.1.x | 2.0.x |
| 3.0.x | 3.0.x | 3.0.x | 3.0.x | 3.0.x |

---

### Maintenance Best Practices

**DO:**
- ✅ Keep TEC and ET Common in sync
- ✅ Test in all plugins before releasing
- ✅ Document breaking changes
- ✅ Provide migration paths
- ✅ Communicate early and often
- ✅ Version carefully
- ✅ Update this guide when architecture changes

**DON'T:**
- ❌ Make breaking changes without warning
- ❌ Update only one Common copy
- ❌ Skip testing in all plugins
- ❌ Remove deprecated features immediately
- ❌ Forget to update documentation
- ❌ Release incompatible versions

---

## Glossary

**BEM** - Block Element Modifier: CSS naming methodology

**Common** - Shared components available in both TEC and ET Common directories

**ECP** - Events Calendar Pro plugin

**ET** - Event Tickets plugin

**ETP** - Event Tickets Plus plugin

**External** - Webpack external resolution (modules loaded via window globals)

**Fill** - WordPress Slot/Fill pattern: content provided to a slot

**Plugin** - TEC, ET, ECP, or ETP plugin (not Common)

**Slot** - WordPress Slot/Fill pattern: placeholder for extensibility

**Store** - Redux store registered with @wordpress/data

**TEC** - The Events Calendar plugin

**Upsert** - Create or Update operation (from SQL)

**Webpack** - Module bundler used to build JavaScript

---

## Quick Reference

### Essential Commands

```bash
# Build
nvm use && npm run build

# Watch mode
nvm use && npm run build:watch

# Test
nvm use && npm run classy:jest

# Test watch
nvm use && npm run classy:jest -- --watch

# Verify Common sync
diff -rq the-events-calendar/common/src/resources/packages/classy/ \
         event-tickets/common/src/resources/packages/classy/
```

---

### Essential Imports

```typescript
// Common components
import {
    ClassyField,
    ClassyModal,
    DatePicker,
    TimePicker,
    IconAdd,
    CenteredSpinner
} from '@tec/common/classy/components';

// Common types
import { FieldProps } from '@tec/common/classy/types';

// WordPress
import { useSelect, useDispatch } from '@wordpress/data';
import { Button, Modal } from '@wordpress/components';
import { useState, useEffect, useCallback } from '@wordpress/element';
```

---

### Essential BEM Pattern

```css
/* Block */
.classy-component { }

/* Element */
.classy-component__element { }

/* Modifier */
.classy-component--modifier { }
.classy-component__element--modifier { }
```

---

### Essential File Locations

```
Common Components:
└─> /common/src/resources/packages/classy/components/

Plugin Components:
└─> /src/resources/packages/classy/fields/

Common Styles:
└─> /common/src/resources/packages/classy/style.pcss

Plugin Styles:
└─> /src/resources/packages/classy/style.pcss

Tests:
└─> /tests/classy_jest/

Build Output:
└─> /build/classy/
```

---

## Contributing

### Before You Start

1. Read this guide thoroughly
2. Understand BEM conventions
3. Know the difference between Common and plugin-specific
4. Set up your development environment

---

### Making Changes

1. Follow established patterns
2. Keep Common in sync
3. Write tests
4. Update documentation
5. Test in all relevant plugins

---

### Getting Help

- **Documentation:** This guide
- **Code Examples:** Component Catalog section
- **Architecture:** Phase 2 & 3 documentation
- **Team:** Ask in Slack #classy channel

---

## Conclusion

This guide is the primary reference for Classy development. Keep it updated as the architecture evolves.

**Remember:**
- BEM naming is critical
- Common sync is non-negotiable
- Test thoroughly
- Document changes
- Communicate with team

**Happy coding!**

---

**End of Component & Style Guide**
