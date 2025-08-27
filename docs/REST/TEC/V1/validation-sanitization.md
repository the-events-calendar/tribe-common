# REST API Validation and Sanitization System

## Overview

The TEC REST API implements a sophisticated multi-stage validation and sanitization pipeline that leverages WordPress core's validation system while maintaining strong typing and OpenAPI compliance.

## Architecture

### Processing Pipeline

The validation/sanitization system processes requests through multiple stages:

```
Request → Experimental Check → Schema Validation → Custom Filtering → Operation Handler
```

### Core Components

#### 1. Parameter Processing Pipeline (`Endpoint::respond()`)

```php
protected function respond(callable $callback): callable {
    $experimental_response = fn($request) =>
        $this->is_experimental() && $this->assure_experimental_acknowledgement($request);

    $params_sanitizer = fn($request) =>
        $this->get_schema_defined_params($operation, $request->get_params());

    $params_filter = fn($params) =>
        $this->filter_params($params, $operation);

    // Execute pipeline
    return static function ($request) use ($callback, $params_sanitizer, $experimental_response, $params_filter) {
        $experimental_response($request);
        $response = $callback($params_filter($params_sanitizer($request)));
        return $response;
    };
}
```

#### 2. Schema-Based Validation (`get_schema_defined_params()`)

This method applies schema-defined validation rules:

```php
protected function get_schema_defined_params(string $schema_name, array $request_params = []): array {
    switch ($schema_name) {
        case 'read':
            $schema = $this->read_schema();
            break;
        case 'create':
            $schema = $this->create_schema();
            break;
        case 'update':
            $schema = $this->update_schema();
            break;
        case 'delete':
            $schema = $this->delete_schema();
            break;
    }

    // Throws InvalidRestArgumentException if validation fails
    return $schema->filter($request_params);
}
```

#### 3. OpenAPI Schema Filtering (`OpenAPI_Schema::filter()`)

The schema filter performs comprehensive validation:

```php
public function filter(array $data = []): array {
    // 1. Categorize parameters by location
    $path_params = $this->get_path_parameters();
    $query_params = $this->get_query_parameters();
    $body_params = $this->get_request_body();

    // 2. Validate required parameters
    foreach ($params as $param) {
        if ($param->is_required() && !isset($data[$param->get_name()])) {
            throw new InvalidRestArgumentException(
                $param->get_name(),
                'Missing required argument'
            );
        }
    }

    // 3. Apply type coercion for URL parameters
    if ('Body' !== $type && $param instanceof Number) {
        $data[$param_name] = $param instanceof Integer ?
            intval($data[$param_name]) :
            floatval($data[$param_name]);
    }

    // 4. Set default values
    if (!isset($data[$param_name]) && $param->get_default() !== null) {
        $data[$param_name] = $param->get_default();
    }

    return $data;
}
```

## Request Body vs Query Parameters

### Request Body Collection (POST/PUT/PATCH)

Used for structured data in create/update operations:

```php
public function create_params(): RequestBodyCollection {
    $collection = new RequestBodyCollection();
    $definition = new Event_Request_Body_Definition();

    $collection[] = new Definition_Parameter($definition);

    return $collection
        ->set_description_provider(fn() => __('Event data'))
        ->set_required(true)
        ->set_example($definition->get_example());
}
```

### Query Argument Collection (GET/DELETE)

Used for URL parameters:

```php
public function read_params(): QueryArgumentCollection {
    $collection = new QueryArgumentCollection();

    $collection->add(
        new Positive_Integer('page', fn() => __('Page number'), 1, 1)
    );

    return $collection;
}
```

## WordPress Integration

### Bridge Pattern

The `RequestBodyCollection::to_query_argument_collection()` method bridges OpenAPI structure with WordPress REST API:

```php
// In Endpoint::get_create_attributes()
return [
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => $this->respond([$this, 'create']),
    'permission_callback' => [$this, 'can_create'],
    'args' => $args->to_query_argument_collection()->to_array(), // Bridge here
];
```

This ensures WordPress core receives properly formatted validation rules while maintaining OpenAPI documentation structure.

## Custom Filtering

Endpoints can implement operation-specific filtering:

```php
class Event extends Post_Entity_Endpoint {
    // Automatically called by filter_params() for create operations
    protected function filter_create_params(array $params): array {
        // Remove internal fields
        unset($params['_internal']);

        // Transform data
        if (isset($params['date'])) {
            $params['start_date'] = $params['date'];
            unset($params['date']);
        }

        return $params;
    }
}
```

## Error Handling

### InvalidRestArgumentException

Custom exception for validation errors:

```php
throw new InvalidRestArgumentException(
    'start_date',                              // Parameter name
    'Start date must be in the future',        // Error message
    'rest_invalid_date'                        // Error code
);
```

### Error Response Format

```json
{
    "code": "rest_invalid_param",
    "message": "Invalid parameter(s): start_date",
    "data": {
        "status": 400,
        "params": {
            "start_date": "Start date must be in the future"
        }
    }
}
```

## Type System Integration

### Automatic Type Coercion

URL parameters (query/path) receive automatic type conversion:

```php
// In OpenAPI_Schema::filter()
if ('Body' !== $type && $param instanceof Number) {
    // Convert string "123" to integer 123
    $data[$param_name] = $param instanceof Integer ?
        intval($data[$param_name]) :
        floatval($data[$param_name]);
}
```

### Parameter Validation

Each parameter type implements its own validation:

```php
class Positive_Integer extends Integer {
    public function validate($value): bool {
        return parent::validate($value) && $value > 0;
    }

    public function sanitize($value) {
        return max(1, intval($value));
    }
}
```

## Best Practices

### 1. Use Appropriate Collections

- **GET operations**: Use `QueryArgumentCollection`
- **POST/PUT/PATCH operations**: Use `RequestBodyCollection`
- **DELETE operations**: Use `QueryArgumentCollection`

### 2. Define Request Bodies with Definitions

```php
public function create_params(): RequestBodyCollection {
    $collection = new RequestBodyCollection();
    $definition = new Event_Request_Body_Definition();

    // Use Definition_Parameter for complex objects
    $collection[] = new Definition_Parameter($definition);

    return $collection
        ->set_required(true)
        ->set_example($definition->get_example());
}
```

### 3. Implement Custom Filtering When Needed

```php
protected function filter_update_params(array $params): array {
    // Apply business logic transformations
    if (isset($params['status']) && $params['status'] === 'draft') {
        $params['post_status'] = 'draft';
        unset($params['status']);
    }

    return $params;
}
```

### 4. Leverage Type Coercion

URL parameters automatically convert to proper types:

```php
// Request: GET /events?page=2&per_page=20
// After processing:
$params = [
    'page' => 2,        // Integer, not "2"
    'per_page' => 20,   // Integer, not "20"
];
```

### 5. Handle Nested Definitions

The system recursively processes nested definitions:

```php
// RequestBodyCollection::get_props_from_collection()
foreach ($collection as $parameter) {
    if ($parameter instanceof Definition_Parameter) {
        // Recursively extract properties from definition
        $collections = $parameter->get_collections();
        foreach ($collections as $collection) {
            $props = array_merge($props, $this->get_props_from_collection($collection));
        }
    }
}
```

## Testing Validation

### Unit Testing

```php
public function test_validation() {
    $endpoint = new Event();
    $schema = $endpoint->create_schema();

    // Test required field validation
    $this->expectException(InvalidRestArgumentException::class);
    $schema->filter_before_request([]); // Missing required fields

    // Test successful validation
    $result = $schema->filter_before_request([
        'title' => 'Test Event',
        'start_date' => '2024-12-01T10:00:00',
    ]);

    $this->assertIsArray($result);
}
```

### Integration Testing

```php
public function test_endpoint_validation() {
    $response = $this->post('/tec/v1/events', [
        // Missing required 'title'
        'start_date' => '2024-12-01',
    ]);

    $this->assertEquals(400, $response->get_status());
    $this->assertStringContainsString('Missing required parameter', $response->get_data()['message']);
}
```

## See Also

- [Parameter Types](parameter-types.md) - Available parameter types and collections
- [Interfaces](interfaces.md) - Endpoint interface definitions
- [Abstract Classes](abstract-classes.md) - Base class implementations
- [Creating Endpoints](../../creating-endpoints.md) - Step-by-step guide
