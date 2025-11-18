<?php
/**
 * Defines the schema for the key-value cache table.
 *
 * @since 6.9.1
 *
 * @package TEC\Common\Key_Value_Cache\Table;
 */

namespace TEC\Common\Key_Value_Cache\Table;

use TEC\Common\StellarWP\Schema\Collections\Column_Collection;
use TEC\Common\StellarWP\Schema\Columns\Column_Types;
use TEC\Common\StellarWP\Schema\Columns\String_Column;
use TEC\Common\StellarWP\Schema\Columns\Integer_Column;
use TEC\Common\StellarWP\Schema\Columns\Text_Column;
use TEC\Common\StellarWP\Schema\Tables\Contracts\Table;
use TEC\Common\StellarWP\Schema\Tables\Table_Schema;

/**
 * Class Schema.
 *
 * @since 6.9.1
 *
 * @package TEC\Common\Key_Value_Cache\Table;
 */
class Schema extends Table {
	/**
	 * The version number for this schema definition.
	 *
	 * @since 6.9.1
	 *
	 * @var string|null
	 */
	const SCHEMA_VERSION = '1.0.0';

	/**
	 * The base table name.
	 *
	 * @since 6.9.1
	 *
	 * @var string
	 */
	protected static $base_table_name = 'tec_kv_cache';

	/**
	 * The organizational group this table belongs to.
	 *
	 * @since 6.9.1
	 *
	 * @var string
	 */
	protected static $group = 'tec';

	/**
	 * The slug used to identify the custom table.
	 *
	 * @since 6.9.1
	 *
	 * @var string|null
	 */
	protected static $schema_slug = 'tec-kv-cache';

	/**
	 * The field that uniquely identifies a row in the table.
	 *
	 * @since 6.9.1
	 *
	 * @var string
	 */
	protected static $uid_column = 'key';

	/**
	 * Get the schema history.
	 *
	 * @since 6.10.0
	 *
	 * @return array
	 */
	public static function get_schema_history(): array {
		$table_name = self::table_name();

		return [
			self::SCHEMA_VERSION => function () use ( $table_name ) {
				$columns = new Column_Collection();

				$columns[] = ( new String_Column( 'cache_key' ) )->set_length( 191 )->set_is_primary_key( true );
				$columns[] = ( new Text_Column( 'value' ) )->set_nullable( true )->set_type( Column_Types::LONGTEXT );
				$columns[] = ( new Integer_Column( 'expiration' ) )->set_default( 0 )->set_signed( false )->set_length( 20 );

				return new Table_Schema( $table_name, $columns );
			},
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_definition(): string {
		global $wpdb;
		$table_name      = self::table_name( true );
		$charset_collate = $wpdb->get_charset_collate();

		/*
		 * The cache_key length of 191 is taken to stick with the index length of the meta_key freom the
		 * `wp_postmeta` table.
		 */

		return "CREATE TABLE {$table_name} (
				cache_key varchar(191) NOT NULL,
				value longtext DEFAULT NULL,
				expiration bigint(20) UNSIGNED DEFAULT 0,
				PRIMARY KEY  (cache_key)
			) {$charset_collate}; ";
	}
}
