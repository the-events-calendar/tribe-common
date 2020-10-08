<?php

namespace Tribe\Widget;

use Tribe\Events\Views\V2\View;
use Tribe\Events\Views\V2\View_Interface;
use Tribe__Utils__Array as Arr;
use Tribe__Context as Context;

/**
 * The abstract all widgets should implement.
 *
 * @since   TBD
 *
 * @package Tribe\Widget
 *
 */
abstract class Widget_Abstract extends \WP_Widget implements Widget_Interface {

	/**
	 * Slug of the current widget.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected $slug;

	/**
	 * The view interface for the widget.
	 *
	 * @since TBD
	 *
	 * @var View_Interface;
	 */
	protected $view;

	/**
	 * The slug of the widget view.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected $view_slug;

	/**
	 * Default arguments to be merged into final arguments of the widget.
	 *
	 * @since TBD
	 *
	 * @var array<string,mixed>
	 */
	protected $default_arguments = [];

	/**
	 * Array map allowing aliased widget arguments.
	 *
	 * The array keys are aliases of the array values (i.e. the "real" widget attributes to parse).
	 * Example array: [ 'alias' => 'canonical', 'from' => 'to', 'that' => 'becomes_this' ]
	 * Example widget usage: [some_tag alias=17 to='Fred'] will be parsed as [some_tag canonical=17 to='Fred']
	 *
	 * @since TBD
	 *
	 * @var array<string,string>
	 */
	protected $aliased_arguments = [];

	/**
	 * Array of callbacks for validation of arguments.
	 *
	 * @since TBD
	 *
	 * @var array<string,callable>
	 */
	protected $validate_arguments_map = [];

	/**
	 * Arguments of the current widget.
	 *
	 * @since TBD
	 *
	 * @var array<string,mixed>
	 */
	protected $arguments;

	/**
	 * HTML content of the current widget.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected $content;

	/**
	 * {@inheritDoc}
	 */
	public function __construct( $id_base = '', $name = '', $widget_options = [], $control_options = [] ) {
		$arguments = $this->get_arguments();

		parent::__construct(
			Arr::get( $arguments, 'id_base', '' ),
			Arr::get( $arguments, 'name', '' ),
			Arr::get( $arguments, 'widget_options', [] ),
			Arr::get( $arguments, 'control_options', [] )
		);

		// Setup a view instance for the widget.
		$this->setup_view();

		// @todo add what this does in in TEC-3612 & TEC-3613.
		$this->setup();
	}

	/**
	 * Setup the widget.
	 *
	 * @todo update in TEC-3612 & TEC-3613
	 *
	 * @since TBD
	 *
	 * @return mixed
	 */
	public abstract function setup();

	/**
	 * Setup the view for the widget.
	 *
	 * @since TBD
	 */
	public function setup_view() {
		$context = tribe_context();

		// Modifies the Context for the widget params.
		// todo update per https://github.com/moderntribe/tribe-common/pull/1451#discussion_r501498990
		$context = $this->alter_context( $context );

		// Setup the view instance.
		$view = View::make( $this->get_view_slug(), $context );

		$view->get_template()->set_values( $this->get_arguments(), false );

		$this->set_view( $view );
	}

	/**
	 * Echoes the widget content.
	 *
	 * @since TBD
	 *
	 * @param array<string,mixed> $args     Display arguments including 'before_title', 'after_title',
	 *                                      'before_widget', and 'after_widget'.
	 * @param array<string,mixed> $instance The settings for the particular instance of the widget.
	 */
	public function widget( $args, $instance ) {
		echo $this->get_html();
	}

