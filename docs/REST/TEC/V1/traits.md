# REST API Traits

Traits provide reusable functionality for REST API endpoints, implementing common patterns for response handling, ORM access, and data transformation.

## Common Library Traits

### Location

`/wp-content/plugins/the-events-calendar/common/src/Common/REST/TEC/V1/Traits/`

### Response Handler Traits

### `Read_Archive_Response`

Handles reading collections of entities with pagination.

#### Features

- Automatic pagination handling
- Total count calculation
- Link header generation
- 404 handling for invalid pages

#### Required Methods

Your class must implement:

- `build_query(WP_REST_Request $request): Tribe__Repository__Interface`
- `get_default_posts_per_page(): int`
- `format_entity_collection(array $posts): array`
- `get_post_type(): string`
- `get_current_rest_url(WP_REST_Request $request): string`

#### Usage

```php
class Events extends Post_Entity_Endpoint implements Readable_Endpoint {
    use Read_Archive_Response;

    protected function build_query( WP_REST_Request $request ): Tribe__Repository__Interface {
        $query = tribe_events();

        if ( ! empty( $request['search'] ) ) {
            $query->search( $request['search'] );
        }

        return $query;
    }
}
```

#### Response Headers

- `X-WP-Total` - Total number of items
- `X-WP-TotalPages` - Total number of pages
- `Link` - RFC 5988 pagination links

### `Create_Entity_Response`

Handles creating new entities.

#### Features

- Standardized creation response (201 Created)
- Error handling with appropriate status codes
- Location header with created resource URL
- Formatted entity in response body

#### Required Methods

Your class must implement:
- `get_orm(): Tribe__Repository__Interface` - Return the ORM instance
- `get_formatted_entity(WP_Post $post): array` - Format the created entity
- `get_read_url(int $id): string` - Get URL for the created resource

#### Usage

```php
class Events extends Post_Entity_Endpoint implements Creatable_Endpoint {
    use Create_Entity_Response;
    use With_Events_ORM;

    public function create(WP_REST_Request $request): WP_REST_Response {
        $orm = $this->get_orm();
        $post_id = $orm->set_args($request->get_params())->create();

        if (is_wp_error($post_id)) {
            return $this->get_error_response($post_id);
        }

        return $this->get_create_response($post_id);
    }
}
```

### `Read_Entity_Response`

Handles reading single entities.

#### Features

- Standardized read response (200 OK)
- 404 handling for missing entities
- Entity formatting
- Permission checking

#### Required Methods

Your class must implement:
- `get_post(int $id): ?WP_Post` - Retrieve the post
- `get_formatted_entity(WP_Post $post): array` - Format the entity

### `Update_Entity_Response`

Handles updating entities.

#### Features

- Standardized update response (200 OK)
- Error handling
- Updated entity in response
- Partial update support (PATCH)

#### Required Methods

Your class must implement:
- `get_orm(): Tribe__Repository__Interface` - Return the ORM instance
- `get_formatted_entity(WP_Post $post): array` - Format the updated entity

### `Delete_Entity_Response`

Handles deleting entities.

#### Features

- Standardized delete response (200 OK or 410 Gone)
- Soft delete and force delete support
- Previous entity data in response
- Trash/permanent deletion handling

#### Required Methods

Your class must implement:
- `get_post(int $id): ?WP_Post` - Retrieve the post
- `get_formatted_entity(WP_Post $post): array` - Format the deleted entity

## Plugin-Specific Traits

### The Events Calendar Plugin Traits

Located in `/wp-content/plugins/the-events-calendar/src/Events/REST/TEC/V1/Traits/`

#### `With_Events_ORM`

Provides access to the events ORM.

```php
trait With_Events_ORM {
    public function get_orm() {
        return tribe_events();
    }
}
```

#### `With_Organizers_ORM`

Provides access to the organizers ORM.

```php
trait With_Organizers_ORM {
    public function get_orm() {
        return tribe_organizers();
    }
}
```

#### `With_Venues_ORM`

Provides access to the venues ORM.

```php
trait With_Venues_ORM {
    public function get_orm() {
        return tribe_venues();
    }
}
```

#### `With_Transform_Organizers_And_Venues`

Handles transformation of organizer and venue relationships.

##### Features

- Converts organizer/venue IDs to full objects
- Handles multiple venues/organizers
- Provides fallback for missing entities
- Integrates with Events endpoint responses

##### Usage

```php
class Event extends Post_Entity_Endpoint {
    use With_Transform_Organizers_And_Venues;

    protected function format_entity(WP_Post $post): array {
        $data = parent::format_entity($post);

        // Transform venue and organizer IDs to objects
        $data = $this->transform_organizers_and_venues($data);

        return $data;
    }
}
```

