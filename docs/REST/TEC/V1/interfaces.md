# TEC REST API Interfaces

The TEC REST API uses interfaces to define endpoint capabilities. Understanding these interfaces is crucial for creating new endpoints. The interface system follows a composition pattern where endpoints implement specific operation interfaces based on their capabilities.

## Core Interfaces

### Endpoint Interfaces

Located in `/wp-content/plugins/the-events-calendar/common/src/Common/REST/TEC/V1/Contracts/`

#### 1. `Endpoint_Interface`

**Location**: `Endpoint_Interface.php`

Base interface that all endpoints must implement. Defines:

- `get_base_path(): string` - The endpoint's base path
- `get_schema(): array` - JSON schema for the endpoint
- `register_routes(): void` - Register endpoint routes

#### 2. `Readable_Endpoint`

**Location**: `Readable_Endpoint.php`

For endpoints that support GET requests:

- `read(array $params = []): WP_REST_Response` - Handle read operation with sanitized parameters
- `can_read(WP_REST_Request $request): bool` - Permission check for read operations
- `read_params(): QueryArgumentCollection` - Define query parameters for GET requests
- `read_schema(): OpenAPI_Schema` - OpenAPI documentation for read operation
- `get_read_attributes(): array` - WordPress REST registration attributes

#### 3. `Creatable_Endpoint`

**Location**: `Creatable_Endpoint.php`

For endpoints that support POST requests:

- `create(array $params = []): WP_REST_Response` - Handle create operation with sanitized parameters
- `can_create(WP_REST_Request $request): bool` - Permission check for create operations
- `create_params(): RequestBodyCollection` - Define request body structure (changed from QueryArgumentCollection)
- `create_schema(): OpenAPI_Schema` - OpenAPI documentation for create operation
- `get_create_attributes(): array` - WordPress REST registration attributes

#### 4. `Updatable_Endpoint`

**Location**: `Updatable_Endpoint.php`

For endpoints that support PUT/PATCH requests:

- `update(array $params = []): WP_REST_Response` - Handle update operation with sanitized parameters
- `can_update(WP_REST_Request $request): bool` - Permission check for update operations
- `update_params(): RequestBodyCollection` - Define request body structure (changed from QueryArgumentCollection)
- `update_schema(): OpenAPI_Schema` - OpenAPI documentation for update operation
- `get_update_attributes(): array` - WordPress REST registration attributes

#### 5. `Deletable_Endpoint`

**Location**: `Deletable_Endpoint.php`

For endpoints that support DELETE requests:

- `delete(array $params = []): WP_REST_Response` - Handle delete operation with sanitized parameters
- `can_delete(WP_REST_Request $request): bool` - Permission check for delete operations
- `delete_params(): QueryArgumentCollection` - Define query parameters for DELETE requests
- `delete_schema(): OpenAPI_Schema` - OpenAPI documentation for delete operation
- `get_delete_attributes(): array` - WordPress REST registration attributes

### Composite Interfaces

#### `Collection_Endpoint`

**Location**: `Collection_Endpoint.php`

Combines read and create operations for collection endpoints:

- Extends: `Readable_Endpoint`, `Creatable_Endpoint`
- Used for: Collection endpoints like Events, Venues, Organizers, Tickets
- Note: Despite the name, does NOT include Update or Delete operations for collections

#### `RUD_Endpoint`

**Location**: `RUD_Endpoint.php`

For single entity endpoints (Read, Update, Delete):

- Extends: `Readable_Endpoint`, `Updatable_Endpoint`, `Deletable_Endpoint`
- Used for: Single entity operations (Event, Organizer, Venue)

### Post Entity Interface

#### `Post_Entity_Endpoint_Interface`

**Location**: `Post_Entity_Endpoint_Interface.php`

Specific interface for WordPress post-based endpoints:

- `get_post_type(): string` - The WordPress post type
- `get_model_class(): string` - The model class name
- `guest_can_read(): bool` - Whether guests can read
- Provides standardized handling for WordPress post entities

### Documentation Interfaces

#### `Definition_Interface`

**Location**: `Definition_Interface.php`

For OpenAPI schema definitions:

- `get_name(): string` - Definition name
- `get_definition(): array` - OpenAPI schema definition

#### `OpenAPI_Schema`

**Location**: `OpenAPI_Schema.php`

For OpenAPI operation schemas:

- `get_schema(): array` - Returns OpenAPI operation schema
- Used by endpoint methods to define their request/response schemas

#### `Tag_Interface`

**Location**: `Tag_Interface.php`

For OpenAPI documentation tags:

- `get_name(): string` - Tag name
- `get_description(): string` - Tag description
- `get_external_docs(): array` - External documentation links

### Controller Interfaces

#### `Endpoints_Controller_Interface`

**Location**: `Endpoints_Controller_Interface.php`

For endpoint controllers:

