<?php

/**
 * Admin Troubleshooting for TEC plugins.
 *
 * @since TBD
 *
 * @package Tribe\Admin
 */

namespace Tribe\Admin;

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
    public function __construct()
    {
        add_action('admin_menu', [ $this, 'add_menu_page' ], 100);
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
     * @since 4.5.7
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
     */
    public function do_menu_page()
    {
        $main = Tribe__Main::instance();
        include_once Tribe__Main::instance()->plugin_path . 'src/admin-views/troubleshooting.php';
    }

    /**
     * Static Singleton Factory Method
     *
     * @return Tribe__Troubleshooting
     */
    public static function instance()
    {
        if (! isset(self::$instance)) {
            $className      = __CLASS__;
            self::$instance = new $className;
        }

        return self::$instance;
    }
}
