<?php

namespace PHPSTORM_META {

	// Allow PhpStorm IDE to resolve return types when calling tribe( Object_Type::class ) or tribe( `Object_Type` )
	use TEC\Common\StellarWP\ContainerContract\ContainerInterface;

	override(
		\tribe(),
		map( [
			// Custom Alias with global namespace.
			'admin.helpers'                         => \Tribe__Admin__Helpers::class,
			'admin.notice.php.version'              => \Tribe__Admin__Notice__Php_Version::class,
			'ajax.dropdown'                         => \Tribe__Ajax__Dropdown::class,
			'asset.data'                            => \Tribe__Asset__Data::class,
			'assets'                                => \Tribe__Assets::class,
			'assets.pipeline'                       => \Tribe__Assets_Pipeline::class,
			'cache'                                 => \Tribe__Cache::class,
			'callback'                              => \Tribe__Utils__Callback::class,
			'chunker'                               => \Tribe__Meta__Chunker::class,
			'context'                               => \Tribe__Context::class,
			'cost-utils'                            => \Tribe__Cost_Utils::class,
			'customizer'                            => \Tribe__Customizer::class,
			'db'                                    => \Tribe__Db::class,
			'editor'                                => \Tribe__Editor::class,
			'feature-detection'                     => \Tribe__Feature_Detection::class,
			'languages.locations'                   => \Tribe__Languages__Locations::class,
			'logger'                                => \Tribe__Log::class,
			'plugins.api'                           => \Tribe__Plugins_API::class,
			'post-duplicate'                        => \Tribe__Duplicate__Post::class,
			'post-duplicate.strategy-factory'       => \Tribe__Duplicate__Strategy_Factory::class,
			'post-transient'                        => \Tribe__Post_Transient::class,
			'promoter.auth'                         => \Tribe__Promoter__Auth::class,
			'promoter.connector'                    => \Tribe__Promoter__Connector::class,
			'promoter.pue'                          => \Tribe__Promoter__PUE::class,
			'promoter.view'                         => \Tribe__Promoter__View::class,
			'pue.notices'                           => \Tribe__PUE__Notices::class,
			'settings'                              => \Tribe__Settings::class,
			'settings.manager'                      => \Tribe__Settings_Manager::class,
			'tracker'                               => \Tribe__Tracker::class,

			// Custom alias with tribe namespace.
			'tooltip.view'                          => \Tribe\Tooltip\View::class,
			'db-lock'                               => \Tribe\DB_Lock::class,
			'common.service_providers.body_classes' => \Tribe\Utils\Body_Classes::class,
			'dialog.view'                           => \Tribe\Dialog\View::class,
			'monolog'                               => \Tribe\Log\Monolog_Logger::class,

			// Global to match the class name or using the full qualified file name as the class.
			''                                      => '@',
			''                                      => '@class',
		] )
	);

	override( ContainerInterface::get( 0 ), type( 0 ) );

	// Mark tribe_exit() as an exit point.
	exitPoint( \tribe_exit() );
}
