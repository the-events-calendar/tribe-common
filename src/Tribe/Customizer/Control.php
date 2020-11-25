<?php

namespace Tribe\Customizer;

abstract class Control extends \WP_Customize_Control {
	// public $type = 'customtext';
	
	/**
	 * Custom heading which displays above the control
	 *
	 * @since TBD
	 *
	 * @access public
	 * @var string
	 */
	// public $extra = '';
	
	/**
	 * Render the control's content
	 *
	 * @since TBD
	 */
	/* abstract public function render_content() {
		?>
		<label>
				<?php if ( isset( $this->extra ) ) { ?>
					<h4><?php echo esc_html( $this->extra ); ?></h4>
				<?php } ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<input type="text" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
		</label>
		<?php
	} */
}