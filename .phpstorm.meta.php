<?php

namespace PHPSTORM_META {
	// Allow PHP Storm Editor to resolve return types when calling tribe( Object_Type::class ) or tribe( `Object_type` )
	override(
		\tribe( 0 ),
		map( [
			'' => '@',
			'' => '@Class',
		] )
	);
}
