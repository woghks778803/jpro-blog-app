<?php
/**
 * Advanced Ads main admin class
 *
 * @package   Advanced_Ads_Admin
 * @author    Thomas Maier <support@wpadvancedads.com>
 * @license   GPL-2.0+
 * @link      https://wpadvancedads.com
 * @copyright since 2013 Thomas Maier, Advanced Ads GmbH
 *
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 */
class Advanced_Ads_Admin {

	/**
	 * Instance of this class.
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Instance of admin notice class.
	 *
	 * @var      object $notices
	 */
	protected $notices = null;

	/**
	 * Slug of the settings page
	 *
	 * @var      string $plugin_screen_hook_suffix
	 */
	public $plugin_screen_hook_suffix = null;

	/**
	 * General plugin slug
	 *
	 * @var     string
	 */
	protected $plugin_slug = '';

	/**
	 * Admin settings.
	 *
	 * @var      array
	 */
	protected static $admin_settings = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 */
	private function __construct() {
		if ( wp_doing_ajax() ) {
			new Advanced_Ads_Ad_Ajax_Callbacks();
			add_action( 'plugins_loaded', [ $this, 'wp_plugins_loaded_ajax' ] );
		} else {
			add_action( 'plugins_loaded', [ $this, 'wp_plugins_loaded' ] );
			add_filter( 'admin_footer_text', [ $this, 'admin_footer_text' ], 100 );
			Advanced_Ads_Ad_List_Filters::get_instance();
		}
		// add shortcode creator to TinyMCE.
		Advanced_Ads_Shortcode_Creator::get_instance();
		Advanced_Ads_Admin_Licenses::get_instance();
	}

	/**
	 * License handling legacy code after moving license handling code to Advanced_Ads_Admin_Licenses
	 *
	 * @param string $addon slug of the add-on.
	 * @param string $plugin_name name of the add-on.
	 * @param string $options_slug slug of the options the plugin is saving in the options table.
	 *
	 * @return mixed 1 on success or string with error message.
	 * @since version 1.7.16 (early January 2017)
	 */
	public function deactivate_license( $addon = '', $plugin_name = '', $options_slug = '' ) {
		return Advanced_Ads_Admin_Licenses::get_instance()->deactivate_license( $addon, $plugin_name, $options_slug );
	}

	/**
	 * Get license status.
	 *
	 * @param string $slug slug of the add-on.
	 *
	 * @return string   license status
	 */
	public function get_license_status( $slug = '' ) {
		return Advanced_Ads_Admin_Licenses::get_instance()->get_license_status( $slug );
	}

