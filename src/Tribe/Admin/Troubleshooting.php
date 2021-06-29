<?php

/**
 * Admin Troubleshooting for TEC plugins.
 *
 * @since TBD
 *
 * @package Tribe\Admin
 */

namespace Tribe\Admin;
use \Tribe__Settings;
use \Tribe__Main;
use \Tribe__Admin__Helpers;
use \Tribe__Timezones as Timezones;
use \Tribe__Events__Google__Maps_API_Key;

/**
 * Class Admin Troubleshooting.
 *
 * @since TBD
 *
 * @package Tribe\Admin
 */
class Troubleshooting
{
    /**
     * Slug of the WP admin menu item
     *
     * @since TBD
     *
     * @var string
     */
    const MENU_SLUG = 'tribe-troubleshooting';

    /**
     * The slug for the new admin page
     *
     * @var string
     */
    private $admin_page = null;

    /**
     * Class constructor
     */
    public function hook()
    {
        add_action('admin_menu', [ $this, 'add_menu_page' ], 90);
        add_action('wp_before_admin_bar_render', [ $this, 'add_toolbar_item' ], 20);
    }

    /**
     * Adds the page to the admin menu
     */
    public function add_menu_page()
    {
        if (! Tribe__Settings::instance()->should_setup_pages()) {
            return;
        }

        $page_title = esc_html__('Troubleshooting', 'tribe-common');
        $menu_title = esc_html__('Troubleshooting', 'tribe-common');
        $capability = apply_filters('tribe_events_troubleshooting_capability', 'install_plugins');

        $where = Tribe__Settings::instance()->get_parent_slug();

        $this->admin_page = add_submenu_page(
            $where,
            $page_title,
            $menu_title,
            $capability,
            self::MENU_SLUG,
            [
                $this,
                'do_menu_page',
            ]
        );
    }

    /**
     * Adds a link to the the WP admin bar
     */
    public function add_toolbar_item()
    {
        $capability = apply_filters('tribe_events_troubleshooting_capability', 'install_plugins');

        // prevent users who cannot install plugins from seeing addons link
        if (current_user_can($capability)) {
            global $wp_admin_bar;

            $wp_admin_bar->add_menu([
                'id'     => 'tribe-events-troubleshooting',
                'title'  => esc_html__('Event Add-Ons', 'tribe-common'),
                'href'   => Tribe__Settings::instance()->get_url([ 'page' => self::MENU_SLUG ]),
                'parent' => 'tribe-events-settings-group',
            ]);
        }
    }

    /**
     * Checks if the current page is the troubleshooting page
     *
     * @since TBD
     * 
     * @var string
     *
     * @return bool
     */
    public function is_current_page()
    {
        if (! Tribe__Settings::instance()->should_setup_pages() || ! did_action('admin_menu')) {
            return false;
        }

        if (is_null($this->admin_page)) {
            _doing_it_wrong(
                __FUNCTION__,
                'Function was called before it is possible to accurately determine what the current page is.',
                '4.5.6'
            );
            return false;
        }

        return Tribe__Admin__Helpers::instance()->is_screen($this->admin_page);
    }

    /**
     * Renders the Troubleshooting page
     * 
     * @since TBD
     * 
     * @var string
     */
    public function do_menu_page() {
        $main = Tribe__Main::instance();
        include_once Tribe__Main::instance()->plugin_path . 'src/admin-views/troubleshooting.php';
    }

    public function is_any_issue_active() {
        $issues = $this->get_issues_found();
        $active_issues = wp_list_pluck( $issues, 'active' );
        return in_array( true, $active_issues );
    }

    public function is_any_tec_plugin_out_of_date() {
        $current = get_site_transient( 'update_plugins' );
        $plugins = [];
        if ( defined( 'TRIBE_EVENTS_FILE' ) ) {
            $plugins[] = TRIBE_EVENTS_FILE;
        }
        if ( defined( 'EVENTS_CALENDAR_PRO_FILE' ) ) {
            $plugins[] = EVENTS_CALENDAR_PRO_FILE;
        }
        if ( defined( 'EVENT_TICKETS_PLUS_FILE' ) ) {
            $plugins[] = EVENT_TICKETS_PLUS_FILE;
        }
        if ( defined( 'EVENTS_VIRTUAL_FILE' ) ) {
            $plugins[] = EVENTS_VIRTUAL_FILE;
        }
        if ( defined( 'EVENT_TICKETS_MAIN_PLUGIN_FILE' ) ) {
            $plugins[] = EVENT_TICKETS_MAIN_PLUGIN_FILE;
        }
        if ( defined( 'TRIBE_EVENTS_FILTERBAR_FILE' ) ) {
            $plugins[] = TRIBE_EVENTS_FILTERBAR_FILE;
        }
        if ( defined( 'EVENTS_COMMUNITY_TICKETS_FILE' ) ) {
            $plugins[] = EVENTS_COMMUNITY_TICKETS_FILE;
        }
        if ( defined( 'EVENTS_COMMUNITY_FILE' ) ) {
            $plugins[] = EVENTS_COMMUNITY_FILE;
        }
        if ( defined( 'EVENTBRITE_PLUGIN_FILE' ) ) {
            $plugins[] = EVENTBRITE_PLUGIN_FILE;
        }
        if ( defined( 'TRIBE_APM_FILE' ) ) {
            $plugins[] = TRIBE_APM_FILE;
        }
        if ( defined( 'IMAGE_WIDGET_PLUS_DIR' ) ) {
            $plugins[] = IMAGE_WIDGET_PLUS_DIR;
        }
        $plugins = array_map( static function( $file ) {
            $file = \str_replace( WP_PLUGIN_DIR . '/', '', $file );
            return $file;
        }, $plugins );
        // @todo do this for every tec plugin, need to check if plugin is active first 
        
        foreach ( $plugins as $file ) {
            if ( ! isset( $current->response[ $file ] ) ) {
                continue;
            }
            $response = $current->response[ $file ];
            if ( ! empty( $response->new_version ) ) {
                return true;
            }
        }
        return false;
    }

