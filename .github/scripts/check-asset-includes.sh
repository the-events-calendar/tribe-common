#!/bin/bash

# Configuration
DISTFILES_PATH="./.distfiles"

# Patterns to look for in your PHP/JS files
SEARCH_PATTERNS=(
  # Standard WP functions or general inclusions (grep only needs to find the function call on the line)
  "wp_enqueue_script\(.*['\"](vendor/|node_modules/)"
  "wp_enqueue_style\(.*['\"](vendor/|node_modules/)"
  "load_plugin_textdomain\(.*['\"](vendor/|node_modules/)"
  "require.*['\"](vendor/|node_modules/)"
  "include.*['\"](vendor/|node_modules/)"

  # Custom functions (Asset::add, tec_asset, tec_assets)
  "Asset::add\(.*['\"](vendor/|node_modules/)"
  "tec_asset\(.*['\"](vendor/|node_modules/)"
  "tec_assets\(.*['\"](vendor/|node_modules/)"

  # Array-style asset definitions (e.g., [ 'handle', 'vendor/path/file.js', [...] ])
  "\[.*['\"](vendor/|node_modules/)"
)

# Use find to generate a list of files to search (more robust than shell globs)
# Finds .php and .js files in src/, but excludes any file ending in .min.js for sanity.
SEARCH_COMMAND="find ./src -type f \( -name '*.php' -o \( -name '*.js' -a ! -name '*.min.js' \) \) -print0"
MISSING_ASSETS=()
MINIFIED_WARNINGS=()
DEPRECATED_WARNINGS=()

# --- Core Functions ---

# Function to convert a glob pattern to an extended regular expression
glob_to_regex() {
  local pattern="$1"
  # Use placeholders to prevent double-replacement of wildcards
  # **/ matches zero or more path segments, * matches any chars, ? matches one char
  echo "$pattern" | \
    sed -e 's/[.()|&^$+@]/\\&/g' \
        -e 's#\*\*/#__DOUBLESTARSLASH__#g' \
        -e 's/\*\*/__DOUBLESTAR__/g' \
        -e 's/\*/.*/g' \
        -e 's/\?/./g' \
        -e 's#__DOUBLESTARSLASH__#([^/]+/)*#g' \
        -e 's/__DOUBLESTAR__/.*/g'
}

# --- Core Logic ---

echo "🔍 Running .distfiles linting check..."