### Event Tickets Plugin Traits

Located in `/wp-content/plugins/event-tickets/src/Tickets/REST/TEC/V1/Traits/`

#### `With_Tickets_ORM`

Provides access to the tickets ORM.

```php
trait With_Tickets_ORM {
    public function get_orm() {
        return tribe_tickets();
    }

    public function get_ticket_repository() {
        return tribe('tickets.ticket-repository');
    }
}
```

#### `With_Filtered_Ticket_Params`

Filters ticket-specific parameters.

##### Features

- Removes internal ticket fields from requests
- Validates ticket-specific parameters
- Handles Commerce-specific fields
- Sanitizes pricing data

##### Usage

```php
class Ticket extends Post_Entity_Endpoint {
    use With_Filtered_Ticket_Params;

    public function update(WP_REST_Request $request): WP_REST_Response {
        $params = $this->filter_ticket_params($request->get_params());
        // Process filtered params
    }
}
```

#### `With_Parent_Post_Read_Check`

Validates access to parent posts (events) when accessing tickets.

##### Features

- Checks if parent event is readable
- Handles password-protected events
- Validates event status
- Permission inheritance from parent

##### Usage

```php
class Tickets extends Post_Entity_Endpoint {
    use With_Parent_Post_Read_Check;

    public function can_read(WP_REST_Request $request): bool {
        if (!empty($request['event'])) {
            return $this->can_read_parent_post($request['event']);
        }

        return parent::can_read($request);
    }
}
```

#### `With_TC_Provider`

Provides access to Tickets Commerce provider.

##### Features

- Gets Commerce provider instance
- Checks if Commerce is enabled
- Provides provider-specific methods
- Handles provider switching

#### `With_Ticket_Upsert`

Handles ticket creation and updates.

##### Features

- Unified create/update logic
- Stock management
- Pricing calculations
- Sale period handling
- Validation of ticket data

##### Usage

```php
class Tickets extends Post_Entity_Endpoint {
    use With_Ticket_Upsert;

    public function create(WP_REST_Request $request): WP_REST_Response {
        $ticket_data = $this->prepare_ticket_data($request);
        $ticket_id = $this->upsert_ticket($ticket_data);

        if (is_wp_error($ticket_id)) {
            return $this->get_error_response($ticket_id);
        }

        return $this->get_create_response($ticket_id);
    }
}
```

## Creating Custom Traits

When creating traits for REST endpoints:

### Structure

```php
namespace TEC\Events\REST\TEC\V1\Traits;

trait With_Custom_Logic {
    /**
     * Document what this trait provides.
     *
     * @since 1.0.0
     */
    public function custom_method(): array {
        // Implementation
    }

    /**
     * Document required methods from using class.
     *
     * @return \Tribe__Repository__Interface
     */
    abstract public function get_orm();
}
```

### Guidelines

1. **Single Responsibility** - Each trait should handle one specific aspect
2. **Clear Dependencies** - Use abstract methods for required dependencies
3. **Namespace Properly** - Place in `{Plugin}\REST\TEC\V1\Traits\`
4. **Type Hints** - Use proper type declarations and return types
5. **Documentation** - Include PHPDoc with @since tags
6. **Prefix Convention** - Use `With_` prefix for traits that provide functionality

## Best Practices

1. **Use Traits for Shared Logic** - Don't duplicate code across endpoints
2. **Keep Traits Focused** - Each trait should have a single purpose
3. **Document Dependencies** - Use abstract methods or document required methods
4. **Avoid State** - Traits should not maintain properties unless necessary
5. **Type Safety** - Always use proper type declarations and return types
6. **Naming Convention** - Use descriptive prefixes:
   - `With_*` - Provides functionality or access
   - `Has_*` - Adds capabilities or features
   - `Can_*` - Adds permission or validation logic
7. **Error Handling** - Traits should handle errors consistently
8. **Testing** - Write unit tests for trait functionality
9. **Composition** - Combine multiple traits for complex functionality
10. **Version Tags** - Include @since tags in documentation

## Trait Composition Example

```php
class Events extends Post_Entity_Endpoint implements Collection_Endpoint {
    // Response handling
    use Read_Archive_Response;
    use Create_Entity_Response;

    // ORM access
    use With_Events_ORM;

    // Data transformation
    use With_Transform_Organizers_And_Venues;

    // Each trait provides specific functionality
    // Combined, they create a complete endpoint
}
```