	/**
	 * Returns the rendered View HTML code.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_html() {
		return $this->get_view()->get_html();
	}

	/**
	 * {@inheritDoc}
	 */
	public function set_aliased_arguments( array $alias_map ) {
		$this->aliased_arguments = Arr::filter_to_flat_scalar_associative_array( (array) $alias_map );
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_aliased_arguments() {
		return $this->aliased_arguments;
	}

	/**
	 * {@inheritDoc}
	 */
	public function parse_arguments( array $arguments ) {
		$arguments = Arr::parse_associative_array_alias( (array) $arguments, (array) $this->get_aliased_arguments() );

		return $this->validate_arguments( $arguments );
	}

	/**
	 * {@inheritDoc}
	 */
	public function validate_arguments( array $arguments ) {
		$validate_arguments_map = $this->get_validated_arguments_map();
		foreach ( $validate_arguments_map as $key => $callback ) {
			$arguments[ $key ] = $callback( isset( $arguments[ $key ] ) ? $arguments[ $key ] : null );
		}

		return $arguments;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_registration_slug() {
		return $this->slug;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_validated_arguments_map() {
		/**
		 * Applies a filter to the validation map for instance arguments.
		 *
		 * @since TBD
		 *
		 * @param array<string,callable> $validate_arguments_map Current set of callbacks for arguments.
		 * @param static                             $instance                            The widget instance we are dealing with.
		 */
		$validate_arguments_map = apply_filters( 'tribe_widget_validate_arguments_map', $this->validate_arguments_map, $this );

		$registration_slug = $this->get_registration_slug();

		/**
		 * Applies a filter to the validation map for instance arguments for a specific widget. Based on the registration slug of the widget
		 *
		 * @since TBD
		 *
		 * @param array<string,callable> $validate_arguments_map Current set of callbacks for arguments.
		 * @param static                            $instance                             The widget instance we are dealing with.
		 */
		$validate_arguments_map = apply_filters( "tribe__widget_{$registration_slug}_validate_arguments_map", $validate_arguments_map, $this );

		return $validate_arguments_map;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_arguments() {

		return $this->filter_arguments( $this->arguments );
	}

	/**
	 * {@inheritDoc}
	 */
	public function filter_arguments( $arguments ) {
		/**
		 * Applies a filter to instance arguments.
		 *
		 * @since TBD
		 *
		 * @param array<string,mixed> $arguments Current set of arguments.
		 * @param static              $instance  The widget instance we are dealing with.
		 */
		$arguments = apply_filters( 'tribe_widget_arguments', $arguments, $this );

		$registration_slug = $this->get_registration_slug();

		/**
		 * Applies a filter to instance arguments based on the registration slug of the widget.
		 *
		 * @since TBD
		 *
		 * @param array<string,mixed> $arguments Current set of arguments.
		 * @param static              $instance  The widget instance we are dealing with.
		 */
		$arguments = apply_filters( "tribe_widget_{$registration_slug}_arguments", $arguments, $this );

		return $arguments;
	}


	/**
	 * {@inheritDoc}
	 */
	public function get_argument( $index, $default = null ) {
		$arguments = $this->get_arguments();
		$argument  = Arr::get( $arguments, $index, $default );

		return $this->filter_argument( $argument, $index, $default );
	}

	/**
	 * {@inheritDoc}
	 */
	public function filter_argument( $argument, $index, $default = null  ) {
		/**
		 * Applies a filter to a specific widget argument, catch all for all widgets.
		 *
		 * @since TBD
		 *
		 * @param mixed               $argument The argument.
		 * @param string|int          $index    Which index we intend to fetch from the arguments.
		 * @param array<string,mixed> $default  Default value if it doesn't exist.
		 * @param static              $instance The widget instance we are dealing with.
		 */
		$argument = apply_filters( 'tribe_widget_argument', $argument, $index, $default, $this );

		$registration_slug = $this->get_registration_slug();

		/**
		 * Applies a filter to a specific widget argument, to a particular registration slug.
		 *
		 * @since TBD
		 *
		 * @param mixed      $argument The argument value.
		 * @param string|int $index    Which index we intend to fetch from the arguments.
		 * @param array      $default  Default value if it doesn't exist.
		 * @param static     $instance The widget instance we are dealing with.
		 */
		$argument = apply_filters( "tribe_widget_{$registration_slug}_argument", $argument, $index, $default, $this );

		return $argument;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_default_arguments() {
		return $this->filter_default_arguments( $this->default_arguments );
	}

	/**
	 * {@inheritDoc}
	 */
	public function filter_default_arguments( array $default_arguments = [] ) {
		/**
		 * Applies a filter to default instance arguments.
		 *
		 * @since TBD
		 *
		 * @param array<string,mixed>  $default_arguments Current set of default arguments.
		 * @param static               $instance          The widget instance we are dealing with.
		 */
		$default_arguments = apply_filters( 'tribe_widget_default_arguments', $default_arguments, $this );

		$registration_slug = $this->get_registration_slug();

		/**
		 * Applies a filter to default instance arguments based on the registration slug of the widget.
		 *
		 * @since TBD
		 *
		 * @param array<string,mixed>  $default_arguments Current set of default arguments.
		 * @param static               $instance          The widget instance we are dealing with.
		 */
		$default_arguments = apply_filters( "tribe_widget_{$registration_slug}_default_arguments", $default_arguments, $this );

		return $default_arguments;
	}

	/**
	 * {@inheritDoc}
	 */
	public function set_view( View_Interface $view ) {
		$this->view = $view;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_view() {
		return $this->view;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_view_slug() {
		return $this->view_slug;
	}

	/**
	 * Alters the widget context with its arguments.
	 *
	 * @todo update in TEC-3612 & TEC-3613
	 *
	 * @since  TBD
	 *
	 * @param \Tribe__Context $context   Context we will use to build the view.
	 * @param array<string,mixed>        $arguments Current set of arguments.
	 *
	 * @return \Tribe__Context Context after widget changes.
	 */
	public function alter_context( Context $context, array $arguments = [] ) {
		// todo update per https://github.com/moderntribe/tribe-common/pull/1451#discussion_r501498990
		$alter_context = $this->args_to_context( $arguments, $context );

		$context = $context->alter( $alter_context );

		return $context;
	}

	/**
	 * Translates widget arguments to their Context argument counterpart.
	 *
	 * @todo update in TEC-3612 & TEC-3613
	 *
	 * @since TBD
	 *
	 * @param array<string,mixed>   $arguments Current set of arguments.
	 * @param Context               $context   The request context.
	 *
	 * @return array<string,mixed> The translated widget arguments.
	 */
	protected function args_to_context( array $arguments, Context $context ) {
		$context_args = [ 'widget' => true ];

		return $context_args;
	}
}
