# Conditional Content System

A flexible, trait-based system for displaying promotional content in WordPress admin with support for datetime conditions, user dismissal, capability checks, and plugin-based upsell targeting.

## Architecture

The system uses a **composition-over-inheritance** approach with PHP traits, allowing concrete promotional classes to mix and match functionality as needed.

### Core Abstract Class

- **`Promotional_Content_Abstract`** - Base class providing rendering methods for banners and notices. Concrete classes must implement `should_display()` and `hook()`.

### Available Traits

#### Display Condition Traits

1. **`Has_Datetime_Conditions`** - Show content only within specific date ranges
2. **`Is_Dismissible`** - Allow users to dismiss content (stored in user meta)
3. **`Requires_Capability`** - Restrict display to users with specific capabilities

#### Upsell Logic Traits (Mutually Exclusive)

Choose **ONE** of these traits for your promotion:

1. **`Has_Generic_Upsell_Opportunity`** - Show if ANY paid plugin is not installed
2. **`Has_Targeted_Creative_Upsell`** - Show specific ads based on which plugins are missing

## Creating a Promotion

### Step 1: Choose Your Traits

Decide which traits your promotion needs:
- **Required**: One upsell trait (`Has_Generic_Upsell_Opportunity` OR `Has_Targeted_Creative_Upsell`)
- **Recommended**: `Has_Datetime_Conditions`, `Is_Dismissible`, `Requires_Capability`

### Step 2: Extend the Abstract Class

```php
<?php
namespace TEC\Common\Admin\Conditional_Content;

use TEC\Common\Admin\Conditional_Content\Traits\{
    Has_Datetime_Conditions,
    Has_Generic_Upsell_Opportunity,
    Is_Dismissible,
    Requires_Capability
};

class Spring_Sale extends Promotional_Content_Abstract {
    use Has_Datetime_Conditions;
    use Is_Dismissible;
    use Requires_Capability;
    use Has_Generic_Upsell_Opportunity;

    protected string $slug = 'spring-sale';
    protected string $start_date = 'March 1st';
    protected string $end_date = 'March 31st';

    // Implement required methods...
}
```

### Step 3: Implement Required Methods

#### `hook()` - Register WordPress Hooks

```php
public function hook(): void {
    // Register dismiss handler if using Is_Dismissible trait
    add_action( 'wp_ajax_tec_conditional_content_dismiss', [ $this, 'handle_dismiss' ] );
}
```

#### `should_display()` - Compose Display Logic

Combine checks from your traits in order of performance (cheapest first):

```php
protected function should_display(): bool {
    // 1. Check filter (cheapest)
    if ( tec_should_hide_upsell( $this->get_slug() ) ) {
        return false;
    }

    // 2. Check capability (cheap)
    if ( ! $this->check_capability() ) {
        return false;
    }

    // 3. Check dismissal (user meta lookup)
    if ( $this->has_user_dismissed() ) {
        return false;
    }

    // 4. Check datetime (date calculations)
    if ( ! $this->should_display_datetime() ) {
        return false;
    }

    // 5. Check upsell opportunity (most expensive - API calls)
    return $this->has_upsell_opportunity();
}
```

## Usage Examples

### Example 1: Generic Upsell (Single Ad for All Missing Plugins)

Use `Has_Generic_Upsell_Opportunity` when you have one generic promotional image/message:

```php
class Black_Friday extends Promotional_Content_Abstract {
    use Has_Datetime_Conditions;
    use Is_Dismissible;
    use Requires_Capability;
    use Has_Generic_Upsell_Opportunity;

    protected string $slug = 'black-friday';
    protected string $start_date = 'November 26th';
    protected string $end_date = 'December 3rd';

    // ... implementation
}
```

**Behavior**: Shows the promotion if ANY paid plugin is not installed.

### Example 2: Targeted Upsell (Different Ads for Different Plugins)

Use `Has_Targeted_Creative_Upsell` when you want to show specific ads based on context and missing plugins:

```php
class Stellar_Sale extends Promotional_Content_Abstract {
    use Has_Datetime_Conditions;
    use Is_Dismissible;
    use Requires_Capability;
    use Has_Targeted_Creative_Upsell;

    protected string $slug = 'stellar-sale';

    protected function get_suite_creative_map(): array {
        return [
            'events' => [
                'events-calendar-pro/events-calendar-pro.php' => [
                    'image_url'        => 'https://example.com/ecp-ad.png',
                    'narrow_image_url' => 'https://example.com/ecp-narrow.png',
                    'link_url'         => 'https://example.com/ecp',
                    'alt_text'         => 'Get Events Calendar Pro',
                ],
                'default' => [
                    'image_url' => 'https://example.com/default-events.png',
                    // ...
                ],
            ],
            'tickets' => [
                'event-tickets-plus/event-tickets-plus.php' => [
                    'image_url' => 'https://example.com/etp-ad.png',
                    // ...
                ],
                'default' => [
                    'image_url' => 'https://example.com/default-tickets.png',
                    // ...
                ],
            ],
        ];
    }

    // ... implementation
}
```

**Behavior**: Shows different ads based on admin page context (events vs tickets) and which plugins are missing.

### Example 3: Always Show (Ignore Plugin Checks)

Override `should_ignore_plugin_checks()` to always show content:

```php
class Important_Announcement extends Promotional_Content_Abstract {
    use Has_Datetime_Conditions;
    use Is_Dismissible;
    use Requires_Capability;
    use Has_Generic_Upsell_Opportunity;

    // Always show regardless of plugin installation
    protected function should_ignore_plugin_checks(): bool {
        return true;
    }

    // ... implementation
}
```

**Behavior**: Shows to all eligible users regardless of which plugins are installed.

### Example 4: Custom Capability Requirement

Override `get_required_capability()` to require a specific capability:

```php
class Admin_Only_Notice extends Promotional_Content_Abstract {
    use Has_Datetime_Conditions;
    use Is_Dismissible;
    use Requires_Capability;
    use Has_Generic_Upsell_Opportunity;

    // Only show to users who can manage network
    protected function get_required_capability(): string {
        return 'manage_network';
    }

    // ... implementation
}
```

## Trait Reference

### Has_Datetime_Conditions

Controls when content is displayed based on date ranges.

**Properties to define:**
- `protected string $start_date` - Human-readable start date (e.g., "March 1st")
- `protected string $end_date` - Human-readable end date (e.g., "March 31st")
- `protected string $slug` - Unique identifier for the promotion

**Methods:**
- `get_start_time(): ?Date_I18n` - Override to set specific start time
- `get_end_time(): ?Date_I18n` - Override to set specific end time
- `should_display_datetime(): bool` - Check if current date is within range

**Filters:**
- `tec_admin_conditional_content_{$slug}_start_date` - Modify start date
- `tec_admin_conditional_content_{$slug}_end_date` - Modify end date
- `tec_admin_conditional_content_{$slug}_should_display` - Override display logic

### Is_Dismissible

Allows users to dismiss content and stores dismissal in user meta.

**Properties to define:**
- `protected string $slug` - Unique identifier for the promotion

**Methods:**
- `has_user_dismissed(): bool` - Check if user dismissed this content
- `dismiss(): bool` - Mark content as dismissed for current user
- `undismiss(): bool` - Remove dismissal for current user
- `handle_dismiss(): void` - AJAX handler for dismiss action
- `get_nonce_action(): string` - Get nonce action name
- `get_nonce(): string` - Get nonce value

**User Meta Key:** `tec-dismissible-content`

### Requires_Capability

Restricts content display to users with specific WordPress capabilities.

**Methods:**
- `get_required_capability(): string` - Override to change required capability (default: `manage_options`)
- `check_capability(): bool` - Check if current user has required capability

**Filters:**
- `tec_admin_conditional_content_required_capability` - Modify required capability

**Default Behavior:** Only shows to users with `manage_options` (Administrators and Super Admins).

### Has_Generic_Upsell_Opportunity

Shows content if ANY paid plugin is not installed.

**Methods:**
- `has_upsell_opportunity(): bool` - Check if any paid plugin is missing
- `should_ignore_plugin_checks(): bool` - Override to always show content

**Plugin Check:** Uses `Tribe__Plugins_API` to get list of paid plugins.

### Has_Targeted_Creative_Upsell

Shows specific ads based on admin page context and missing plugins.

**Properties to define:**
- `protected string $slug` - Unique identifier
- All methods required by `Promotional_Content_Abstract`

**Methods to implement:**
- `get_suite_creative_map(): array` - Return map of creatives by context and plugin

**Methods provided:**
- `has_upsell_opportunity(): bool` - Check if a creative to show was found
- `get_admin_page_context(): string` - Determine current admin page context
- `get_selected_creative(): ?array` - Get the creative to display
- `get_wide_banner_image_url(): string` - Get wide banner URL
- `get_narrow_banner_image_url(): string` - Get narrow banner URL
- `get_sidebar_image_url(): string` - Get sidebar image URL
- `get_creative_link_url(): string` - Get creative link URL
- `get_creative_alt_text(): string` - Get creative alt text

