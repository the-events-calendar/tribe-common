<?php

namespace Tribe\Dialog;

use function \GuzzleHttp\json_decode;

/**
 * Class View
 *
 * @since TBD
 */
class View extends \Tribe__Template {

	/**
	 * View constructor
	 *
	 * @param string $type
	 */
	public function __construct( $type = 'dialog' ) {
		$this->set_template_origin( \Tribe__Main::instance() );
		$this->set_template_folder( 'src/views/dialog' );

		// Configures this templating class to extract variables
		$this->set_template_context_extract( true );

		// Uses the public folders
		$this->set_template_folder_lookup( true );
	}

	/**
	 * Public wrapper for build method
	 *
	 * @since TBD
	 *
	 * @param array|string $message Array of messages or single message as string.
	 * @param array $args Extra arguments.
	 * @return string A string of html for the dialog.
	 */
	public function render_dialog( $message, $args = [] ) {
		if ( empty( $message ) ) {
			return;
		}

		$html = $this->build_dialog( $message, $args );

		return $html;
	}

	/**
	 * Factory method for dialog HTML
	 *
	 * @since TBD
	 *
	 * @param array|string $message array of messages or single message as string.
	 * @param array $args Extra arguments, defaults include additional classes, template and context (for the filters).
	 * @return string A string of html for the dialog.
	 */
	private function build_dialog( $content, $original_args ) {
		$default_args = [
			'classes'              => 'tribe-dialog',
			'context'              => '',
			'template'             => 'dialog',
			'wrap_classes'         => '',
			'trigger'              => '.tribe-dialog__trigger',
			'dialog_options'       => [
				'appendTarget'         => '', // the dialog will be inserted after the button, you could supply a selector string here to override
				'bodyLock'             => true, // lock the body while dialog open?
				'closeButtonAriaLabel' => 'Close this dialog window', // aria label for close button
				'closeButtonClasses'   => 'tribe-dialog__close-button', // classes for close button
				'contentClasses'       => 'tribe-dialog__content', // dialog content classes
				'effect'               => 'none', // none or fade (for now)
				'effectSpeed'          => 300, // effect speed in milliseconds
				'effectEasing'         => 'ease-in-out', // a css easing string
				'overlayClasses'       => 'tribe-dialog__overlay', // overlay classes
				'overlayClickCloses'   => true, // clicking overlay closes dialog
				'trigger'              => null, // the trigger for the dialog, can be selector string or element node
				'wrapperClasses'       => 'tribe-dialog', // the wrapper class for the dialog
			],
		];

		$args = wp_parse_args( $original_args, $default_args );

		// Check for message to be passed.
		if ( empty( $content ) ) {
			//return '';
			$content = 'test?';
		}

		$args['content'] = $content;

		/**
		 * Allow us to filter the dialog template
		 *
		 * @since  TBD
		 *
		 * @param string $template The dialog template name.
		 * @param array $args Extra arguments, defaults include icon, classes, direction, and context.
		 */
		$template_name = apply_filters( 'tribe_dialog_template', $args['template'], $args );

		ob_start();

		$template = $this->template( $template_name, $args, false );

		if ( ! empty( $template ) ) {
			 echo $template;
		}

		$html = ob_get_clean();

		/**
		 * Allow us to filter the dialog output
		 *
		 * @since  TBD
		 *
		 * @param string $html The dialog HTML.
		 * @param string $content HTML string of content.
		 * @param array $args Extra arguments, defaults include icon, classes, direction, and context.
		 */
		return apply_filters( 'tribe_dialog_html', $html, $content, $args );

	}

}
