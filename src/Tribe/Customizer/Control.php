<?php
// Don't load directly
defined( 'WPINC' ) or die;

// abstract class Tribe__Customizer__Control extends WP_Customize_Color_Control {
	
// }

class Tribe__Customizer__Control extends WP_Customize_Control {
	public $type = 'customtext';
	public $extra = ''; // we add this for the extra description
	public function render_content() {
	?>
	<label>
			<span><?php echo esc_html( $this->extra ); ?></span>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<input type="text" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
	</label>
	<?php
	}
}