- `get_endpoints(): array` - Returns array of endpoint instances
- `register(): void` - Registers the controller's endpoints

## Abstract Classes

### `Endpoint`

**Location**: `/common/src/Common/REST/TEC/V1/Abstracts/Endpoint.php`

Base abstract class providing:

- Route registration logic with automatic method mapping
- Permission callback routing based on implemented interfaces
- Path parameter regex generation for routing
- Experimental endpoint support with acknowledgment headers
- Schema caching system with version-aware fingerprinting
- Automatic parameter validation and sanitization
- URL generation helpers
- Strong typing with PropertiesCollection integration

### `Post_Entity_Endpoint`

**Location**: `/common/src/Common/REST/TEC/V1/Abstracts/Post_Entity_Endpoint.php`

Abstract class for post-based endpoints:

- Extends `Endpoint`
- Implements `Post_Entity_Endpoint_Interface`
- WordPress capability-based permission checking
- Post formatting using WordPress REST controller
- Post status validation (publish, pending, draft, future, private)
- Password protection and visibility checks
- Entity ID validation and retrieval
- Automatic 404 handling for missing entities

### Other Abstract Classes

#### `Definition`

**Location**: `/common/src/Common/REST/TEC/V1/Abstracts/Definition.php`

Base class for OpenAPI definitions:

- Implements `Definition_Interface`
- Provides base schema structure
- Supports schema composition with allOf pattern

#### `Parameter`

**Location**: `/common/src/Common/REST/TEC/V1/Abstracts/Parameter.php`

Base class for parameter types:

- Implements `Parameter` interface
- Provides validation and sanitization
- Supports default values and requirements
- OpenAPI schema generation

#### `Tag`

**Location**: `/common/src/Common/REST/TEC/V1/Abstracts/Tag.php`

Base class for documentation tags:

- Implements `Tag_Interface`
- Provides structured tag definitions

#### `Endpoints_Controller`

**Location**: `/common/src/Common/REST/TEC/V1/Abstracts/Endpoints_Controller.php`

Base controller class:

- Implements `Endpoints_Controller_Interface`
- Manages endpoint registration
- Provides namespace and version handling

## Interface Hierarchy

```bash
Core Interfaces:
├── Endpoint_Interface (base)
│   ├── Readable_Endpoint
│   ├── Creatable_Endpoint
│   ├── Updatable_Endpoint
│   └── Deletable_Endpoint
│
├── Composite Interfaces:
│   ├── Collection_Endpoint (Readable + Creatable)
│   └── RUD_Endpoint (Readable + Updatable + Deletable)
│
├── Entity Interfaces:
│   └── Post_Entity_Endpoint_Interface
│
├── Documentation Interfaces:
│   ├── Definition_Interface
│   ├── OpenAPI_Schema
│   └── Tag_Interface
│
└── Controller Interfaces:
    └── Endpoints_Controller_Interface
```

## Implementation Patterns

### Collection Endpoints

For endpoints managing collections (e.g., `/events`, `/venues`):

```php
class Events extends Post_Entity_Endpoint implements Collection_Endpoint {
    public function read(array $params = []): WP_REST_Response {
        // Handle GET /events with pre-sanitized parameters
    }

    public function create(array $params = []): WP_REST_Response {
        // Handle POST /events with pre-sanitized parameters
    }

    public function read_params(): QueryArgumentCollection {
        // Define query parameters for filtering/pagination
    }

    public function create_params(): RequestBodyCollection {
        // Define request body structure for entity creation
    }
}
```

### Single Entity Endpoints

For endpoints managing individual entities (e.g., `/events/{id}`):

```php
class Event extends Post_Entity_Endpoint implements RUD_Endpoint {
    public function read(array $params = []): WP_REST_Response {
        // Handle GET /events/{id} with pre-sanitized parameters
    }

    public function update(array $params = []): WP_REST_Response {
        // Handle PUT/PATCH /events/{id} with pre-sanitized parameters
    }

    public function delete(array $params = []): WP_REST_Response {
        // Handle DELETE /events/{id} with pre-sanitized parameters
    }

    public function update_params(): RequestBodyCollection {
        // Define request body structure for entity updates
    }
}
```

### Key Design Principles

1. **Interface Segregation**: Endpoints only implement interfaces for operations they support
2. **Composition Over Inheritance**: Use multiple interfaces to define capabilities
3. **Parameter Sanitization**: All operation methods receive pre-sanitized `array $params` instead of raw `WP_REST_Request`
4. **Request Body Collections**: Create/Update operations use `RequestBodyCollection` for structured request bodies
5. **Query Collections**: Read/Delete operations use `QueryArgumentCollection` for query parameters
6. **Permission Integration**: Each operation interface has a corresponding `can_*` method for authorization
7. **Schema Definition**: Each operation has its own args and schema methods for OpenAPI documentation
8. **WordPress Compatibility**: The `to_query_argument_collection()` bridge ensures WordPress REST API compatibility
