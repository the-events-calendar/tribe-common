# Suite_Env Extension

Codeception extension for suite-specific environment setup.

## Usage

1. Add to the main Codeception configuration file (e.g. `codeception.dist.yml`):

```yaml
extensions:
    enabled:
      - TEC\Common\Tests\Extensions\Suite_Env
```

2. Define callbacks in the main bootstrap file:

```php
use TEC\Common\Tests\Extensions\Suite_Env;

Suite_Env::before('my_suite', fn() => putenv('MY_FEATURE_DISABLED=1'));
```

## Event Lifecycle

1. **module_init** - Before modules load.
   * If using the `WPLoader` module: WordPress code will **not** have loaded yet, this is the moment to set up env vars controlling features.
2. **init** - After modules load and initialization, before actor creation.
	* If using the `WPLoader` module: WordPress code has been loaded by this point.
3. **before** - Before suite execution.
4. **after** - After suite execution

## Examples

### Environment Variables

```php
// Single suite
Suite_Env::module_init('my_suite', fn() => putenv('FEATURE_X_ENABLED=1'));

// Multiple suites sharing configuration
$enable_beta = fn() => putenv('BETA_FEATURES=1');
Suite_Env::module_init('my_suite_one', $enable_beta);
Suite_Env::module_init('my_suite_two', $enable_beta);
```

### Feature Flags

```php
Suite_Env::before('my_suite', function() {
    putenv('FEATURE_A=enabled');
    putenv('FEATURE_B=disabled');
    putenv('FEATURE_C_VARIANT=experimental');
});

Suite_Env::after('integration', fn() => putenv('FEATURE_A='));
```

### Multiple Callbacks

```php
Suite_Env::before('my_suite',
    fn() => define('DEBUG_MODE', true),
    fn() => define('VERBOSE_LOGGING', true),
    fn() => define('CACHE_ENABLED', false)
);
```

### Class Methods

```php
class TestConfiguration {
    public static function enableFeatures() {
        define('NEW_UI_ENABLED', true);
        define('LEGACY_MODE', false);
    }
}

Suite_Env::init('functional', [TestConfiguration::class, 'enableFeatures']);
```

### Conditional Setup

```php
Suite_Env::module_init('smoke', function() {
    if (getenv('CI')) {
        putenv('STRICT_MODE=1');
        putenv('ERROR_REPORTING=E_ALL');
    }
});
```

### Runtime Configuration

```php
Suite_Env::init('api', function() {
    // Set API-specific configuration after modules load
    define('API_VERSION', 'v2');
    define('RATE_LIMITING', false);
});
```

### Complex Initialization

```php
$setup_features = function() {
    define('FEATURE_FLAGS', json_encode([
        'new_search' => true,
        'beta_ui' => false,
        'experimental_cache' => true
    ]));
};

$configure_mocks = function() {
    file_put_contents('/tmp/feature-config.json', json_encode([
        'enabled_features' => ['search', 'export'],
        'disabled_features' => ['import']
    ]));
};

Suite_Env::before('e2e', $setup_features, $configure_mocks);
Suite_Env::after('e2e', fn() => unlink('/tmp/feature-config.json'));
```

### Suite-Specific Constants

```php
Suite_Env::module_init('unit', function() {
    define('UNIT_TEST_MODE', true);
    define('EXTERNAL_API_CALLS', false);
});

Suite_Env::module_init('integration', function() {
    define('INTEGRATION_TEST_MODE', true);
    define('USE_MOCK_SERVICES', true);
});
```

## Toggle Features

The `toggle_features` method provides a declarative way to manage feature flags across test suites. It handles the common pattern of disabling features by default and enabling them only for specific suites.

### How It Works

Each feature configuration includes:
- `disable_env_var`: The environment variable that controls the feature (when set to `1`, the feature is disabled)
- `enabled_by_default`: Whether the feature should be enabled when running most test suites
- `active_for_suites`: (optional) Array of suite names where the feature should be explicitly enabled

The method:
1. Sets the environment variable based on `enabled_by_default` (disabled features get `ENVVAR=1`)
2. Registers `module_init` callbacks for each suite in `active_for_suites` to enable the feature

### Basic Usage

```php
use TEC\Common\Tests\Extensions\Suite_Env;

Suite_Env::toggle_features( [
    'My Feature' => [
        'disable_env_var'    => 'MY_FEATURE_DISABLED',
        'enabled_by_default' => false,
        'active_for_suites'  => [
            'feature_integration',
            'feature_acceptance',
        ],
    ],
] );
```

This will:
- Set `MY_FEATURE_DISABLED=1` by default (feature off for most suites)
- Set `MY_FEATURE_DISABLED=0` when running `feature_integration` or `feature_acceptance` suites

### Multiple Features

```php
Suite_Env::toggle_features( [
    'Custom Tables v1' => [
        'disable_env_var'    => 'TEC_CUSTOM_TABLES_V1_DISABLED',
        'enabled_by_default' => false,
        'active_for_suites'  => [
            'ct1_integration',
            'ct1_migration',
            'ct1_multisite_integration',
            'ct1_wp_json_api',
            'classy_integration',
        ],
    ],
    'Classy Editor'    => [
        'disable_env_var'    => 'TEC_CLASSY_EDITOR_DISABLED',
        'enabled_by_default' => false,
        'active_for_suites'  => [
            'classy_integration'
        ],
    ],
] );
```

### Feature Enabled by Default

For features that should be on for most suites but disabled for specific ones:

```php
Suite_Env::toggle_features( [
    'Legacy Mode' => [
        'disable_env_var'    => 'LEGACY_MODE_DISABLED',
        'enabled_by_default' => true,  // Feature is ON by default
        // No active_for_suites needed - feature runs everywhere
    ],
] );
```

### Combining with Manual Callbacks

`toggle_features` uses `module_init` internally, so it works alongside manually registered callbacks. If you register callbacks for the same suite, they will all execute:

```php
// First, toggle features
Suite_Env::toggle_features( [
    'My Feature' => [
        'disable_env_var'    => 'MY_FEATURE_DISABLED',
        'enabled_by_default' => false,
        'active_for_suites'  => [ 'my_suite' ],
    ],
] );

// Additional setup for the same suite
Suite_Env::module_init( 'my_suite', fn() => putenv( 'ADDITIONAL_CONFIG=1' ) );
```

## Notes

- **Important**: All `Suite_Env` methods (`module_init`, `init`, `before`, `after`, `toggle_features`) must be called in the main Codeception bootstrap file (e.g., `tests/_bootstrap.php`), not in suite-specific bootstrap files
- Callbacks execute in registration order
- Suite names must match Codeception suite configuration
- All callbacks receive no parameters
- Exceptions in callbacks halt suite execution
