# Parameter Types

The TEC REST API uses a sophisticated type system for parameter validation, sanitization, and OpenAPI documentation generation. All parameters extend from an abstract base class and can be composed into collections for endpoint definitions.

## Location

`/wp-content/plugins/the-events-calendar/common/src/Common/REST/TEC/V1/Parameter_Types/`

## Base Parameter Contract

All parameter types extend the abstract `Parameter` class from `Abstracts/Parameter.php` and implement the `Parameter` interface from `Contracts/Parameter.php`.

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

### `Date`

For date-only values (without time)

```php
new Date(
    'event_date',
    fn() => __('Event date'),
    null,                              // default
    'Y-m-d'                           // format
);
```

### `Integer`

For integer values (positive or negative)

```php
new Integer(
    'offset',
    fn() => __('Offset value'),
    0,                                 // default
    -100,                             // minimum
    100                               // maximum
);
```

### `Number`

For numeric values including decimals

```php
new Number(
    'price',
    fn() => __('Ticket price'),
    0.00,                             // default
    0,                                // minimum
    9999.99                           // maximum
);
```

### `Hex_Color`

For hexadecimal color values

```php
new Hex_Color(
    'background_color',
    fn() => __('Background color'),
    '#FFFFFF'                         // default
);
```

### `IP`

For IP addresses (IPv4 and IPv6)

```php
new IP(
    'client_ip',
    fn() => __('Client IP address'),
    null,                             // default
    'ipv4'                           // version: 'ipv4', 'ipv6', or null for both
);
```

### `UUID`

For UUID values

```php
new UUID(
    'transaction_id',
    fn() => __('Transaction UUID'),
    null,                             // default
    4                                // UUID version (1-5)
);
```

### `Entity`

For entity references (posts, terms, users)

```php
new Entity(
    'author',
    fn() => __('Author ID'),
    'user',                           // entity type
    null,                             // default
    true                              // required
);
```

### Collections

The API provides specialized collection classes for organizing parameters:

#### `PropertiesCollection`

For request/response body properties:

```php
$properties = new PropertiesCollection();

$properties->add(
    new Text('title', fn() => __('Event title'), null, null, null, null, true)
);
$properties->add(
    new Date_Time('start_date', fn() => __('Start date'))
);

// Convert to OpenAPI schema
$schema = $properties->to_array();
```

#### `QueryArgumentCollection`

For query string parameters:

```php
$query = new QueryArgumentCollection();

$query->add(
    new Positive_Integer('page', fn() => __('Page number'), 1)
);
$query->add(
    new Text('search', fn() => __('Search term'))
);
```

#### `PathArgumentCollection`

For path parameters:

```php
$path = new PathArgumentCollection();

$path->add(
    new Positive_Integer('id', fn() => __('Event ID'), null, 1, null, true)
);
```

#### `RequestBodyCollection`

For request body schemas:

```php
$body = new RequestBodyCollection();

$body->add(
    new Definition_Parameter(new Event_Request_Body_Definition())
);
```

#### `HeadersCollection`

For HTTP headers:

```php
$headers = new HeadersCollection();

$headers->add(
    new Text('X-TEC-Experimental', fn() => __('Experimental acknowledgment'), null, ['acknowledged'])
);
```

#### Base `Collection`

Generic collection for mixed parameters:

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

// Check if parameter exists
if ($collection->has('name')) {
    $name_param = $collection->get('name');
}

// Iterate over parameters
foreach ($collection as $parameter) {
    // Process parameter
}
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

### Defining Parameters with Specialized Collections

