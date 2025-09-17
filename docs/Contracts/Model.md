# Model Contract

This document describes the Model interface (`TEC\Common\Contracts\Model`) that defines the contract for all models in the TEC Common system.

## Overview

The Model contract establishes the required methods that all model implementations must provide. It ensures consistent behavior across different model types and enables the repository system to work with models in a standardized way.

## Interface Definition

```php
namespace TEC\Common\Contracts;

use TEC\Common\Abstracts\Custom_Table_Abstract;

interface Model {
    // Core methods
    public function get_id(): int;
    public function set_id(int $id): void;
    public function save(): int;
    public function delete(): bool;
    public function get_table_interface(): Custom_Table_Abstract;
    public function to_array(): array;
    public static function from_array(array $data): self;

    // Relationship methods
    public function get_relationships(): array;
    public function add_id_to_relationship(string $key, int $id): void;
    public function remove_id_from_relationship(string $key, int $id): void;
    public function delete_relationship_data(string $key): void;
}
```

## Method Specifications

### Core Methods

#### `get_id(): int`

**Purpose**: Retrieve the model's unique identifier.

**Returns**: The integer ID of the model. Returns 0 if not set.

**Usage**:

```php
$id = $model->get_id();
if ($id > 0) {
    // Model has been saved
}
```

#### `set_id(int $id): void`

**Purpose**: Set the model's unique identifier.

**Parameters**:

- `$id` - The integer ID to assign to the model

**Usage**:

```php
$model->set_id(123);
```

#### `save(): int`

**Purpose**: Persist the model to the database.

**Returns**: The ID of the saved model.

**Behavior**:

- Performs an upsert operation (insert or update)
- Returns existing ID for updates
- Returns new ID for inserts
- Saves relationship data after main model

**Throws**: `RuntimeException` if the save operation fails

**Usage**:

```php
$id = $model->save();
echo "Model saved with ID: $id";
```

#### `delete(): bool`

**Purpose**: Remove the model from the database.

**Returns**: `true` if deletion was successful, `false` otherwise.

**Behavior**:

- Requires model to have an ID
- Cleans up all relationship data
- Removes the record from the database

**Throws**: `RuntimeException` if no ID is set

**Usage**:

```php
if ($model->delete()) {
    echo "Model deleted successfully";
}
```

#### `get_table_interface(): Custom_Table_Abstract`

**Purpose**: Get the custom table interface for this model.

**Returns**: An instance of the table interface class.

**Usage**:

```php
$table = $model->get_table_interface();
$columns = $table::get_columns();
```

#### `to_array(): array`

**Purpose**: Convert the model to an associative array.

**Returns**: Array representation of the model.

**Behavior**:

- Includes all table columns
- Calls appropriate getter methods
- Excludes empty UID fields

**Throws**: `RuntimeException` if a required getter method doesn't exist

**Usage**:

```php
$data = $model->to_array();
json_encode($data); // For API responses
```

#### `from_array(array $data): self`

**Purpose**: Create a model instance from an array.

**Parameters**:

- `$data` - Associative array of model data

**Returns**: A new model instance.

**Behavior**:

- Static factory method
- Calls setter methods for each field
- Marks model as saved (no changes)

**Throws**: `InvalidArgumentException` if a setter method doesn't exist

**Usage**:

```php
$data = ['title' => 'Event', 'status' => 'published'];
$model = Event_Model::from_array($data);
```

### Relationship Methods

#### `get_relationships(): array`

**Purpose**: Get all defined relationships for this model.

**Returns**: Array of relationship definitions.

**Structure**:

```php
[
    'venues' => [
        'type' => 'many_to_many',
        'through' => VenueEventTable::class,
        'entity' => 'post',
        'columns' => [
            'this' => 'event_id',
            'other' => 'venue_id'
        ]
    ]
]
```

#### `add_id_to_relationship(string $key, int $id): void`

**Purpose**: Add an ID to a many-to-many relationship.

**Parameters**:

- `$key` - The relationship identifier
- `$id` - The ID to add

**Behavior**:

- Validates relationship exists
- Queues change for batch saving
- Removes from delete queue if present

**Throws**: `InvalidArgumentException` if relationship doesn't exist

**Usage**:

```php
$model->add_id_to_relationship('venues', 123);
$model->add_id_to_relationship('venues', 456);
$model->save(); // Saves all relationship changes
```

#### `remove_id_from_relationship(string $key, int $id): void`

**Purpose**: Remove an ID from a many-to-many relationship.

**Parameters**:

- `$key` - The relationship identifier
- `$id` - The ID to remove

**Behavior**:

- Validates relationship exists
- Queues removal for batch processing
- Removes from insert queue if present

**Throws**: `InvalidArgumentException` if relationship doesn't exist

**Usage**:

```php
$model->remove_id_from_relationship('venues', 123);
$model->save(); // Processes the removal
```

#### `delete_relationship_data(string $key): void`

**Purpose**: Remove all data for a specific relationship.

**Parameters**:

- `$key` - The relationship identifier

**Behavior**:

- Validates relationship exists
- Removes all associated IDs
- For many-to-many, deletes junction table entries

**Throws**: `InvalidArgumentException` if relationship doesn't exist

**Usage**:

```php
$model->delete_relationship_data('venues');
// All venue associations are removed
```

## Implementation Requirements

When implementing this interface, ensure:

1. **Property Getters/Setters**: Implement `get_*` and `set_*` methods for all table columns
2. **Table Interface**: Return the correct Custom_Table_Abstract implementation
3. **Relationship Setup**: Define relationships in the constructor
4. **Type Safety**: Use proper type hints for all methods
5. **Error Handling**: Throw appropriate exceptions for error conditions

## Example Implementation

```php
use TEC\Common\Contracts\Model;
use TEC\Common\Abstracts\Custom_Table_Abstract;

class Product_Model implements Model {
    private int $id = 0;
    private string $name = '';
    private float $price = 0.0;

    public function get_id(): int {
        return $this->id;
    }

    public function set_id(int $id): void {
        $this->id = $id;
    }

    public function get_name(): string {
        return $this->name;
    }

    public function set_name(string $name): void {
        $this->name = $name;
    }

    public function get_price(): float {
        return $this->price;
    }

    public function set_price(float $price): void {
        $this->price = max(0, $price); // Validation
    }

    public function save(): int {
        // Implementation specific to your needs
        // Usually delegates to Model_Abstract
    }

    public function delete(): bool {
        // Implementation specific to your needs
        // Usually delegates to Model_Abstract
    }

    public function get_table_interface(): Custom_Table_Abstract {
        return tribe(Product_Table::class);
    }

    public function to_array(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price
        ];
    }

    public static function from_array(array $data): self {
        $model = new self();

        if (isset($data['id'])) {
            $model->set_id($data['id']);
        }
        if (isset($data['name'])) {
            $model->set_name($data['name']);
        }
        if (isset($data['price'])) {
            $model->set_price($data['price']);
        }

        return $model;
    }

    public function get_relationships(): array {
        return []; // No relationships for this example
    }

    public function add_id_to_relationship(string $key, int $id): void {
        // Implementation if relationships exist
    }

    public function remove_id_from_relationship(string $key, int $id): void {
        // Implementation if relationships exist
    }

    public function delete_relationship_data(string $key): void {
        // Implementation if relationships exist
    }
}
```

## See Also

- [Model Abstract Implementation](../Abstracts/Model_Abstract.md)
- [Custom Table Repository Interface](./Custom_Table_Repository_Interface.md)
- [Custom Table Abstract](../Abstracts/Custom_Table_Abstract.md)
