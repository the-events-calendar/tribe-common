<?php
// Fetch the Help page Instance
$help = Tribe__Admin__Help_Page::instance();

// Fetch plugins
$plugins = $help->get_plugins( null, false );
$sections = array(
	array(
		'type' => 'box',
		'id' => 'feature-box',
		'priority' => 0,
		'content' => array(
			'<img src="' . esc_url( plugins_url( 'resources/images/modern-tribe@2x.png', dirname( __FILE__ ) ) ) . '" alt="Modern Tribe Inc." title="Modern Tribe Inc.">',
			sprintf( esc_html__( 'Thanks you for using %s! All of us at Modern Tribe sincerely appreciate it and are stoked to have you on board.', 'tribe-common' ), $help->get_plugins_text() ),
		),
	),

	array(
		'id' => 'support',
		'title' => __( 'Getting Support', 'tribe-common' ),
		'priority' => 10,
		'content' => array(
			0 => sprintf( __( 'The %s on our website is a great place to find tips and tricks for customizing the plugins and its functionality. It is an extensive collection of tested and updated methods to help you use, tweak, and customize the front-end in creative ways.', 'tribe-common' ), '<a href="http://m.tri.be/18j9" target="_blank">' . __( 'Knowledgebase', 'tribe-common' ) . '</a>' ),

			50 => sprintf( __( '<strong>Want to dive deeper?</strong> Check out our %s for developers.', 'tribe-common' ), '<a href="http://m.tri.be/18jf" target="_blank">' . __( 'list of available functions', 'tribe-common' ) . '</a>' ),
		),
	),

	array(
		'id' => 'extra-help',
		'title' => __( 'I Need Extra Help!', 'tribe-common' ),
		'priority' => 20,
		'content' => array(
			0 => __( 'While the resources above help solve a majority of the issues we see, there are times you might be looking for extra support. If you need assistance with a legitimate bug with the plugin and would like us to take a look please follow these steps:', 'tribe-common' ),

			10 => array(
				'type' => 'ol',

				sprintf( __( '%s. All of the common (and not-so-common) answers to questions we see are all here. It’s often the fastest path to finding an answer!', 'tribe-common' ), '<strong><a href="http://m.tri.be/18j9" target="_blank">' . __( 'Check our Knowledgebase', 'tribe-common' ) . '</a></strong>' ),
				sprintf( __( '%s. Testing for an existing conflict is the best start for in-depth troubleshooting. We will often ask you to follow these steps when opening a new thread, so doing this ahead of time will be super helpful.', 'tribe-common' ), '<strong><a href="http://m.tri.be/18jh" target="_blank">' . __( 'Test for a theme or plugin conflict', 'tribe-common' ) . '</a></strong>' ),
				sprintf( __( '%s. There are very few issues we haven’t seen and it’s likely another user has already asked your question and gotten an answer from our support staff. While posting to the forums is open only to paid customers, they are open for anyone to search and review.', 'tribe-common' ), '<strong><a href="http://m.tri.be/4w/" target="_blank">' . __( 'Search our support forum', 'tribe-common' ) . '</a></strong>' ),
			),

			20 => __( 'If you have tried the steps above and are still having trouble, you can post a new thread to our open-source forums on WordPress.org:', 'the-events-calendar' ),

			21 => $help->get_plugin_forum_links(),

			22 => __( 'Our support staff monitors these forums once a week and would be happy to assist you there.', 'the-events-calendar' ),

			30 => sprintf( __( 'Looking for more immediate support? %s on our website with the purchase of any of our premium plugins. Pick up a license and you can post directly there and expect a response within 24-48 hours during weekdays.', 'the-events-calendar' ), '<a href="http://m.tri.be/4w/" target="_blank">' . esc_html__( 'We offer premium support', 'the-events-calendar' ) . '</a>' ),


			40 => __( 'Please note that all hands-on support is provided via the forums. You can email or tweet at us… ​but we will probably point you back to the forums 😄.', 'tribe-common' ),
			50 => '<div style="text-align: right;"><a href="http://m.tri.be/18ji" target="_blank" class="button">' . __( 'Read more about our support policy', 'tribe-common' ) . '</a></div>',
		),
	),

	array(
		'id' => 'system-info',
		'title' => __( 'System Information', 'tribe-common' ),
		'priority' => 30,
		'content' => array(
			__( 'The details of your plugins and settings is often needed for you or our staff to help troubleshoot an issue. Please copy and paste this information into the System Information field when opening a new thread and it will help us help you faster!', 'tribe-common' ),
		),
	),
);
?>

