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

## Notes

- Callbacks execute in registration order
- Suite names must match Codeception suite configuration
- All callbacks receive no parameters
- Exceptions in callbacks halt suite execution
