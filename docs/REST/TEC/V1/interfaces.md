# TEC REST API Interfaces

The TEC REST API uses interfaces to define endpoint capabilities. Understanding these interfaces is crucial for creating new endpoints.

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

- `read(WP_REST_Request $request): WP_REST_Response`
- `read_args(): Collection`
- `read_schema(): OpenAPI_Schema`
- `can_read(WP_REST_Request $request): bool`

#### 3. `Creatable_Endpoint`

**Location**: `Creatable_Endpoint.php`

For endpoints that support POST requests:

- `create(WP_REST_Request $request): WP_REST_Response`
- `create_args(): Collection`
- `create_schema(): OpenAPI_Schema`
- `can_create(WP_REST_Request $request): bool`

#### 4. `Updatable_Endpoint`

**Location**: `Updatable_Endpoint.php`

For endpoints that support PUT/PATCH requests:

- `update(WP_REST_Request $request): WP_REST_Response`
- `update_args(): Collection`
- `update_schema(): OpenAPI_Schema`
- `can_update(WP_REST_Request $request): bool`

#### 5. `Deletable_Endpoint`

**Location**: `Deletable_Endpoint.php`

For endpoints that support DELETE requests:

- `delete(WP_REST_Request $request): WP_REST_Response`
- `delete_args(): Collection`
- `delete_schema(): OpenAPI_Schema`
- `can_delete(WP_REST_Request $request): bool`

### Composite Interfaces

#### `Collection_Endpoint`

**Location**: `Collection_Endpoint.php`

Combines all CRUD operations for collection endpoints:

- Extends: `Readable_Endpoint`, `Creatable_Endpoint`, `Updatable_Endpoint`, `Deletable_Endpoint`
- Used for: Batch operations on collections (future implementation)

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

## Abstract Classes

### `Endpoint`

**Location**: `/common/src/Common/REST/TEC/V1/Abstracts/Endpoint.php`

Base abstract class providing:

- Route registration logic
- Method mapping based on implemented interfaces
- URL generation helpers
- Default parameter handling

### `Post_Entity_Endpoint`

**Location**: `/common/src/Common/REST/TEC/V1/Abstracts/Post_Entity_Endpoint.php`

Abstract class for post-based endpoints:

- Extends `Endpoint`
- Implements `Post_Entity_Endpoint_Interface`
- Provides permission checking
- Handles post formatting
- Status validation

## Interface Hierarchy

```bash
Endpoint_Interface
├── Readable_Endpoint
├── Creatable_Endpoint
├── Updatable_Endpoint
├── Deletable_Endpoint
├── Collection_Endpoint (extends all CRUD interfaces)
├── RUD_Endpoint (extends Read, Update, Delete)
└── Post_Entity_Endpoint_Interface
```
