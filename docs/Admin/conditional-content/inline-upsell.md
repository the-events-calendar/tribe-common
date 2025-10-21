# Inline Upsell Component

Simple, reusable component for displaying inline upsell notices in WordPress admin.

## When to Use

Use `Inline_Upsell` for:
- ✅ **Evergreen feature prompts** (always relevant)
- ✅ **Context-specific upsells** (appears where feature would be used)
- ✅ **Simple text + link notices** (not large banner campaigns)
- ✅ **Non-dismissible prompts** (always visible to eligible users)

Do NOT use for:
- ❌ Time-sensitive campaigns (use `Conditional_Content` system instead)
- ❌ Dismissible notices (use `Conditional_Content` system instead)
- ❌ Large banner ads (use `Conditional_Content` system instead)

## Basic Usage

### Simple Example

```php
use TEC\Common\Admin\Inline_Upsell;

tribe( Inline_Upsell::class )->render( [
    'slug'    => 'my-upsell-identifier',
    'text'    => sprintf(
        esc_html__( 'Get advanced features with %s', 'event-tickets' ),
        ''
    ),
    'link'    => [
        'text'    => 'Event Tickets Plus',
        'url'     => 'https://evnt.is/my-link',
        'classes' => [ 'tec-admin__upsell-link--underlined' ],
    ],
    'classes' => [
        'my-custom-class',
    ],
] );
```

### With Plugin Condition

Only show if a specific plugin is NOT active:

```php
tribe( Inline_Upsell::class )->render( [
    'slug'       => 'tickets-plus-upsell',
    'text'       => sprintf(
        esc_html__( 'Manually add attendees with %s', 'event-tickets' ),
        ''
    ),
    'link'       => [
        'text' => 'Event Tickets Plus',
        'url'  => 'https://evnt.is/et-in-app-manual-attendees',
    ],
    'conditions' => [
        'plugin_not_active' => 'event-tickets-plus/event-tickets-plus.php',
    ],
] );
```

### Quick Helper Method

For simple "show if plugin not active" upsells:

```php
tribe( Inline_Upsell::class )->render_for_plugin(
    'event-tickets-plus/event-tickets-plus.php',
    sprintf( esc_html__( 'Get advanced capacity with %s', 'event-tickets' ), '' ),
    'Event Tickets Plus',
    'https://evnt.is/et-capacity',
    [
        'classes' => [ 'my-custom-class' ],
    ]
);
```

## Arguments

### Main `render()` Arguments

```php
[
    'slug'        => string,   // Unique identifier for filtering
    'classes'     => array,    // CSS classes for container
    'text'        => string,   // Upsell message (use %s for link placeholder)
    'link_target' => string,   // Default: '_blank'
    'icon_url'    => string,   // URL to icon image
    'link'        => [
        'classes' => array,    // CSS classes for link
        'text'    => string,   // Link text
        'url'     => string,   // Link URL
        'target'  => string,   // Default: '_blank'
        'rel'     => string,   // Default: 'noopener noreferrer'
    ],
    'conditions'  => [
        'plugin_not_active'    => string,   // Plugin path that must NOT be active
        'plugin_not_installed' => string,   // Plugin slug that must NOT be installed
        'callback'             => callable, // Custom condition check
    ],
]
```

### Available Conditions

**`plugin_not_active`** - Show only if plugin is not active
```php
'conditions' => [
    'plugin_not_active' => 'event-tickets-plus/event-tickets-plus.php',
]
```

**`plugin_not_installed`** - Show only if paid plugin is not installed (checks via Plugins API)
```php
'conditions' => [
    'plugin_not_installed' => 'events-calendar-pro',
]
```

**`callback`** - Custom condition logic
```php
'conditions' => [
    'callback' => function() {
        return ! class_exists( 'My_Plugin' );
    },
]
```

**Multiple conditions** - All must pass
```php
'conditions' => [
    'plugin_not_active' => 'event-tickets-plus/event-tickets-plus.php',
    'callback' => function() {
        return current_user_can( 'manage_options' );
    },
]
```

## Styling Classes

### Container Classes

