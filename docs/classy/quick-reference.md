# Classy Quick Reference Card

**Project:** Classy BEM Refactoring
**Version:** 1.0
**Last Updated:** 2025-10-25

---

## BEM Pattern

```
.classy-block__element--modifier
```

**Rules:**
- Always start with `classy-`
- Double underscore (`__`) for elements
- Double hyphen (`--`) for modifiers
- Lowercase with hyphens between words

**Examples:**
```css
.classy-modal                          /* ✅ Block */
.classy-modal__header                  /* ✅ Element */
.classy-modal--large                   /* ✅ Modifier */
.classy-modal__header--highlighted     /* ✅ Element + Modifier */

.modal                                 /* ❌ No classy- prefix */
.classy-modal_header                   /* ❌ Single underscore */
.classy-modal-large                    /* ❌ Single hyphen for modifier */
```

---

## Import Pattern

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

## Common vs Plugin-Specific

**Add to Common if:**
- Used by both TEC and ET
- Generic UI pattern
- Reusable across features
- No plugin-specific logic

**Add to Plugin if:**
- Feature-specific
- Plugin-specific logic
- Single plugin use
- Plugin-specific dependencies

**Locations:**
```
Common (TEC):  /the-events-calendar/common/src/resources/packages/classy/
Common (ET):   /event-tickets/common/src/resources/packages/classy/
Plugin (TEC):  /the-events-calendar/src/resources/packages/classy/
Plugin (ECP):  /events-pro/src/resources/packages/classy/
Plugin (ET):   /event-tickets/src/resources/packages/classy/
Plugin (ETP):  /event-tickets-plus/src/resources/packages/classy/
```

---

## Common Sync Procedure

**When?** Any change to Common

**How?**
```bash
# Navigate to plugins directory
cd /Users/lucatume/work/tec/tec-dev/wp-content/plugins

# Sync TEC → ET
rsync -av --delete \
  the-events-calendar/common/src/resources/packages/classy/ \
  event-tickets/common/src/resources/packages/classy/

# Verify
diff -rq \
  the-events-calendar/common/src/resources/packages/classy/ \
  event-tickets/common/src/resources/packages/classy/
```

**Expected:** No output = success!

---

## Before Commit Checklist

Essential checks before committing:

- [ ] **Format:** `nvm use && npm run classy:format`
- [ ] **Test:** `nvm use && npm run classy:jest`
- [ ] **Build:** `nvm use && npm run build`
- [ ] **Sync:** If Common changed, sync to both TEC & ET
- [ ] **Verify sync:** Run diff command (no output)
- [ ] **Build both:** Build TEC and ET if Common changed
- [ ] **Test both:** Test in all affected plugins

---

## Build Commands

```bash
# Build Classy
nvm use && npm run build

# Watch mode (auto-rebuild)
nvm use && npm run build:watch

# Format code
nvm use && npm run classy:format

# Run tests
nvm use && npm run classy:jest

# Test watch mode
nvm use && npm run classy:jest -- --watch
```

---

## CSS Variables

**Global variables (in `:root`):**
```css
:root {
    --classy-padding: 16px;
    --classy-gap: 12px;
    --classy-border-radius: 4px;
    --classy-border: 1px solid #ddd;
    --classy-text-primary: #000;
    --classy-text-secondary: #666;
}
```

**Local variables (in component):**
```css
.classy-modal {
    --modal-width: 600px;
    --modal-padding: var(--classy-padding);

    width: var(--modal-width);
    padding: var(--modal-padding);
}
```

**Use TEC tokens when available:**
```css
.classy-component {
    padding: var(--tec-spacer-4);      /* Use TEC tokens */
    color: var(--tec-color-text-primary);
    font-size: var(--tec-font-size-3);
}
```

---

## Component Template

```tsx
/**
 * ComponentName Component
 *
 * Brief description of what it does.
 *
 * @package TEC\Common\Classy (or TEC\Classy for plugin)
 */

import React from 'react';
import { useState } from '@wordpress/element';

/**
 * Props for ComponentName
 */
export interface ComponentNameProps {
    /** Prop description */
    value: string;
    /** Callback description */
    onChange: (value: string) => void;
    /** Optional prop */
    disabled?: boolean;
}

/**
 * ComponentName component
 */
export const ComponentName: React.FC<ComponentNameProps> = ({
    value,
    onChange,
    disabled = false
}) => {
    return (
        <div className="classy-component-name">
            {/* Component JSX */}
        </div>
    );
};
```

---

## Test Template

