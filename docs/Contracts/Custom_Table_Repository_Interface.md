# Custom Table Repository Interface

This document describes the Custom Table Repository Interface (`TEC\Common\Contracts\Custom_Table_Repository_Interface`) that defines the contract for repositories working with custom database tables.

## Overview

The Custom Table Repository Interface extends the base `Repository_Interface` and adds specific requirements for repositories that manage models stored in custom database tables.

## Interface Definition

```php
namespace TEC\Common\Contracts;

interface Custom_Table_Repository_Interface extends Repository_Interface {
    /**
     * Gets the model class.
     *
     * @return class-string<Model> The model class.
     */
    public function get_model_class(): string;

    /**
     * Gets the schema.
     *
     * @return array The schema.
     */
    public function get_schema(): array;
}
```

## Method Specifications

### `get_model_class(): string`

**Purpose**: Return the fully qualified class name of the model this repository manages.

**Returns**: A string containing the model class name.

**Requirements**:

- Must return a valid class name that implements the `Model` interface
- The class must exist and be autoloadable
- Should be consistent throughout the repository's lifetime

**Usage**:

```php
class Event_Repository implements Custom_Table_Repository_Interface {
    public function get_model_class(): string {
        return Event_Model::class;
    }
}

// Usage
$repository = tribe(Event_Repository::class);
$model_class = $repository->get_model_class();
$new_model = new $model_class();
```

### `get_schema(): array`

**Purpose**: Return the schema definition array that maps query parameters to database operations.

**Returns**: An associative array where:

- Keys are parameter names used in queries
- Values are callable functions that transform parameters into query conditions

**Structure**:

```php
[
    'status' => function($value) {
        return [
            'column' => 'status',
            'value' => $value,
            'operator' => '='
        ];
    },
    'price_gt' => function($value) {
        return [
            'column' => 'price',
            'value' => $value,
            'operator' => '>'
        ];
    },
    'tags_in' => function($value) {
        // Complex relationship query logic
        return [...];
    }
]
```

**Usage**:

```php
public function get_schema(): array {
    return array_merge(
        $this->auto_generated_schema,  // From parent
        $this->custom_schema           // Custom additions
    );
}
```

## Inherited from Repository_Interface

As this interface extends `Repository_Interface`, implementations must also provide all methods from the parent interface:

### Query Methods

- `by($key, $value = null): self`
- `where($key, $value = null): self`
- `by_args(array $args): self`
- `page($page): self`
- `per_page($per_page): self`
- `offset($offset, $increment = false): self`
- `order($order = 'ASC'): self`
- `order_by($order_by, $order = 'DESC'): self`
- `fields($fields): self`
- `in($post_ids): self`
- `not_in($post_ids): self`
- `parent($post_id): self`
- `parent_in($post_ids): self`
- `parent_not_in($post_ids): self`
- `search($search): self`

### Retrieval Methods

- `all($return_generator = false)`
- `get_ids($return_generator = false)`
- `count(): int`
- `found(): int`
- `first()`
- `last()`
- `nth($n)`
- `take($n): array`
- `pluck($field): array`
- `filter($args = [], $operator = 'AND'): array`
- `sort($orderby = [], $order = 'ASC', $preserve_keys = false): array`
- `by_primary_key($primary_key)`

### Mutation Methods

- `set($key, $value): self`
- `set_args(array $update_map): self`
- `save($return_promise = false)`
- `create()`
- `delete($return_promise = false)`

## Implementation Example

```php
namespace TEC\Events\Repositories;

use TEC\Common\Contracts\Custom_Table_Repository_Interface;
use TEC\Common\Abstracts\Custom_Table_Repository;
use TEC\Events\Models\Event_Model;

class Event_Repository extends Custom_Table_Repository
    implements Custom_Table_Repository_Interface {

    /**
     * Get the model class name.
     *
     * @return class-string<Model>
     */
    public function get_model_class(): string {
        return Event_Model::class;
    }

    /**
     * Get the repository schema.
     *
     * @return array
     */
    public function get_schema(): array {
        // Return the schema built in constructor
        return $this->schema;
    }

    /**
     * Constructor to set up custom schema entries.
     */
    public function __construct() {
        parent::__construct();

        // Add custom schema entries
        $this->add_schema_entry('featured', function($value) {
            return [
                'column' => 'is_featured',
                'value' => $value ? 1 : 0,
                'operator' => '='
            ];
        });

        $this->add_schema_entry('date_range', function($dates) {
            if (!is_array($dates) || count($dates) !== 2) {
                return [];
            }

            // This would need custom handling in the query builder
            return [
                'column' => 'event_date',
                'value' => $dates,
                'operator' => 'BETWEEN'
            ];
        });

        // Set default query arguments
        $this->set_default_args([
            'status' => 'published'
        ]);
    }
}
```

## Usage Patterns

### Basic Repository Usage

```php
// Get the repository instance
$repository = tribe(Event_Repository::class);

// Verify it implements the interface
if (!$repository instanceof Custom_Table_Repository_Interface) {
    throw new Exception('Invalid repository type');
}

// Use the interface methods
$model_class = $repository->get_model_class();
$schema = $repository->get_schema();

// Perform queries
$events = $repository->by('status', 'published')
                     ->by('featured', true)
                     ->page(1)
                     ->per_page(10)
                     ->all();
```

### Schema Validation

```php
class Schema_Validator {
    public function validate(Custom_Table_Repository_Interface $repository): bool {
        $schema = $repository->get_schema();

        foreach ($schema as $key => $handler) {
            if (!is_callable($handler)) {
                throw new InvalidArgumentException(
                    "Schema entry '$key' must be callable"
                );
            }
        }

        return true;
    }
}
```

### Dynamic Model Creation

```php
class Model_Factory {
    public function create_from_repository(
        Custom_Table_Repository_Interface $repository,
        array $data
    ): Model {
        $model_class = $repository->get_model_class();

        if (!class_exists($model_class)) {
            throw new RuntimeException("Model class $model_class not found");
        }

        if (!is_subclass_of($model_class, Model::class)) {
            throw new RuntimeException("$model_class must implement Model interface");
        }

        return $model_class::from_array($data);
    }
}
```

## Best Practices

1. **Type Safety**: Always use type hints and return type declarations
2. **Schema Consistency**: Ensure schema entries return consistent structures
3. **Model Validation**: Verify the model class exists and implements the Model interface
4. **Documentation**: Document custom schema entries and their expected formats
5. **Error Handling**: Handle invalid schema entries gracefully

## Common Pitfalls

1. **Forgetting Parent Constructor**: Always call `parent::__construct()` in implementations
2. **Invalid Schema Callbacks**: Ensure all schema entries are callable
3. **Model Class Changes**: Keep model class references up to date
4. **Schema Conflicts**: Avoid overriding auto-generated schema entries unintentionally

## See Also

- [Custom Table Repository Abstract](../Abstracts/Custom_Table_Repository.md)
- [Update Repository Implementations](../Repositories/update-repository-implementations.md)
