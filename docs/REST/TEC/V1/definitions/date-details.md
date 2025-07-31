# Date Details Definition

The Date Details definition provides comprehensive date information with various formats and timezone details.

## Class
`TEC\Common\REST\TEC\V1\Documentation\Date_Details_Definition`

## Schema

```json
{
    "type": "object",
    "properties": {
        "date": {
            "type": "string",
            "format": "date-time",
            "description": "The date in Y-m-d H:i:s format"
        },
        "date_display": {
            "type": "string",
            "description": "Formatted date for display"
        },
        "date_i18n": {
            "type": "string",
            "description": "Internationalized date format"
        },
        "date_utc": {
            "type": "string",
            "format": "date-time",
            "description": "The date in UTC timezone"
        },
        "timezone": {
            "type": "string",
            "description": "Timezone identifier (e.g., 'America/New_York')"
        },
        "timezone_abbr": {
            "type": "string",
            "description": "Timezone abbreviation (e.g., 'EST', 'EDT')"
        },
        "timezone_offset": {
            "type": "string",
            "description": "Timezone offset from UTC (e.g., '-05:00')"
        },
        "timestamp": {
            "type": "integer",
            "description": "Unix timestamp"
        },
        "iso8601": {
            "type": "string",
            "format": "date-time",
            "description": "ISO 8601 formatted date"
        }
    }
}
```

## Example

```json
{
    "date": "2024-03-15 14:00:00",
    "date_display": "March 15, 2024 @ 2:00 PM",
    "date_i18n": "15 mars 2024 à 14h00",
    "date_utc": "2024-03-15T19:00:00+00:00",
    "timezone": "America/New_York",
    "timezone_abbr": "EDT",
    "timezone_offset": "-04:00",
    "timestamp": 1710522000,
    "iso8601": "2024-03-15T14:00:00-04:00"
}
```

## Usage

This definition is typically used for:
- Event start and end dates with full details
- Creation and modification timestamps
- Any date field requiring comprehensive information

### In Event Responses

```json
{
    "id": 123,
    "title": "Workshop",
    "start_date_details": {
        "date": "2024-03-15 09:00:00",
        "date_display": "March 15, 2024 @ 9:00 AM",
        "timezone": "America/New_York",
        "timezone_abbr": "EDT"
    },
    "end_date_details": {
        "date": "2024-03-15 17:00:00",
        "date_display": "March 15, 2024 @ 5:00 PM",
        "timezone": "America/New_York",
        "timezone_abbr": "EDT"
    }
}
```

## Timezone Handling

The definition supports:
- **Local Time**: The `date` field in the event's timezone
- **UTC Time**: The `date_utc` for universal time
- **Display Time**: Formatted for user display
- **Timezone Info**: Full timezone details

## Formatting Options

Different formats serve different purposes:
- `date` - Database storage format
- `date_display` - User-friendly display
- `date_i18n` - Localized format
- `iso8601` - Machine-readable standard

## Related Definitions

- [Date Definition](date.md) - Simpler date structure
- [Event Definition](../../../../../docs/REST/TEC/V1/definitions/event.md) - Uses date details for events