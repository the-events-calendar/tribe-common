# Key-Value Cache API

The key-value cache API provides a unified interface for storing computationally intensive data with automatic expiration. It uses WordPress object caching when available (Redis, Memcached, etc.) and falls back to a custom database table when object caching is not present.

## Usage

Access the cache via the `tec_kv_cache()` function:

```php
$cache = tec_kv_cache();

// Store a value with 5-minute expiration (minimum allowed).
$cache->set('user_data_123', 'serialized data here', 300);

// Retrieve a value.
$data = $cache->get('user_data_123'); // Returns the stored string.
$data = $cache->get('missing_key', 'default'); // Returns 'default' if not found.

// Check if a key exists.
if ($cache->has('user_data_123')) {
    // Key exists and hasn't expired.
}

// Store and retrieve JSON data.
$json_string = wp_json_encode(['status' => 'ok', 'count' => 42]);
$cache->set('api_response', $json_string, 3600);
$response = $cache->get_json('api_response', true); // Returns associative array.
$response = $cache->get_json('api_response'); // Returns stdClass object.

// Delete a specific key.
$cache->delete('user_data_123');

// Clear all the cached data.
// When using the WordPress cache this will only flush the group `tec_kv_cache`.
// When using the table cache this will delete all entries from the table.
$cache->flush();
```

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
