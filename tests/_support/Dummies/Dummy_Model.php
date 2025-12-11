<?php

namespace Tribe\Tests\Dummies;

use TEC\Common\StellarWP\SchemaModels\SchemaModel;
use TEC\Common\StellarWP\Models\ValueObjects\Relationship;
use TEC\Common\StellarWP\Schema\Tables\Contracts\Table as Table_Interface;

class Dummy_Model extends SchemaModel {

	protected function constructRelationships(): void {
		$this->defineRelationship( 'posts', Relationship::MANY_TO_MANY, Dummy_Relationship_Provider_Table::class );
		$this->defineRelationshipColumns( 'posts', 'dummy_id', 'post_id' );
	}

	public static function getTableInterface(): Table_Interface {
		return tribe( Dummy_Table::class );
	}
};