# Loop through all defined search patterns
for pattern in "${SEARCH_PATTERNS[@]}"; do
  # Use the find command to get the list of files, then pipe to grep
  # Use -H to include filenames in output (format: filename:matched_line)
  while IFS= read -r line; do

    # 0. Extract the source filename (before the first colon)
    SOURCE_FILE="${line%%:*}"

    # Check if this asset is from a deprecated file (either in /deprecated/ folder or contains _deprecated_file)
    IS_DEPRECATED=false
    if [[ "$SOURCE_FILE" =~ /deprecated/ ]]; then
      IS_DEPRECATED=true
    else
      # Check if the file contains _deprecated_file() function call
      if grep -q "_deprecated_file" "$SOURCE_FILE" 2>/dev/null; then
        IS_DEPRECATED=true
      fi
    fi

    # 1. Extract the path string (e.g., vendor/library/file.js)
    # Use awk with FPAT to find the first quoted string containing vendor/ or node_modules/
    PATH_RAW=$(echo "$line" | awk '
        # Set quotes as field separators. Fields 2, 4, 6... will be quoted strings.
        BEGIN { FPAT = "(\047[^\047]+\047|\"[^\"]+\")" }
        {
            for (i = 1; i <= NF; i++) {
                path_raw = $i

                # Check if the field contains the vendor/ or node_modules/ path
                if (path_raw ~ /vendor\/|node_modules\//) {

                    # Remove surrounding quotes for cleaning
                    if (substr(path_raw, 1, 1) == "\047" || substr(path_raw, 1, 1) == "\"") {
                        path_raw = substr(path_raw, 2, length(path_raw) - 2)
                    }

                    # Print the path and exit this line processing
                    print path_raw
                    exit
                }
            }
        }' | head -n 1) # Take the first extracted path

    # Clean the result: remove any potential trailing comma/parenthesis/quotes left by PHP formatting
    PATH_RAW=$(echo "$PATH_RAW" | sed -E 's/[), '\''\"]*$//')

     # 1.5. Force normalization: remove any potential leading slash from the extracted path
    PATH_RAW=$(echo "$PATH_RAW" | sed 's/^\///')

    # Ensure the path is non-empty before proceeding
    if [ -z "$PATH_RAW" ]; then
        continue
    fi

    # If this is a deprecated file, just add to warnings and skip validation
    if [ "$IS_DEPRECATED" = true ]; then
      # Add to deprecated warnings (format: "asset_path (in: source_file)")
      DEPRECATED_WARNINGS+=("$PATH_RAW (in: $SOURCE_FILE)")
      continue
    fi

    # 2. Check if the asset path is covered by any entry in .distfiles
    IS_FOUND=false

    # Read the .distfiles line by line
    while IFS= read -r DIST_PATTERN || [ -n "$DIST_PATTERN" ]; do

      # Skip empty lines or comments
      [[ -z "$DIST_PATTERN" || "$DIST_PATTERN" =~ ^# ]] && continue

      # Remove any leading path separator (e.g., /vendor/ becomes vendor/) for consistency
      CLEAN_DIST_PATTERN=$(echo "$DIST_PATTERN" | sed 's/^\///')

      # Convert the glob pattern to regex
      REGEX_PATTERN=$(glob_to_regex "$CLEAN_DIST_PATTERN")

      # Use regex match: check if the found asset path matches the converted glob regex
      if [[ "$PATH_RAW" =~ ^${REGEX_PATTERN}$ ]]; then
        IS_FOUND=true
        break # Found a match, move to the next asset
      fi
    done < "$DISTFILES_PATH"

    # 3. If no match was found, check if it's a minifiable asset with a minified version in distfiles
    if [ "$IS_FOUND" = false ]; then
      # Check if this is a .js or .css file (not .php) that might have a minified version
      if [[ "$PATH_RAW" =~ \.(js|css)$ ]] && [[ ! "$PATH_RAW" =~ \.min\. ]]; then
        # Generate the minified path (e.g., foo.js -> foo.min.js, bar.css -> bar.min.css)
        MINIFIED_PATH=$(echo "$PATH_RAW" | sed -E 's/\.(js|css)$/.min.\1/')

        # Check if the minified version matches any pattern in .distfiles
        MIN_IS_FOUND=false
        while IFS= read -r DIST_PATTERN || [ -n "$DIST_PATTERN" ]; do
          [[ -z "$DIST_PATTERN" || "$DIST_PATTERN" =~ ^# ]] && continue
          CLEAN_DIST_PATTERN=$(echo "$DIST_PATTERN" | sed 's/^\///')
          REGEX_PATTERN=$(glob_to_regex "$CLEAN_DIST_PATTERN")

          if [[ "$MINIFIED_PATH" =~ ^${REGEX_PATTERN}$ ]]; then
            MIN_IS_FOUND=true
            break
          fi
        done < "$DISTFILES_PATH"

        # If minified version is covered, it's just a warning (asset system will auto-swap)
        if [ "$MIN_IS_FOUND" = true ]; then
          MINIFIED_WARNINGS+=("$PATH_RAW")
        else
          MISSING_ASSETS+=("$PATH_RAW")
        fi
      else
        # PHP files or already minified assets must be explicitly listed
        MISSING_ASSETS+=("$PATH_RAW")
      fi
    fi
  done < <(eval "$SEARCH_COMMAND" | xargs -0 grep -E -H "$pattern" 2>/dev/null || true)
done

# --- Reporting ---

# Handle warnings for assets in deprecated files (won't cause failure)
if [ ${#DEPRECATED_WARNINGS[@]} -gt 0 ]; then
  DEDUPED_DEPRECATED=$(printf "%s\n" "${DEPRECATED_WARNINGS[@]}" | sort -u)

  echo "--------------------------------------------------------"
  echo "⚠️  NOTICE: The following assets are referenced in deprecated files."
  echo "Deprecated files are those in /deprecated/ folders or containing _deprecated_file()."
  echo "These may reference vendors that no longer exist."
  echo "This is OK if the deprecated code is never actually executed:"
  echo ""
  echo "$DEDUPED_DEPRECATED"
  echo "--------------------------------------------------------"
  echo ""
fi

# Handle warnings for assets with minified versions (won't cause failure)
if [ ${#MINIFIED_WARNINGS[@]} -gt 0 ]; then
  DEDUPED_WARNINGS=$(printf "%s\n" "${MINIFIED_WARNINGS[@]}" | sort -u)

  echo "--------------------------------------------------------"
  echo "⚠️  NOTICE: The following assets reference non-minified files,"
  echo "but minified versions are in ${DISTFILES_PATH}."
  echo "This is OK if your asset system auto-swaps to minified versions in production:"
  echo ""
  echo "$DEDUPED_WARNINGS"
  echo "--------------------------------------------------------"
  echo ""
fi

# Handle actual failures for missing assets
if [ ${#MISSING_ASSETS[@]} -gt 0 ]; then
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
