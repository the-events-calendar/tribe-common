<?php

namespace Tribe\Admin;

/**
 * Admin Settings class.
 * 
 * @since TBD
 */

class Settings {

    /**
     * Keep track of whether or not image assets have already been loaded.
     * 
     * @since TBD
     * 
     * @var bool
     */
    static $image_field_assets_loaded = false;
    static $color_field_assets_loaded = false;

    /**
     * Loaded image field assets if not already loaded.
     * 
     * @since TBD
     *
     * @return void
     */
    public function maybe_load_image_field_assets() {
        if ( $this->image_field_assets_loaded ) {
            return;
        }

        tribe_asset_enqueue( 'tribe-admin-image-field' );
        wp_enqueue_media();

        $this->image_field_assets_loaded = true;
    }

    /**
     * Load color field assets if not already loaded.
     *
     * @return void
     */
    public function maybe_load_color_field_assets() {
        if ( $this->color_field_assets_loaded ) {
            return;
        }

        tribe_asset_enqueue( 'tribe-settings-color-field' );
        wp_enqueue_style( 'wp-color-picker' );

        $this->color_field_assets_loaded = true;
    }
    
}