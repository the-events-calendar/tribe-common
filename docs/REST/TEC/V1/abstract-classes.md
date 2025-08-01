# Abstract Classes

The common REST API provides abstract classes that serve as base implementations for endpoints.

## Location

`/wp-content/plugins/the-events-calendar/common/src/Common/REST/TEC/V1/Abstracts/`

## Available Abstract Classes

### `Endpoint`

**File**: `Endpoint.php`

Base abstract class for all endpoints. Provides:

#### Key Methods

- `register_routes()` - Automatically registers routes based on implemented interfaces
- `get_methods()` - Maps HTTP methods to interface implementations
- `get_documentation()` - Generates OpenAPI documentation
- `get_current_rest_url()` - Gets the current REST URL
- `get_default_posts_per_page()` - Default pagination setting
- `get_url()` - Generates endpoint URLs
- `get_path()` - Builds the route path with parameters
- `get_open_api_path()` - OpenAPI-formatted path

#### Properties

- `EDITABLE` - Constant for PUT/PATCH methods

#### Usage Example

```php
abstract class MyEndpoint extends Endpoint implements Readable_Endpoint {
    public function get_base_path(): string {
        return '/my-endpoint';
    }

    public function get_schema(): array {
        return [
            '$schema' => 'http://json-schema.org/draft-04/schema#',
            'title' => 'my-endpoint',
            'type' => 'object'
        ];
    }

    // Implement Readable_Endpoint methods...
}
```

### `Post_Entity_Endpoint`

**File**: `Post_Entity_Endpoint.php`

Abstract class for WordPress post-based endpoints. Extends `Endpoint` and implements `Post_Entity_Endpoint_Interface`.

#### Key Features

- Permission checking based on WordPress capabilities
- Post formatting and transformation
- Status validation
- Model integration

#### Key Methods

- `guest_can_read()` - Whether guests can read (default: false)
- `can_read()` - Permission check for reading
- `can_create()` - Permission check for creating
- `can_update()` - Permission check for updating
- `can_delete()` - Permission check for deleting
- `get_post_type_object()` - Gets the WordPress post type object
- `format_post_entity_collection()` - Formats multiple posts
- `get_formatted_entity()` - Formats a single post
- `add_properties_to_model()` - Adds model properties to response
- `validate_status()` - Validates post status values
- `transform_entity()` - Transform hook for entities

#### Constants

- `ALLOWED_STATUS` - Array of allowed post statuses:
  - `publish`
  - `pending`
  - `draft`
  - `future`
  - `private`

#### Usage Example

```php
class EventEndpoint extends Post_Entity_Endpoint implements RUD_Endpoint {
    public function get_post_type(): string {
        return 'tribe_events';
    }

    public function get_model_class(): string {
        return Event_Model::class;
    }

    public function guest_can_read(): bool {
        return true; // Public events
    }

    // Implement RUD_Endpoint methods...
}
```

## Best Practices

1. **Extend the Appropriate Base Class**
   - Use `Endpoint` for non-post endpoints
   - Use `Post_Entity_Endpoint` for WordPress post-based endpoints

2. **Let the Abstract Handle Common Tasks**
   - Don't override `register_routes()` - it's handled automatically
   - Don't override permission methods unless necessary
   - Use provided formatting methods

3. **Focus on Your Business Logic**
   - Implement only the required interface methods
   - Use traits for common patterns
   - Let the abstract classes handle infrastructure

4. **Leverage Built-in Features**
   - Automatic route registration
   - Permission checking
   - OpenAPI documentation generation
   - URL generation helpers

## Method Inheritance

When extending these abstract classes:

### From `Endpoint`

- Route registration is automatic
- Method mapping based on interfaces
- URL helpers are available
- Documentation generation is handled

### From `Post_Entity_Endpoint`

- All `Endpoint` features plus:
- WordPress capability checking
- Post formatting utilities
- Status validation
- Model property mapping
