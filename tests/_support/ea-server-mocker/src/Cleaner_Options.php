<?php


class Tribe__Events__Aggregator_Mocker__Cleaner_Options {

	public function hook() {
		add_action( 'ea_mocker-options_form', array( $this, 'fields' ), 5 );
	}

	public function fields() {
		?>
		<tr valign="top">
			<th scope="row">House Cleaning</th>
			<td>
				<label>
					<input type="checkbox" name="ea_mocker-clean-events" value="tribe_events">
					All Events (posts of type <code>tribe_events</code>)
				</label>
				<label>
					<input type="checkbox" name="ea_mocker-clean-ea-records" value="tribe-ea-record">
					All EA Records (posts of type <code>tribe-ea-record</code>)
				</label>
				<input type="submit" class="button-primary" value="Clean">

				<?php do_action( 'ea_mocker-cleaner-messages' ); ?>

			</td>
		</tr>
		<?php
	}
}