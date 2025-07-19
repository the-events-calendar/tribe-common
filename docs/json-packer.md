# JSON Packer API

The JSON Packer API provides a robust way to serialize and deserialize PHP values into JSON format.
Unlike standard JSON encoding, this API can handle complex PHP objects, DateTime instances, and circular references.

The API has a strict-by-default approach, contrary to the `serialize` and `unserialize` functions, where packing objects requires specifying the object classes that should be considered safe to pack and unpack.

## Usage

The JSON Packer API is accessible via the `tec_json_pack` and `tec_json_unpack` functions:

```php
// Pack a value into JSON.
$json_packed = tec_json_pack($value);

// Unpack JSON back to the original value.
$value = tec_json_unpack($json_packed);

// Pack with allowed classes - only these classes will be preserved.
$json_packed = tec_json_pack($value, ['MyClass', 'MyOtherClass']);

// Unpack with allowed classes.
$value = tec_json_unpack($json_packed, true, ['MyClass', 'MyOtherClass']);
```

### Basic value packing

Pack and unpack scalar values:

```php
// Pack simple values.
$json = tec_json_pack('Hello World');
$value = tec_json_unpack($json); // Returns 'Hello World'.

// Pack numbers.
$json = tec_json_pack(42);
$value = tec_json_unpack($json); // Returns 42.

// Pack booleans and null.
$json = tec_json_pack(true);
$value = tec_json_unpack($json); // Returns true.
```

### Working with arrays

Pack both sequential and associative arrays:

```php
// Sequential array.
$data = [1, 2, 3, 'four', 5.5];
$json = tec_json_pack($data);
$unpacked = tec_json_unpack($json); // Returns the same array.

// Associative array.
$user_data = [
    'name' => 'John Doe',
    'age' => 30,
    'active' => true
];
$json = tec_json_pack($user_data);
$unpacked = tec_json_unpack($json); // Returns the same associative array.

// Nested arrays.
$complex = [
    'users' => [
        ['id' => 1, 'name' => 'Alice'],
        ['id' => 2, 'name' => 'Bob']
    ],
    'settings' => [
        'theme' => 'dark',
        'notifications' => false
    ]
];
$json = tec_json_pack($complex);
$unpacked = tec_json_unpack($json); // Preserves full structure.
```

### Object packing

Pack objects while preserving their class type:

```php
// Pack stdClass objects.
// Note: stdClass is considered safe by default, no need to specify in allowed_classes.
$obj = new stdClass();
$obj->name = 'Test';
$obj->value = 123;
$obj->active = true;

$json = tec_json_pack($obj);
$unpacked = tec_json_unpack($json); // Returns a stdClass with the same properties.

// Pack custom objects with private properties.
class User {
    private string $username;
    private string $email;

    public function __construct(string $username, string $email) {
        $this->username = $username;
        $this->email = $email;
    }
}

$user = new User('john_doe', 'john@example.com');
$json = tec_json_pack($user, [User::class]);
 // Returns a User instance with properties restored.
$unpacked = tec_json_unpack($json, true, [User::class]);
```

### DateTime handling

Special handling for DateTime objects:

```php
// Pack DateTime objects.
// Note: DateTime, DateTimeImmutable, and DateTimeZone are considered safe by default.
$date = new DateTime('2024-01-15 10:30:00', new DateTimeZone('UTC'));
$json = tec_json_pack($date);
$unpacked = tec_json_unpack($json); // Returns DateTime with the same date and timezone.

// Pack DateTimeImmutable objects.
$date = new DateTimeImmutable('2024-01-15 10:30:00', new DateTimeZone('America/New_York'));
$json = tec_json_pack($date);
$unpacked = tec_json_unpack($json); // Returns DateTimeImmutable with the correct timezone.
```

### Circular References

The API handles circular references automatically:

```php
class Node {
    public $value;
    public $next;
}

$node1 = new Node();
$node1->value = 'first';

$node2 = new Node();
$node2->value = 'second';

// Create circular reference.
$node1->next = $node2;
$node2->next = $node1;

$json = tec_json_pack($node1, [Node::class]);
$unpacked = tec_json_unpack($json, true, [Node::class]);

// Circular reference is preserved.
$unpacked->next->next === $unpacked; // true
```

### Dynamic properties

Support for objects with dynamic properties:

```php
// Dynamic properties on stdClass.
// Note: stdClass is considered safe by default.
$obj = new stdClass();
$obj->dynamic_field = 'dynamic value';
$obj->rating = 4.5;
$obj->tags = ['php', 'json', 'serialization'];

$json = tec_json_pack($obj);
$unpacked = tec_json_unpack($json); // All dynamic properties preserved.

// Dynamic properties on WordPress objects.
$post = get_post(123);
$post->custom_meta = 'custom value';
$post->view_count = 100;

$json = tec_json_pack($post, [WP_Post::class]);
$unpacked = tec_json_unpack($json, true, [WP_Post::class]); // WP_Post with dynamic properties intact.
```

### Error handling with `fail_on_error`

The `fail_on_error` parameter controls behavior when unpacking encounters missing classes:

```php
// Assume we have packed an object of class 'MyCustomClass' that no longer exists.
$json = '{"type":"MyCustomClass","properties":{"name":{"type":"string","value":"test"}}}';

// With fail_on_error = true (default): returns null if class missing.
$result = tec_json_unpack($json, true);
// $result is null

// With fail_on_error = false: returns stdClass replacement.
$result = tec_json_unpack($json, false);
// $result is stdClass with:
// - $result->__original_class__ = 'MyCustomClass'
// - $result->name = 'test'
```

## Use Cases

### Caching complex data

Store complex computation results that include objects:

```php
// Cache a complex calculation result.
// Note: stdClass and DateTime are considered safe by default.
$result = new stdClass();
$result->computed_at = new DateTime();
$result->data = perform_expensive_calculation();
$result->metadata = ['version' => '1.0', 'method' => 'advanced'];

// Store in cache as JSON.
set_transient('calculation_result', tec_json_pack($result), HOUR_IN_SECONDS);

// Later, retrieve and unpack.
$cached_json = get_transient('calculation_result');
if ($cached_json) {
    $result = tec_json_unpack($cached_json);
    // Use $result with the full object structure preserved.
}
```

### Storing Objects in Database

Save complex objects in database fields:

```php
// Prepare user preferences object.
// Note: stdClass and DateTime are considered safe by default.
$preferences = new stdClass();
$preferences->theme = 'dark';
$preferences->notifications = ['email' => true, 'sms' => false];
$preferences->last_updated = new DateTime();

// Save to user meta as packed JSON.
update_user_meta($user_id, 'app_preferences', tec_json_pack($preferences));

// Retrieve and unpack.
$json = get_user_meta($user_id, 'app_preferences', true);
$preferences = tec_json_unpack($json);
```

### API Data Exchange

Exchange complex data structures via APIs:

```php
// Prepare response data.
// Note: stdClass and DateTime are considered safe by default.
$response = new stdClass();
$response->status = 'success';
$response->timestamp = new DateTime();
$response->data = [
    'users' => $user_objects,
    'settings' => $settings_object
];

// Send it as JSON with allowed classes.
// Only need to specify custom classes (stdClass and DateTime are safe by default).
$allowed = [User::class, Settings::class];
wp_send_json(tec_json_pack($response, $allowed));

// On the receiving end.
$json = wp_remote_retrieve_body($api_response);
$data = tec_json_unpack($json, true, $allowed);
```

## Important Considerations

### Class availability

When unpacking objects, the original class must be available in the environment. If a class is missing:
- With `fail_on_error = true`: The unpack function returns `null`; differently from the `unserialize` method, the unpack function will not throw.
- With `fail_on_error = false`: A `stdClass` instance is created with an `__original_class__` property, the `stdClass` instance has the properties as the original class, but not its methods.

### Allowed Classes

The `allowed_classes` parameter provides a security mechanism to control which classes can be instantiated during unpacking:

```php
// Only allow specific classes to be instantiated.
$allowed = ['MyNamespace\\MyClass'];
$json = tec_json_pack($object, $allowed);
$unpacked = tec_json_unpack($json, true, $allowed);
```

- If `allowed_classes` is empty (default), all classes except safe ones are replaced with `stdClass` instances
- Only classes in the `allowed_classes` array will be instantiated as their original type
- Classes not in the allowed list are converted to `stdClass` with the same properties
- This provides protection against instantiating potentially dangerous classes
- **Note**: `stdClass`, `DateTime`, `DateTimeImmutable`, and `DateTimeZone` classes are always allowed by default for convenience and are considered safe

### Security

- Use the `allowed_classes` parameter to specify which classes can be instantiated
- The unpacker creates objects without calling constructors, which bypasses constructor validation
- Private and protected properties are exposed in the packed JSON

### Limitations

- Cannot pack resources, closures, or anonymous functions
- Objects must be reconstructible (some internal PHP objects may not work)
- Large object graphs with many circular references may result in large JSON output
