# TEC REST API Abstract Classes

Abstract classes provide base implementations for endpoints, definitions, and other REST API components.

## Location

`/wp-content/plugins/the-events-calendar/common/src/Common/REST/TEC/V1/Abstracts/`

## Core Abstract Classes

### `Endpoint`

**Location**: `Endpoint.php`

Base abstract class for all REST API endpoints.

#### Key Features

- **Automatic Route Registration**: Maps HTTP methods to interface methods
- **Permission Callback Routing**: Routes to appropriate `can_*` methods based on interfaces
- **Path Parameter Support**: Generates regex patterns for path parameters
- **Experimental Endpoint Support**: Built-in beta endpoint acknowledgment system
- **Schema Caching**: Version-aware caching with plugin fingerprinting
- **Parameter Validation**: Automatic validation and sanitization
- **Strong Typing**: Integration with PropertiesCollection system

#### Required Methods

```php
abstract public function get_base_path(): string;
abstract public function get_path_regex(): string;
```

#### Key Methods

- `register_routes()`: Registers endpoint routes with WordPress REST API
- `get_schema()`: Returns OpenAPI schema for the endpoint
- `get_args()`: Returns WordPress REST API arguments
- `permission_callback()`: Routes to appropriate permission method
- `is_experimental()`: Check if endpoint is experimental

#### Usage Example

```php
class Events extends Endpoint implements Collection_Endpoint {
    public function get_base_path(): string {
        return '/events';
    }
    
    public function get_path_regex(): string {
        return ''; // No path parameters for collection
    }
    
    // Interface methods implementation...
}
```

### `Post_Entity_Endpoint`

**Location**: `Post_Entity_Endpoint.php`

Abstract class for WordPress post-based entities.

#### Key Features

- **WordPress Integration**: Full integration with WordPress post system
- **Capability-Based Permissions**: Uses WordPress capabilities for authorization
- **Post Status Validation**: Validates post status (publish, draft, etc.)
- **Password Protection**: Handles password-protected posts
- **Entity Formatting**: Uses WordPress REST controller for formatting
- **404 Handling**: Automatic handling of missing entities

#### Required Methods

```php
abstract public function get_post_type(): string;
abstract public function get_model_class(): string;
abstract public function guest_can_read(): bool;
```

#### Key Methods

- `get_post()`: Retrieve a post by ID
- `format_post()`: Format post for response
- `can_read()`: Check read permissions
- `can_create()`: Check create permissions
- `can_update()`: Check update permissions
- `can_delete()`: Check delete permissions

#### Usage Example

```php
class Event extends Post_Entity_Endpoint implements RUD_Endpoint {
    public function get_post_type(): string {
        return 'tribe_events';
    }
    
    public function get_model_class(): string {
        return \Tribe\Events\Models\Post_Types\Event::class;
    }
    
    public function guest_can_read(): bool {
        return true; // Public events
    }
}
```

### `Definition`

**Location**: `Definition.php`

Base class for OpenAPI schema definitions.

#### Key Features

- **Schema Generation**: Generates OpenAPI-compliant schemas
- **Composition Support**: Supports allOf pattern for inheritance
- **Type System**: Integrates with parameter type system
- **Priority System**: Controls registration order

#### Required Methods

```php
abstract public function get_name(): string;
abstract public function get_definition(): array;
```

#### Key Methods

- `get_priority()`: Returns registration priority
- `to_array()`: Converts to OpenAPI schema array

#### Usage Example

```php
class Event_Definition extends Definition {
    public function get_name(): string {
        return 'Event';
    }
    
    public function get_definition(): array {
        return [
            'allOf' => [
                ['$ref' => '#/components/schemas/TEC_Post_Entity'],
                [
                    'type' => 'object',
                    'properties' => [
                        'start_date' => [
                            'type' => 'string',
                            'format' => 'date-time',
                        ],
                        // More properties...
                    ],
                ],
            ],
        ];
    }
}
```

### `Parameter`

**Location**: `Parameter.php`

Base class for parameter types.

#### Key Features

- **Validation**: Built-in validation support
- **Sanitization**: Automatic sanitization
- **OpenAPI Schema**: Generates OpenAPI parameter schemas
- **Default Values**: Support for default values
- **Location Support**: Query, path, header parameters

#### Required Properties

```php
protected string $name;
protected callable $description;
protected string $type;
protected $default;
protected bool $required;
```

#### Key Methods

- `validate()`: Validate parameter value
- `sanitize()`: Sanitize parameter value
- `to_array()`: Convert to OpenAPI/WordPress args array
- `get_location()`: Get parameter location (query, path, header)

#### Usage Example