- `.tec-admin__upsell` - Base class (applied automatically)
- `.tec-admin__upsell--rounded-corners` - Rounded corners with gray background (entire notice)
- `.tec-admin__upsell--rounded-corners-text` - Rounded corners with gray background (text only)

### Link Classes

- `.tec-admin__upsell-link` - Base class (applied automatically)
- `.tec-admin__upsell-link--dark` - Dark text color
- `.tec-admin__upsell-link--underlined` - Underlined link

## Filtering

### Hide Specific Upsell

```php
add_filter( 'tec_should_hide_upsell', function( $hide, $slug ) {
    if ( $slug === 'my-upsell-identifier' ) {
        return true;
    }
    return $hide;
}, 10, 2 );
```

### Hide All Upsells

```php
// Via filter
add_filter( 'tec_should_hide_upsell', '__return_true' );

// Or via constant
define( 'TRIBE_HIDE_UPSELL', true );
```

## Migration from `Upsell_Notice\Main`

### Before (Deprecated)
```php
use Tribe\Admin\Upsell_Notice;

tribe( Upsell_Notice\Main::class )->render( [
    'text'    => 'Get features with Event Tickets Plus',
    'link'    => [
        'text' => 'Event Tickets Plus',
        'url'  => 'https://evnt.is/link',
    ],
] );
```

### After (New)
```php
use TEC\Common\Admin\Inline_Upsell;

tribe( Inline_Upsell::class )->render( [
    'slug'    => 'my-upsell',  // Add unique identifier
    'text'    => 'Get features with Event Tickets Plus',
    'link'    => [
        'text' => 'Event Tickets Plus',
        'url'  => 'https://evnt.is/link',
    ],
] );
```

## Complete Examples

### Example 1: Capacity & ARF Upsell

```php
tribe( Inline_Upsell::class )->render( [
    'slug'       => 'et-capacity-arf',
    'classes'    => [ 'tec-admin__upsell-tec-tickets-capacity-arf' ],
    'text'       => sprintf(
        esc_html__( 'Get individual information collection from each attendee and advanced capacity options with %s', 'event-tickets' ),
        ''
    ),
    'link'       => [
        'classes' => [ 'tec-admin__upsell-link--underlined' ],
        'text'    => 'Event Tickets Plus',
        'url'     => 'https://evnt.is/et-in-app-capacity-arf',
    ],
    'conditions' => [
        'plugin_not_active' => 'event-tickets-plus/event-tickets-plus.php',
    ],
] );
```

### Example 2: Conditional Display with Wrapper

```php
if ( ! tribe( Inline_Upsell::class )->is_plugin_active( 'event-tickets-plus/event-tickets-plus.php' ) ) {
    echo '<div class="welcome-panel-column welcome-panel-extra">';
    
    tribe( Inline_Upsell::class )->render( [
        'slug' => 'et-manual-attendees',
        'text' => sprintf(
            esc_html__( 'Manually add attendees with %s', 'event-tickets' ),
            ''
        ),
        'link' => [
            'text' => 'Event Tickets Plus',
            'url'  => 'https://evnt.is/et-in-app-manual-attendees',
        ],
    ] );
    
    echo '</div>';
}
```

### Example 3: Using Quick Helper

```php
// Simple one-liner
tribe( Inline_Upsell::class )->render_for_plugin(
    'event-tickets-plus/event-tickets-plus.php',
    sprintf( esc_html__( 'Get wallet features with %s', 'event-tickets' ), '' ),
    'Event Tickets Plus',
    'https://evnt.is/wallet'
);
```

## Template

The component uses the same template as the deprecated `Upsell_Notice\Main`:
- Location: `src/admin-views/notices/upsell/main.php`
- Already exists in tribe-common
- No template changes needed

## Best Practices

1. **Always provide a unique slug** - Enables filtering per-upsell
2. **Use descriptive CSS classes** - Makes styling and targeting easier
3. **Check plugin status first** - Don't render unnecessary HTML
4. **Use sprintf with %s** - Maintains consistent link positioning in translations
5. **Add conditions to args** - Let the component handle conditional logic
6. **Use `render_for_plugin()`** - For simple plugin-based upsells

## Support

For time-sensitive campaigns or dismissible notices, use the `Conditional_Content` system instead:
- See `/src/Common/Admin/Conditional_Content/README.md`

