# Parameter Types

The TEC REST API uses a type system for parameter validation and OpenAPI documentation generation.

## Location

`/wp-content/plugins/the-events-calendar/common/src/Common/REST/TEC/V1/Parameter_Types/`

## Base Parameter Contract

All parameter types implement the `Parameter` interface from `Contracts/Parameter.php`.

## Available Parameter Types

### `Positive_Integer`

For positive integer values (IDs, counts, etc.)

```php
new Positive_Integer(
    'id',                              // name
    fn() => __('Description'),         // description
    1,                                 // default
    1,                                 // minimum
    100,                               // maximum
    true,                              // required
    fn($v) => $v > 0,                 // validate callback
    fn($v) => (int) $v,               // sanitize callback
    'Custom error message',            // error message
    Positive_Integer::LOCATION_PATH    // location (PATH, QUERY, HEADER)
);
```

### `Text`

For string values

```php
new Text(
    'title',
    fn() => __('Event title'),
    null,                              // default
    ['publish', 'draft'],              // allowed values (enum)
    null,                              // pattern (regex)
    null,                              // format
    true                               // required
);
```

### `Boolean`

For true/false values

```php
new Boolean(
    'featured',
    fn() => __('Is featured'),
    false                              // default
);
```

### `Date_Time`

For date and time values

```php
new Date_Time(
    'start_date',
    fn() => __('Event start date'),
    null,                              // default
    'Y-m-d H:i:s'                     // format
);
```

### `Email`

For email addresses

```php
new Email(
    'contact_email',
    fn() => __('Contact email address')
);
```

### `URI`

For URLs and URIs

```php
new URI(
    'website',
    fn() => __('Event website'),
    null,                              // default
    ['http', 'https']                  // allowed schemes
);
```

### `Array_Of_Type`

For arrays of a specific type

```php
new Array_Of_Type(
    'categories',
    fn() => __('Category IDs'),
    Positive_Integer::class,           // item type
    null,                              // allowed values
    [1, 2, 3],                        // default
    fn($arr) => count($arr) <= 10    // validate callback
);
```

### `Collection`

Container for multiple parameters

```php
$collection = new Collection();

$collection[] = new Text('name', fn() => __('Name'));
$collection[] = new Email('email', fn() => __('Email'));

// Convert to array for endpoint registration
$args = $collection->to_array();

// Filter by location
$query_params = $collection->filter(
    fn(Parameter $p) => $p->get_location() === Parameter::LOCATION_QUERY
);
```

### `Definition_Parameter`

Wrapper for OpenAPI definitions

```php
new Definition_Parameter(
    new Event_Definition()
);
```

## Parameter Locations

Parameters can specify their location:

- `Parameter::LOCATION_QUERY` - Query string parameters (default)
- `Parameter::LOCATION_PATH` - Path parameters (e.g., `/events/{id}`)
- `Parameter::LOCATION_HEADER` - HTTP headers

## Creating Custom Parameter Types

To create a custom parameter type:

```php
namespace TEC\Common\REST\TEC\V1\Parameter_Types;

class My_Custom_Type extends Parameter {
    public function __construct(
        string $name,
        callable $description,
        $default = null,
        bool $required = false,
        ?callable $validate_callback = null,
        ?callable $sanitize_callback = null
    ) {
        parent::__construct(
            $name,
            $description,
            'string',              // JSON Schema type
            $default,
            $required,
            $validate_callback,
            $sanitize_callback
        );
    }

    public function to_array(): array {
        $schema = parent::to_array();

        // Add custom schema properties
        $schema['pattern'] = '^[A-Z]{2,4}$';

        return $schema;
    }
}
```

## Usage in Endpoints

### Defining Parameters

```php
public function read_args(): Collection {
    $collection = new Collection();

    // Query parameters
    $collection[] = new Positive_Integer(
        'page',
        fn() => __('Page number'),
        1,
        1
    );

    // Path parameters
    $collection[] = new Positive_Integer(
        'id',
        fn() => __('Entity ID'),
        null,
        1,
        null,
        true,
        null,
        null,
        null,
        Positive_Integer::LOCATION_PATH
    );

    return $collection;
}
```

### Validation

Parameters automatically validate based on their type and constraints:

- Type checking
- Required field validation
- Min/max constraints
- Enum validation
- Custom validation callbacks

### Sanitization

Parameters are automatically sanitized:

- Type casting
- Trimming strings
- Custom sanitization callbacks

## Best Practices

1. **Use Specific Types** - Choose the most specific type for better validation
2. **Provide Descriptions** - Use translatable strings for descriptions
3. **Set Constraints** - Use min/max, enums, and patterns for validation
4. **Required Fields** - Mark required fields explicitly
5. **Custom Validation** - Add validation callbacks for complex rules
6. **Location Matters** - Specify correct location for path parameters
