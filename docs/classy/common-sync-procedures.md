# Common Sync Procedures

**Project:** Classy BEM Refactoring
**Version:** 1.0
**Last Updated:** 2025-10-25

---

## Overview

The Classy component system exists in two separate Common directories that must be kept identical. This document provides complete procedures for maintaining synchronization between TEC Common and ET Common.

---

## 1. Why Two Common Directories?

### The Situation

Classy components exist in two locations:

**TEC Common:**
```
/the-events-calendar/common/src/resources/packages/classy/
```

**ET Common:**
```
/event-tickets/common/src/resources/packages/classy/
```

---

### Why They Exist

The Events Calendar (TEC) and Event Tickets (ET) are **independent WordPress plugins** that can be:

- Installed separately
- Activated without each other
- Sold as standalone products
- Updated independently
- Used in different combinations

Because of this independence:

- Each plugin must contain its own copy of Common
- Common cannot be a shared dependency
- Duplication is intentional and necessary
- Both copies must remain identical

---

### What This Means

**For developers:**
- Any change to Common must be synced to both locations
- Both copies must build identically
- Both copies must test identically
- Both copies must be committed together

**For the codebase:**
- Source code is duplicated
- Build output is duplicated
- Tests run in both locations
- Documentation references both

---

## 2. What Needs Syncing

### Always Sync

These must **always** be synchronized between TEC and ET Common:

**Components:**
```
✓ components/
  ✓ All .tsx files
  ✓ All .ts files
  ✓ index.ts exports
  ✓ Component tests
```

**Styles:**
```
✓ style.pcss
✓ All CSS files
✓ CSS variable definitions
```

**Types:**
```
✓ types/
  ✓ All type definitions
  ✓ Interface definitions
  ✓ Type exports
```

**Configuration:**
```
✓ package.json (Classy-related dependencies)
✓ tsconfig.json
✓ jest.config.js
✓ .eslintrc (if Classy-specific)
✓ .stylelintrc (if Classy-specific)
```

**Tests:**
```
✓ All test utilities
✓ Mock factories
✓ Test helpers
✓ Common test configuration
```

**Documentation:**
```
✓ component-guide.md
✓ All Classy documentation
✓ README files
```

---

### Never Sync

These are **plugin-specific** and should never be synced:

**Plugin Components:**
```
✗ TEC-specific components
✗ ECP-specific components
✗ ET-specific components
✗ ETP-specific components
```

**Plugin Styles:**
```
✗ Plugin-specific style.pcss sections
✗ Plugin-specific modifiers
✗ Plugin-specific theming
```

**Plugin Tests:**
```
✗ Tests for plugin-specific components
✗ Plugin-specific test setup
✗ Plugin-specific mocks
```

**Plugin Documentation:**
```
✗ Plugin-specific docs
✗ Feature-specific guides
✗ Plugin README files
```

---

## 3. When to Sync

### Required Sync Triggers

Sync is **required** when you:

- [ ] Add any file to Common
- [ ] Modify any file in Common
- [ ] Delete any file from Common
- [ ] Update style.pcss
- [ ] Change component code
- [ ] Update type definitions
- [ ] Modify tests
- [ ] Change configuration
- [ ] Update documentation

---

### Recommended Workflow

**Option A: Make changes in TEC, sync to ET**
```
1. Make changes in TEC Common
2. Test in TEC
3. Sync to ET Common
4. Test in ET
5. Commit both together
```

**Option B: Make changes in ET, sync to TEC**
```
1. Make changes in ET Common
2. Test in ET
3. Sync to TEC Common
4. Test in TEC
5. Commit both together
```

**Important:** Pick one direction per change session to avoid confusion.

---

## 4. How to Sync

### Automatic Sync (Recommended)

Use rsync for reliable, complete synchronization:

```bash
# Navigate to plugins directory
cd /Users/lucatume/work/tec/tec-dev/wp-content/plugins

# Sync from TEC to ET
rsync -av --delete \
  the-events-calendar/common/src/resources/packages/classy/ \
  event-tickets/common/src/resources/packages/classy/

# Sync from ET to TEC
rsync -av --delete \
  event-tickets/common/src/resources/packages/classy/ \
  the-events-calendar/common/src/resources/packages/classy/
```

