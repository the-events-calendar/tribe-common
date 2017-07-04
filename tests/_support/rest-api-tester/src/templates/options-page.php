<?php
/**
 * @var string $json
 * @var array $users
 * @var bool $is_documentation
 * @var string $documentation
 * @var string $current_path
 * @var string $current_url
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
		<div>
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

		<div>
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
				<?php foreach ( $documentation as $method => $data ) : ?>
					<?php $method = strtolower( $method ); ?>
					<fieldgroup class="method-parameters" id="<?php echo $method; ?>-method-parameters">

						<h3><?php echo strtoupper( $method ); ?> Request parameters</h3>

						<?php foreach ( $data['parameters'] as $parameter ) : ?>
							<div class="method-parameter">
								<?php
								$required = true === ! empty( $parameter['required'] ) ? 'required' : '';
								$default  = ! empty( $parameter['default'] ) ? $parameter['default'] : '';
								$name     = "{$method}-{$parameter['name']}";
								$in       = ! empty( $parameter['in'] ) ? $parameter['in'] : 'query';
								?>
								<label for="<?php echo $name; ?>"><?php echo $parameter['description']; ?></label>
								<input
										type="text"
										name="<?php echo $name; ?>"
										id="<?php echo $name; ?>" <?php echo $required; ?>
										value="<?php echo $default; ?>"
										data-name="<?php echo $parameter['name']; ?>"
										data-in="<?php echo $in; ?>"
								>
							</div>
						<?php endforeach; ?>
					</fieldgroup>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

	</div>

	<div>
		<button id="trap-request" class="button-primary">
			Request
		</button>
	</div>

	<h3>Documentation</h3>
	<div class="documentation">
		<?php var_dump( $documentation ); ?>
	</div>

	<br>
</div>

