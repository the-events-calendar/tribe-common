<?php

class Tribe__Post_Transient {

		/**
		 * Get (and instantiate, if necessary) the instance of the class
		 *
		 * @static
		 * @return self
		 *
		 */
		public static function instance() {
			static $instance;

			if ( ! $instance instanceof self ) {
				$instance = new self;
			}

			return $instance;
		}

		/**
		 * Delete a post meta transient.
		 */
		public function delete( $post_id, $transient, $value = null ) {
			global $_wp_using_ext_object_cache;

			if ( is_numeric( $post_id ) ){
				$post_id = (int) $post_id;
			} else {
				$post = get_post( $post_id );
				$post_id = $post->ID;
			}

			do_action( 'tribe_delete_post_meta_transient_' . $transient, $post_id, $transient );

			if ( $_wp_using_ext_object_cache ) {
				$result = wp_cache_delete( "tribe_{$transient}-{$post_id}", "tribe_post_meta_transient-{$post_id}" );
			} else {
				$meta_timeout = '_transient_timeout_' . $transient;
				$meta = '_transient_' . $transient;
				$result = delete_post_meta( $post_id, $meta, $value );
				if ( $result ) {
					delete_post_meta( $post_id, $meta_timeout, $value );
				}
			}

			if ( $result ){
				do_action( 'tribe_deleted_post_meta_transient', $transient, $post_id, $transient );
			}

			return $result;
		}

		/**
		 * Get the value of a post meta transient.
		 */
		public function get( $post_id, $transient ) {
			global $_wp_using_ext_object_cache;

			if ( is_numeric( $post_id ) ){
				$post_id = (int) $post_id;
			} else {
				$post = get_post( $post_id );
				$post_id = $post->ID;
			}

			if ( has_filter( 'tribe_pre_post_meta_transient_' . $transient ) ) {
				$pre = apply_filters( 'tribe_pre_post_meta_transient_' . $transient, $post_id, $transient );
				if ( false !== $pre ) {
					return $pre;
				}
			}

			if ( $_wp_using_ext_object_cache ) {
				$value = wp_cache_get( "tribe_{$transient}-{$post_id}", "tribe_post_meta_transient-{$post_id}" );
			} else {
				$meta_timeout = '_transient_timeout_' . $transient;
				$meta = '_transient_' . $transient;
				$value = get_post_meta( $post_id, $meta, true );
				if ( $value && ! defined( 'WP_INSTALLING' ) ) {
					if ( get_post_meta( $post_id, $meta_timeout, true ) < time() ) {
						self::delete( $post_id, $transient );
						return false;
					}
				}
			}

			return
				has_filter( 'tribe_post_meta_transient_' . $transient )
				? apply_filters( 'tribe_post_meta_transient_' . $transient, $value, $post_id )
				: $value;
		}

		/**
		 * Set/update the value of a post meta transient.
		 */
		public function set( $post_id, $transient, $value, $expiration = 0 ) {
			global $_wp_using_ext_object_cache;

			if ( is_numeric( $post_id ) ){
				$post_id = (int) $post_id;
			} else {
				$post = get_post( $post_id );
				$post_id = $post->ID;
			}

			self::delete( $post_id, $transient );
			if ( has_filter( 'tribe_pre_set_post_meta_transient_' . $transient ) ) {
				$value = apply_filters( 'tribe_pre_set_post_meta_transient_' . $transient, $value, $post_id, $transient );
			}

			if ( $_wp_using_ext_object_cache ) {
				$result = wp_cache_set( "tribe_{$transient}-{$post_id}", $value, "tribe_post_meta_transient-{$post_id}", $expiration );
			} else {
				$meta_timeout = '_transient_timeout_' . $transient;
				$meta = '_transient_' . $transient;
				if ( $expiration ) {
					add_post_meta( $post_id, $meta_timeout, time() + $expiration, true );
				}
				$result = add_post_meta( $post_id, $meta, $value, true );
			}

			if ( $result ) {
				do_action( 'tribe_set_post_meta_transient_' . $transient, $post_id, $transient );
				do_action( 'tribe_setted_post_meta_transient', $transient, $post_id, $transient );
			}

			return $result;
		}


}