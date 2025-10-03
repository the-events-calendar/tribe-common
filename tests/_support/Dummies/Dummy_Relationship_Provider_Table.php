<?php

namespace Tribe\Tests\Dummies;

use TEC\Common\StellarWP\Schema\Tables\Contracts\Table;
use TEC\Common\StellarWP\Schema\Collections\Column_Collection;
use TEC\Common\StellarWP\Schema\Columns\ID;
use TEC\Common\StellarWP\Schema\Columns\Referenced_ID;
use TEC\Common\StellarWP\Schema\Tables\Table_Schema;

class Dummy_Relationship_Provider_Table extends Table {
	const SCHEMA_VERSION = '0.0.1-test';

	protected static $base_table_name = 'test_relationship_provider_table';

	protected static $group = 'test_group';

	protected static $schema_slug = 'test-relationship-provider';

	public static function get_schema_history(): array {
		$table_name = static::table_name( true );
		return [
			self::SCHEMA_VERSION => function () use ( $table_name ) {
				$columns = new Column_Collection();

				$columns[] = new ID( 'id' );
				$columns[] = new Referenced_ID( 'dummy_id' );
				$columns[] = new Referenced_ID( 'post_id' );

				return new Table_Schema( $table_name, $columns );
			},
		];
	}

	public static function transform_from_array( array $data ) {
		return $data;
	}
};
