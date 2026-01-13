# Test Filters Utility

The `TEC\Common\Tests\Filters` class provides methods for adding WordPress filters and actions before WordPress is fully initialized.

## Location

`tests/_support/Filters.php`

## Namespace

```php
namespace TEC\Common\Tests;
```

## Methods

### `Filters::add_pre_initialized_filter`

Adds a filter to the WordPress filter stack, even before WordPress is loaded.

```php
Filters::add_pre_initialized_filter(
    string $tag,
    callable $callback,
    int $priority = 10,
    int $accepted_args = 1
): void
```

**Parameters:**

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$tag` | `string` | - | The name of the filter to hook into |
| `$callback` | `callable` | - | The callback to execute when the filter is applied |
| `$priority` | `int` | `10` | The priority of the filter (lower runs earlier) |
| `$accepted_args` | `int` | `1` | The number of arguments the callback accepts |

**Behavior:**

- If `add_filter()` is available, it uses the standard WordPress function
- If WordPress is not yet loaded, it directly manipulates the global `$wp_filter` array using the pre-initialized hooks pattern (see `WP_Hook::build_preinitialized_hooks()`)

### `Filters::add_pre_initialized_action`

Adds an action to the WordPress action stack before WordPress is fully initialized.

```php
Filters::add_pre_initialized_action(
    string $tag,
    callable $callback,
    int $priority = 10
): void
```

**Parameters:**

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$tag` | `string` | - | The name of the action to hook into |
| `$callback` | `callable` | - | The callback to execute when the action fires |
| `$priority` | `int` | `10` | The priority of the action (lower runs earlier) |

## Usage Example

```php
use TEC\Common\Tests\Filters;

// Override a filter value before WordPress loads.
Filters::add_pre_initialized_filter( 'some_feature_version', fn() => 2, 0 );

// Add an early action.
Filters::add_pre_initialized_action( 'init', fn() => do_something(), 5 );
```
