# ORM Usage in REST API

This guide explains how to use The Events Calendar ORM system within REST API endpoints.

## Overview

The TEC ORM provides a fluent interface for querying and manipulating WordPress posts. It's the preferred method for data access in REST endpoints.

## Available ORMs

### Events ORM

Access via: `tribe_events()` or `tribe( 'events.orm' )`

### Organizers ORM

Access via: `tribe_organizers()` or `tribe( 'organizers.orm' )`

### Venues ORM

Access via: `tribe_venues()` or `tribe( 'venues.orm' )`

## Basic Usage

### Querying

```php
// Get all published events
$events = tribe_events()
    ->where( 'post_status', 'publish' )
    ->all();

// Get events with pagination
$events = tribe_events()
    ->page( 2 )
    ->per_page( 20 )
    ->all();

// Get total count
$total = tribe_events()
    ->where( 'post_status', 'publish' )
    ->found();
```

### Filtering

```php
// Filter by date range
$events = tribe_events()
    ->where( 'starts_after', '2024-01-01' )
    ->where( 'ends_before', '2024-12-31' )
    ->all();

// Search
$events = tribe_events()
    ->search( 'conference' )
    ->all();

// Multiple conditions
$events = tribe_events()
    ->where( 'venue', [123, 456] )
    ->where( 'featured', true )
    ->where( 'ticketed', true )
    ->all();
```

### Creating

```php
// Create new event
$event_id = tribe_events()
    ->set_args( [
        'title' => 'New Event',
        'content' => 'Event description',
        'start_date' => '2024-03-15 09:00:00',
        'end_date' => '2024-03-15 17:00:00',
        'venue' => [456],
        'organizer' => [789],
    ] )
    ->create();

if ( $event_id ) {
    $event = get_post( $event_id );
}
```

### Updating

```php
// Update existing event
$updated = tribe_events()
    ->where( 'id', 123 )
    ->set_args( [
        'title' => 'Updated Title',
        'featured' => true,
    ] )
    ->save();
```

### Deleting

```php
// Delete event (moves to trash)
$deleted = tribe_events()
    ->where( 'id', 123 )
    ->delete();

// Force delete
$deleted = tribe_events()
    ->where( 'id', 123 )
    ->delete( true );
```

## REST Endpoint Integration

### Using ORM Traits

Create traits for ORM access:

```php
namespace TEC\Events\REST\TEC\V1\Traits;

trait With_Events_ORM {
    public function get_orm() {
        return tribe_events();
    }
}
```

### In Endpoints

```php
class Events extends Post_Entity_Endpoint implements Readable_Endpoint {
    use With_Events_ORM;

    protected function build_query( WP_REST_Request $request ): Tribe__Repository__Interface {
        $query = $this->get_orm();

        // Apply filters from request
        if ( ! empty( $request['start_date'] ) ) {
            $query->where( 'starts_after', $request['start_date'] );
        }

        if ( ! empty( $request['venue'] ) ) {
            $query->where( 'venue', array_map( 'absint', $request['venue'] ) );
        }

        if ( isset( $request['featured'] ) ) {
            $query->where( 'featured', (bool) $request['featured'] );
        }

        return $query;
    }
}
```

## Available Filters

### Event Filters

- `starts_after` - Events starting after date
- `starts_before` - Events starting before date
- `ends_after` - Events ending after date
- `ends_before` - Events ending before date
- `venue` - Filter by venue ID(s)
- `organizer` - Filter by organizer ID(s)
- `featured` - Featured events only
- `ticketed` - Events with tickets
- `category` - Filter by category ID(s)
- `tag` - Filter by tag ID(s)

### Common Filters

- `post_status` - Post status
- `search` - Search terms
- `post__in` - Specific IDs
- `post__not_in` - Exclude IDs
- `author` - Author ID
- `meta_key` / `meta_value` - Meta queries

## Ordering

```php
$events = tribe_events()
    ->order_by( 'event_date', 'ASC' )
    ->all();

// Multiple order criteria
$events = tribe_events()
    ->order_by( 'featured', 'DESC' )
    ->order_by( 'event_date', 'ASC' )
    ->all();
```

## Relationships

The ORM automatically handles relationships:

```php
// Get event with venues and organizers
$event = tribe_events()
    ->where( 'id', 123 )
    ->first();

// Access relationships
$venues = $event->venues->all();      // Collection of venue posts
$organizers = $event->organizers->all(); // Collection of organizer posts
```
