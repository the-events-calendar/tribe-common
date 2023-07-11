# System Configuration

Provides a system-wide set of configuration values. Easily access feature flags, and other immutable configurations.

Inspired by systems that load configurations from various sources, like retrieving `conf.ini` or `.env` values.

## Setup

Add a configuration loader, so the system knows where to get the configuration values from.

```php
// Constants_Provider.php
class Constants_Provider implements Configuration_Provider_Interface {

	public function has( $key ): bool {
		return defined( $key );
	}

	public function get( $key ) {
		if ( $this->has( $key ) ) {

			return constant( $key );
		}

		return null;
	}

	public function all(): array {
		return get_defined_constants( false );
	}
}
```
```php
// Provider.php

class Provider {
	protected function register(): void {
		// Can add other loaders with other configuration values, such as local vs prod configurations.
		tribe( Configuration_Loader::class )->add( new Constants_Provider() )
	}
}
```

This is an extensible loader to allow various configuration sources and application specific logic to bind configuration providers in different ways.

## Retrieve Configuration Value

```php
// wp-config.php
define('TEC_FEATURE_FLAG', true);
```
```php
// Model.php
public function tec_magic() {
	// Feature enabled?
	if ( tribe( Configuration::class )->get( 'TEC_FEATURE_FLAG' ) ) {
		// do stuff...
	}
}
```