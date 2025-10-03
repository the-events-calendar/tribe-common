<?php

namespace Tribe\Tests\Dummies;

use TEC\Common\Abstracts\Custom_Table_Repository;

class Dummy_Custom_Table_Repository extends Custom_Table_Repository {
	public function get_model_class(): string {
		return Dummy_Model::class;
	}
};
