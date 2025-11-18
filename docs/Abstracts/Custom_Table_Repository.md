# Custom Table Repository Contract and Abstract

This document describes the Custom Table Repository system in TEC Common, which provides a query builder and data access layer for custom database tables.

## Overview

The Custom Table Repository system consists of:

- **Repository Interface** (`TEC\Common\Contracts\Custom_Table_Repository_Interface`) - Extends the base Repository Interface
- **Repository Abstract** (`TEC\Common\Abstracts\Custom_Table_Repository`) - The comprehensive implementation

## Repository Contract Interface

The Custom Table Repository Interface extends the base Repository Interface and adds:

### Required Methods

#### `get_model_class(): string`

Returns the fully qualified class name of the associated model.

#### `get_schema(): array`

Returns the repository's schema definition for query building.

## Repository Abstract Implementation

The abstract class provides a complete query builder with automatic schema generation, relationship handling, and fluent interface.

### Key Features

1. **Automatic Schema Generation** - Creates query filters for all table columns
2. **Operator Support** - Multiple comparison operators per column
3. **Relationship Queries** - Automatic support for many-to-many relationships
4. **Fluent Interface** - Chainable methods for building complex queries
5. **Generator Support** - Memory-efficient iteration over large result sets
6. **Batch Operations** - Update multiple records efficiently

### Constructor Initialization

The constructor automatically initializes the repository with default ordering, schema generation for all table columns with supported operators, and relationship-based filters for many-to-many relationships:

## Query Building Methods

### Filtering Methods

#### `by(string $key, $value = null): self`

Add a filter condition to the query.

```php
$repository->by('status', 'active')
           ->by('price_gt', 100);
```

#### `where(string $key, $value = null): self`

Alias for `by()` method.

#### `by_args(array $args): self`

Add multiple filter conditions at once.

```php
$repository->by_args([
    'status' => 'active',
    'category_in' => [1, 2, 3]
]);
```

### Pagination Methods

#### `page(int $page): self`

Set the page number for paginated results.

#### `per_page(int $per_page): self`

Set the number of results per page.

#### `offset(int $offset): self`

Set the query offset directly.

```php
$results = $repository->page(2)
                      ->per_page(20)
                      ->all();
```

### Ordering Methods

#### `order(string $order): self`

Set the sort direction ('ASC' or 'DESC').

#### `order_by(string $order_by, string $order = 'DESC'): self`

Set the column to order by and optionally the direction.

```php
$repository->order_by('created_at', 'DESC');
```

### Field Selection

#### `fields($fields): self`

Specify which fields to retrieve.

```php
// Get only IDs
$ids = $repository->fields('id')->all();

// Get multiple fields (returns associative arrays)
$data = $repository->fields(['id', 'name', 'email'])->all();
```

## Data Retrieval Methods

### Getting Results

#### `all(bool $return_generator = false, int $batch_size = 50)`

Retrieve all matching records.

```php
// Get array of models
$models = $repository->all();

// Get generator for memory efficiency
foreach ($repository->all(true, 100) as $model) {
    // Process each model
}
```

#### `first(): ?Model`

Get the first matching record.

#### `last(): ?Model`

Get the last matching record (reverses order).

#### `nth(int $n): ?Model`

Get the nth matching record.

#### `take(int $n): array`

Get the first n matching records.

```php
$first = $repository->by('status', 'active')->first();
$last = $repository->by('status', 'active')->last();
$fifth = $repository->by('status', 'active')->nth(5);
$top10 = $repository->by('status', 'active')->take(10);
```

### Specialized Retrievals

#### `get_ids(bool $return_generator = false, int $batch_size = 50)`

Get only the IDs of matching records.

```php
$ids = $repository->by('status', 'active')->get_ids();
```

#### `pluck(string $field): array`

Extract a single field from all matching records.

```php
$emails = $repository->by('status', 'active')->pluck('email');
```

#### `by_primary_key($primary_key)`

Get a single model by its primary key.

```php
$model = $repository->by_primary_key(123);
```

### Counting and Metadata

#### `count(): int`

Get the count of matching records (respects per_page limit).

#### `found(): int`

Get the total count of matching records (ignores pagination).

```php
$repository->by('status', 'active')->per_page(10);
$count = $repository->count();  // Returns max 10
$total = $repository->found();  // Returns total matching records
```

## Data Manipulation Methods

### Creating Records

#### `set(string $key, $value): self`

Set a field value for creation/update.

#### `set_args(array $update_map): self`

Set multiple field values.

#### `create(): ?Model`

Create a new record with the set values.

```php
$model = $repository->set('name', 'John Doe')
                   ->set('email', 'john@example.com')
                   ->set('tags', [1, 2, 3])  // Relationship
                   ->create();
```