```php
public function read_args(): Collection {
    // For query parameters
    $query = new QueryArgumentCollection();
    
    $query->add(
        new Positive_Integer('page', fn() => __('Page number'), 1, 1)
    );
    $query->add(
        new Positive_Integer('per_page', fn() => __('Items per page'), 10, 1, 100)
    );
    $query->add(
        new Text('search', fn() => __('Search term'))
    );
    
    // For path parameters
    $path = new PathArgumentCollection();
    
    $path->add(
        new Positive_Integer('id', fn() => __('Entity ID'), null, 1, null, true)
    );
    
    // Combine collections
    $collection = new Collection();
    $collection->merge($query);
    $collection->merge($path);
    
    return $collection;
}

// For request body
public function create_args(): Collection {
    $body = new RequestBodyCollection();
    
    $body->add(
        new Definition_Parameter(new Event_Request_Body_Definition())
    );
    
    return $body;
}
```

### Real-World Example from Events Endpoint

```php
public function read_args(): Collection {
    $query = new QueryArgumentCollection();
    
    // Pagination
    $query->add(
        new Positive_Integer('page', fn() => __('Page number', 'the-events-calendar'), 1, 1)
    );
    $query->add(
        new Positive_Integer('per_page', fn() => __('Events per page', 'the-events-calendar'), 10, 1, 100)
    );
    
    // Date filtering
    $query->add(
        new Date_Time('start_date', fn() => __('Events starting after', 'the-events-calendar'))
    );
    $query->add(
        new Date_Time('end_date', fn() => __('Events ending before', 'the-events-calendar'))
    );
    
    // Status filtering
    $query->add(
        new Boolean('featured', fn() => __('Only featured events', 'the-events-calendar'), false)
    );
    $query->add(
        new Boolean('ticketed', fn() => __('Only events with tickets', 'the-events-calendar'))
    );
    
    // Relationship filtering
    $query->add(
        new Array_Of_Type(
            'venue',
            fn() => __('Filter by venue IDs', 'the-events-calendar'),
            Positive_Integer::class
        )
    );
    $query->add(
        new Array_Of_Type(
            'organizer',
            fn() => __('Filter by organizer IDs', 'the-events-calendar'),
            Positive_Integer::class
        )
    );
    
    // Sorting
    $query->add(
        new Text(
            'orderby',
            fn() => __('Sort events by', 'the-events-calendar'),
            'event_date',
            ['event_date', 'title', 'menu_order', 'modified']
        )
    );
    $query->add(
        new Text(
            'order',
            fn() => __('Sort order', 'the-events-calendar'),
            'ASC',
            ['ASC', 'DESC']
        )
    );
    
    return $query;
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

1. **Use Specialized Collections** - Use `QueryArgumentCollection`, `PathArgumentCollection`, etc. for better type safety
2. **Use Specific Types** - Choose the most specific parameter type for better validation
3. **Provide Translatable Descriptions** - Always use `__()` for descriptions with text domain
4. **Set Appropriate Constraints** - Use min/max, enums, and patterns for validation
5. **Mark Required Fields** - Explicitly mark required fields in constructors
6. **Add Custom Validation** - Use validation callbacks for complex business rules
7. **Specify Correct Location** - Use appropriate location constants for path/query/header parameters
8. **Leverage Type Composition** - Use `Array_Of_Type` for arrays of specific types
9. **Document Default Values** - Always specify sensible defaults where applicable
10. **Use Definition Parameters** - For complex request/response bodies, use `Definition_Parameter` with schema definitions

## Migration from Legacy Arrays

The API has migrated from array-based parameter definitions to strongly-typed collections:

### Before (Legacy)
```php
public function get_arguments() {
    return [
        'id' => [
            'type' => 'integer',
            'minimum' => 1,
            'required' => true,
            'description' => 'Event ID',
        ],
    ];
}
```

### After (Current)
```php
public function read_args(): Collection {
    $path = new PathArgumentCollection();
    
    $path->add(
        new Positive_Integer(
            'id',
            fn() => __('Event ID', 'the-events-calendar'),
            null,
            1,
            null,
            true
        )
    );
    
    return $path;
}
```

This migration provides:
- Type safety at development time
- Automatic validation and sanitization
- Better IDE support and autocomplete
- Consistent OpenAPI schema generation
- Reusable parameter definitions