```tsx
import { render, screen, fireEvent } from '@testing-library/react';
import { ComponentName } from '@tec/common/classy/components';

describe('ComponentName', () => {
    it('renders with value', () => {
        render(
            <ComponentName
                value="test"
                onChange={() => {}}
            />
        );

        expect(screen.getByDisplayValue('test')).toBeInTheDocument();
    });

    it('calls onChange when value changes', () => {
        const onChange = jest.fn();

        render(
            <ComponentName
                value="test"
                onChange={onChange}
            />
        );

        fireEvent.change(screen.getByRole('textbox'), {
            target: { value: 'new value' }
        });

        expect(onChange).toHaveBeenCalledWith('new value');
    });

    it('is disabled when disabled prop is true', () => {
        render(
            <ComponentName
                value="test"
                onChange={() => {}}
                disabled={true}
            />
        );

        expect(screen.getByRole('textbox')).toBeDisabled();
    });
});
```

---

## Slot/Fill Pattern

**Provide slot in Common:**
```tsx
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
```

**Fill slot in plugin:**
```tsx
import { Fill } from '@tec/common/packages/slot-fill';

export const ModalFooter = () => {
    return (
        <Fill name="tec.classy.modal.footer">
            <div className="classy-modal__footer">
                <Button>Save</Button>
            </div>
        </Fill>
    );
};
```

**Slot naming:** `tec.classy.{plugin|common}.{component}.{location}`

---

## Redux Store Pattern

**Register store:**
```typescript
import { registerStore } from '@wordpress/data';

const STORE_NAME = 'tec/classy/events';

registerStore(STORE_NAME, {
    reducer,
    actions,
    selectors,
    resolvers
});
```

**Use store:**
```tsx
import { useSelect, useDispatch } from '@wordpress/data';

const MyComponent = () => {
    const events = useSelect((select) =>
        select('tec/classy/events').getEvents()
    );

    const { updateEvent } = useDispatch('tec/classy/events');

    return <div>{/* Use data */}</div>;
};
```

---

## Common Pitfalls

**❌ Wrong:**
```tsx
className="modal"                      // Missing classy- prefix
className="classy-modal_header"        // Single underscore
className="classy-modal-large"         // Single hyphen for modifier
import { Modal } from '../Modal'       // Relative import
```

**✅ Right:**
```tsx
className="classy-modal"               // Correct prefix
className="classy-modal__header"       // Double underscore
className="classy-modal--large"        // Double hyphen
import { Modal } from '@tec/common/classy/components'  // Alias import
```

---

## File Locations

**Components:**
```
Common:  common/src/resources/packages/classy/components/
Plugin:  src/resources/packages/classy/fields/ (or components/)
```

**Styles:**
```
Common:  common/src/resources/packages/classy/style.pcss
Plugin:  src/resources/packages/classy/style.pcss
```

**Types:**
```
Common:  common/src/resources/packages/classy/types/
Plugin:  src/resources/packages/classy/types/
```

**Tests:**
```
Both:    tests/classy_jest/
```

**Build Output:**
```
Both:    build/classy/
```

**Documentation:**
```
Common:  common/src/resources/packages/classy/component-guide.md
         common/docs/classy/
```

---

## Troubleshooting

**Issue:** Build fails with "Cannot resolve module"
**Fix:**
```bash
# Check webpack config has correct aliases
# Check import path uses @tec/common/classy/...
# Rebuild: npm run build
```

---

**Issue:** Tests fail with "Cannot find module"
**Fix:**
```bash
# Check jest.config.js has correct moduleNameMapper
# Check import paths in tests
# Rebuild: npm run classy:jest
```

---

**Issue:** Common out of sync
**Fix:**
```bash
# Re-sync
cd /Users/lucatume/work/tec/tec-dev/wp-content/plugins
rsync -av --delete \
  the-events-calendar/common/src/resources/packages/classy/ \
  event-tickets/common/src/resources/packages/classy/

# Verify
diff -rq \
  the-events-calendar/common/src/resources/packages/classy/ \
  event-tickets/common/src/resources/packages/classy/
```

---

**Issue:** Styles not applying
**Fix:**
```bash
# Check BEM naming is correct
# Check style.pcss imported in component
# Rebuild CSS: npm run build
# Check browser console for errors
# Clear browser cache
```

---

**Issue:** Changes not reflected in browser
**Fix:**
```bash
# Rebuild
npm run build

# Hard refresh browser
# Cmd+Shift+R (Mac) or Ctrl+Shift+R (Windows)

# Check build output exists
ls -la build/classy/

# Check PHP enqueues correct build file
```

---

## Resources

**Primary Documentation:**
- **Component Guide:** `common/src/resources/packages/classy/component-guide.md`
- **Linting Guide:** `common/docs/classy/linting-recommendations.md`
- **Sync Guide:** `common/docs/classy/common-sync-procedures.md`
- **Maintenance Guide:** `common/docs/classy/maintenance-guide.md`

