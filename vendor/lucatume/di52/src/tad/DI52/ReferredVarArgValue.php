<?php


	class tad_DI52_ReferredVarArgValue extends tad_DI52_ReferredArgValue{

		public function get_value() {
			return $this->container->get_var($this->alias);
		}
	}