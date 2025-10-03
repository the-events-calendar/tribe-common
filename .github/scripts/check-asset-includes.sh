#!/bin/bash

# Configuration
DISTFILES_PATH="./.distfiles"

# Patterns to look for in your PHP/JS files
SEARCH_PATTERNS=(
  # 1. Standard WP functions or general inclusions
  "wp_enqueue_script\(.*['\"](vendor/|node_modules/)"
  "wp_enqueue_style\(.*['\"](vendor/|node_modules/)"
  "load_plugin_textdomain\(.*['\"](vendor/|node_modules/)"
  "require.*['\"](vendor/|node_modules/)"
  "include.*['\"](vendor/|node_modules/)"

  # 2. Custom object method, Stellar Assets: Asset::add()
  # Looks for Asset::add( followed by any argument, then a path string
  "Asset::add\(.*['\"](vendor/|node_modules/)[^'\"]*"

  # 3. Custom function: tec_asset()
  # Looks for tec_asset( followed by multiple arguments, then a path string
  "tec_asset\(.*['\"](vendor/|node_modules/)[^'\"]*"

  # 4. Custom function: tec_assets()
  # This is tricky; we'll target the array syntax that wraps the path.
  # This pattern looks for [, followed by a quote, then 'vendor/' or 'node_modules/'.
  "\[\s*['\"][^'\"]+['\"]\s*,\s*['\"](vendor/|node_modules/)[^'\"]*"
)
# Use find to generate a list of files to search (more robust than shell globs)
SEARCH_COMMAND="find . -type f \( -name '*.php' -o -name '*.js' \) -print0"
MISSING_ASSETS=()

# --- Core Functions ---

# Function to convert a glob pattern to an extended regular expression
glob_to_regex() {
  local pattern="$1"
  # 1. Escape all regex special characters first (except the glob wildcards *, ?)
  # 2. Convert glob wildcards (*, ?) to their regex equivalents (.*, .)
  echo "$pattern" | \
    sed -e 's/[.()|&^$+@]/\\&/g' \
        -e 's/\*/.*/g' \
        -e 's/\?//g' \
        -e 's/\?././g'
}

# --- Core Logic ---

echo "🔍 Running .distfiles linting check..."

# Loop through all defined search patterns
for pattern in "${SEARCH_PATTERNS[@]}"; do
  # Use the find command to get the list of files, then pipe to grep
  # We use xargs -0 to handle files with spaces/special characters
  # || true prevents the script from failing if grep finds no matches
  eval "$SEARCH_COMMAND" | xargs -0 grep -E -h "$pattern" || true |

  while IFS= read -r line; do

    # 1. Extract the path string (e.g., vendor/library/file.js)
    # The regex extracts the content between the first pair of single/double quotes
    # that contains 'vendor/' or 'node_modules/'.
    PATH_RAW=$(echo "$line" | sed -E 's/.*(\'|\")((vendor|node_modules)\/[^'\'"]+).*/\2/')

    # 2. Normalize and check path validity
    if [[ "$PATH_RAW" == vendor/* || "$PATH_RAW" == node_modules/* ]]; then

      IS_FOUND=false

      # Read the .distfiles line by line
      # The || [ -n "$DIST_PATTERN" ] handles the case where the last line isn't newline-terminated
      while IFS= read -r DIST_PATTERN || [ -n "$DIST_PATTERN" ]; do

        # Skip empty lines or comments
        [[ -z "$DIST_PATTERN" || "$DIST_PATTERN" =~ ^# ]] && continue

        # Convert the glob pattern to regex
        REGEX_PATTERN=$(glob_to_regex "$DIST_PATTERN")

        # Use regex match: check if the found asset path matches the converted glob regex
        # The ^ and $ anchor the regex to match the entire string
        if [[ "$PATH_RAW" =~ ^"$REGEX_PATTERN"$ ]]; then
          IS_FOUND=true
          break # Found a match, move to the next asset
        fi
      done < "$DISTFILES_PATH"

      # 3. Check if the asset was NOT covered by any .distfiles entry
      if [ "$IS_FOUND" = false ]; then
        MISSING_ASSETS+=("$PATH_RAW")
      fi
    fi
  done
done

# --- Reporting ---

if [ ${#MISSING_ASSETS[@]} -gt 0 ]; then
  # Use array to deduplicate the list before printing
  DEDUPED_MISSING=$(printf "%s\n" "${MISSING_ASSETS[@]}" | sort -u)

  echo "--------------------------------------------------------"
  echo "🚨 ASSET INCLUDES CHECK FAILED: Assets loaded from 'vendor/' or 'node_modules/' are missing from ${DISTFILES_PATH}."
  echo "Please add these **exact paths** or covering glob patterns to your ${DISTFILES_PATH} file:"
  echo ""
  echo "$DEDUPED_MISSING"
  echo "--------------------------------------------------------"

  exit 1
else
  echo "✅ Distfiles check passed: All tracked vendor/node_modules assets are listed."
  exit 0
fi
