<?php

namespace Tribe\Tests\Dummies;

use TEC\Common\StellarWP\Schema\Tables\Contracts\Table;
use TEC\Common\StellarWP\Schema\Collections\Column_Collection;
use TEC\Common\StellarWP\Schema\Columns\ID;
use TEC\Common\StellarWP\Schema\Columns\String_Column;
use TEC\Common\StellarWP\Schema\Columns\Text_Column;
use TEC\Common\StellarWP\Schema\Columns\Integer_Column;
use TEC\Common\StellarWP\Schema\Columns\Created_At;
use TEC\Common\StellarWP\Schema\Columns\Updated_At;
use TEC\Common\StellarWP\Schema\Tables\Table_Schema;

class Dummy_Table extends Table {
	const SCHEMA_VERSION = '0.0.1-test';

	protected static $base_table_name = 'test_repository_table';

	protected static $group = 'test_group';

	protected static $schema_slug = 'test-repository';

	public static function get_schema_history(): array {
		$table_name = static::table_name( true );

		return [
			self::SCHEMA_VERSION => function () use ( $table_name ) {
				$columns = new Column_Collection();

				$columns[] = new ID( 'id' );
				$columns[] = ( new String_Column( 'name' ) );
				$columns[] = ( new Text_Column( 'description' ) )->set_nullable( true );
				$columns[] = ( new String_Column( 'status' ) )->set_default( 'active' )->set_nullable( true );
				$columns[] = ( new Integer_Column( 'value' ) );
				$columns[] = new Created_At( 'created_at' );
				$columns[] = new Updated_At( 'updated_at' );

				return new Table_Schema( $table_name, $columns );
			},
		];
	}

	public static function transform_from_array( array $data ) {
		$model = new Dummy_Model();

		// Populate model with data
		foreach ( $data as $key => $value ) {
			$method = 'set_' . $key;
			$model->$method( $value );
		}

		return $model;
	}
};
