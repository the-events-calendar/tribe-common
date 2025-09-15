<?php
/**
 * Tests for Custom_Table_Repository abstract class.
 *
 * @since TBD
 *
 * @package TEC\Common\Abstracts
 */

namespace TEC\Common\Abstracts;

use Tribe\Tests\Dummies\Dummy_Model;
use Tribe\Tests\Dummies\Dummy_Table;
use Tribe\Tests\Dummies\Dummy_Custom_Table_Repository;
use Tribe\Tests\Testcases\Common\Abstracts\Abstract_Custom_Table_Repository_Testcase;


/**
 * Class Custom_Table_Repository_Test
 *
 * Tests the basic CRUD operations of the Custom_Table_Repository abstract class.
 *
 * @since TBD
 *
 * @package TEC\Common\Abstracts
 */
class Custom_Table_Repository_Test extends Abstract_Custom_Table_Repository_Testcase {
	/**
	 * The test model class.
	 *
	 * @var string
	 */
	protected string $test_model_class = Dummy_Model::class;

	/**
	 * The test repository class.
	 *
	 * @var string
	 */
	protected string $test_repository_class = Dummy_Custom_Table_Repository::class;

	/**
	 * The test table class.
	 *
	 * @var string
	 */
	protected string $test_table_class = Dummy_Table::class;
}