---

### What rsync Does

**`-a` (archive):** Preserves permissions, timestamps, symlinks
**`-v` (verbose):** Shows files being copied
**`--delete`:** Removes files in destination that don't exist in source

**Output example:**
```
sending incremental file list
components/
components/ClassyModal.tsx
components/ClassyField.tsx
style.pcss

sent 15,234 bytes  received 89 bytes  30,646.00 bytes/sec
total size is 245,678  speedup is 16.03
```

---

### Manual Sync (Not Recommended)

If you must sync manually:

```bash
# Copy specific file
cp the-events-calendar/common/src/resources/packages/classy/components/ClassyModal.tsx \
   event-tickets/common/src/resources/packages/classy/components/ClassyModal.tsx

# Copy entire directory
cp -r the-events-calendar/common/src/resources/packages/classy/components/ \
      event-tickets/common/src/resources/packages/classy/components/
```

**Warning:** Manual sync is error-prone. Use rsync instead.

---

### Sync Script (Copy-Paste Ready)

Create a reusable sync script:

```bash
#!/bin/bash
# File: sync-classy-common.sh

set -e

PLUGINS_DIR="/Users/lucatume/work/tec/tec-dev/wp-content/plugins"
TEC_COMMON="${PLUGINS_DIR}/the-events-calendar/common/src/resources/packages/classy"
ET_COMMON="${PLUGINS_DIR}/event-tickets/common/src/resources/packages/classy"

echo "🔄 Syncing Classy Common..."
echo "Source: TEC Common"
echo "Target: ET Common"
echo ""

# Perform sync
rsync -av --delete "${TEC_COMMON}/" "${ET_COMMON}/"

echo ""
echo "✅ Sync complete!"
echo ""
echo "Next steps:"
echo "1. Run verification: ./verify-classy-sync.sh"
echo "2. Build both plugins"
echo "3. Run tests"
echo "4. Commit both together"
```

Make it executable:
```bash
chmod +x sync-classy-common.sh
```

Run it:
```bash
./sync-classy-common.sh
```

---

## 5. Verification Procedures

### Verification Checklist

After syncing, verify with this checklist:

- [ ] Run diff to confirm identical content
- [ ] Build TEC Common successfully
- [ ] Build ET Common successfully
- [ ] Run TEC tests - all pass
- [ ] Run ET tests - all pass
- [ ] Check git status in both plugins
- [ ] Verify no unexpected file differences

---

### Diff Verification

Run diff to ensure directories match:

```bash
# Navigate to plugins directory
cd /Users/lucatume/work/tec/tec-dev/wp-content/plugins

# Compare directories
diff -rq \
  the-events-calendar/common/src/resources/packages/classy/ \
  event-tickets/common/src/resources/packages/classy/
```

---

### Expected Output

**✅ Success (directories match):**
```
# No output means success!
```

**❌ Failure (directories differ):**
```
Files the-events-calendar/common/src/resources/packages/classy/components/ClassyModal.tsx and event-tickets/common/src/resources/packages/classy/components/ClassyModal.tsx differ
Only in the-events-calendar/common/src/resources/packages/classy/components: NewComponent.tsx
```

---

### Verification Script (Copy-Paste Ready)

Create a verification script:

```bash
#!/bin/bash
# File: verify-classy-sync.sh

set -e

PLUGINS_DIR="/Users/lucatume/work/tec/tec-dev/wp-content/plugins"
TEC_COMMON="${PLUGINS_DIR}/the-events-calendar/common/src/resources/packages/classy"
ET_COMMON="${PLUGINS_DIR}/event-tickets/common/src/resources/packages/classy"

echo "🔍 Verifying Classy Common sync..."
echo ""

# Run diff
DIFF_OUTPUT=$(diff -rq "${TEC_COMMON}" "${ET_COMMON}" 2>&1 || true)

if [ -z "$DIFF_OUTPUT" ]; then
  echo "✅ Verification successful!"
  echo "TEC Common and ET Common are identical."
  echo ""
  echo "Next steps:"
  echo "1. Build TEC: cd the-events-calendar && npm run build"
  echo "2. Build ET: cd event-tickets && npm run build"
  echo "3. Run tests"
  echo "4. Commit both plugins together"
else
  echo "❌ Verification failed!"
  echo "TEC Common and ET Common differ:"
  echo ""
  echo "$DIFF_OUTPUT"
  echo ""
  echo "Fix by running: ./sync-classy-common.sh"
  exit 1
fi
```

