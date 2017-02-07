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
				<fieldset>
					<label>
						<input type="checkbox" name="ea_mocker-clean-events" value="tribe_events">
						All Events (posts of type <code>tribe_events</code> and meta)
					</label>
					<label>
						<input type="checkbox" name="ea_mocker-clean-venues" value="tribe_venue">
						All Venues (posts of type <code>tribe_venue</code> and meta)
					</label>
					<label>
						<input type="checkbox" name="ea_mocker-clean-organizers" value="tribe_organizer">
						All Organizers (posts of type <code>tribe_organizer</code> and meta)
					</label>
					<label>
						<input type="checkbox" name="ea_mocker-clean-ea-records" value="tribe-ea-record">
						All EA Records (posts of type <code>tribe-ea-record</code> and meta)
					</label>
					<input type="submit" class="button-primary" value="Clean">
					<input type="submit" class="button-primary" value="Clean all" name="ea_mocker-clean-all">
				</fieldset>
			</td>
		</tr>
		<?php
	}
}