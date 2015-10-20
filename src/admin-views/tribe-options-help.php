<?php
// Fetch the Help page Instance
$help = Tribe__Admin__Help_Page::instance();

// Fetch plugins
$plugins = $help->get_plugins( null, false );

$text_featurebox = sprintf( esc_html__( "Thanks you for using %s! From all of us at Modern Tribe, we sincerely appreciate it. If you're looking for help with our plugins, you've come to the right place.", 'tribe-common' ), $help->get_plugins_text() );

$intro_text[] = '<p>' . esc_html__( "If this is your first time using The Events Calendar, you're in for a treat and are already well on your way to creating a first event. Here are some basics we've found helpful for users jumping into it for the first time:", 'tribe-common' ) . '</p>';
$intro_text[] = '<ul>';
$intro_text[] = '<li>';
$intro_text[] = sprintf( esc_html__( '%sOur New User Primer%s was designed for folks in your exact position. Featuring both step-by-step videos and written walkthroughs that feature accompanying screenshots, the primer aims to take you from zero to hero in no time.', 'tribe-common' ), '<a href="' . $help->get_ga_link( 'knowledgebase/new-user-primer-the-events-calendar-and-events-calendar-pro' ) . '" target="blank">', '</a>' );
$intro_text[] = '</li><li>';
$intro_text[] = sprintf( esc_html__( '%sInstallation/Setup FAQs%s from our support page can help give an overview of what the plugin can and cannot do. This section of the FAQs may be helpful as it aims to address any basic install questions not addressed by the new user primer.', 'tribe-common' ), '<a href="' . $help->get_ga_link( 'knowledgebase' ) . '" target="blank">', '</a>' );
$intro_text[] = '</li></ul><p>';
$intro_text[] = esc_html__( "Otherwise, if you're feeling adventurous, you can get started by heading to the Events menu and adding your first event.", 'tribe-common' );
$intro_text[] = '</p>';
$intro_text   = implode( $intro_text );

$support_text[] = '<p>' . sprintf( esc_html__( "We've redone our support page from the ground up in an effort to better help our users. Head over to our %sSupport Page%s and you'll find lots of great resources, including:", 'tribe-common' ), '<a href="' . $help->get_ga_link( 'support/' ) . '" target="blank">', '</a>' ) . '</p>';
$support_text[] = '<ul><li>';
$support_text[] = sprintf( esc_html__( '%sTemplate tags, functions, and hooks & filters%s for The Events Calendar &amp; Events Calendar PRO', 'tribe-common' ), '<a href="http://m.tri.be/fk" target="blank">', '</a>' );
$support_text[] = '</li><li>';
$support_text[] = sprintf( esc_html__( '%sFrequently Asked Questions%s ranging from the most basic setup questions to advanced themer tweaks', 'tribe-common' ), '<a href="' . $help->get_ga_link( 'knowledgebase' ) . '" target="blank">', '</a>' );

$support_text[] = '</li><li>';
$support_text[] = sprintf( esc_html__( '%sTutorials%s written by both members of our team and users from the community, covering custom queries, integration with third-party themes and plugins, etc.', 'tribe-common' ), '<a href="' . $help->get_ga_link( 'the-events-calendar-for-wordpress-tutorials' ) . '" target="blank">', '</a>' );
$support_text[] = '</li><li>';
$support_text[] = esc_html__( "Release notes for painting an overall picture of the plugin's lifecycle and when features/bug fixes were introduced.", 'tribe-common' );
$support_text[] = '</li><li>';
$support_text[] = sprintf( esc_html__( "%sAdd-on documentation%s for all of Modern Tribe's official extensions for The Events Calendar (including WooTickets, Community Events, Eventbrite Tickets, Facebook Events, etc)", 'tribe-common' ), '<a href="' . $help->get_ga_link( 'knowledgebase-category/primers' ) . '" target="blank">', '</a>' );
$support_text[] = '</li></ul>';
$support_text[] = '<p>' . sprintf( esc_html__( "We've also got a %sModern Tribe UserVoice%s page where we're actively watching for feature ideas from the community. If after playing with the plugin and reviewing the resources above, you're finding a feature isn't present that should be, let us know. Vote up existing feature requests or add your own, and help us shape the future of the products business in a way that best meets the community's needs.", 'tribe-common' ), '<a href="http://tribe.uservoice.com/" target="blank">', '</a>' ) . '</p>';
$support_text   = implode( $support_text );


