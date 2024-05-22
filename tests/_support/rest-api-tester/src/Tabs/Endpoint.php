<?php


class Tribe__RAP__Tabs__Endpoint extends Tribe__Tabbed_View__Tab {

	/**
	 * @var Tribe__Events__REST__V1__Endpoints__Base
	 */
	protected $endpoint;

	/**
	 * @return \Tribe__Events__REST__V1__Endpoints__Base
	 */
	public function get_endpoint() {
		return $this->endpoint;
	}

	public function set_endpoint( $endpoint ) {
		$this->endpoint = $endpoint;
	}
}