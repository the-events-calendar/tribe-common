# Update Repository Implementations

This document describes how repositories handle updates in the TEC Common system, covering both the Custom Table Repository pattern and traditional WordPress post-based repositories.

## Overview

The repository system provides two main approaches for handling updates:

1. **Custom Table Repositories** - For custom database tables using the Model system
2. **Post-Based Repositories** - For WordPress post types extending `Tribe__Repository`

## Custom Table Repository Updates

### Update Field Aliases

The Custom Table Repository system uses field aliases to map friendly names to database columns:

```php
class Event_Repository extends Custom_Table_Repository {
    public function __construct() {
        parent::__construct();

        // Map friendly names to actual columns
        $this->add_update_field_alias('id', 'event_id');
        $this->add_update_field_alias('organizer', 'organizer_id');
        $this->add_update_field_alias('start', 'start_datetime');
    }
}
```

### Single Record Updates

Update a single record by ID:

```php
$repository = tribe(Event_Repository::class);

// Get the model
$event = $repository->by_primary_key(123);

// Update using model methods
$event->set_title('Updated Title');
$event->set_status('published');
$event->save();
```

### Batch Updates

Update multiple records matching criteria:

```php
// Update all draft events to published
$repository->by('status', 'draft')
           ->set('status', 'published')
           ->set('published_at', date('Y-m-d H:i:s'))
           ->save();

// Update with multiple conditions
$repository->by('category', 'workshop')
           ->by('price_gt', 100)
           ->set('discount', 0.10)
           ->set('updated_at', date('Y-m-d H:i:s'))
           ->save();
```

### Relationship Updates

Update many-to-many relationships:

```php
// Add venues to an event
$event = $repository->by_primary_key(123);
$event->add_id_to_relationship('venues', 456);
$event->add_id_to_relationship('venues', 789);
$event->save();

// Replace all relationships
$repository->by_primary_key(123)
           ->set('venues', [100, 200, 300])
           ->save();

// Clear relationships
$event->delete_relationship_data('venues');
$event->save();
```

### Update Methods Reference

#### `set(string $key, $value): self`

Set a single field for update:

```php
$repository->set('status', 'active');
```

#### `set_args(array $update_map): self`

Set multiple fields at once:

```php
$repository->set_args([
    'status' => 'active',
    'updated_by' => get_current_user_id(),
    'updated_at' => current_time('mysql')
]);
```

#### `save(bool $return_promise = false)`

Execute the update operation:

```php
// Synchronous update
$updated_ids = $repository->by('status', 'pending')
                          ->set('status', 'processing')
                          ->save();

// Asynchronous update (returns Promise)
$promise = $repository->by('status', 'pending')
                      ->set('status', 'processing')
                      ->save(true);
```

## Post-Based Repository Updates

For repositories extending `Tribe__Repository` (like Order_Repository):

### Update Field Aliases Configuration

```php
class Order_Repository extends Tribe__Repository {
    public function __construct() {
        parent::__construct();

        // Configure update field aliases for meta fields
        $this->update_fields_aliases = array_merge(
            $this->update_fields_aliases,
            [
                'gateway'              => Order::$gateway_meta_key,
                'gateway_order_id'     => Order::$gateway_order_id_meta_key,
                'total_value'          => Order::$total_value_meta_key,
                'currency'             => Order::$currency_meta_key,
                'purchaser_email'      => Order::$purchaser_email_meta_key,
                'status'               => 'post_status',  // Map to post field
            ]
        );
    }
}
```

### Single Order Update

```php
$repository = tribe(Order_Repository::class);

// Update by ID
$updated = $repository->by_primary_key(123)
                      ->set('status', 'completed')
                      ->set('gateway_order_id', 'stripe_xyz123')
                      ->set('completed_at', date('Y-m-d H:i:s'))
                      ->save();
```

### Batch Order Updates

```php
// Update all pending orders older than 1 hour
$repository->by('status', 'pending')
           ->by('created_date_lt', date('Y-m-d H:i:s', strtotime('-1 hour')))
           ->set('status', 'expired')
           ->save();

// Update orders for a specific gateway
$repository->by('gateway', 'stripe')
           ->by('currency', 'USD')
           ->set('processing_fee', 2.9)
           ->save();
```

## Advanced Update Patterns

### Conditional Updates

Update only if certain conditions are met:

```php
class Event_Repository extends Custom_Table_Repository {
    public function publish_if_ready(): array {
        $published = [];

        foreach ($this->by('status', 'draft')->all(true) as $event) {
            if ($event->is_ready_to_publish()) {
                $event->set_status('published');
                $event->set_published_at(date('Y-m-d H:i:s'));
                $event->save();
                $published[] = $event->get_id();
            }
        }

        return $published;
    }
}
```

### Transaction-Like Updates

Group related updates:

```php
class Order_Service {
    public function complete_order(int $order_id): bool {
        $order_repo = tribe(Order_Repository::class);
        $ticket_repo = tribe(Ticket_Repository::class);

        try {
            // Update order status
            $order = $order_repo->by_primary_key($order_id);
            $order->set('status', 'completed');
            $order->set('completed_at', current_time('mysql'));
            $order->save();

            // Update related tickets
            $ticket_repo->by('order_id', $order_id)
                       ->set('status', 'valid')
                       ->set('activated_at', current_time('mysql'))
                       ->save();

            return true;
        } catch (Exception $e) {
            // Handle rollback logic if needed
            return false;
        }
    }
}
```

### Incremental Updates

Update numeric fields incrementally:

