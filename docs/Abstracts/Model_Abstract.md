# Model Contract and Abstract

This document describes the Model system in TEC Common, which provides a robust framework for creating and managing data models with support for relationships, custom tables, and automatic change tracking.

## Overview

The Model system consists of two main components:

- **Model Contract** (`TEC\Common\Contracts\Model`) - The interface defining the required methods
- **Model Abstract** (`TEC\Common\Abstracts\Model_Abstract`) - The base implementation

## Model Contract Interface

The Model contract defines the essential methods that all models must implement:

### Core Methods

#### `get_id(): int`

Returns the model's unique identifier.

#### `set_id(int $id): void`

Sets the model's unique identifier.

#### `save(): int`

Persists the model to the database. Returns the ID of the saved model.

- Only performs database operations if the model has changes
- Automatically handles both insert and update operations
- Saves relationship data after the main model

#### `delete(): bool`

Removes the model from the database.

- Returns true if deletion was successful
- Automatically cleans up relationship data
- Throws RuntimeException if ID is not set

#### `get_table_interface(): Custom_Table_Abstract`

Returns the custom table interface associated with this model.

#### `to_array(): array`

Converts the model to an associative array.

- Uses the table schema to determine which fields to include
- Automatically calls getter methods for each field
- Removes empty UID fields

#### `from_array(array $data): self`

Creates a model instance from an array of data.

- Static factory method
- Automatically calls setter methods for each field
- Marks the model as saved (no changes) after creation

### Relationship Methods

#### `get_relationships(): array`

Returns an array of defined relationships for the model.

#### `add_id_to_relationship(string $key, int $id): void`

Adds an ID to a many-to-many relationship.

- Validates that the relationship exists
- Tracks changes for batch saving

#### `remove_id_from_relationship(string $key, int $id): void`

Removes an ID from a many-to-many relationship.

- Validates that the relationship exists
- Tracks changes for batch saving

#### `delete_relationship_data(string $key): void`

Completely removes all data for a specific relationship.

## Model Abstract Implementation

The abstract class provides the base implementation with several powerful features:

### Relationship Types

The abstract supports four types of relationships:

```php
const RELATIONSHIP_TYPE_ONE_TO_ONE = 'one_to_one';
const RELATIONSHIP_TYPE_ONE_TO_MANY = 'one_to_many';
const RELATIONSHIP_TYPE_MANY_TO_ONE = 'many_to_one';
const RELATIONSHIP_TYPE_MANY_TO_MANY = 'many_to_many';
```

Currently, many-to-many relationships are fully implemented with automatic handling.

### Change Tracking

The model automatically tracks changes by comparing current values with the last saved state:

```php
private array $db_data = [];  // Stores the last saved state

private function has_changes(): bool {
    return $this->db_data !== $this->to_array();
}

private function mark_saved(): void {
    $this->db_data = $this->to_array();
}
```

This ensures database operations only occur when necessary.

### Magic Methods

The abstract provides magic `__call` method for handling many-to-many relationships:

```php
// Getter example
$tags = $model->get_tags();  // Returns array of tag IDs

// Setter example
$model->set_tags([1, 2, 3]);  // Sets the tags relationship
$model->set_tags(null);       // Clears all tags
```

### Protected Methods for Child Classes

#### `set_relationship(string $key, string $type, ?string $through = null, string $relationship_entity = 'post'): void`

Defines a relationship for the model.

- `$key` - The relationship identifier
- `$type` - One of the RELATIONSHIP_TYPE constants
- `$through` - The junction table class for many-to-many relationships
- `$relationship_entity` - The type of related entity (default: 'post')

#### `set_relationship_columns(string $key, string $this_entity_column, string $other_entity_column): void`

Specifies the column names used in the junction table for many-to-many relationships.

## Example Implementation

```php
use TEC\Common\Abstracts\Model_Abstract;

class Event_Model extends Model_Abstract {
    private string $title = '';
    private string $description = '';
    private array $venue_ids = [];

    public function __construct() {
        // Define a many-to-many relationship with venues
        $this->set_relationship(
            'venues',
            self::RELATIONSHIP_TYPE_MANY_TO_MANY,
            Event_Venue_Table::class
        );

        // Set the junction table columns
        $this->set_relationship_columns(
            'venues',
            'event_id',
            'venue_id'
        );
    }

    // Required: Define the table interface
    public function get_table_interface(): Custom_Table_Abstract {
        return tribe( Event_Table::class );
    }

    // Getters and setters for properties
    public function get_title(): string {
        return $this->title;
    }

    public function set_title(string $title): void {
        $this->title = $title;
    }

    public function get_description(): string {
        return $this->description;
    }

    public function set_description(string $description): void {
        $this->description = $description;
    }
}
```

## Usage Examples

### Creating a New Model

```php
$event = new Event_Model();
$event->set_title('Workshop');
$event->set_description('A hands-on workshop');
$event->add_id_to_relationship('venues', 123);
$event->add_id_to_relationship('venues', 456);
$id = $event->save();
```

### Loading from Database

```php
// Assuming you have the data from the table
$data = Event_Table::get_by_id(1);
$event = Event_Model::from_array($data);

// Model is marked as saved - no database hit on save() unless changed
$event->save(); // No-op if no changes
```

### Managing Relationships

```php
// Add venues to an event
$event->add_id_to_relationship('venues', 789);

// Remove a venue
$event->remove_id_from_relationship('venues', 123);

// Get all venue IDs (using magic method)
$venue_ids = $event->get_venues();

// Replace all venues (using magic method)
$event->set_venues([100, 200, 300]);

// Clear all venues
$event->delete_relationship_data('venues');
```

### Batch Operations

```php
// The model tracks all relationship changes
$event->add_id_to_relationship('venues', 1);
$event->add_id_to_relationship('venues', 2);
$event->remove_id_from_relationship('venues', 3);

// All changes are saved in a single transaction
$event->save();
```

## Best Practices

1. **Always define getter/setter methods** for all table columns
2. **Use type hints** in getter/setter methods for better IDE support
3. **Initialize relationships in constructor** to ensure they're available
4. **Let the abstract handle change tracking** - don't bypass it
5. **Use relationship methods** for many-to-many instead of direct database queries
6. **Validate data in setters** to ensure data integrity

## Error Handling

The model system throws specific exceptions:

- `RuntimeException` - When required methods don't exist, save fails, or delete lacks ID
- `InvalidArgumentException` - When invalid data is passed to methods
- `BadMethodCallException` - When calling undefined magic methods

## Performance Considerations

1. **Change tracking prevents unnecessary saves** - The model only writes to the database when data actually changes
2. **Relationship batching** - All relationship changes are saved together, reducing database queries
3. **Lazy loading** - Relationship data is only loaded when accessed
4. **Efficient deletes** - Relationship cleanup happens automatically on model deletion

## See Also

- [Model Contract](../Contracts/Model.md)
- [Custom Table Repository Abstract](./Custom_Table_Repository.md)
- [Custom Table Repository Interface](../Contracts/Custom_Table_Repository_Interface.md)
- [Update Repository Implementations](../Repositories/update-repository-implementations.md)
