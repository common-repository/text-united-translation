<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.textunited.com/
 * @since      1.0.0
 *
 * @package    Text_United_Translation
 * @subpackage Text_United_Translation/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Text_United_Translation
 * @subpackage Text_United_Translation/includes
 * @author     Text United <servus@textunited.com>
 */
class Text_United_Translation {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Text_United_Translation_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'TEXT_UNITED_TRANSLATION_VERSION' ) ) {
			$this->version = TEXT_UNITED_TRANSLATION_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'text-united-translation';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Text_United_Translation_Loader. Orchestrates the hooks of the plugin.
	 * - Text_United_Translation_i18n. Defines internationalization functionality.
	 * - Text_United_Translation_Admin. Defines all hooks for the admin area.
	 * - Text_United_Translation_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-text-united-translation-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-text-united-translation-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-text-united-translation-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-text-united-translation-public.php';

		$this->loader = new Text_United_Translation_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Text_United_Translation_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Text_United_Translation_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Text_United_Translation_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		//admin menu

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'tu_admin_menu' );
		$this->loader->add_action('admin_bar_menu', $plugin_admin, 'TUT_add_toolbar_items', 100);
		//register general setting
		
		$this->loader->add_action( 'admin_init', $plugin_admin, 'TUT_register_general_settings' );
		$this->loader->add_action( 'init', $plugin_admin, 'TUT_custom_page_rules');
		if(!empty(get_option( 'TUlanguagesAdd' )) && !empty(get_option( 'TUdefaultLanguage' )) && !empty(get_option( 'TUprojectInfo' ))){
		$this->loader->add_action( 'quick_edit_custom_box', $plugin_admin, 'TUT_custom_edit_box', 10, 3 );
		$this->loader->add_action( 'bulk_edit_custom_box', $plugin_admin,  'TUT_quick_edit_fields', 10, 3);
		
		foreach(get_option( 'TUcustomTypes' ) as $type){
		$this->loader->add_filter( 'manage_'.$type.'_posts_columns', $plugin_admin, 'TUT_custom_columnadd', 10, 3 );
		$this->loader->add_filter( 'manage_'.$type.'_posts_custom_column', $plugin_admin, 'TUT_custom_columnaddAction' , 10, 3);
		$this->loader->add_filter( ''.$type.'_row_actions', $plugin_admin, 'TUT_custom_post_action_links', 10, 3 );
		$this->loader->add_action( 'admin_notices', $plugin_admin,'TUT_my_admin_notice' , 10, 3);
		$this->loader->add_filter( 'attachment_fields_to_edit', $plugin_admin, 'TUT_custom_media_add_media_custom_field',  10, 3 );
        $this->loader->add_action( 'edit_attachment',  $plugin_admin,'TUT_custom_media_save_attachment', 10, 3 );
		$this->loader->add_action( 'rest_api_init', $plugin_admin, 'TUT_adding_url_meta_rest' , 10, 3);
		$this->loader->add_filter('rest_attachment_query', $plugin_admin, 'TUT_rest_url_filters', 10, 3);
        }

		$this->loader->add_action( 'restrict_manage_posts', $plugin_admin, 'TUT_custom_filter_language');
	    $this->loader->add_filter( 'parse_query', $plugin_admin, 'TUT_custom_filter_language_selector', 10, 3 );

		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'TUT_create_meta_box' );
		
		
		$this->loader->add_action( 'save_post', $plugin_admin, 'TUT_save_meta_box', 10, 3 );
		$this->loader->add_action( 'wp_ajax_bulk_edit', $plugin_admin, 'TUT_bulk_edit'); 
		$this->loader->add_action( 'wp_ajax_nopriv_bulk_edit', $plugin_admin, 'TUT_bulk_edit' ); 
	    }
		
		 
		$this->loader->add_action('update_option_TUtoken', $plugin_admin, 'TUT_reset_plugin', 10, 3);
        $this->loader->add_action('update_option_TUlanguagesAdd', $plugin_admin, 'TUT_reset_languages', 10, 3);

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Text_United_Translation_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		
			if(!empty(get_option( 'TUlanguagesAdd' )) && !empty(get_option( 'TUdefaultLanguage' )) && !empty(get_option( 'TUprojectInfo' ))){
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
  
		//Updating page home directory
		$this->loader->add_action( 'init', $plugin_public, 'TUT_custom_page_rules');
		//Set page locale
		$this->loader->add_filter( 'locale', $plugin_public, 'TUT_display_filter', 10, 1 );
		//Changin default manu based on selected language
		$this->loader->add_filter( 'wp_nav_menu_args', $plugin_public, 'TUT_menu_swap', 10, 1 );
        //start buffer output
        $this->loader->add_filter( 'rank_math/frontend/title', $plugin_public,'TUT_titleSeo', 10, 1);        
	    $this->loader->add_action( 'wp_head', $plugin_public, 'TUT_start_modify_html',0 );
		$this->loader->add_action( 'wp_head', $plugin_public, 'TUT_add_hreflans',0 );
		//end buffer
		 $this->loader->add_action('wp_footer', $plugin_public, 'TUT_foo_buffer_stop' );
	
		}
	    //checking if page is localized
		$this->loader->add_action( 'wp', $plugin_public, 'TUT_pre_page_rules');
	    //Add language slector to primary menu
		$this->loader->add_filter( 'wp_nav_menu_objects', $plugin_public, 'TUT_restructure_menu_links', 10, 2 );
        //Check if post can be displayed on current language
		$this->loader->add_filter( 'pre_get_posts', $plugin_public, 'TUT_blog_language', 10, 1);
	    //filter post for selected language
		$this->loader->add_filter( 'get_previous_post_where', $plugin_public, 'TUT_get_prev_past_events_where', 10, 1);
		$this->loader->add_filter( 'get_next_post_where', $plugin_public, 'TUT_get_next_past_events_where', 10, 1);
		//Sending request formain blog refresh
        $this->loader->add_filter('comment_post_redirect', $plugin_public, 'TUT_afterCommentPost', 10, 2);
		//language selector as shortcode
		$this->loader->add_shortcode( 'textunited_selector', $plugin_public, 'TUT_display_info' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Text_United_Translation_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}