### Updating Records

#### `save(bool $return_promise = false)`

Update all matching records with the set values.

```php
// Update all active users
$updated = $repository->by('status', 'active')
                      ->set('last_seen', date('Y-m-d H:i:s'))
                      ->save();
```

### Deleting Records

#### `delete(bool $return_promise = false)`

Delete all matching records.

```php
// Delete inactive users
$deleted = $repository->by('status', 'inactive')
                      ->by('created_at_lt', '2020-01-01')
                      ->delete();
```

## Relationship Queries

For models with many-to-many relationships, the repository automatically creates filters:

```php
// Find events with specific venues
$events = $repository->by('venues', [1, 2, 3])->all();
$events = $repository->by('venues_in', [1, 2, 3])->all();

// Find events without specific venues
$events = $repository->by('venues_not_in', [4, 5, 6])->all();
```

## Advanced Features

### Schema Customization

Add custom filter logic:

```php
$repository->add_schema_entry('custom_filter', function($value) {
    return [
        'column' => 'status',
        'value' => $value ? 'active' : 'inactive',
        'operator' => '='
    ];
});
```

### Field Aliases

Map friendly names to database columns:

```php
$repository->add_update_field_alias('id', 'event_id');
```

### Default Arguments

Set default filters for all queries:

```php
$repository->set_default_args([
    'status' => 'published',
    'visibility' => 'public'
]);
```

### Filter Method

Filter results in memory (post-query):

```php
$filtered = $repository->filter(['status' => 'active'], 'AND');
```

## Example Repository Implementation

```php
use TEC\Common\Abstracts\Custom_Table_Repository;

class Event_Repository extends Custom_Table_Repository {

    public function get_model_class(): string {
        return Event_Model::class;
    }

    public function __construct() {
        parent::__construct();

        // Set default filters
        $this->set_default_args([
            'status' => 'published'
        ]);

        // Add custom schema entries
        $this->add_schema_entry('upcoming', function($value) {
            if (!$value) {
                return [];
            }

            return [
                'column' => 'start_date',
                'value' => date('Y-m-d H:i:s'),
                'operator' => '>='
            ];
        });

        // Add field aliases for updates
        $this->add_update_field_alias('organizer', 'organizer_id');
    }

    // Custom repository methods
    public function find_upcoming_by_venue(int $venue_id): array {
        return $this->by('venues', $venue_id)
                    ->by('upcoming', true)
                    ->order_by('start_date', 'ASC')
                    ->all();
    }
}
```

## Usage Examples

### Basic Queries

```php
$repository = tribe(Event_Repository::class);

// Get all published events (uses default args)
$events = $repository->all();

// Get events for a specific date range
$events = $repository->by('start_date_gte', '2024-01-01')
                     ->by('start_date_lte', '2024-12-31')
                     ->all();

// Complex query with relationships
$events = $repository->by('status', 'published')
                     ->by('venues_in', [1, 2, 3])
                     ->by('price_lt', 100)
                     ->order_by('start_date', 'ASC')
                     ->page(1)
                     ->per_page(20)
                     ->all();
```

### Batch Operations

```php
// Mark all past events as archived
$repository->by('end_date_lt', date('Y-m-d'))
           ->set('status', 'archived')
           ->save();

// Delete all draft events older than 30 days
$repository->by('status', 'draft')
           ->by('created_at_lt', date('Y-m-d', strtotime('-30 days')))
           ->delete();
```

### Memory-Efficient Processing

```php
// Process large datasets without loading all into memory
foreach ($repository->by('status', 'pending')->all(true, 100) as $model) {
    // Process each model
    $model->process();
    $model->save();

    // Memory is freed after each batch of 100
}
```

## Performance Optimization

1. **Use Generators** for large datasets to avoid memory issues
2. **Specify fields** when you don't need the full model
3. **Use batch_size parameter** to control memory usage
4. **Add indexes** to frequently queried columns
5. **Use field selection** for read-only operations

## Error Handling

The repository throws exceptions for:

- `RuntimeException` - Unsupported operations or save failures
- `InvalidArgumentException` - Invalid method calls or parameters

## Limitations

Some methods from the base Repository Interface are not supported:

- `in()` and `not_in()` - Use column-specific operators instead
- `parent()`, `parent_in()`, `parent_not_in()` - Not applicable to custom tables
- `sort()` - Use `order_by()` instead
- `collect()` - Not implemented

## See Also

- [Custom Table Repository Interface](../Contracts/Custom_Table_Repository_Interface.md)
- [Update Repository Implementations](../Repositories/update-repository-implementations.md)
