<?php

namespace TEC\Common\Settings;

/**
 * Helper class that creates HTML headings for use in Settings.
 *
 * @since TBD
 */
class Heading extends Abstract_Field  {
	public function render() {
		ob_start();
		?>
			<h3
				id="<?php echo esc_attr( $this->id ); ?>"
			><?php
				echo esc_html( $this->label )
			?></h3>
		<?php

		$content = ob_end_clean();

		$content = apply_filters(
			'tec-settings-field-heading-content',
			$this->content,
			$this
		);

		echo $content;
	}
}