Make it executable:
```bash
chmod +x verify-classy-sync.sh
```

Run it:
```bash
./verify-classy-sync.sh
```

---

### Build Verification

After sync, build both plugins:

```bash
# Build TEC Common
cd /Users/lucatume/work/tec/tec-dev/wp-content/plugins/the-events-calendar
nvm use
npm run build

# Build ET Common
cd /Users/lucatume/work/tec/tec-dev/wp-content/plugins/event-tickets
nvm use
npm run build
```

**Both builds must succeed without errors.**

---

### Test Verification

After building, run tests:

```bash
# Test TEC Common
cd /Users/lucatume/work/tec/tec-dev/wp-content/plugins/the-events-calendar
nvm use
npm run classy:jest

# Test ET Common
cd /Users/lucatume/work/tec/tec-dev/wp-content/plugins/event-tickets
nvm use
npm run classy:jest
```

**All tests must pass in both plugins.**

---

## 6. Git Workflow

### Committing Synced Changes

Always commit TEC and ET Common changes together:

```bash
# Stage changes in TEC
cd /Users/lucatume/work/tec/tec-dev/wp-content/plugins/the-events-calendar
git add common/src/resources/packages/classy/

# Stage changes in ET
cd /Users/lucatume/work/tec/tec-dev/wp-content/plugins/event-tickets
git add common/src/resources/packages/classy/

# Commit both (if in same repo)
cd /Users/lucatume/work/tec/tec-dev
git commit -m "Add Classy modal component to Common

- Add ClassyModal.tsx component
- Add modal styles to style.pcss
- Add modal tests
- Update component catalog
- Synced to both TEC and ET Common

🤖 Generated with [Claude Code](https://claude.com/claude-code)

Co-Authored-By: Claude <noreply@anthropic.com>"
```

---

### Commit Message Template

Use this template for Common sync commits:

```
[Action] [Component/Feature] in Common

- [Change 1]
- [Change 2]
- [Change 3]
- Synced to both TEC and ET Common

🤖 Generated with [Claude Code](https://claude.com/claude-code)

Co-Authored-By: Claude <noreply@anthropic.com>
```

Examples:

```
Add ClassyDatePicker to Common

- Create DatePicker component
- Add date picker styles
- Add date picker tests
- Update component catalog
- Synced to both TEC and ET Common
```

```
Update ClassyModal API in Common

- Add size prop (small, medium, large)
- Update modal styles for sizing
- Add tests for size variants
- Update component documentation
- Synced to both TEC and ET Common
```

```
Fix ClassyField validation in Common

- Fix empty value validation
- Update field tests
- Add regression test
- Synced to both TEC and ET Common
```

---

### Pull Request Strategy

When creating PRs, clearly indicate Common sync:

**PR Title:**
```
Add ClassyModal to Common (TEC + ET sync)
```

**PR Description:**
```markdown
## Summary
Adds ClassyModal component to Common for use across TEC and ET.

## Changes
- Created ClassyModal.tsx component
- Added modal styles to style.pcss
- Added comprehensive tests
- Updated component-guide.md

## Common Sync
✅ Synced to both TEC Common and ET Common
✅ Verified with diff - directories match
✅ TEC build successful
✅ ET build successful
✅ All TEC tests pass
✅ All ET tests pass

## Testing
- [ ] Tested in TEC
- [ ] Tested in ET
- [ ] All unit tests pass
- [ ] No build errors
```

---

## 7. Common Sync Automation

### Future Automation Options

While manual sync is current process, future automation could include:

**Option 1: CI/CD Check**
```yaml
# .github/workflows/verify-common-sync.yml
name: Verify Common Sync

on: [pull_request]

jobs:
  verify:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Verify Common directories match
        run: |
          diff -r \
            the-events-calendar/common/src/resources/packages/classy/ \
            event-tickets/common/src/resources/packages/classy/ \
          || (echo "Common directories not synced!" && exit 1)
```

---

