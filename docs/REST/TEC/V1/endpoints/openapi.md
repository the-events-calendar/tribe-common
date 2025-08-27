# OpenAPI Documentation Endpoint

The OpenAPI endpoint provides machine-readable API documentation following the OpenAPI 3.0 specification.

## Overview

- **Path**: `/docs`
- **Class**: `TEC\Common\REST\TEC\V1\Endpoints\OpenApiDocs`
- **Interface**: `Readable_Endpoint`
- **Operations**: GET

## GET /docs

Retrieve the complete OpenAPI 3.0 specification for the TEC REST API.

### Parameters

None

### Response

Returns a JSON object containing the full OpenAPI specification.

#### Example Request

```bash
GET /wp-json/tec/v1/docs
```

#### Example Response (Excerpt)

```json
{
    "openapi": "3.0.0",
    "info": {
        "title": "The Events Calendar REST API",
        "version": "1.0.0",
        "description": "REST API for The Events Calendar plugin",
        "contact": {
            "name": "The Events Calendar",
            "url": "https://theeventscalendar.com"
        }
    },
    "servers": [
        {
            "url": "https://example.com/wp-json/tec/v1",
            "description": "Production server"
        }
    ],
    "paths": {
        "/events": {
            "get": {
                "summary": "Retrieve Events",
                "description": "Returns a list of events",
                "operationId": "getEvents",
                "tags": ["Events"],
                "parameters": [...],
                "responses": {...}
            },
            "post": {
                "summary": "Create an Event",
                "description": "Creates a new event",
                "operationId": "createEvent",
                "tags": ["Events"],
                "requestBody": {...},
                "responses": {...}
            }
        }
    },
    "components": {
        "schemas": {
            "Event": {...},
            "Venue": {...},
            "Organizer": {...}
        }
    }
}
```

## Features

### Complete API Documentation

- All endpoints with their operations
- Request/response schemas
- Parameter definitions
- Authentication requirements

### OpenAPI 3.0 Compliance

- Standard format for API documentation
- Compatible with Swagger UI
- Supports code generation tools
- Machine-readable specification

### Dynamic Generation

- Documentation updates automatically
- Reflects current endpoint configuration
- Includes custom endpoints

## Usage

### Swagger UI Integration

You can use the OpenAPI spec with Swagger UI:

```html
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swagger-ui-dist@4/swagger-ui.css">
</head>
<body>
    <div id="swagger-ui"></div>
    <script src="https://cdn.jsdelivr.net/npm/swagger-ui-dist@4/swagger-ui-bundle.js"></script>
    <script>
        SwaggerUIBundle({
            url: "https://yoursite.com/wp-json/tec/v1/docs",
            dom_id: '#swagger-ui'
        });
    </script>
</body>
</html>
```

### Code Generation

Use the OpenAPI spec to generate client libraries:

```bash
# Generate PHP client
openapi-generator generate \
    -i https://yoursite.com/wp-json/tec/v1/docs \
    -g php \
    -o ./generated-client

# Generate JavaScript client
openapi-generator generate \
    -i https://yoursite.com/wp-json/tec/v1/docs \
    -g javascript \
    -o ./generated-client
```

### API Testing

Import the spec into API testing tools:

- Postman (Import > Link > Enter URL)
- Insomnia (Import > From URL)
- Paw (File > Import > OpenAPI 3.0)

## Components

### Paths

All registered endpoints with their operations:
- `/events` - Events collection
- `/events/{id}` - Single event
- `/venues` - Venues collection
- `/venues/{id}` - Single venue
- `/organizers` - Organizers collection
- `/organizers/{id}` - Single organizer

### Schemas

Data structure definitions:
- `Event` - Event object schema
- `Venue` - Venue object schema
- `Organizer` - Organizer object schema
- Common types and definitions

### Security Schemes

Authentication methods:

- Basic authentication
- Application passwords

## Customization

### Adding Custom Documentation

Endpoints can customize their OpenAPI documentation:

```php
public function read_schema(): OpenAPI_Schema {
    $schema = new OpenAPI_Schema(
        fn() => __( 'Get Events', 'text-domain' ),
        fn() => __( 'Returns a list of events', 'text-domain' ),
        'getEvents',
        [ 'Events' ],  // Tags
        $this->read_params()
    );

    // Add custom documentation
    $schema->add_example( 'default', [
        'id' => 123,
        'title' => 'Sample Event'
    ]);

    return $schema;
}
```

### Filtering the Specification

```php
add_filter( 'tec_rest_openapi_spec', function( $spec ) {
    // Modify the specification
    $spec['info']['x-custom'] = 'Custom value';

    return $spec;
} );
```

## Benefits

1. **Developer Experience** - Interactive documentation
2. **Client Generation** - Auto-generate SDKs
3. **Testing** - Import into testing tools
4. **Validation** - Ensure API compliance
5. **Discovery** - Explore available endpoints

## Related Tools

- [Swagger Editor](https://editor.swagger.io/) - Edit and validate specs
- [OpenAPI Generator](https://openapi-generator.tech/) - Generate clients
- [Redoc](https://github.com/Redocly/redoc) - Alternative documentation UI