**Creative Map Structure:**
```php
[
    'context' => [
        'plugin/path.php' => [
            'image_url'         => '...',
            'narrow_image_url'  => '...',
            'sidebar_image_url' => '...',
            'link_url'          => '...',
            'alt_text'          => '...',
        ],
        'feature-check' => [
            'callback' => [ 'Class', 'method' ], // Returns false if feature not active
            'image_url' => '...',
            // ...
        ],
        'default' => [ ... ] // Fallback when all plugins installed
    ],
]
```

**Contexts:** `events`, `tickets`, `default`

## Performance Considerations

### Optimal `should_display()` Order

Order checks from cheapest to most expensive:

1. **Filter check** (`tec_should_hide_upsell`) - Simple boolean filter
2. **Capability check** - Single WordPress function call
3. **Dismissal check** - User meta lookup
4. **Datetime check** - Date parsing and comparison
5. **Upsell check** - API calls and plugin status checks

```php
protected function should_display(): bool {
    if ( tec_should_hide_upsell( $this->get_slug() ) ) {
        return false;
    }

    if ( ! $this->check_capability() ) {
        return false;
    }

    if ( $this->has_user_dismissed() ) {
        return false;
    }

    if ( ! $this->should_display_datetime() ) {
        return false;
    }

    return $this->has_upsell_opportunity();
}
```

## Filters Available

### Global Filters

- `tec_should_hide_upsell` - Hide specific promotions by slug
  ```php
  add_filter( 'tec_should_hide_upsell', function( $hide, $slug ) {
      if ( $slug === 'spring-sale-2025' ) {
          return true;
      }
      return $hide;
  }, 10, 2 );
  ```

### Trait-Specific Filters

**Has_Datetime_Conditions:**
- `tec_admin_conditional_content_{$slug}_start_date`
- `tec_admin_conditional_content_{$slug}_end_date`
- `tec_admin_conditional_content_{$slug}_should_display`

**Requires_Capability:**
- `tec_admin_conditional_content_required_capability`

## Testing

Each trait has dedicated test coverage in `tests/wpunit/Common/Admin/Conditional_Content/Traits/`:

- `Has_Datetime_Conditions_Test.php`
- `Has_Generic_Upsell_Opportunity_Test.php`
- `Has_Targeted_Creative_Upsell_Test.php`
- `Is_Dismissible_Test.php`
- `Requires_Capability_Test.php`
- `Trait_Integration_Test.php` - Tests trait interactions

**Run all tests:**
```bash
slic run wpunit common/tests/wpunit/Common/Admin/Conditional_Content/
```

## Migration from Legacy Code

### Deprecated Classes/Traits

- `Dismissible_Trait` → Use `Is_Dismissible`
- `Datetime_Conditional_Abstract` → Use `Has_Datetime_Conditions` trait

### Migration Example

**Before (Inheritance):**
```php
class My_Promo extends Datetime_Conditional_Abstract {
    use Dismissible_Trait;
    // ...
}
```

**After (Composition):**
```php
class My_Promo extends Promotional_Content_Abstract {
    use Has_Datetime_Conditions;
    use Is_Dismissible;
    use Requires_Capability;
    use Has_Generic_Upsell_Opportunity;

    public function hook(): void {
        add_action( 'wp_ajax_tec_conditional_content_dismiss', [ $this, 'handle_dismiss' ] );
    }

    protected function should_display(): bool {
        // Compose checks from traits
    }
}
```

## Best Practices

1. **Always use one upsell trait** - Don't mix `Has_Generic_Upsell_Opportunity` and `Has_Targeted_Creative_Upsell`
2. **Order checks by performance** - Put cheapest checks first in `should_display()`
3. **Use trait method aliasing** - Rename trait methods if you need to wrap them
4. **Provide fallbacks** - Always include a `default` creative in targeted upsell maps
5. **Test thoroughly** - Create trait-specific tests for custom behavior
6. **Document overrides** - Add docblocks explaining why you're overriding trait methods

## Support

For questions or issues, refer to:
- [Tests](../../../../tests/wpunit/Common/Admin/Conditional_Content/) for usage examples
- Existing implementations: `Black_Friday.php`, `Stellar_Sale.php`
