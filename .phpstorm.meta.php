<?php

namespace PHPSTORM_META {

	// Allow PhpStorm IDE to resolve return types when calling tribe( Object_Type::class ) or tribe( `Object_Type` )


	override(
		\tribe(),
		map( [
			// Custom Alias with global namespace.
			'promoter.auth'                   => \Tribe__Promoter__Auth::class,
			'promoter.pue'                    => \Tribe__Promoter__PUE::class,
			'promoter.view'                   => \Tribe__Promoter__View::class,
			'feature-detection'               => \Tribe__Feature_Detection::class,
			'settings.manager'                => \Tribe__Settings_Manager::class,
			'settings'                        => \Tribe__Settings::class,
			'ajax.dropdown'                   => \Tribe__Ajax__Dropdown::class,
			'asssassets'                      => \Tribe__Assets::class,
			'assets.pipeline'                 => \Tribe__Assets_Pipeline::class,
			'asset.data'                      => \Tribe__Asset__Data::class,
			'admin.helpers'                   => \Tribe__Admin__Helpers::class,
			'tracker'                         => \Tribe__Tracker::class,
			'chunker'                         => \Tribe__Meta__Chunker::class,
			'cache'                           => \Tribe__Cache::class,
			'languages.locations'             => \Tribe__Languages__Locations::class,
			'plugins.api'                     => \Tribe__Plugins_API::class,
			'logger'                          => \Tribe__Log::class,
			'cost-utils'                      => \Tribe__Cost_Utils::class,
			'post-duplicate.strategy-factory' => \Tribe__Duplicate__Strategy_Factory::class,
			'post-duplicate'                  => \Tribe__Duplicate__Post::class,
			'context'                         => \Tribe__Context::class,

			// Custom alias with tribe namespace.
			'tooltip.view'                    => \Tribe\Tooltip\View::class,

			// Global to match the class name or using the full qualified file name as the class.
			''                                => '@',
			''                                => '@Class',
		] )
	);
}
