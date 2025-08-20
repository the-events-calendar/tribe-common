# REST API Controller

The Controller class manages REST API initialization and endpoint registration.

## Class

`TEC\Common\REST\TEC\V1\Controller`

## Location

`/wp-content/plugins/the-events-calendar/common/src/Common/REST/TEC/V1/Controller.php`

## Overview

The Controller is responsible for:

- REST API namespace management
- Endpoint registration coordination
- API versioning
- Hook management

## Key Methods

### `get_versioned_namespace()`

Returns the versioned namespace for the REST API.

```php
public static function get_versioned_namespace(): string {
    return 'tec/v1';
}
```

### `get_namespace()`

Returns the base namespace without version.

```php
public static function get_namespace(): string {
    return 'tec';
}
```

### `register()`

Main registration method that hooks into WordPress.

```php
public function register(): void {
    add_action( 'rest_api_init', [ $this, 'register_endpoints' ] );
}
```

## Usage

The controller is typically registered early in the plugin lifecycle:

```php
// In your main plugin file or service provider
add_action( 'plugins_loaded', function() {
    tribe( Controller::class )->register();
} );
```

## Endpoint Registration

The controller delegates endpoint registration to plugin-specific endpoint controllers:

```php
// Each plugin has its own Endpoints controller
tribe( 'tec.rest-api.v1.endpoints' )->register();
```

## Versioning Strategy

The API uses semantic versioning:

- Current version: `v1`
- Full namespace: `tec/v1`
- Future versions would be `tec/v2`, etc.