```php
// Custom implementation for incrementing
class Stats_Repository extends Custom_Table_Repository {
    public function increment_view_count(int $id): void {
        $model = $this->by_primary_key($id);
        if ($model) {
            $current = $model->get_view_count();
            $model->set_view_count($current + 1);
            $model->save();
        }
    }
}
```

## Update Hooks and Filters

### Custom Table Repositories

Apply filters during updates:

```php
add_filter('tec_common_custom_table_query_where', function($where) {
    // Modify WHERE clause for updates
    return $where;
});
```

### Post-Based Repositories

Use WordPress hooks:

```php
// Before save
add_action('save_post_' . Order::POSTTYPE, function($post_id, $post, $update) {
    if ($update) {
        // Handle update logic
    }
}, 10, 3);

// After meta update
add_action('updated_post_meta', function($meta_id, $post_id, $meta_key, $meta_value) {
    if ($meta_key === Order::$status_meta_key) {
        // React to status changes
    }
}, 10, 4);
```

## Performance Considerations

### Batch Size Management

```php
// Process large updates in batches
$repository = tribe(Event_Repository::class);
$batch_size = 100;
$page = 1;

do {
    $batch = $repository->by('needs_processing', true)
                       ->page($page)
                       ->per_page($batch_size)
                       ->all();

    if (empty($batch)) {
        break;
    }

    foreach ($batch as $model) {
        $model->process();
        $model->set_needs_processing(false);
        $model->save();
    }

    $page++;
} while (count($batch) === $batch_size);
```

### Minimize Database Queries

```php
// Inefficient: Multiple queries
foreach ($ids as $id) {
    $repository->by_primary_key($id)
               ->set('status', 'archived')
               ->save();
}

// Efficient: Single query
$repository->by('id_in', $ids)
           ->set('status', 'archived')
           ->save();
```

### Use Generators for Memory Efficiency

```php
// Memory efficient for large datasets
foreach ($repository->by('status', 'pending')->all(true, 50) as $model) {
    $model->process();
    $model->save();
    // Memory freed after each batch
}
```

## Common Update Patterns

### Status Transitions

```php
class Order_Repository extends Tribe__Repository {
    public function transition_status(int $order_id, string $new_status): bool {
        $order = $this->by_primary_key($order_id);

        if (!$order) {
            return false;
        }

        $old_status = $order->status;

        // Validate transition
        if (!$this->is_valid_transition($old_status, $new_status)) {
            return false;
        }

        // Perform update
        $this->set('status', $new_status)
             ->set('status_changed_at', current_time('mysql'))
             ->set('status_changed_by', get_current_user_id())
             ->save();

        // Trigger transition hook
        do_action('order_status_transition', $order_id, $new_status, $old_status);

        return true;
    }
}
```

### Soft Deletes

```php
class Event_Repository extends Custom_Table_Repository {
    public function soft_delete(int $id): bool {
        return (bool) $this->by_primary_key($id)
                          ->set('deleted_at', current_time('mysql'))
                          ->set('deleted_by', get_current_user_id())
                          ->save();
    }

    public function restore(int $id): bool {
        return (bool) $this->by_primary_key($id)
                          ->set('deleted_at', null)
                          ->set('deleted_by', null)
                          ->save();
    }
}
```

### Audit Trail

```php
class Auditable_Repository extends Custom_Table_Repository {
    public function save_with_audit($changes): array {
        $user_id = get_current_user_id();
        $timestamp = current_time('mysql');

        // Add audit fields
        $this->set('last_modified_by', $user_id)
             ->set('last_modified_at', $timestamp);

        // Store change history (in separate audit table)
        foreach ($changes as $field => $value) {
            $this->set($field, $value);
        }

        $result = $this->save();

        // Log the changes
        $this->log_audit_trail($result, $changes, $user_id, $timestamp);

        return $result;
    }
}
```

## Error Handling

### Try-Catch Patterns

```php
try {
    $updated = $repository->by('status', 'pending')
                         ->set('status', 'processing')
                         ->save();

    if (empty($updated)) {
        throw new Exception('No records updated');
    }

} catch (RuntimeException $e) {
    // Handle repository errors
    error_log('Repository error: ' . $e->getMessage());
} catch (Exception $e) {
    // Handle general errors
    error_log('Update failed: ' . $e->getMessage());
}
```

### Validation Before Update

```php
class Event_Repository extends Custom_Table_Repository {
    public function validated_update(int $id, array $data): bool {
        $model = $this->by_primary_key($id);

        if (!$model) {
            throw new InvalidArgumentException('Model not found');
        }

        // Validate data
        $errors = $this->validate($data);
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }

        // Perform update
        foreach ($data as $key => $value) {
            $method = 'set_' . $key;
            if (method_exists($model, $method)) {
                $model->$method($value);
            }
        }

        return $model->save() > 0;
    }
}
```

## Best Practices

1. **Use Field Aliases** - Define clear mappings between API names and database columns
2. **Batch Updates** - Update multiple records in single queries when possible
3. **Use Generators** - For large datasets to avoid memory issues
4. **Add Indexes** - On frequently queried/updated columns
5. **Validate Before Update** - Ensure data integrity
6. **Log Critical Updates** - Maintain audit trails for important changes
7. **Handle Relationships Properly** - Use the model's relationship methods
8. **Test Update Scenarios** - Include edge cases and error conditions

## See Also

- [Custom Table Repository Abstract](../Abstracts/Custom_Table_Repository.md)
- [Custom Table Repository Interface](../Contracts/Custom_Table_Repository_Interface.md)
