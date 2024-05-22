<?php


class Tab_Extension extends Tribe__Tabbed_View__Tab {

	/**
	 * Enforces a method to display the tab or not
	 *
	 * @return boolean
	 */
	public function is_visible() {
	}

	/**
	 * Enforces a method to return the Label of the Tab
	 *
	 * @return string
	 */
	public function get_label() {
	}

	/**
	 * Enforces a method to return the Tab Slug
	 *
	 * @return string
	 */
	public function get_slug() {
		return 'tab_extension';
	}
}