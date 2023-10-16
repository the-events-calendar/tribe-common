<?php

namespace TEC\Common\Integrations;

class Dummy_Module extends Integration_Abstract {
	use Traits\Module_Integration;

	/**
	 * @var bool Used for tests purposes.
	 */
	public bool $tests_was_loaded = false;

	/**
	 * @var bool Used for tests purposes.
	 */
	public bool $tests_should_load = false;

	/**
	 * @inheritDoc
	 */
	public static function get_slug(): string {
		return 'dummy-module';
	}

	/**
	 * @inheritDoc
	 */
	public static function get_parent(): string {
		return 'dummy-parent';
	}

	/**
	 * @inheritDoc
	 */
	public function load_conditionals(): bool {
		return $this->tests_should_load;
	}

	/**
	 * @inheritDoc
	 */
	protected function load(): void {
		$this->tests_was_loaded = true;
	}
}