**Phase Documentation:**
- **Phase 0:** `common/docs/classy/phase-00-summary.md` - Architecture & Planning
- **Phase 1:** `common/docs/classy/phase-01-summary.md` - Common Infrastructure
- **Phase 2:** `common/docs/classy/phase-02-summary.md` - TEC Recurrence Refactor
- **Phase 3:** `common/docs/classy/phase-03-summary.md` - ECP Fields Refactor
- **Phase 4:** `common/docs/classy/phase-04-summary.md` - ET Refactor
- **Phase 5:** `common/docs/classy/phase-05-summary.md` - ETP Refactor
- **Phase 6:** `common/docs/classy/phase-06-summary.md` - Consolidation
- **Phase 7:** `common/docs/classy/phase-07-summary.md` - Enforcement & Maintenance

**External Resources:**
- **BEM Methodology:** https://getbem.com/
- **WordPress Components:** https://developer.wordpress.org/block-editor/reference-guides/components/
- **WordPress Data:** https://developer.wordpress.org/block-editor/reference-guides/packages/packages-data/
- **React Testing Library:** https://testing-library.com/docs/react-testing-library/intro/

---

## Quick Commands Cheat Sheet

```bash
# Development
nvm use                                          # Use correct Node version
npm run build                                    # Build Classy
npm run build:watch                              # Auto-rebuild on changes
npm run classy:format                            # Format code
npm run classy:jest                              # Run tests
npm run classy:jest -- --watch                   # Test watch mode

# Common Sync
cd /Users/lucatume/work/tec/tec-dev/wp-content/plugins
rsync -av --delete \
  the-events-calendar/common/src/resources/packages/classy/ \
  event-tickets/common/src/resources/packages/classy/
diff -rq \
  the-events-calendar/common/src/resources/packages/classy/ \
  event-tickets/common/src/resources/packages/classy/

# Find usages
grep -r "ComponentName" src/ --include="*.tsx" --include="*.ts"
grep -r "classy-component" src/ --include="*.pcss"

# Check imports
grep -r "@tec/common/classy" src/ --include="*.tsx" --include="*.ts"

# Build all plugins
cd the-events-calendar && nvm use && npm run build
cd ../events-pro && nvm use && npm run build
cd ../event-tickets && nvm use && npm run build
cd ../event-tickets-plus && nvm use && npm run build
```

---

## Commit Message Template

```
[Action] [Component/Feature] in [Common|Plugin]

- [Change 1]
- [Change 2]
- [Change 3]
- Synced to both TEC and ET Common (if Common change)

🤖 Generated with [Claude Code](https://claude.com/claude-code)

Co-Authored-By: Claude <noreply@anthropic.com>
```

**Examples:**
```
Add ClassyDatePicker to Common

- Create DatePicker component
- Add date picker styles
- Add comprehensive tests
- Update component catalog
- Synced to both TEC and ET Common

🤖 Generated with [Claude Code](https://claude.com/claude-code)

Co-Authored-By: Claude <noreply@anthropic.com>
```

```
Fix ClassyField validation in TEC

- Fix empty value validation
- Add error message display
- Update tests

🤖 Generated with [Claude Code](https://claude.com/claude-code)

Co-Authored-By: Claude <noreply@anthropic.com>
```

---

## Decision Trees

### Where Should Component Go?

```
Is it used by multiple plugins?
├─ YES → Will it be used by both TEC and ET?
│   ├─ YES → Common (requires sync)
│   └─ NO → Plugin-specific
└─ NO → Is it a core UI pattern?
    ├─ YES → Common (for future reuse)
    └─ NO → Plugin-specific
```

### How Should I Extend Component?

```
What am I changing?
├─ Visual only → BEM modifier
├─ Spacing/theming → CSS variables
├─ Adding content → Slot/Fill
└─ Complex logic → Composition
```

### Is This a Breaking Change?

```
Will this change break existing code?
├─ YES → Is it necessary?
│   ├─ YES → Follow breaking change process
│   └─ NO → Find backward-compatible solution
└─ NO → Safe to proceed
```

---

## Emergency Contacts

**Issues?**
- Check this quick reference
- Read full Component Guide
- Search phase documentation
- Ask in Slack #classy channel

**Still stuck?**
- Review component-guide.md
- Check phase documentation in common/docs/classy/
- Create detailed bug report with:
  - What you're trying to do
  - What's happening
  - What you expected
  - Steps to reproduce
  - Error messages
  - Screenshots if applicable

---

**End of Quick Reference Card**

---

**Print this out or bookmark it for quick access during development!**
