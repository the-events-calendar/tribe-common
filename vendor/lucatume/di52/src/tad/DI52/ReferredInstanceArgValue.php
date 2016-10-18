<?php


	class tad_DI52_ReferredInstanceArgValue extends tad_DI52_ReferredArgValue {

		public function getValue() {
			return $this->container->make($this->alias);
		}
	}