	/**
	 * Actions and filter available after all plugins are initialized.
	 */
	public function wp_plugins_loaded() {
		// call $plugin_slug from public plugin class.
		$plugin            = Advanced_Ads::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		add_action( 'current_screen', [ $this, 'current_screen' ] );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ], 9 );

		// update placements.
		add_action( 'admin_init', [ 'Advanced_Ads_Placements', 'update_placements' ] );

		// add Advanced Ads admin notices
		// removes admin notices from other plugins
		// `in_admin_header` is the last hook to run before àdmin_notices` according to https://codex.wordpress.org/Plugin_API/Action_Reference.
		add_action( 'in_admin_header', [ $this, 'register_admin_notices' ] );

		// add links to plugin page.
		add_filter( 'plugin_action_links_' . ADVADS_BASE, [ $this, 'add_plugin_links' ] );

		// display information when user is going to disable the plugin.
		add_filter( 'admin_footer', [ $this, 'add_deactivation_logic' ] );
		// add_filter( 'after_plugin_row_' . ADVADS_BASE, array( $this, 'display_deactivation_message' ) );
		// disable adding rel="noopener noreferrer" to link added through TinyMCE for rich content ads.
		add_filter( 'tiny_mce_before_init', [ $this, 'tinymce_allow_unsafe_link_target' ] );

		add_action( 'plugins_api_result', [ $this, 'recommend_suitable_add_ons' ], 11, 3 );

		// register dynamic action to load a starter setup.
		add_action( 'admin_action_advanced_ads_starter_setup', [ $this, 'import_starter_setup' ] );

		Advanced_Ads_Admin_Meta_Boxes::get_instance();
		Advanced_Ads_Admin_Menu::get_instance();
		Advanced_Ads_Admin_Ad_Type::get_instance();
		Advanced_Ads_Admin_Settings::get_instance();
		Advanced_Ads_Ad_Authors::get_instance();
		new Advanced_Ads_Admin_Upgrades();
		new Advanced_Ads\Admin\Post_List();
	}

	/**
	 * Actions and filters that should also be available for ajax
	 */
	public function wp_plugins_loaded_ajax() {
		// needed here in order to work with Quick Edit option on ad list page.
		Advanced_Ads_Admin_Ad_Type::get_instance();

		add_action( 'wp_ajax_advads_send_feedback', [ $this, 'send_feedback' ] );
		add_action( 'wp_ajax_advads_load_rss_widget_content', [ 'Advanced_Ads_Admin_Meta_Boxes', 'dashboard_widget_function_output' ] );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * General stuff after page is loaded and screen variable is available
	 */
	public function current_screen() {
		$screen = get_current_screen();

		if ( ! isset( $screen->id ) ) {
			return;
		}

		switch ( $screen->id ) {
			case 'edit-advanced_ads': // ad overview page.
			case 'advanced_ads': // ad edit page.
				// remove notice about missing first ad.
				break;
		}
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 */
	public function enqueue_admin_styles() {
		wp_enqueue_style( $this->plugin_slug . '-ui-styles', plugins_url( 'assets/css/ui.css', __FILE__ ), [], ADVADS_VERSION );
		wp_enqueue_style( $this->plugin_slug . '-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), [], ADVADS_VERSION );

		$screen = get_current_screen();
		if ( ! $screen instanceof \WP_Screen ) {
			return;
		}

		if ( $screen->post_type === Advanced_Ads::POST_TYPE_SLUG && $screen->base === 'post' ) {
			wp_enqueue_style( $this->plugin_slug . '-ad-positioning-styles', ADVADS_BASE_URL . '/modules/ad-positioning/assets/css/ad-positioning.css', [ $this->plugin_slug . '-admin-styles' ], ADVADS_VERSION );
		}
	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 */
	public function enqueue_admin_scripts() {

		// global js script.
		wp_enqueue_script( $this->plugin_slug . '-admin-global-script', plugins_url( 'assets/js/admin-global.js', __FILE__ ), [ 'jquery' ], ADVADS_VERSION, false );
		wp_enqueue_script( $this->plugin_slug . '-admin-find-adblocker', plugins_url( 'assets/js/advertisement.js', __FILE__ ), [], ADVADS_VERSION, false );

		// register ajax nonce.
		$params = [
			'ajax_nonce' => wp_create_nonce( 'advanced-ads-admin-ajax-nonce' ),
		];
		wp_localize_script( $this->plugin_slug . '-admin-global-script', 'advadsglobal', $params );

		if ( self::screen_belongs_to_advanced_ads() ) {
			wp_register_script( $this->plugin_slug . '-ui-scripts', plugins_url( 'assets/js/ui.js', __FILE__ ), [ 'jquery' ], ADVADS_VERSION, false );
			wp_register_script( $this->plugin_slug . '-conditions-script', plugins_url( 'assets/js/conditions.js', __FILE__ ), [ 'jquery', $this->plugin_slug . '-ui-scripts' ], ADVADS_VERSION, false );
			wp_register_script( $this->plugin_slug . '-wizard-script', plugins_url( 'assets/js/wizard.js', __FILE__ ), [ 'jquery' ], ADVADS_VERSION, false );

			$this->enqueue_main_admin_script();

			// just register this script for later inclusion on ad group list page.
			wp_register_script( 'inline-edit-group-ads', plugins_url( 'assets/js/inline-edit-group-ads.js', __FILE__ ), [ 'jquery' ], ADVADS_VERSION, false );

			// register admin.js translations.
			$translation_array = [
				'condition_or'                  => __( 'or', 'advanced-ads' ),
				'condition_and'                 => __( 'and', 'advanced-ads' ),
				'after_paragraph_promt'         => __( 'After which paragraph?', 'advanced-ads' ),
				'page_level_ads_enabled'        => Advanced_Ads_AdSense_Admin::get_auto_ads_messages()['enabled'],
				'today'                         => __( 'Today', 'advanced-ads' ),
				'yesterday'                     => __( 'Yesterday', 'advanced-ads' ),
				'this_month'                    => __( 'This Month', 'advanced-ads' ),
				/* translators: 1: The number of days. */
				'last_n_days'                   => __( 'Last %1$d days', 'advanced-ads' ),
				/* translators: 1: An error message. */
				'error_message'                 => __( 'An error occurred: %1$s' ),
				'all'                           => __( 'All', 'advanced-ads' ),
				'no_results'                    => __( 'There were no results returned for this ad. Please make sure it is active, generating impressions and double check your ad parameters.', 'advanced-ads' ),
				'show_inactive_ads'             => __( 'Show inactive ads', 'advanced-ads' ),
				'hide_inactive_ads'             => __( 'Hide inactive ads', 'advanced-ads' ),
				'display_conditions_form_name'  => Advanced_Ads_Display_Conditions::FORM_NAME, // not meant for translation.
				'delete_placement_confirmation' => __( 'Permanently delete this placement?', 'advanced-ads' ),
				'close'                         => __( 'Close', 'advanced-ads' ),
				'confirmation'                  => __( 'Data you have entered has not been saved. Are you sure you want to discard your changes?', 'advanced-ads' ),
				'admin_page'                    => self::get_advanced_ads_admin_screen(),
				'placements_allowed_ads'        => [
					'action' => 'advads-placements-allowed-ads',
					'nonce'  => wp_create_nonce( 'advads-placements-allowed-ads' ),
				],
			];

			wp_localize_script( $this->plugin_slug . '-admin-script', 'advadstxt', $translation_array );

			wp_enqueue_script( $this->plugin_slug . '-admin-script' );
			wp_enqueue_script( $this->plugin_slug . '-conditions-script' );
			wp_enqueue_script( $this->plugin_slug . '-wizard-script' );
		}

		// call media manager for image upload only on ad edit pages.
		$screen = get_current_screen();

		if ( ! $screen instanceof \WP_Screen ) {
			return;
		}

		if ( isset( $screen->id ) && Advanced_Ads::POST_TYPE_SLUG === $screen->id ) {
			// the 'wp_enqueue_media' function can be executed only once and should be called with the 'post' parameter
			// in this case, the '_wpMediaViewsL10n' js object inside html will contain id of the post, that is necessary to view oEmbed priview inside tinyMCE editor.
			// since other plugins can call the 'wp_enqueue_media' function without the 'post' parameter, Advanced Ads should call it earlier.
			global $post;
			wp_enqueue_media( [ 'post' => $post ] );
		}

		// single ad edit screen.
		if ( $screen->post_type === Advanced_Ads::POST_TYPE_SLUG && $screen->base === 'post' ) {
			wp_enqueue_script(
				$this->plugin_slug . '-ad-positioning-script',
				ADVADS_BASE_URL . '/modules/ad-positioning/assets/js/ad-positioning.js',
				[],
				ADVADS_VERSION,
				true
			);
		}
	}

	/**
	 * Enqueue the minified version of the admin script, or the parts if SCRIPT_DEBUG is defined and true.
	 *
	 * @return void
	 */
	private function enqueue_main_admin_script() {
		$dependencies = [ 'jquery', $this->plugin_slug . '-ui-scripts', 'jquery-ui-autocomplete', 'wp-util' ];

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), $dependencies, ADVADS_VERSION, false );
			$dependencies[] = $this->plugin_slug . '-admin-script';

			wp_enqueue_script( $this->plugin_slug . '-admin-script-termination', plugins_url( 'assets/js/termination.js', __FILE__ ), $dependencies, ADVADS_VERSION, false );
			$dependencies[] = $this->plugin_slug . '-admin-script-termination';

			wp_enqueue_script( $this->plugin_slug . '-admin-script-dialog', plugins_url( 'assets/js/dialog-advads-modal.js', __FILE__ ), $dependencies, ADVADS_VERSION, false );

			return;
		}

		wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.min.js', __FILE__ ), $dependencies, ADVADS_VERSION, false );
	}

	/**
	 * Check if the current screen belongs to Advanced Ads
	 *
	 * @return bool
	 */
	public static function screen_belongs_to_advanced_ads() {
		return self::get_advanced_ads_admin_screen() !== '';
	}

	/**
	 * Get the current screen id if the page belongs to AA, otherwise empty string.
	 *
	 * @return string
	 */
	private static function get_advanced_ads_admin_screen() {
		if ( ! function_exists( 'get_current_screen' ) ) {
			return '';
		}

		$screen = get_current_screen();
		if ( ! isset( $screen->id ) ) {
			return '';
		}

		$advads_pages = apply_filters(
			'advanced-ads-dashboard-screens',
			[
				'advanced-ads_page_advanced-ads-groups', // ad groups.
				'edit-advanced_ads', // ads overview.
				'advanced_ads', // ad edit page.
				'advanced-ads_page_advanced-ads-placements', // placements.
				'advanced-ads_page_advanced-ads-settings', // settings.
				'toplevel_page_advanced-ads', // overview.
				'admin_page_advanced-ads-debug', // debug.
				// 'advanced-ads_page_advanced-ads-support', // support.
				'admin_page_advanced-ads-import-export', // import & export.
			]
		);

		if ( ! in_array( $screen->id, $advads_pages, true ) ) {
			return '';
		}

		return $screen->id;
	}

	/**
	 * Get action from the params
	 */
	public function current_action() {
		$request = wp_unslash( $_REQUEST );
		if ( isset( $request['action'] ) && - 1 !== $request['action'] ) {
			return $request['action'];
		}

		return false;
	}

	/**
	 * Get DateTimeZone object for the WP installation
	 *
	 * @return DateTimeZone object set in WP settings.
	 * @see        Advanced_Ads_Utils::get_wp_timezone()
	 *
	 * @deprecated This is also used outside of admin as well as other plugins.
	 */
	public static function get_wp_timezone() {
		return Advanced_Ads_Utils::get_wp_timezone();
	}

	/**
	 * Get literal expression of timezone.
	 *
	 * @param DateTimeZone $date_time_zone the DateTimeZone object to get literal value from.
	 *
	 * @return string time zone.
	 * @see        Advanced_Ads_Utils::get_timezone_name()
	 *
	 * @deprecated This is also used outside of admin as well as other plugins.
	 */
	public static function timezone_get_name( DateTimeZone $date_time_zone ) {
		return Advanced_Ads_Utils::get_timezone_name();
	}

	/**
	 * Registers Advanced Ads admin notices
	 * prevents other notices from showing up on our own pages
	 */
	public function register_admin_notices() {

		/**
		 * Remove all registered admin_notices from AA screens
		 * we need to use this or some users have half or more of their viewports cluttered with unrelated notices
		 */
		if ( $this->screen_belongs_to_advanced_ads() ) {
			remove_all_actions( 'admin_notices' );
		}

		// register our own notices.
		add_action( 'admin_notices', [ $this, 'admin_notices' ] );
	}

	/**
	 * Initiate the admin notices class
	 */
	public function admin_notices() {
		// display ad block warning to everyone who can edit ads.
		if ( current_user_can( Advanced_Ads_Plugin::user_cap( 'advanced_ads_edit_ads' ) ) ) {
			if ( $this->screen_belongs_to_advanced_ads() ) {
				$ad_blocker_notice_id = Advanced_Ads_Plugin::get_instance()->get_frontend_prefix() . 'abcheck-' . md5( microtime() );
				wp_register_script( $ad_blocker_notice_id . '-adblocker-notice', false, [], ADVADS_VERSION, true );
				wp_enqueue_script( $ad_blocker_notice_id . '-adblocker-notice' );
				wp_add_inline_script( $ad_blocker_notice_id . '-adblocker-notice', "
				jQuery( document ).ready( function () {
							if ( typeof advanced_ads_adblocker_test === 'undefined' ) {
								jQuery( '#" . esc_attr( $ad_blocker_notice_id ) . ".message' ).show();
							}
						} );" );
				include ADVADS_BASE_PATH . 'admin/views/notices/adblock.php';
			}
		}

		if ( $this->screen_belongs_to_advanced_ads() ) {
			$this->branded_admin_header();
		}

		// Show success notice after starter setup was imported. Registered here because it will be visible only once.
		if ( isset( $_GET['message'] ) && 'advanced-ads-starter-setup-success' === $_GET['message'] ) {
			add_action( 'advanced-ads-admin-notices', [ $this, 'starter_setup_success_message' ] );
		}

		// register our own notices on Advanced Ads pages, except from the overview page where they should appear in the notices section.
		$screen = get_current_screen();
		if ( class_exists( 'Advanced_Ads_Admin_Notices' )
			 && current_user_can( Advanced_Ads_Plugin::user_cap( 'advanced_ads_edit_ads' ) )
			 && ( ! isset( $screen->id ) || 'toplevel_page_advanced-ads' !== $screen->id ) ) {
			$this->notices = Advanced_Ads_Admin_Notices::get_instance()->notices;

			echo '<div class="wrap">';
			Advanced_Ads_Admin_Notices::get_instance()->display_notices();

			// allow other Advanced Ads plugins to show admin notices at this late stage.
			do_action( 'advanced-ads-admin-notices' );
			echo '</div>';
		}
	}

	/**
	 * Add links to the plugins list
	 *
	 * @param array $links array of links for the plugins, adapted when the current plugin is found.
	 *
	 * @return array $links
	 */
	public function add_plugin_links( $links ) {
		if ( ! is_array( $links ) ) {
			return $links;
		}

		// add link to support page.
		$support_link = '<a href="' . esc_url( admin_url( 'admin.php?page=advanced-ads-settings#top#support' ) ) . '">' . __( 'Support', 'advanced-ads' ) . '</a>';
		array_unshift( $links, $support_link );

		// add link to add-ons.
		if ( defined( 'AAP_VERSION' ) ) {
			$extend_link = '<a href="' . ADVADS_URL . 'add-ons/?utm_source=advanced-ads&utm_medium=link&utm_campaign=plugin-page-add-ons" target="_blank">' . __( 'Add-Ons', 'advanced-ads' ) . '</a>';
		} else {
			$extend_link = '<a href="' . ADVADS_URL . 'add-ons/all-access/?utm_source=advanced-ads&utm_medium=link&utm_campaign=plugin-page-features" target="_blank" class="aa-get-pro">' . __( 'See Pro Features', 'advanced-ads' ) . '</a>';
		}
		array_unshift( $links, $extend_link );

		return $links;
	}

	/**
	 * Display deactivation logic on plugins page
	 *
	 * @since 1.7.14
	 */
	public function add_deactivation_logic() {
		$screen = get_current_screen();
		if ( ! isset( $screen->id ) || ! in_array( $screen->id, [ 'plugins', 'plugins-network' ], true ) ) {
			return;
		}

		$current_user = wp_get_current_user();
		if ( ! ( $current_user instanceof WP_User ) ) {
			$from  = '';
			$email = '';
		} else {
			$from  = $current_user->user_nicename . ' <' . trim( $current_user->user_email ) . '>';
			$email = $current_user->user_email;
		}

		include ADVADS_BASE_PATH . 'admin/views/feedback-disable.php';
	}

	/**
	 * Send feedback via email
	 *
	 * @since 1.7.14
	 */
	public function send_feedback() {
		/**
		 * We first need to get the form data from the string and can verify the nonce afterwards
		 * This throws an issue with the WP Coding Standards though
		 */
		if ( isset( $_POST['formdata'] ) ) {
			parse_str( wp_unslash( $_POST['formdata'] ), $form );
		}

		if ( ! wp_verify_nonce( $form['advanced_ads_disable_form_nonce'], 'advanced_ads_disable_form' ) ) {
			die();
		}

		$text = '';
		if ( isset( $form['advanced_ads_disable_text'] ) ) {
			$text = implode( "\n\r", $form['advanced_ads_disable_text'] );
		}

		// get first version to see if this is a new problem or might be an older on.
		$options   = Advanced_Ads_Plugin::get_instance()->internal_options();
		$installed = isset( $options['installed'] ) ? date( 'd.m.Y', $options['installed'] ) : '–';

		$text .= "\n\n" . home_url() . " ($installed)";

		$headers = [];

		$from = isset( $form['advanced_ads_disable_from'] ) ? $form['advanced_ads_disable_from'] : '';
		// the user clicked on the "don’t disable" button or if an address is given in the form then use that one.
		if ( isset( $form['advanced_ads_disable_reason'] )
			 && 'get help' === $form['advanced_ads_disable_reason']
			 && ! empty( $form['advanced_ads_disable_reply_email'] ) ) {
			$email = trim( $form['advanced_ads_disable_reply_email'] );
			if ( ! is_email( $email ) ) {
				die();
			}

			$current_user = wp_get_current_user();
			$name         = ( $current_user instanceof WP_User ) ? $current_user->user_nicename : '';
			$from         = $name . ' <' . $email . '>';
			$is_german    = ( preg_match( '/\.de$/', $from ) || 'de_' === substr( get_locale(), 0, 3 ) || 'de_' === substr( get_user_locale(), 0, 3 ) );
			if ( isset( $form['advanced_ads_disable_text'][0] )
				 && trim( $form['advanced_ads_disable_text'][0] ) !== '' ) { // is a text given then ask for help.
				// send German text.
				if ( $is_german ) {
					$text .= "\n\n Hilfe ist auf dem Weg.";
				} else {
					$text .= "\n\n Help is on its way.";
				}
			} else { // if no text is given, just reply.
				if ( $is_german ) {
					$text .= "\n\n Vielen Dank für das Feedback.";
				} else {
					$text .= "\n\n Thank you for your feedback.";
				}
			}
		}
		if ( $from ) {
			$headers[] = "From: $from";
			$headers[] = "Reply-To: $from";
		}

		$subject = isset( $form['advanced_ads_disable_reason'] ) ? $form['advanced_ads_disable_reason'] : '(no reason given)';
		// append plugin name to get a better subject.
		$subject .= ' (Advanced Ads)';

		$success = wp_mail( 'improve@wpadvancedads.com', $subject, $text, $headers );

		die();
	}

	/**
	 * Configure TinyMCE to allow unsafe link target.
	 *
	 * @param boolean $mce_init the tinyMce initialization array.
	 *
	 * @return boolean
	 */
	public function tinymce_allow_unsafe_link_target( $mce_init ) {

		// check if we are on the ad edit screen.
		if ( ! function_exists( 'get_current_screen' ) ) {
			return $mce_init;
		}

		$screen = get_current_screen();
		if ( isset( $screen->id ) && 'advanced_ads' === $screen->id ) {
			$mce_init['allow_unsafe_link_target'] = true;
		}

		return $mce_init;
	}

	/**
	 * Sort visitor and display condition arrays alphabetically by their label.
	 *
	 * @param array $a array to be compared.
	 * @param array $b array to be compared.
	 *
	 * @return mixed
	 */
	public static function sort_condition_array_by_label( $a, $b ) {
		if ( ! isset( $a['label'] ) || ! isset( $b['label'] ) ) {
			return;
		}

		return strcmp( strtolower( $a['label'] ), strtolower( $b['label'] ) );
	}

	/**
	 * Recommend additional add-ons
	 *
	 * @param object|WP_Error $result Response object or WP_Error.
	 * @param string          $action The type of information being requested from the Plugin Installation API.
	 * @param object          $args Plugin API arguments.
	 *
	 * @return object|WP_Error Response object or WP_Error.
	 */
	public function recommend_suitable_add_ons( $result, $action, $args ) {
		if ( empty( $args->browse ) ) {
			return $result;
		}

		if ( 'featured' !== $args->browse && 'recommended' !== $args->browse && 'popular' !== $args->browse ) {
			return $result;
		}

		if ( ! isset( $result->info['page'] ) || 1 < $result->info['page'] ) {
			return $result;
		}

		// Recommend AdSense In-Feed add-on.
		if ( ! is_plugin_active( 'advanced-ads-adsense-in-feed/advanced-ads-in-feed.php' )
			 && ! is_plugin_active_for_network( 'advanced-ads-adsense-in-feed/advanced-ads-in-feed.php' ) ) {

			// Grab all slugs from the api results.
			$result_slugs = wp_list_pluck( $result->plugins, 'slug' );

			if ( in_array( 'advanced-ads-adsense-in-feed', $result_slugs, true ) ) {
				return $result;
			}

			$query_args  = [
				'slug'   => 'advanced-ads-adsense-in-feed',
				'fields' => [
					'icons'             => true,
					'active_installs'   => true,
					'short_description' => true,
					'group'             => true,
				],
			];
			$plugin_data = plugins_api( 'plugin_information', $query_args );

			if ( ! is_wp_error( $plugin_data ) ) {
				if ( 'featured' === $args->browse ) {
					array_push( $result->plugins, $plugin_data );
				} else {
					array_unshift( $result->plugins, $plugin_data );
				}
			}
		}

		// Recommend Genesis Ads add-on.
		if ( defined( 'PARENT_THEME_NAME' ) && 'Genesis' === PARENT_THEME_NAME
			 && ! is_plugin_active( 'advanced-ads-genesis/genesis-ads.php' )
			 && ! is_plugin_active_for_network( 'advanced-ads-genesis/genesis-ads.php' ) ) {

			// Grab all slugs from the api results.
			$result_slugs = wp_list_pluck( $result->plugins, 'slug' );

			if ( in_array( 'advanced-ads-genesis', $result_slugs, true ) ) {
				return $result;
			}

			$query_args  = [
				'slug'   => 'advanced-ads-genesis',
				'fields' => [
					'icons'             => true,
					'active_installs'   => true,
					'short_description' => true,
					'group'             => true,
				],
			];
			$plugin_data = plugins_api( 'plugin_information', $query_args );

			if ( ! is_wp_error( $plugin_data ) ) {
				if ( 'featured' === $args->browse ) {
					array_push( $result->plugins, $plugin_data );
				} else {
					array_unshift( $result->plugins, $plugin_data );
				}
			}
		}

		// Recommend WP Bakery (former Visual Composer) add-on.
		if ( defined( 'WPB_VC_VERSION' )
			 && ! is_plugin_active( 'ads-for-visual-composer/advanced-ads-vc.php' )
			 && ! is_plugin_active_for_network( 'ads-for-visual-composer/advanced-ads-vc.php' ) ) {

			// Grab all slugs from the api results.
			$result_slugs = wp_list_pluck( $result->plugins, 'slug' );

			if ( in_array( 'ads-for-visual-composer', $result_slugs, true ) ) {
				return $result;
			}

			$query_args  = [
				'slug'   => 'ads-for-visual-composer',
				'fields' => [
					'icons'             => true,
					'active_installs'   => true,
					'short_description' => true,
					'group'             => true,
				],
			];
			$plugin_data = plugins_api( 'plugin_information', $query_args );

			if ( ! is_wp_error( $plugin_data ) ) {
				if ( 'featured' === $args->browse ) {
					array_push( $result->plugins, $plugin_data );
				} else {
					array_unshift( $result->plugins, $plugin_data );
				}
			}
		}

		return $result;
	}

	/**
	 * Overrides WordPress text in Footer
	 *
	 * @param String $default_text The default footer text.
	 *
	 * @return string
	 */
	public function admin_footer_text( $default_text ) {
		if ( $this->screen_belongs_to_advanced_ads() ) {

			/* translators: %s is the URL to add a new review, https://wordpress.org/support/plugin/advanced-ads/reviews/#new-post */
			return sprintf( __( 'Thank the developer with a &#9733;&#9733;&#9733;&#9733;&#9733; review on <a href="%s" target="_blank">wordpress.org</a>', 'advanced-ads' ), 'https://wordpress.org/support/plugin/advanced-ads/reviews/#new-post' );
		}

		return $default_text;
	}

	/**
	 * Import a starter setup for new users
	 */
	public function import_starter_setup() {
		if (
			! isset( $_GET['action'] )
			|| 'advanced_ads_starter_setup' !== $_GET['action']
			|| ! current_user_can( Advanced_Ads_Plugin::user_cap( 'advanced_ads_edit_ads' ) )
		) {
			return;
		}

		check_admin_referer( 'advanced-ads-starter-setup' );

		// start importing the ads.
		$xml = file_get_contents( ADVADS_BASE_PATH . 'admin/assets/xml/starter-setup.xml' );

		Advanced_Ads_Import::get_instance()->import( $xml );

		// redirect to ad overview page.
		wp_safe_redirect( admin_url( 'edit.php?post_type=advanced_ads&message=advanced-ads-starter-setup-success' ) );
	}

	/**
	 * Show success message after starter setup was created.
	 */
	public function starter_setup_success_message() {

		// load link to latest post.

		$args           = [
			'numberposts' => 1,
		];
		$last_post      = get_posts( $args );
		$last_post_link = isset( $last_post[0]->ID ) ? get_permalink( $last_post[0]->ID ) : false;

		include ADVADS_BASE_PATH . 'admin/views/notices/starter-setup-success.php';
	}

	/**
	 * Get admin settings of the current user.
	 *
	 * @return array
	 */
	public static function get_admin_settings() {
		if ( null === self::$admin_settings ) {
			self::$admin_settings = get_user_meta( get_current_user_id(), 'advanced-ads-admin-settings', true );

			if ( ! is_array( self::$admin_settings ) ) {
				self::$admin_settings = [];
			}
		}
		return self::$admin_settings;
	}

	/**
	 * Update admin settings of the current user.
	 *
	 * @param array $new_settings New admin settings.
	 */
	public static function update_admin_setttings( $new_settings ) {
		$current = self::get_admin_settings();

		if ( $current !== $new_settings ) {
			update_user_meta( get_current_user_id(), 'advanced-ads-admin-settings', $new_settings );
			self::$admin_settings = $new_settings;
		}
	}

	/**
	 * Add an Advanced Ads branded header to plugin pages
	 *
	 * @see Advanced_Ads_Admin::screen_belongs_to_advanced_ads()
	 */
	private function branded_admin_header() {
		$screen              = get_current_screen();
		$manual_url          = 'manual/';
		$new_button_id       = '';
		$new_button_label    = '';
		$new_button_href     = '';
		$show_filter_button  = false;
		$reset_href          = '';
		$filter_disabled     = '';
		$show_screen_options = false;
		$title               = get_admin_page_title();
		$tooltip             = '';

		switch ( $screen->id ) {
			// ad overview
			case 'advanced_ads':
				$new_button_label = __( 'New Ad', 'advanced-ads' );
				$new_button_href  = admin_url( 'post-new.php?post_type=advanced_ads' );
				$manual_url       = 'manual/first-ad/';
				break;
			case 'edit-advanced_ads':
				$title               = __( 'Your Ads', 'advanced-ads' );
				$new_button_label    = __( 'New Ad', 'advanced-ads' );
				$new_button_href     = admin_url( 'post-new.php?post_type=advanced_ads' );
				$manual_url          = 'manual/first-ad/';
				$show_filter_button  = ! Advanced_Ads_Ad_List_Filters::uses_filter_or_search();
				$reset_href          = ! $show_filter_button ? esc_url( admin_url( 'edit.php?post_type=' . Advanced_Ads::POST_TYPE_SLUG ) ) : '';
				$filter_disabled     = $screen->get_option( 'show-filters' ) ? 'disabled' : '';
				$show_screen_options = true;
				break;
			case 'advanced-ads_page_advanced-ads-groups': // ad groups
				$title              = __( 'Your Groups', 'advanced-ads' );
				$new_button_label   = __( 'New Ad Group', 'advanced-ads' );
				$new_button_href    = '#modal-group-new';
				$new_button_id      = 'advads-new-ad-group-link';
				$manual_url         = 'manual/ad-groups/';
				$show_filter_button = empty( $_GET['s'] );
				$reset_href         = ! $show_filter_button ? esc_url( admin_url( 'admin.php?page=advanced-ads-groups' ) ) : '';
				$tooltip            = Advanced_Ads_Groups_List::get_description();
				break;
			case 'advanced-ads_page_advanced-ads-placements':
				$title              = __( 'Your Placements', 'advanced-ads' );
				$new_button_label   = __( 'New Placement', 'advanced-ads' );
				$new_button_href    = '#modal-placement-new';
				$manual_url         = 'manual/placements/';
				$show_filter_button = true;
				$tooltip            = Advanced_Ads_Placements::get_description();
				break;
			case 'advanced-ads_page_advanced-ads-settings':
				$title            = __( 'Advanced Ads Settings', 'advanced-ads' );
				$new_button_href  = '';
				break;
		}

		$manual_url = apply_filters( 'advanced-ads-admin-header-manual-url', $manual_url, $screen->id );

		include ADVADS_BASE_PATH . 'admin/views/header.php';
	}


	
	/**
	 * Show a note about a deprecated feature and link to the appropriate page in our manual
	 *
	 * @param string $feature simple string to indicate the deprecated feature. Will be added to the UTM campaign attribute.
	 */
	public static function show_deprecated_notice( $feature = '' ) {
		$url = esc_url( ADVADS_URL ) . 'manual/deprecated-features/';

		if ( '' !== $feature ) {
			$url .= '#utm_source=advanced-ads&utm_medium=link&utm_campaign=deprecated-' . sanitize_title_for_query( $feature );
		}

		echo '<br/><br/><span class="advads-notice-inline advads-error">';
		printf(
			// Translators: %1$s is the opening link tag, %2$s is closing link tag.
			esc_html__( 'This feature is deprecated. Please find the removal schedule %1$shere%2$s', 'advanced-ads-pro' ),
			'<a href="' . esc_url( $url ) . '" target="_blank">',
			'</a>'
		);
		echo '</span>';
	}
}