<div id="tribe-help-general">
<<<<<<< HEAD
	<?php $help->render_sections( $sections ); ?>
=======
	<div id="modern-tribe-info">
		<img src="<?php echo esc_url( plugins_url( 'resources/images/modern-tribe@2x.png', dirname( __FILE__ ) ) ); ?>" alt="Modern Tribe Inc." title="Modern Tribe Inc.">
		<?php
		/**
		 * Filter the text inside the box at the top of the Settings > Help tab
		 *
		 * @param array $text_featurebox
		 */
		echo $help->get_html_from_text( apply_filters( 'tribe_help_text_featurebox', $text_featurebox ) ); ?>
	</div>

	<div class="tribe-settings-form-wrap">

		<h3><?php esc_html_e( 'Getting Started', 'tribe-common' ); ?></h3>
		<?php
		/**
		 * Filter the "Getting Started" text on the Settings > Help tab
		 *
		 * @param array $intro_text
		 */
		echo $help->get_html_from_text( apply_filters( 'tribe_help_text_intro', $intro_text ) );
		?>

		<h3><?php esc_html_e( 'Support Resources To Help You Be Awesome', 'tribe-common' ); ?></h3>
		<?php
		/**
		 * Filter the "Support Resources To Help You Be Awesome" text on the Settings > Help tab
		 *
		 * @param array $intro_text
		 */
		echo $help->get_html_from_text( apply_filters( 'tribe_help_text_support', $support_text ) );
		?>

		<h3><?php esc_html_e( 'Forums: Because Everyone Needs A Buddy', 'tribe-common' ); ?></h3>
		<?php
		/**
		 * Filter the "Forums: Because Everyone Needs A Buddy" text on the Settings > Help tab
		 *
		 * @param array $forum_text
		 */
		echo $help->get_html_from_text( apply_filters( 'tribe_help_text_forum', $forum_text ) );
		?>

		<h3><?php esc_html_e( 'Not getting help?', 'tribe-common' ); ?></h3>
		<?php
		/**
		 * Filter the "Not getting help?" text on the Settings > Help tab
		 *
		 * @param array $outro_text
		 */
		echo $help->get_html_from_text( apply_filters( 'tribe_help_text_outro', $outro_text ) );

		/**
		 * Fires at the end of the help text content on the Settings > Help tab
		 */
		do_action( 'tribe_help_text_sections' ); ?>

	</div>

>>>>>>> master
</div>


<div id="tribe-help-sidebar">
	<?php
	/**
	 * Fires at the top of the sidebar on Settings > Help tab
	 */
	do_action( 'tribe_help_sidebar_before' );

	foreach ( $plugins as $key => $plugin ) {
		$help->print_plugin_box( $key );
	}
	?>
	<h3><?php esc_html_e( 'News and Tutorials', 'tribe-common' ); ?></h3>
	<ul>
		<?php
		foreach ( $help->get_feed_items() as $item ) {
			echo '<li><a href="' . $help->get_ga_link( $item['link'], false ) . '">' . $item['title'] . '</a></li>';
		}
		echo '<li><a href="' . $help->get_ga_link( 'category/products' ) . '">' . esc_html__( 'More...', 'tribe-common' ) . '</a></li>';
		?>
	</ul>

	<?php
	/**
	 * Fires at the bottom of the sidebar on the Settings > Help tab
	 */
	do_action( 'tribe_help_sidebar_after' ); ?>

</div>
