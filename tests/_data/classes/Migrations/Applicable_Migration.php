<?php

namespace TEC\Common\Tests\Migrations;

use TEC\Common\StellarWP\Migrations\Abstracts\Migration_Abstract;
use TEC\Common\StellarWP\Migrations\Enums\Operation;

/**
 * A migration that always applies, so reading its status queries the executions table.
 */
class Applicable_Migration extends Migration_Abstract {
	public function get_label(): string {
		return 'Applicable migration';
	}

	public function get_description(): string {
		return 'A migration used by the tests.';
	}

	public function get_total_items( ?Operation $operation = null ): int {
		return 1;
	}

	public function get_default_batch_size(): int {
		return 1;
	}

	public function is_applicable(): bool {
		return true;
	}

	public function is_up_done(): bool {
		return false;
	}

	public function is_down_done(): bool {
		return true;
	}

	public function up( int $batch, int $batch_size ): void {
	}

	public function down( int $batch, int $batch_size ): void {
	}
}
