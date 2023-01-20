<?php

namespace TEC\Common\Storage;

/**
 * Class Timed_Option which will handle the storage of values that need to be transient in nature but without the
 * performance cost of Transients. This is specially important when dealing with WordPress installs that have no Object
 * Caching, on those cases Transients will execute two SQL queries when using `get_transient()`, which is demolishes
 * the performance of certain pages.
 *
 * @since   5.0.6
 *
 * @package TEC\Common
 */
class Timed_Option {
	/**
	 *
	 *
	 * @since 5.0.6
	 *
	 * @var bool
	 */
	protected $active = true;

	/**
	 * Prefix for all the Timed Options stored on the database.
	 *
	 * @since 5.0.6
	 *
	 * @var string
	 */
	protected $option_name_prefix = 'tec_timed_';

	/**
	 * Local storage of the data on the Options. Keys will not have the prefix used on the database.
	 *
	 * @since 5.0.6
	 *
	 * @var array
	 */
	protected $data = [];

	/**
	 * Deactivate the usage of Database Timed Options, all timed options are only a glorified memoization.
	 *
	 * @since 5.0.6
	 *
	 * @return void
	 */
	public function deactivate(): void {
		$this->active = false;
	}

	/**
	 * Activate the usage of Database Timed Options.
	 *
	 * @since 5.0.6
	 *
	 * @return void
	 */
	public function activate(): void {
		$this->active = true;
	}

	/**
	 * Is the timed options active?
	 *
	 * @since 5.0.6
	 *
	 * @return bool
	 */
	public function is_active(): bool {
		/**
		 * Allows the modification of the state of usage for Timed Options.
		 *
		 * @since 5.0.6
		 *
		 * @param bool $active Whether we use Database Timed Options or a glorified Memoization system.
		 */
		return (bool) apply_filters( 'tec_common_timed_option_is_active', $this->active );
	}

	/**
	 * Gets the option name for a given timed option, by attaching a prefix and allowing filtering.
	 *
	 * @since 5.0.6
	 *
	 * @param string $key Key for the option we are trying to get the option name for.
	 *
	 * @return string
	 */
	public function get_option_name( string $key ): string {
		/**
		 * Allows the modification of where we store the Transient Data.
		 *
		 * @since 5.0.6
		 *
		 * @param string $option_name Name of the option where all the transient data will live.
		 */
		return (string) apply_filters( 'tec_common_timed_option_name', $this->option_name_prefix . $key, $this->option_name_prefix );
	}

	/**
	 * Fetches the value of a given timed option.
	 *
	 * @since 5.0.6
	 *
	 * @param string $key     Key for the option we are trying to get.
	 * @param mixed  $default Default value when the option is either expired or not-set.
	 * @param bool   $force   If we should expire cache and fetch from the database.
	 *
	 * @return mixed|null
	 */
	public function get( $key, $default = null, bool $force = false ) {
		/**
		 * Allows the filtering the default timed_option value.
		 *
		 * @since 5.0.6
		 *
		 * @param mixed  $default Default value when the option is either expired or not-set.
		 * @param string $key     Key for the option we are trying to get.
		 * @param bool   $force   If we should expire cache and fetch from the database.
		 */
		$default = apply_filters( 'tec_common_timed_option_default_value', $default, $key, $force );

		/**
		 * Allows the filtering to short-circuit the whole fetch logic.
		 *
		 * @since 5.0.6
		 *
		 * @param mixed|null $pre     If anything diff than null it will short-circuit.
		 * @param string     $key     Key for the option we are trying to get.
		 * @param mixed      $default Default value when the option is either expired or not-set.
		 * @param bool       $force   If we should expire cache and fetch from the database.
		 */
		$pre = apply_filters( 'tec_common_timed_option_pre_value', null, $key, $default, $force );

		if ( null !== $pre ) {
			return $pre;
		}

		$time = time();

		// If we have a stored value that is not expired, use it.
		if (
			! $force
			&& isset( $this->data[ $key ] )
			&& is_numeric( $this->data[ $key ]['expiration'] )
			&& $time < $this->data[ $key ]['expiration']
		) {
			/**
			 * Allows the filtering of the cached value of the timed option.
			 *
			 * @since 5.0.6
			 *
			 * @param mixed  $value   If anything diff than null it will short-circuit.
			 * @param string $key     Key for the option we are trying to get.
			 * @param mixed  $default Default value when the option is either expired or not-set.
			 * @param bool   $force   If we should expire cache and fetch from the database.
			 * @param bool   $cache   If the value was pulled from cache.
			 */
			return apply_filters( 'tec_common_timed_option_value', $this->data[ $key ]['value'], $key, $default, $force, true );
		}

		$timed_option = null;

		if ( $this->is_active() ) {
			$timed_option_name = $this->get_option_name( $key );
			if ( true === $force ) {
				wp_cache_delete( $timed_option_name, 'options' );
			}
			$timed_option = get_option( $timed_option_name, null );
		}

		// Bail with default when non-existent.
		if ( empty( $timed_option ) ) {
			if ( $this->is_active() ) {
				// Avoids next request check, forces auto-loading.
				$this->set( $key, null, 0 );
			}

			return $default;
		}

		// Bail with default when expired.
		if ( $time >= $timed_option['expiration'] ) {
			$this->delete( $key );

			return $default;
		}

		$this->data[ $key ] = $timed_option;

		/**
		 * Allows the filtering of the value of the timed option.
		 *
		 * @since 5.0.6
		 *
		 * @param mixed  $value   If anything diff than null it will short-circuit.
		 * @param string $key     Key for the option we are trying to get.
		 * @param mixed  $default Default value when the option is either expired or not-set.
		 * @param bool   $force   If we should expire cache and fetch from the database.
		 * @param bool   $cache   If the value was pulled from cache.
		 */
		return apply_filters( 'tec_common_timed_option_value', $timed_option['value'], $key, $default, $force, false );
	}

