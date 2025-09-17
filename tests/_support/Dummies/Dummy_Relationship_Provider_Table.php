<?php

namespace Tribe\Tests\Dummies;

use TEC\Common\Abstracts\Custom_Table_Abstract;
use TEC\Common\Contracts\Model as Model_Interface;
use Exception;

class Dummy_Relationship_Provider_Table extends Custom_Table_Abstract {
	const SCHEMA_VERSION = '0.0.1-test';

	protected static $base_table_name = 'test_relationship_provider_table';

	protected static $group = 'test_group';

	protected static $schema_slug = 'test-relationship-provider';

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
			'dummy_id'           => [
				'type'     => self::COLUMN_TYPE_BIGINT,
				'php_type' => self::PHP_TYPE_INT,
				'length'   => 20,
				'nullable' => false,
				'index'    => true,
			],
			'post_id'           => [
				'type'     => self::COLUMN_TYPE_BIGINT,
				'php_type' => self::PHP_TYPE_INT,
				'length'   => 20,
				'nullable' => false,
				'index'    => true,
			],
		];
	}

	protected static function get_model_from_array( array $data ): Model_Interface {
		throw new Exception( 'Not implemented' );
	}
};
