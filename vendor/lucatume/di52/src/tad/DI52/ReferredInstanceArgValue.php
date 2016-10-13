<?php


	class tad_DI52_ReferredInstanceArgValue extends tad_DI52_ReferredArgValue {

		public function get_value() {
			return $this->container->make($this->alias);
		}
	}