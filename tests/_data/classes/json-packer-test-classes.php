<?php
// Test classes for the JSON packer tests.

namespace Tribe\tests\integration\Json_Packer;

class Test_User {
	private string $username;
	private string $email;
	private bool $active;

	public function __construct( string $username, string $email, bool $active ) {
		$this->username = $username;
		$this->email    = $email;
		$this->active   = $active;
	}

	public function getUsername(): string {
		return $this->username;
	}

	public function getEmail(): string {
		return $this->email;
	}

	public function isActive(): bool {
		return $this->active;
	}
}

class Test_Admin_User extends \Tribe\tests\integration\Json_Packer\Test_User {
	private array $permissions;

	public function __construct( string $username, string $email, bool $active, array $permissions ) {
		parent::__construct( $username, $email, $active );
		$this->permissions = $permissions;
	}

	public function getPermissions(): array {
		return $this->permissions;
	}
}
class Test_Address {
	private string $street;
	private string $city;
	private string $state;
	private string $zip;

	public function __construct( string $street, string $city, string $state, string $zip ) {
		$this->street = $street;
		$this->city   = $city;
		$this->state  = $state;
		$this->zip    = $zip;
	}

	public function getStreet(): string {
		return $this->street;
	}

	public function getCity(): string {
		return $this->city;
	}

	public function getState(): string {
		return $this->state;
	}

	public function getZip(): string {
		return $this->zip;
	}
}

class Test_User_With_Address extends Test_User {
	private \Tribe\tests\integration\Json_Packer\Test_Address $address;

	public function __construct( string $username, string $email, bool $active, Test_Address $address ) {
		parent::__construct( $username, $email, $active );
		$this->address = $address;
	}

	public function getAddress(): Test_Address {
		return $this->address;
	}
}

class Test_User_With_Friend extends Test_User {
	private ?\Tribe\tests\integration\Json_Packer\Test_User_With_Friend $friend = null;

	public function setFriend( Test_User_With_Friend $friend ): void {
		$this->friend = $friend;
	}

	public function getFriend(): ?Test_User_With_Friend {
		return $this->friend;
	}
}

class Test_Object_With_Uninitialized_Property {
	private string $initialized = 'initialized value';
	private string $uninitialized;

	public function hasInitialized(): bool {
		return $this->initialized === 'initialized value';
	}
}
// @phpcs::enable

