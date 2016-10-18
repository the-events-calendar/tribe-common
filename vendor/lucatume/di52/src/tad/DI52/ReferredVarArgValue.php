<?php


	class tad_DI52_ReferredVarArgValue extends tad_DI52_ReferredArgValue{

		public function getValue() {
			return $this->container->getVar($this->alias);
		}
	}
