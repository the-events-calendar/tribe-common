# Asset Includes Check Action

This GitHub Action verifies that all vendor and node_modules assets referenced in source code are included in the `.distfiles` manifest for distribution.

## Features

- ✅ Detects missing PHP, JavaScript, and CSS files from `vendor/` and `node_modules/`
- ✅ Supports glob patterns (`**/*`, `*.ext`, `*`)
- ⚠️  Smart minification detection: warns (but doesn't fail) when non-minified assets have minified versions
- 🚨 Fails only on genuinely missing assets
- 📦 Works with asset systems that auto-swap to minified versions in production

## Usage

### Basic Usage

```yaml
name: Asset Check

on: [pull_request]

jobs:
  check-assets:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: ./.github/actions/asset-check
```

### With PR Comments (Recommended)

Post the check results as a comment on the PR for easy visibility:

```yaml
name: Asset Check

on:
  pull_request:
    branches: [ main, develop ]

permissions:
  contents: read
  pull-requests: write  # Required to post comments

jobs:
  check-assets:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4

      - name: Run Asset Check
        id: asset-check
        uses: ./.github/actions/asset-check
        continue-on-error: true

      - name: Post PR Comment
        if: github.event_name == 'pull_request'
        uses: actions/github-script@v7
        with:
          script: |
            const output = `${{ steps.asset-check.outputs.result }}`;
            const status = `${{ steps.asset-check.outputs.status }}`;

            const emoji = status === 'passed' ? '✅' : '❌';
            const title = status === 'passed' ? 'Asset Check Passed' : 'Asset Check Failed';

            const comment = `## ${emoji} ${title}

            <details>
            <summary>Click to see full output</summary>

            \`\`\`
            ${output}
            \`\`\`

            </details>
            `;

            // Find and update existing comment, or create new
            const { data: comments } = await github.rest.issues.listComments({
              owner: context.repo.owner,
              repo: context.repo.repo,
              issue_number: context.issue.number,
            });

            const botComment = comments.find(comment =>
              comment.user.type === 'Bot' &&
              comment.body.includes('Asset Check')
            );

            if (botComment) {
              await github.rest.issues.updateComment({
                owner: context.repo.owner,
                repo: context.repo.repo,
                comment_id: botComment.id,
                body: comment
              });
            } else {
              await github.rest.issues.createComment({
                owner: context.repo.owner,
                repo: context.repo.repo,
                issue_number: context.issue.number,
                body: comment
              });
            }

      - name: Fail if check failed
        if: steps.asset-check.outputs.status == 'failed'
        run: exit 1
```

## Inputs

| Input | Description | Required | Default |
|-------|-------------|----------|---------|
| None | This action runs from repository root | - | - |

## Outputs

| Output | Description | Example |
|--------|-------------|---------|
| `result` | Full output from the asset check | Multi-line script output |
| `status` | Status of the check | `passed` or `failed` |

## How It Works

### Detection Patterns

The action searches for these patterns in PHP and JavaScript files:

- `wp_enqueue_script()` and `wp_enqueue_style()`
- `require` and `include` statements
- `Asset::add()`, `tec_asset()`, `tec_assets()`
- Array-style asset definitions: `[ 'handle', 'vendor/path/file.js', [...] ]`

### Deprecated File Detection

Assets referenced in deprecated files are reported as warnings but don't fail the build. A file is considered deprecated if:
- It's in a `/deprecated/` folder, OR
- It contains a `_deprecated_file()` function call

**Result:** ⚠️ Warning (build passes)
```
⚠️  NOTICE: The following assets are referenced in deprecated files.
Deprecated files are those in /deprecated/ folders or containing _deprecated_file().
These may reference vendors that no longer exist.
This is OK if the deprecated code is never actually executed:

vendor/freemius/start.php (in: ./src/deprecated/Tribe__Freemius.php)
```

### Smart Minification Handling

When a non-minified asset (e.g., `vendor/tooltipster/tooltipster.bundle.js`) is found but the minified version (`vendor/tooltipster/tooltipster.bundle.min.js`) is covered in `.distfiles`:

**Result:** ⚠️ Warning (build passes)
```
⚠️  NOTICE: The following assets reference non-minified files,
but minified versions are in ./.distfiles.
This is OK if your asset system auto-swaps to minified versions in production:

vendor/tooltipster/tooltipster.bundle.js
```

### Real Failures

When assets are genuinely missing (or PHP files without coverage):

**Result:** 🚨 Failure (build fails)
```
🚨 ASSET INCLUDES CHECK FAILED: Assets loaded from 'vendor/' or 'node_modules/'
are missing from ./.distfiles.

vendor/freemius/start.php
```

## Exit Codes

- **0**: All assets covered (warnings are OK)
- **1**: Missing assets detected (fails CI)

## Example Output

### Successful Build (with warnings)
```
🔍 Running .distfiles linting check...
--------------------------------------------------------
⚠️  NOTICE: The following assets reference non-minified files,
but minified versions are in ./.distfiles.
This is OK if your asset system auto-swaps to minified versions in production:

vendor/jquery-tribe-timepicker/jquery.timepicker.js
vendor/tooltipster/tooltipster.bundle.css
--------------------------------------------------------

✅ Distfiles check passed: All tracked vendor/node_modules assets are listed.
```

### Failed Build
```
🔍 Running .distfiles linting check...
--------------------------------------------------------
🚨 ASSET INCLUDES CHECK FAILED: Assets loaded from 'vendor/' or 'node_modules/'
are missing from ./.distfiles.
Please add these **exact paths** or covering glob patterns to your ./.distfiles file:

vendor/freemius/start.php
vendor/datatables/datatables.js
--------------------------------------------------------
```

## Troubleshooting

### False Positives

If you're getting warnings for assets that are intentionally not minified, you can either:

1. Add the exact path to `.distfiles`
2. Accept the warning (it won't fail the build)

### Script Not Found

Ensure the script is executable and located at:
```
.github/scripts/check-asset-includes.sh
```

The action automatically runs `chmod +x` but the file must exist in the repository.

## Local Testing

Test the check locally before pushing:

```bash
# From repository root
bash .github/scripts/check-asset-includes.sh
```

## Requirements

- Bash shell (available on all GitHub-hosted runners)
- Standard POSIX tools: `sed`, `awk`, `grep`, `find`