	/**
	 * Delete a given timed option based on a key.
	 * Will also clear local cache.
	 *
	 * @since 5.0.6
	 *
	 * @param string $key Which timed option we are checking.
	 *
	 * @return bool
	 */
	public function delete( $key ): bool {
		$key     = (string) $key;
		$updated = false;

		if ( $this->is_active() ) {
			$timed_option_name = $this->get_option_name( $key );
			$updated           = update_option( $timed_option_name, null, true );
			wp_cache_delete( $timed_option_name, 'options' );
		}

		// Bail with default when non-existent.
		if ( ! isset( $this->data[ $key ] ) ) {
			return $updated;
		}

		unset( $this->data[ $key ] );

		return $updated;
	}

	/**
	 * Checks if a given timed option exists.
	 *
	 * @since 5.0.6
	 *
	 * @param string $key   Which timed option we are checking.
	 * @param bool   $force Clears the cache before get_option()
	 *
	 * @return bool
	 */
	public function exists( $key, bool $force = false ): bool {
		/**
		 * Allows the filtering to short-circuit the whole exists logic.
		 *
		 * @since 5.0.6
		 *
		 * @param mixed|null $pre   If anything diff than null it will short-circuit.
		 * @param string     $key   Key for the option we are trying to get.
		 * @param bool       $force If we should expire cache and fetch from the database.
		 */
		$pre = apply_filters( 'tec_common_timed_option_pre_exists', null, $key, $force );

		if ( null !== $pre ) {
			return (bool) $pre;
		}

		$time         = time();
		$cached       = false;
		$timed_option = null;

		// If we have a stored value that is not expired, use it.
		if (
			! $force
			&& isset( $this->data[ $key ] )
			&& is_numeric( $this->data[ $key ]['expiration'] )
			&& $time < $this->data[ $key ]['expiration']
		) {
			$cached       = true;
			$timed_option = $this->data[ $key ];
		} elseif ( $this->is_active() ) {
			$timed_option_name = $this->get_option_name( $key );
			if ( true === $force ) {
				wp_cache_delete( $timed_option_name, 'options' );
			}
			$timed_option = get_option( $timed_option_name, null );
		}

		$exists = true;

		if ( null === $timed_option ) {
			$exists = false;
		}

		if ( ! is_array( $timed_option ) ) {
			$exists = false;
		}

		if ( ! isset( $timed_option['expiration'] ) || ! is_numeric( $timed_option['expiration'] ) ) {
			$exists = false;
		}

		/**
		 * Does a particular timed option key exists.
		 *
		 * @since 5.0.6
		 *
		 * @param mixed  $exists If anything diff than null it will short-circuit.
		 * @param string $key    Key for the option we are trying to get.
		 * @param bool   $force  If we should expire cache and fetch from the database.
		 * @param bool   $cached If the value was pulled from cache.
		 */
		return (bool) apply_filters( 'tec_common_timed_option_exists', $exists, $key, $force, $cached );
	}

	/**
	 * Update the value of a timed option on the database and on local cache.
	 *
	 * @since 5.0.6
	 *
	 * @param string $key        Key for this option.
	 * @param mixed  $value      Value stored for this option.
	 * @param int    $expiration Expiration in seconds for this timed option.
	 *
	 * @return bool
	 */
	public function set( $key, $value, int $expiration = DAY_IN_SECONDS ): bool {
		$key  = (string) $key;
		$data = [
			'key'        => $key,
			'value'      => $value,
			'expiration' => time() + $expiration,
		];

		$this->data[ $key ] = $data;
		$updated            = true;

		if ( $this->is_active() ) {
			$updated = update_option( $this->get_option_name( $key ), $data, true );
		}

		return $updated;
	}
}