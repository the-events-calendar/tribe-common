# TEC Post Entity Definition

The TEC Post Entity definition provides the base structure for all post-based entities in the TEC REST API.

## Class
`TEC\Common\REST\TEC\V1\Documentation\TEC_Post_Entity_Definition`

## Schema

```json
{
    "type": "object",
    "properties": {
        "id": {
            "type": "integer",
            "description": "Unique identifier for the entity"
        },
        "author": {
            "type": "integer",
            "description": "The user ID of the entity author"
        },
        "date": {
            "type": "string",
            "format": "date-time",
            "description": "The date the entity was published, in the site's timezone"
        },
        "date_gmt": {
            "type": "string",
            "format": "date-time",
            "description": "The date the entity was published, as GMT"
        },
        "modified": {
            "type": "string",
            "format": "date-time",
            "description": "The date the entity was last modified, in the site's timezone"
        },
        "modified_gmt": {
            "type": "string",
            "format": "date-time",
            "description": "The date the entity was last modified, as GMT"
        },
        "status": {
            "type": "string",
            "enum": ["publish", "pending", "draft", "future", "private", "trash"],
            "description": "A named status for the entity"
        },
        "type": {
            "type": "string",
            "description": "Type of Post for the entity"
        },
        "link": {
            "type": "string",
            "format": "uri",
            "description": "URL to the entity"
        },
        "title": {
            "type": "object",
            "properties": {
                "rendered": {
                    "type": "string",
                    "description": "HTML title for the entity, transformed for display"
                }
            }
        },
        "content": {
            "type": "object",
            "properties": {
                "rendered": {
                    "type": "string",
                    "description": "HTML content for the entity, transformed for display"
                },
                "protected": {
                    "type": "boolean",
                    "description": "Whether the content is protected with a password"
                }
            }
        },
        "excerpt": {
            "type": "object",
            "properties": {
                "rendered": {
                    "type": "string",
                    "description": "HTML excerpt for the entity, transformed for display"
                },
                "protected": {
                    "type": "boolean",
                    "description": "Whether the excerpt is protected with a password"
                }
            }
        },
        "slug": {
            "type": "string",
            "description": "An alphanumeric identifier for the entity unique to its type"
        },
        "guid": {
            "type": "object",
            "properties": {
                "rendered": {
                    "type": "string",
                    "format": "uri",
                    "description": "GUID for the entity, as it exists in the database"
                }
            }
        },
        "_links": {
            "type": "object",
            "description": "HAL links for the entity"
        }
    },
    "required": ["id", "date", "date_gmt", "modified", "modified_gmt", "status", "type"]
}
```

## Purpose

This definition serves as the base for all WordPress post-based entities:
- Events
- Venues
- Organizers
- Any custom post types

## Inheritance

Other definitions extend this base:

```php
class Event_Definition extends TEC_Post_Entity_Definition {
    public function get_documentation(): array {
        $base = parent::get_documentation();
        
        // Add event-specific properties
        $base['properties']['start_date'] = [
            'type' => 'string',
            'format' => 'date-time',
            'description' => 'Event start date and time'
        ];
        
        return $base;
    }
}
```

## Common Properties

All entities share these properties:
- **Identification**: `id`, `slug`, `guid`
- **Timestamps**: `date`, `modified` (with GMT versions)
- **Content**: `title`, `content`, `excerpt` (with rendered versions)
- **Metadata**: `status`, `type`, `author`
- **Navigation**: `link`, `_links`

## HAL Links

The `_links` property contains hypermedia links:

```json
{
    "_links": {
        "self": [
            {
                "href": "https://example.com/wp-json/tec/v1/events/123"
            }
        ],
        "collection": [
            {
                "href": "https://example.com/wp-json/tec/v1/events"
            }
        ],
        "author": [
            {
                "embeddable": true,
                "href": "https://example.com/wp-json/wp/v2/users/1"
            }
        ],
        "wp:attachment": [
            {
                "href": "https://example.com/wp-json/wp/v2/media?parent=123"
            }
        ]
    }
}
```

## Usage Example

```json
{
    "id": 123,
    "author": 1,
    "date": "2024-01-15T10:00:00",
    "date_gmt": "2024-01-15T15:00:00",
    "modified": "2024-01-20T14:30:00",
    "modified_gmt": "2024-01-20T19:30:00",
    "status": "publish",
    "type": "tribe_events",
    "link": "https://example.com/event/my-event/",
    "title": {
        "rendered": "My Event Title"
    },
    "content": {
        "rendered": "<p>Event description...</p>",
        "protected": false
    },
    "excerpt": {
        "rendered": "<p>Short description...</p>",
        "protected": false
    },
    "slug": "my-event",
    "guid": {
        "rendered": "https://example.com/?p=123"
    }
}
```

## Related Definitions

- [Event Definition](../../../../../docs/REST/TEC/V1/definitions/event.md) - Extends this base
- [Venue Definition](../../../../../docs/REST/TEC/V1/definitions/venue.md) - Extends this base
- [Organizer Definition](../../../../../docs/REST/TEC/V1/definitions/organizer.md) - Extends this base