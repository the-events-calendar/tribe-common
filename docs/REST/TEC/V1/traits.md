# REST API Traits

Traits provide reusable functionality for REST API endpoints.

## Location

`/wp-content/plugins/the-events-calendar/common/src/Common/REST/TEC/V1/Traits/`

## Available Traits

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
- `format_post_entity_collection(array $posts): array`
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

- Standardized creation response
- Error handling
- 201 status for successful creation

#### Required Methods

Your class must implement:
- `get_orm()` - Return the ORM instance
- `get_formatted_entity(WP_Post $post): array`

## Plugin-Specific Traits

### Events Calendar Traits

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

## Creating Custom Traits

When creating traits for REST endpoints:

1. **Single Responsibility** - Each trait should handle one specific aspect
2. **Clear Dependencies** - Document required methods
3. **Namespace Properly** - Place in appropriate plugin namespace
4. **Type Hints** - Use proper type declarations

## Best Practices

1. **Use Traits for Shared Logic** - Don't duplicate code across endpoints
2. **Keep Traits Focused** - Each trait should have a single purpose
3. **Document Dependencies** - Clearly state what methods the trait expects
4. **Avoid State** - Traits should not maintain state
5. **Type Safety** - Use proper type declarations and return types
6. **Naming Convention** - Use descriptive names (e.g., `With_*`, `Has_*`)
