<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       raileo
 * @since      1.0.0
 *
 * @package    Raileo
 * @subpackage Raileo/includes
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
 * @package    Raileo
 * @subpackage Raileo/includes
 * @author     Raileo <raileo>
 */
class Raileo {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Raileo_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	protected $apiUrl;
	public function __construct() {
		if ( defined( 'RAILEO_VERSION' ) ) {
			$this->version = RAILEO_VERSION;
		} else {
			$this->version = '1.0.3';
		}
		$this->plugin_name = 'options';
		$this->apiUrl = 'https://raileo.com/api/v1';
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
	 * - Raileo_Loader. Orchestrates the hooks of the plugin.
	 * - Raileo_i18n. Defines internationalization functionality.
	 * - Raileo_Admin. Defines all hooks for the admin area.
	 * - Raileo_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-raileo-loader.php';
		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-raileo-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-raileo-admin.php';
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/raileo-admin-display.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-raileo-public.php';

		$this->loader = new Raileo_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Raileo_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Raileo_i18n();

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

		$plugin_admin = new Raileo_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_raileo_to_sidebar' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Raileo_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

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
	 * @return    Raileo_Loader    Orchestrates the hooks of the plugin.
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
	
	/**
	 * save api key to db
	 */
	public function saveApiKey($raileoApiKey) {
		if($this->checkIfSpecialCharacters($raileoApiKey)){
			update_option('raileo_api_key', $raileoApiKey);
			if($raileoApiKey == '') {
				update_option('raileo_urls', '');
			}
			$this->getUrlsFomApi();
		}
		else{
			update_option('raileo_api_key', '');
			update_option('raileo_urls', '');
		}
	}

	/**
	 * get api key from db
	 */
	public function getApiKey() {
		return get_option('raileo_api_key' ,'');
	}
	/**
	 * delete urls stored in db
	 */
	public function emptyUrlsCache() {
		update_option('raileo_urls', []);
	}

	/**
	 * get urls from the api
	 * and store them in db as cache
	 */
	public function getUrlsFomApi() {
		$apiKey = $this->getApiKey();
		$result = null;
		if(isset($apiKey) && $apiKey != '') {
			$args = array(
				'headers' => array(
					'Authorization' => 'Bearer ' . $apiKey,
				),
			);
			$url = $this->apiUrl.'/urls/';
			$response = wp_remote_get( $url, $args );
			$response = wp_remote_retrieve_body($response);
			if(isset(json_decode($response)->data) && json_decode($response)->data != NULL) {
				$result = $response;
			}
		}
		update_option('raileo_urls', $result);
		return $result;
	}
	/**
	 * get urls 
	 * if not present in the db, call the api
	 */
	public function getUrls($refresh = null) {
		$result = get_option('raileo_urls');
		if($result == NULL || $refresh == true) {
			$result = $this->getUrlsFomApi();
		}
		if($result !== null)
			$result =json_decode($result);
		return $result;
	}
	/**
	 * Fet upime, ssl and pagespeed data for a url
	 */
	public function fetchUrlData($urlId) {
		$result = [];
		$apiKey = $this->getApiKey();
		if(isset($apiKey)) {
			$args = array(
				'headers' => array(
					'Authorization' => 'Bearer ' . $apiKey,
				),
			);
			$url = $this->apiUrl.'/urls/'.$urlId;
			$result = wp_remote_get( $url, $args );
			$result = json_decode(wp_remote_retrieve_body($result));
		}
		return $result;
	}

	/**
	 * check if api key has any special characters
	 */
	public function checkIfSpecialCharacters($apiKey) {
		if(preg_match('#[^a-zA-Z0-9]#', $apiKey))
			return false;
		else
			return true;
	}
}