$forum_text[] = '<p>' . sprintf( esc_html__( 'Written documentation can only take things so far...sometimes, you need help from a real person. This is where our %ssupport forums%s come into play.', 'tribe-common' ), '<a href="http://wordpress.org/support/plugin/the-events-calendar" target="blank">', '</a>' ) . '</p>';
$forum_text[] = '<p>' . sprintf( esc_html__( "Users of the free The Events Calendar should post their support concerns to the plugin's %sWordPress.org support forum%s. While we are happy to help identify and fix bugs that are reported at WordPress.org, please make sure to read our %ssupport expectations sticky thread%s before posting so you understand our limitations.", 'tribe-common' ), '<a href="http://wordpress.org/support/plugin/the-events-calendar" target="blank">', '</a>', '<a href="http://wordpress.org/support/topic/welcome-the-events-calendar-users-read-this-first?replies=1" target="blank">', '</a>' ) . '</p>';
$forum_text[] = '<p>' . esc_html__( "We hit the WordPress.org forum throughout the week, watching for bugs. If you report a legitimate bug that we're able to reproduce, we will log it and patch for an upcoming release. However we are unfortunately unable to provide customization tips or assist in integrating with 3rd party plugins or themes.", 'tribe-common' ) . '</p>';
$forum_text[] = '<p>' . sprintf( esc_html__( "If you're a user of The Events Calendar and would like more support, please %spurchase a PRO license%s. We hit the PRO forums daily, and can provide a deeper level of customization/integration support for paying users than we can on WordPress.org.", 'tribe-common' ), '<a href="' . $help->get_ga_link( 'product/wordpress-events-calendar-pro' ) . '" target="blank">', '</a>' ) . '</p>';
$forum_text   = implode( $forum_text );


$outro_text = '<p>' . sprintf( esc_html__( 'If you find that you aren\'t getting the level of service you\'ve come to expect from Modern Tribe, shoot us an email at %1$s or tweet %2$s and tell us why. We\'ll do what we can to make it right.', 'tribe-common' ), '<a href="mailto:pro@tri.be">pro@tri.be</a>', '<a href="http://www.twitter.com/moderntribeinc" target="blank">@moderntribeinc</a>' ) . '</p>';
$more_text  = esc_html__( 'More...', 'tribe-common' );


?>

<div id="tribe-help-general">
	<div id="modern-tribe-info">
		<img src="<?php echo esc_url( plugins_url( 'resources/images/modern-tribe@2x.png', dirname( __FILE__ ) ) ); ?>" alt="Modern Tribe Inc." title="Modern Tribe Inc.">
		<?php
		/**
		 * Filter the text inside the box at the top of the Settings > Help tab
		 *
		 * @param string $text_featurebox
		 */
		echo wpautop( apply_filters( 'tribe_help_text_featurebox', $text_featurebox ) ); ?>
	</div>

	<div class="tribe-settings-form-wrap">

		<h3><?php esc_html_e( 'Getting Started', 'tribe-common' ); ?></h3>
		<?php
		/**
		 * Filter the "Getting Started" text on the Settings > Help tab
		 *
		 * @param string $intro_text
		 */
		echo apply_filters( 'tribe_help_tab_introtext', $intro_text );
		?>

		<h3><?php esc_html_e( 'Support Resources To Help You Be Awesome', 'tribe-common' ); ?></h3>
		<?php
		/**
		 * Filter the "Support Resources To Help You Be Awesome" text on the Settings > Help tab
		 *
		 * @param string $intro_text
		 */
		echo apply_filters( 'tribe_help_tab_supporttext', $support_text );
		?>

		<h3><?php esc_html_e( 'Forums: Because Everyone Needs A Buddy', 'tribe-common' ); ?></h3>
		<?php
		/**
		 * Filter the "Forums: Because Everyone Needs A Buddy" text on the Settings > Help tab
		 *
		 * @param string $forum_text
		 */
		echo apply_filters( 'tribe_help_tab_forumtext', $forum_text );
		?>

		<h3><?php esc_html_e( 'Not getting help?', 'tribe-common' ); ?></h3>
		<?php
		/**
		 * Filter the "Not getting help?" text on the Settings > Help tab
		 *
		 * @param string $outro_text
		 */
		echo apply_filters( 'tribe_help_tab_outro', $outro_text );

		/**
		 * Fires at the end of the help text content on the Settings > Help tab
		 */
		do_action( 'tribe_help_tab_sections' ); ?>

	</div>

</div>


<div id="tribe-help-sidebar">
	<?php
	/**
	 * Fires at the top of the sidebar on Settings > Help tab
	 */
	do_action( 'tribe_help_sidebar_top' );

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
		echo '<li><a href="' . $help->get_ga_link( 'category/products' ) . '">' . $more_text . '</a></li>';
		?>
	</ul>

	<?php
	/**
	 * Fires at the bottom of the sidebar on the Settings > Help tab
	 */
	do_action( 'tribe_help_tab_sidebar' ); ?>

</div>
