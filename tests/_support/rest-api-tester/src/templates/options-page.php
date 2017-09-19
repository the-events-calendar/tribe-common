<?php
/**
 * @var string $json
 * @var array $users
 * @var bool $is_documentation
 * @var array $documentation
 * @var string $current_path
 * @var string $current_url
 * @var array $methods_map
 * @var string $documentation_json
 */
?>
<div id="trap-wrap">

	<div id="trap-json" class="hidden">
		<?php echo $json; ?>
	</div>

	<input type="hidden" name="trap-nonce" id="trap-nonce" value="">
	<input type="hidden" name="trap-path" id="trap-path" value="<?php echo $current_path; ?>">
	<input type="hidden" name="trap-url" id="trap-url" value="<?php echo $current_url; ?>">

	<h2>Response</h2>
	<div id="trap-response" class="full-width medium-height"></div>

	<br>

	<h3>Request</h3>
	<div class="request">
		<div class="margin">
			<label for="trap-request-method">Method:</label>
			<select name="trap-request-method" id="trap-request-method">
				<option value="get">GET</option>
				<option value="post">POST</option>
				<option value="put">PUT</option>
				<option value="patch">PATCH</option>
				<option value="delete">DELETE</option>
				<option value="head">HEAD</option>
			</select>
		</div>

		<div class="margin">
			<label for="trap-user-id">User:</label>
			<select name="trap-user-id" id="trap-user-id">
				<option value="0">Visitor (not logged-in)</option>
				<?php /** @var WP_User $user */
				foreach ( $users as $user ) : ?>
					<option value="<?php echo $user->get( 'ID' ); ?>">
						<?php echo $user->get( 'user_nicename' ); ?> (<?php echo implode( ',', $user->roles ); ?>)
					</option>
				<?php endforeach; ?>
			</select>
		</div>

		<?php if ( ! $is_documentation ) : ?>
			<div>
				<?php foreach ( $documentation as $parent_method => $data ) : ?>
					<?php
					$parent_method = strtolower( $parent_method );
					$methods       = $methods_map[ $parent_method ];
					?>
					<?php foreach ( $methods as $method ) : ?>
						<?php $method = strtolower( $method ); ?>
						<fieldgroup class="method-parameters" id="<?php echo $method; ?>-method-parameters">

							<h3><?php echo strtoupper( $method ); ?> Request parameters</h3>

							<?php foreach ( $data['parameters'] as $parameter ) : ?>
								<div class="method-parameter margin">
									<?php
									$required = true === ! empty( $parameter['required'] ) ? 'required' : '';
									$default  = ! empty( $parameter['default'] ) ? $parameter['default'] : '';
									$type     = ! empty( $parameter['type'] ) ? $parameter['type'] : 'string';
									$name     = "{$method}-{$parameter['name']}";
									$in       = ! empty( $parameter['in'] ) ? $parameter['in'] : 'query';
									?>
									<label
										for="<?php echo $name; ?>"><?php echo $parameter['description']; ?></label>
									<?php if ( 'boolean' === $type ) : ?>
										<input
											type="checkbox"
											name="<?php echo esc_attr( $name ); ?>"
											id="<?php echo esc_attr( $name ); ?>" <?php echo $required; ?>
											value="1"
											data-name="<?php echo esc_attr( $parameter['name'] ); ?>"
											data-in="<?php echo esc_attr( $in ); ?>"
											<?php checked( 1, $default ); ?>
										>
									<?php elseif ( in_array( $type, array( 'number', 'integer' ), true ) ) : ?>
										<input
											type="number"
											name="<?php echo esc_attr( $name ); ?>"
											id="<?php echo esc_attr( $name ); ?>" <?php echo $required; ?>
											value="<?php echo esc_attr( $default ); ?>"
											data-name="<?php echo esc_attr( $parameter['name'] ); ?>"
											data-in="<?php echo esc_attr( $in ); ?>"
											<?php checked( 1, $default ); ?>
										>
									<?php else : ?>
										<input
											type="text"
											name="<?php echo esc_attr( $name ); ?>"
											id="<?php echo esc_attr( $name ); ?>" <?php echo $required; ?>
											value="<?php echo esc_attr( $default ); ?>"
											data-name="<?php echo esc_attr( $parameter['name'] ); ?>"
											data-in="<?php echo esc_attr( $in ); ?>"
										>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						</fieldgroup>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

	</div>

	<div>
		<button id="trap-request" class="button-primary margin">
			Request
		</button>
	</div>

	<?php if ( ! $is_documentation ) : ?>
		<h3>Documentation</h3>
		<div id="trap-documentation-json" class="hidden">
			<?php echo $documentation_json; ?>
		</div>
		<div id="trap-documentation"></div>
	<?php endif; ?>
</div>

