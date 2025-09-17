<?php

namespace Tribe\Tests\Dummies;

use TEC\Common\Abstracts\Custom_Table_Abstract;
use TEC\Common\Contracts\Model as Model_Interface;

class Dummy_Table extends Custom_Table_Abstract {
	const SCHEMA_VERSION = '0.0.1-test';

	protected static $base_table_name = 'test_repository_table';

	protected static $group = 'test_group';

	protected static $schema_slug = 'test-repository';

	protected static $uid_column = 'id';

	public static function get_columns(): array {
		return [
			'id'          => [
				'type'           => self::COLUMN_TYPE_BIGINT,
				'length'         => 20,
				'unsigned'       => true,
				'auto_increment' => true,
				'nullable'       => false,
				'php_type'       => self::PHP_TYPE_INT,
			],
			'name'        => [
				'type'     => self::COLUMN_TYPE_VARCHAR,
				'length'   => 255,
				'nullable' => false,
				'php_type' => self::PHP_TYPE_STRING,
			],
			'description' => [
				'type'     => self::COLUMN_TYPE_TEXT,
				'nullable' => true,
				'php_type' => self::PHP_TYPE_STRING,
			],
			'status'      => [
				'type'     => self::COLUMN_TYPE_VARCHAR,
				'length'   => 50,
				'default'  => 'active',
				'nullable' => true,
				'php_type' => self::PHP_TYPE_STRING,
			],
			'value'       => [
				'type'     => self::COLUMN_TYPE_BIGINT,
				'length'   => 11,
				'default'  => 0,
				'nullable' => true,
				'php_type' => self::PHP_TYPE_INT,
			],
			'created_at'  => [
				'type'     => self::COLUMN_TYPE_TIMESTAMP,
				'default'  => 'CURRENT_TIMESTAMP',
				'nullable' => false,
				'php_type' => self::PHP_TYPE_DATETIME,
			],
			'updated_at'  => [
				'type'     => self::COLUMN_TYPE_TIMESTAMP,
				'nullable' => true,
				'php_type' => self::PHP_TYPE_DATETIME,
			],
		];
	}

	protected static function get_model_from_array( array $data ): Model_Interface {
		$model = new Dummy_Model();

		// Populate model with data
		foreach ( $data as $key => $value ) {
			$method = 'set_' . $key;
			$model->$method( $value );
		}

		return $model;
	}
};