```php
class Custom_Parameter extends Parameter {
    public function __construct(
        string $name,
        callable $description,
        $default = null
    ) {
        parent::__construct(
            $name,
            $description,
            'string',
            $default,
            false
        );
    }
    
    public function validate($value): bool {
        // Custom validation logic
        return is_string($value);
    }
}
```

### `Tag`

**Location**: `Tag.php`

Base class for OpenAPI documentation tags.

#### Key Features

- **Tag Grouping**: Groups related endpoints
- **Documentation**: Provides tag descriptions
- **External Docs**: Support for external documentation links

#### Required Methods

```php
abstract public function get_name(): string;
abstract public function get_description(): string;
```

#### Key Methods

- `get_external_docs()`: Returns external documentation links
- `to_array()`: Converts to OpenAPI tag array

#### Usage Example

```php
class Events_Tag extends Tag {
    public function get_name(): string {
        return 'Events';
    }
    
    public function get_description(): string {
        return 'Operations for managing events';
    }
    
    public function get_external_docs(): array {
        return [
            'description' => 'Learn more',
            'url' => 'https://example.com/docs',
        ];
    }
}
```

### `Endpoints_Controller`

**Location**: `Endpoints_Controller.php`

Base controller for managing endpoints.

#### Key Features

- **Endpoint Registration**: Manages endpoint registration
- **Namespace Management**: Handles API namespace
- **Version Support**: API versioning support

#### Required Methods

```php
abstract public function get_endpoints(): array;
```

#### Key Methods

- `register()`: Register all controller endpoints
- `get_namespace()`: Get API namespace
- `get_version()`: Get API version

#### Usage Example

```php
class Events_Endpoints extends Endpoints_Controller {
    public function get_endpoints(): array {
        return [
            new Events(),
            new Event(),
            new Organizers(),
            new Organizer(),
            new Venues(),
            new Venue(),
        ];
    }
}
```

## Abstract Class Hierarchy

```
Endpoint (base for all endpoints)
└── Post_Entity_Endpoint (for WordPress post entities)
    ├── Event
    ├── Events
    ├── Organizer
    ├── Organizers
    ├── Venue
    ├── Venues
    ├── Ticket
    └── Tickets

Definition (base for OpenAPI definitions)
├── TEC_Post_Entity_Definition
├── Event_Definition
├── Organizer_Definition
├── Venue_Definition
└── Ticket_Definition

Parameter (base for parameter types)
├── Positive_Integer
├── Text
├── Boolean
├── Date_Time
├── Array_Of_Type
└── ... (other parameter types)

Tag (base for documentation tags)
├── TEC_Tag
├── Tickets_Tag
└── Common_Tag

Endpoints_Controller (base for endpoint controllers)
├── TEC\Events\REST\TEC\V1\Endpoints
├── TEC\Tickets\REST\TEC\V1\Endpoints
└── TEC\Common\REST\TEC\V1\Endpoints
```

## Best Practices

### When to Extend Abstract Classes

1. **Extend `Endpoint`** when creating a non-post entity endpoint
2. **Extend `Post_Entity_Endpoint`** for WordPress post-based entities
3. **Extend `Definition`** for new OpenAPI schema definitions
4. **Extend `Parameter`** for custom parameter types
5. **Extend `Tag`** for new documentation tags
6. **Extend `Endpoints_Controller`** for plugin-specific controllers

### Implementation Guidelines

1. **Always implement required abstract methods**
2. **Call parent constructors when overriding**
3. **Use type declarations for all methods**
4. **Document with PHPDoc blocks**
5. **Follow WordPress coding standards**
6. **Include @since tags for versioning**

### Common Patterns

#### Permission Checks

```php
public function can_read(WP_REST_Request $request): bool {
    // Check parent permissions first
    if (!parent::can_read($request)) {
        return false;
    }
    
    // Add custom permission logic
    return current_user_can('read_private_posts');
}
```

#### Schema Generation

```php
public function get_schema(): array {
    $schema = parent::get_schema();
    
    // Add custom schema properties
    $schema['properties']['custom_field'] = [
        'type' => 'string',
        'description' => 'Custom field description',
    ];
    
    return $schema;
}
```

#### Parameter Validation

```php
public function validate($value): bool {
    // Use parent validation first
    if (!parent::validate($value)) {
        return false;
    }
    
    // Add custom validation
    return strlen($value) <= 100;
}
```

## See Also

- [Interfaces](interfaces.md) - Interface definitions
- [Traits](traits.md) - Reusable functionality
- [Parameter Types](parameter-types.md) - Available parameter types
- [Creating Endpoints](../../creating-endpoints.md) - Step-by-step guide