**Option 2: Pre-commit Hook**
```bash
#!/bin/bash
# .git/hooks/pre-commit

# Check if Common files changed
TEC_COMMON_CHANGED=$(git diff --cached --name-only | grep "the-events-calendar/common/src/resources/packages/classy")
ET_COMMON_CHANGED=$(git diff --cached --name-only | grep "event-tickets/common/src/resources/packages/classy")

if [ -n "$TEC_COMMON_CHANGED" ] || [ -n "$ET_COMMON_CHANGED" ]; then
  echo "⚠️  Common files changed. Verifying sync..."

  diff -rq \
    the-events-calendar/common/src/resources/packages/classy/ \
    event-tickets/common/src/resources/packages/classy/ \
  || (echo "❌ Common directories not synced! Run: ./sync-classy-common.sh" && exit 1)

  echo "✅ Common sync verified"
fi
```

---

**Option 3: Automated Sync Script**
```bash
#!/bin/bash
# .git/hooks/post-merge

# After pulling changes, auto-sync Common
if git diff-tree --name-only -r HEAD@{1} HEAD | grep "the-events-calendar/common/src/resources/packages/classy"; then
  echo "🔄 TEC Common changed, syncing to ET..."
  ./sync-classy-common.sh
fi
```

---

**Option 4: Monorepo Symlinks**
```bash
# Not recommended, but theoretically possible
# Create symlink in ET pointing to TEC Common
ln -s \
  ../../the-events-calendar/common/src/resources/packages/classy \
  event-tickets/common/src/resources/packages/classy
```
**Note:** Symlinks complicate builds and deployment. Current duplication is safer.

---

## 8. Troubleshooting

### Common Issues

**Issue: "diff shows differences after rsync"**

Cause: Rsync ran on wrong directories or with wrong flags

Solution:
```bash
# Verify paths
ls -la the-events-calendar/common/src/resources/packages/classy/
ls -la event-tickets/common/src/resources/packages/classy/

# Re-run rsync with correct paths
rsync -av --delete \
  the-events-calendar/common/src/resources/packages/classy/ \
  event-tickets/common/src/resources/packages/classy/

# Verify again
diff -rq \
  the-events-calendar/common/src/resources/packages/classy/ \
  event-tickets/common/src/resources/packages/classy/
```

---

**Issue: "Build succeeds in TEC but fails in ET"**

Cause: Incomplete sync or plugin-specific dependencies

Solution:
```bash
# Check if all files synced
diff -r \
  the-events-calendar/common/src/resources/packages/classy/ \
  event-tickets/common/src/resources/packages/classy/

# Check for plugin-specific imports
cd event-tickets
grep -r "@tec/specific" common/src/resources/packages/classy/

# Rebuild from clean slate
rm -rf build/classy
npm run build
```

---

**Issue: "Tests pass in TEC but fail in ET"**

Cause: Incomplete sync of test files or test utilities

Solution:
```bash
# Verify test files synced
diff -r \
  the-events-calendar/common/src/resources/packages/classy/__tests__/ \
  event-tickets/common/src/resources/packages/classy/__tests__/

# Check test utilities
diff \
  the-events-calendar/tests/classy_jest/setup.ts \
  event-tickets/tests/classy_jest/setup.ts

# Re-run sync including tests
rsync -av --delete \
  the-events-calendar/common/src/resources/packages/classy/ \
  event-tickets/common/src/resources/packages/classy/
```

---

**Issue: "Forgot to sync before commit"**

Cause: Made changes in one Common, forgot to sync

Solution:
```bash
# Check what changed
git diff the-events-calendar/common/src/resources/packages/classy/

# Sync now
./sync-classy-common.sh

# Verify
./verify-classy-sync.sh

# Stage ET changes
cd event-tickets
git add common/src/resources/packages/classy/

# Amend commit to include both
git commit --amend --no-edit
```

---

**Issue: "Conflicting changes in both TEC and ET"**

Cause: Simultaneous changes in both Common directories

Solution:
```bash
# Choose one as source of truth
# Option A: Keep TEC version
rsync -av --delete \
  the-events-calendar/common/src/resources/packages/classy/ \
  event-tickets/common/src/resources/packages/classy/

# Option B: Keep ET version
rsync -av --delete \
  event-tickets/common/src/resources/packages/classy/ \
  the-events-calendar/common/src/resources/packages/classy/

# Option C: Manually merge
# Open both files and combine changes
code the-events-calendar/common/src/resources/packages/classy/components/ClassyModal.tsx
code event-tickets/common/src/resources/packages/classy/components/ClassyModal.tsx
# After merging, sync to both
```

