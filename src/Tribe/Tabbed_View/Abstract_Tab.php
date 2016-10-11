<?php


abstract class Tribe__Tabbed_View__Abstract_Tab {

	/**
	 * To Order the Tabs on the UI you need to change the priority
	 * @var integer
	 */
	public $priority = 50;

	/**
	 * Enforces a method to display the tab or not
	 *
	 * @return boolean
	 */
	abstract public function is_visible();

	/**
	 * Enforces a method to return the Tab Slug
	 *
	 * @return string
	 */
	abstract public function get_slug();

	/**
	 * Enforces a method to return the Label of the Tab
	 *
	 * @return string
	 */
	abstract public function get_label();
}