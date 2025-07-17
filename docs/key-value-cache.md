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

### Working with JSON Data

Store and retrieve structured data using JSON:

```php
// Store JSON data using set_json().
$cache->set_json( 'api_response', [ 'status' => 'ok', 'count' => 42], 3600 );

// Retrieve as associative array.
$response = $cache->get_json( 'api_response', true );

// Retrieve as stdClass object.
$response = $cache->get_json( 'api_response', false );
```

### Serialized PHP Objects

Store complex PHP objects with automatic serialization:

```php
// Store a complex object.
$user_data = new stdClass();
$user_data->name = 'John Doe';
$user_data->preferences = [ 'theme' => 'dark', 'notifications' => true ];
$cache->set_serialized( 'user_123_data', $user_data, 600 );

// Retrieve the original object specifying the allowed classes.
$retrieved_data = $cache->get_serialized( 'user_123_data', [ \stdClass::class ] );

// Works with arrays and scalar values too.
$cache->set_serialized( 'config_array', [ 'key' => 'value' ], 300 );
$config = $cache->get_serialized( 'config_array' ); // Returns the array.
```

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

## Why Explicit Data Type APIs?

The key-value cache provides distinct APIs for different data types (`set()`/`get()` for strings, `set_serialized()`/`get_serialized()` for PHP objects, and `set_json()`/`get_json()` for JSON) rather than a single API that automatically detects and handles data types.

This design choice is intentional:

1. **Developer clarity**: By requiring developers to explicitly choose which API to use, they must think about the data type they're storing and retrieving. This prevents assumptions and makes the code more self-documenting.

2. **Avoid hidden errors**: Automatic type detection can lead to subtle bugs. For example:
   - A string that looks like JSON but isn't valid would fail silently
   - Serialized data could be mistaken for a regular string
   - Type mismatches between storage and retrieval could cause runtime errors

3. **Performance**: Knowing the data type upfront avoids the overhead of trying multiple parsing strategies to determine how to handle the value.

4. **Predictable behavior**: Each API has clear expectations about input and output types, making the cache behavior consistent and testable.

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
- **Value type**: Strings only (encode complex data as a JSON string)

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