    public function is_active_issue( $slug ) {
        if ( 'timezone' === $slug ) {
            return Timezones::is_utc_offset( Timezones::wp_timezone_string() );
        }
        if ( 'install-max' === $slug ) {
            // Tribe__PUE__Checker::validate_key()
        }
        if ( 'geolocation' === $slug && class_exists( 'Tribe__Events__Google__Maps_API_Key' ) ) {
            $key = \tribe_get_option( 'google_maps_js_api_key', false );
            return empty( $key ) || Tribe__Events__Google__Maps_API_Key::$default_api_key === $key ;
        }
        if ( 'out-of-date' === $slug ) {
            return $this->is_any_tec_plugin_out_of_date();
        }
        return false;
    }

    public function get_issues_found() {
        $issues_found = apply_filters( 'tec_help_troubleshooting_issues_found', [
            [
                'title' => __('Time zone is not set', 'tribe-common'),
                'description' => __('We recommend that our users use a location time zone and avoid using UTC offsets.', 'tribe-common'),
                'more_info' => 'https://evnt.is/somewhere',
                'fix' => 'https://evnt.is/somewhere',
                'active' => $this->is_active_issue( 'timezone' ),
            ],
            [
                'title' => __('Install max has been reached', 'tribe-common'),
                'description' => __('	Lorem ipsum dolor sit amet consectetur adipisicing elit. Ullam fugit tempora ipsam deserunt voluptatum?', 'tribe-common'),
                'more_info' => 'https://evnt.is/somewhere',
                'fix' => 'https://evnt.is/somewhere',
                'active' => $this->is_active_issue( 'install-max' ),
            ],
            [
                'title' => __('Geolocation code is missing', 'tribe-common'),
                'description' => __('	Lorem ipsum dolor sit amet consectetur adipisicing elit. Ullam fugit tempora ipsam deserunt voluptatum?', 'tribe-common'),
                'more_info' => 'https://evnt.is/somewhere',
                'fix' => 'https://evnt.is/somewhere',
                'active' => $this->is_active_issue( 'geolocation' ),
            ],
            [
                'title' => __('Plugin versions are out of date', 'tribe-common'),
                'description' => __('	Lorem ipsum dolor sit amet consectetur adipisicing elit. Ullam fugit tempora ipsam deserunt voluptatum?', 'tribe-common'),
                'more_info' => 'https://evnt.is/somewhere',
                'fix' => 'https://evnt.is/somewhere',
                'active' => $this->is_active_issue( 'out-of-date' ),
            ],
        ] );

        return $issues_found;
    }

    public function get_common_issues() {
    // there should only be 4 in this list
    $commonIssues = apply_filters( 'tec_help_troubleshooting_issues', [
        [
            'issue' => __('I got an error message. Now what?', 'tribe-common'),
            'solution' => __('Here’s an overview of %s and what they mean.', 'tribe-common'),
            'link' => 'https://theeventscalendar.com/knowledgebase/k/troubleshooting-the-most-common-installation-issues/#fatal-errors',
            'link_label' => 'common error messages',
        ],
        [
            'issue' => __('My calendar doesn’t look right.', 'tribe-common'),
            'solution' => __('This can happen when other plugins try to improve performance. %s.'),
            'link' => 'https://theeventscalendar.com/knowledgebase/k/troubleshooting-the-most-common-installation-issues/#layout-issue',
            'link_label' => 'More info',
        ],
        [
            'issue' => __('I installed the calendar and it crashed my site.', 'tribe-common'),
            'solution' => __('%s and other common installation issues.', 'tribe-common'),
            'link' => 'https://evnt.is/1aqg',
            'link_label' => 'Find solutions to this',
        ],
        [
            'issue' => __('I keep getting “Page Not Found” on events.', 'tribe-common'),
            'solution' => __('There are a few %s to resolve and prevent 404 errors.', 'tribe-common'),
            'link' => 'https://evnt.is/1aqg',
            'link_label' => 'things you can do',
        ],
    ] );

        return $commonIssues;
    }
}
