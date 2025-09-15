<?php

namespace Tribe\Tests\Dummies;

use TEC\Common\Abstracts\Model_Abstract;
use TEC\Common\Abstracts\Custom_Table_Abstract;
use DateTimeInterface;

class Dummy_Model extends Model_Abstract {
	protected $name;
	protected $description;
	protected $status;
	protected $value;
	protected $created_at;
	protected $updated_at;

	public function get_name() {
		return $this->name;
	}

	public function set_name( string $name ) {
		$this->name = $name;
		return $this;
	}

	public function get_description(): ?string {
		return $this->description;
	}

	public function set_description( ?string $description ) {
		$this->description = $description;
		return $this;
	}

	public function get_status(): ?string {
		return $this->status;
	}

	public function set_status( ?string $status ) {
		$this->status = $status;
		return $this;
	}

	public function get_value(): ?int {
		return $this->value;
	}

	public function set_value( ?int $value ) {
		$this->value = $value;
		return $this;
	}

	public function get_created_at(): DateTimeInterface {
		return $this->created_at;
	}

	public function set_created_at( DateTimeInterface $created_at ) {
		$this->created_at = $created_at;
		return $this;
	}

	public function get_updated_at(): ?DateTimeInterface {
		return $this->updated_at;
	}

	public function set_updated_at( ?DateTimeInterface $updated_at ) {
		$this->updated_at = $updated_at;
		return $this;
	}

	public function get_table_interface(): Custom_Table_Abstract {
		return tribe( Dummy_Table::class );
	}
};