---

## 9. Quick Reference

### Before Making Common Changes

```bash
# 1. Ensure you're in the right directory
pwd
# Should show: /Users/lucatume/work/tec/tec-dev/wp-content/plugins/the-events-calendar
# or: /Users/lucatume/work/tec/tec-dev/wp-content/plugins/event-tickets

# 2. Verify Common is currently synced
cd /Users/lucatume/work/tec/tec-dev/wp-content/plugins
./verify-classy-sync.sh

# 3. Make your changes in ONE Common (TEC or ET)
```

---

### After Making Common Changes

```bash
# 1. Sync to other Common
cd /Users/lucatume/work/tec/tec-dev/wp-content/plugins
./sync-classy-common.sh

# 2. Verify sync
./verify-classy-sync.sh

# 3. Build TEC
cd the-events-calendar
nvm use && npm run build

# 4. Build ET
cd ../event-tickets
nvm use && npm run build

# 5. Test TEC
cd ../the-events-calendar
nvm use && npm run classy:jest

# 6. Test ET
cd ../event-tickets
nvm use && npm run classy:jest

# 7. Commit both together
cd /Users/lucatume/work/tec/tec-dev
git add the-events-calendar/common/src/resources/packages/classy/
git add event-tickets/common/src/resources/packages/classy/
git commit -m "Your message here - Synced to both TEC and ET Common"
```

---

### Emergency Rollback

If something goes wrong:

```bash
# Revert changes in both plugins
cd /Users/lucatume/work/tec/tec-dev
git checkout the-events-calendar/common/src/resources/packages/classy/
git checkout event-tickets/common/src/resources/packages/classy/

# Verify rollback
./verify-classy-sync.sh

# Rebuild both
cd the-events-calendar && nvm use && npm run build
cd ../event-tickets && nvm use && npm run build
```

---

## 10. Maintenance

### Weekly Verification

Run weekly to ensure sync hasn't drifted:

```bash
# Verify Common is still synced
cd /Users/lucatume/work/tec/tec-dev/wp-content/plugins
./verify-classy-sync.sh

# If differences found, investigate
diff -r \
  the-events-calendar/common/src/resources/packages/classy/ \
  event-tickets/common/src/resources/packages/classy/ \
  > common-diff.txt

# Review diff
less common-diff.txt

# Determine which is correct, then sync
```

---

### Quarterly Audit

Every quarter, perform full audit:

```bash
# 1. Verify sync
./verify-classy-sync.sh

# 2. Check build outputs match
diff -rq \
  the-events-calendar/build/classy/ \
  event-tickets/build/classy/

# 3. Review any exceptions
grep -r "plugin-specific" the-events-calendar/common/src/resources/packages/classy/
grep -r "plugin-specific" event-tickets/common/src/resources/packages/classy/

# 4. Update documentation if needed
```

---

## Conclusion

Maintaining Common sync is critical for the Classy system. By following these procedures, you ensure:

- Consistent behavior across plugins
- Reliable builds and tests
- Clean git history
- Team confidence

**Remember the golden rule:** Any change to Common must be synced to both TEC and ET.

---

## Cheat Sheet

```bash
# Sync (TEC → ET)
rsync -av --delete \
  the-events-calendar/common/src/resources/packages/classy/ \
  event-tickets/common/src/resources/packages/classy/

# Verify
diff -rq \
  the-events-calendar/common/src/resources/packages/classy/ \
  event-tickets/common/src/resources/packages/classy/

# Build both
cd the-events-calendar && nvm use && npm run build
cd ../event-tickets && nvm use && npm run build

# Test both
cd the-events-calendar && nvm use && npm run classy:jest
cd ../event-tickets && nvm use && npm run classy:jest

# Commit both
git add the-events-calendar/common/src/resources/packages/classy/
git add event-tickets/common/src/resources/packages/classy/
git commit -m "Message - Synced to both TEC and ET Common"
```

---

**End of Common Sync Procedures**
