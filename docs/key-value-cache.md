# Key-Value Cache API

The key-value cache API provides a unified interface for storing computationally intensive data with automatic expiration. It uses WordPress object caching when available (Redis, Memcached, etc.) and falls back to a custom database table when object caching is not present.

## Usage

Access the cache via the `tec_kv_cache()` function:

```php
$cache = tec_kv_cache();
```

### Basic String Storage

Store and retrieve simple string values:

```php
// Store a value with 5-minute expiration (minimum allowed).
$cache->set( 'user_data_123', 'serialized data here', 300 );

// Retrieve a value.
$data = $cache->get( 'user_data_123' ); // Returns the stored string.
$data = $cache->get( 'missing_key', 'default' ); // Returns 'default' if not found.
```

### Checking Key Existence

Verify if a key exists before retrieving:

```php
if ($cache->has( 'user_data_123' )) {
    // Key exists and hasn't expired.
    $value = $cache->get( 'user_data_123' );
}
```

### Working with Complex Data

To store complex data structures like arrays, objects, or any PHP values, use the [JSON Packer API][1] to convert them to strings:

```php
// Store an array using JSON Packer.
$api_response = [ 'status' => 'ok', 'count' => 42];
$cache->set( 'api_response', tec_json_pack($api_response), 3600 );

// Retrieve and unpack.
$response = tec_json_unpack( $cache->get( 'api_response' ) ); // Returns the original array.

// Store a complex object.
$user_data = new stdClass();
$user_data->name = 'John Doe';
$user_data->preferences = [ 'theme' => 'dark', 'notifications' => true ];
$cache->set( 'user_123_data', tec_json_pack($user_data), 600 );

// Retrieve the original object.
$retrieved_data = tec_json_unpack( $cache->get( 'user_123_data' ) );

// Store custom objects with allowed classes.
class UserPreferences {
    public string $theme;
    public array $notifications;
}

$prefs = new UserPreferences();
$prefs->theme = 'dark';
$prefs->notifications = ['email' => true, 'push' => false];

// Pack with allowed classes, then store.
$packed = tec_json_pack($prefs, [UserPreferences::class]);
$cache->set( 'user_prefs', $packed, 300 );

// Retrieve and unpack with allowed classes.
$packed_data = $cache->get( 'user_prefs' );
$prefs = tec_json_unpack( $packed_data, true, [UserPreferences::class] );
```

The [JSON Packer AP][1] provides robust serialization that handles:
- Complex nested structures
- Object instances with type preservation
- Circular references
- DateTime objects
- Private and protected properties

See the [JSON Packer documentation][1] for more details on using `tec_json_pack()` and `tec_json_unpack()`.

### Cache Management

Delete specific keys or clear all cached data:

```php
// Delete a specific key.
$cache->delete('user_data_123');

// Clear all the cached data.
// When using the WordPress cache this will only flush the group `tec_kv_cache`.
// When using the table cache this will delete all entries from the table.
$cache->flush();
```

## Using the JSON Packer for Complex Data

The key-value cache stores string values only. For complex data types (arrays, objects, etc.), use the [JSON Packer API][1] to convert them to strings before storage:

```php
// Convert any PHP value to a JSON string.
$packed = tec_json_pack($complex_value, $allowed_classes);
$cache->set('my_key', $packed, 300);

// Retrieve and unpack.
$packed = $cache->get('my_key');
$original_value = tec_json_unpack($packed, true, $allowed_classes);
```

The [JSON Packer][1] provides several advantages over standard serialization:
1. **Security**: Explicit allowed classes prevent arbitrary object instantiation
2. **Portability**: JSON format is language-agnostic and debuggable
3. **Robustness**: Handles circular references and complex object graphs
4. **Type preservation**: Maintains object types when allowed

## Implementation Details

### Backend Selection

The API automatically selects the appropriate backend:
- **Object Cache**: Used when `wp_using_ext_object_cache()` returns `true`
  See the `TEC\Common\Key_Value_Cache\Object_Cache` class for the implementation details.
- **Database Table**: Used as fallback, stores data in `wp_tec_kv_cache` table
  See the `TEC\Common\Key_Value_Cache\Key_Value_Cache_Table` class for the implementation details.

The use of the database can be forced with a filter:
```php
add_filter('tec_key_value_cache_force_use_of_table_cache', '__return_true');
```

### Constraints

- **Minimum expiration**: 300 seconds (5 minutes) - WordPress VIP requirement
- **Maximum key length**: 191 characters to align with `postmeta.meta_key` index length.
- **Value type**: Strings only (use the [JSON Packer API][1] to convert complex data)

For cache durations under 5 minutes, use alternative caching or memoization systems like:
- `wp_cache_` functions with persistent and non-persistent groups.
- `tribe_cache()` and its `ArrayAccess` API for memoization.
- `tribe_cache()` and its setter/getter API for caching.

### Expired values cleaning

A cron job is scheduled to clean expired values from the database table every 12 hours.
The scheduled cron job action is the `tec_clear_expired_key_value_cache` one.

If the backend used is the WordPress cache one, then the eviction of expired values is left to the cache backend.

### Error Handling

Any failure is handled gracefully and logged using the `tribe_log` action.
The key-value cache API will not throw exceptions in any case, but will either not storee the value or return the fallback value.


[1]: json-packer.md
