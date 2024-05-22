<?php


class Tribe__Events__Aggregator_Mocker__Notices {

	public function render() {
		?>
		<div class="notice notice-warning">
			<p>Event Aggregator server responses are being mocked!</p>
			<p><a href="<?php echo admin_url( 'admin.php?page=ea-mocker' ); ?>">Want to disable? Go here!</a></p>
		</div>
		<?php
	}
}