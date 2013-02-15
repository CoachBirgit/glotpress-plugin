<?php

/**
 * The GlotPress Plugin
 *
 * GlotPress is a collaborative, web-based software translation tool.
 *
 * $Id$
 *
 * @package GlotPress
 * @subpackage Main
 */

/**
 * Plugin Name: GlotPress
 * Plugin URI:  http://glotpress.org
 * Description: GlotPress is a collaborative, web-based software translation tool.
 * Author:      The GlotPress Community
 * Author URI:  http://glotpress.org
 * Version:     0.1-bleeding
 * Text Domain: glotpress
 * Domain Path: /languages/
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'GlotPress' ) ) :
/**
 * Main GlotPress Class
 */
final class GlotPress {

	/** Magic *****************************************************************/

	/**
	 * GlotPress uses many variables, several of which can be filtered to
	 * customize the way it operates. Most of these variables are stored in a
	 * private array that gets updated with the help of PHP magic methods.
	 *
	 * This is a precautionary measure, to avoid potential errors produced by
	 * unanticipated direct manipulation of GlotPress's run-time data.
	 *
	 * @see GlotPress::setup_globals()
	 * @var array
	 */
	private $data;

	/**
	 * @var GlotPress The one true GlotPress
	 */
	private static $instance;

	/**
	 * @var array Redirect notices
	 */
	public static $redirect_notices = array();

	/**
	 * Main GlotPress Instance
	 *
	 * Insures that only one instance of GlotPress exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since 0.1
	 * @static var array $instance
	 * @uses GlotPress::setup_globals() Setup the globals needed
	 * @uses GlotPress::includes() Include the required files
	 * @uses GlotPress::setup_actions() Setup the hooks and actions
	 * @see glotpress()
	 * @return The one true GlotPress
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new GlotPress;
			self::$instance->setup_globals();
			self::$instance->includes();
			self::$instance->initialize_classes();
			self::$instance->setup_actions();
		}
		return self::$instance;
	}

	/** Magic Methods *********************************************************/

	/**
	 * A dummy constructor to prevent GlotPress from being loaded more than once.
	 *
	 * @since 0.1
	 * @see GlotPress::instance()
	 * @see glotpress();
	 */
	private function __construct() { /* Do nothing here */ }

	/**
	 * A dummy magic method to prevent GlotPress from being cloned
	 *
	 * @since 0.1
	 */
	public function __clone() { _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'glotpress' ), '0.1' ); }

	/**
	 * A dummy magic method to prevent GlotPress from being unserialized
	 *
	 * @since 0.1
	 */
	public function __wakeup() { _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'glotpress' ), '0.1' ); }

	/**
	 * Magic method for checking the existence of a certain custom field
	 *
	 * @since 0.1
	 */
	public function __isset( $key ) { return isset( $this->data[$key] ); }

	/**
	 * Magic method for getting GlotPress variables
	 *
	 * @since 0.1
	 */
	public function __get( $key ) { return isset( $this->data[$key] ) ? $this->data[$key] : null; }

	/**
	 * Magic method for setting GlotPress variables
	 *
	 * @since 0.1
	 */
	public function __set( $key, $value ) { $this->data[$key] = $value; }

	/**
	 * Magic method for unsetting GlotPress variables
	 *
	 * @since 0.1
	 */
	public function __unset( $key ) { if ( isset( $this->data[$key] ) ) unset( $this->data[$key] ); }

	/**
	 * Magic method to prevent notices and errors from invalid method calls
	 *
	 * @since 0.1
	 */
	public function __call( $name = '', $args = array() ) { unset( $name, $args ); return null; }

	/**
	 * Set some smart defaults to class variables. Allow some of them to be
	 * filtered to allow for early overriding.
	 *
	 * @since 0.1
	 * @access private
	 * @uses plugin_dir_path() To generate GlotPress plugin path
	 * @uses plugin_dir_url() To generate GlotPress plugin url
	 * @uses apply_filters() Calls various filters
	 */
	private function setup_globals() {

		/** Versions **********************************************************/

		$this->version    = '0.1';
		$this->db_version = '1';

		/** Paths *************************************************************/

		// Setup some base path and URL information
		$this->file       = __FILE__;
		$this->basename   = apply_filters( 'gp_plugin_basenname', plugin_basename( $this->file ) );
		$this->plugin_dir = apply_filters( 'gp_plugin_dir_path',  plugin_dir_path( $this->file ) );
		$this->plugin_url = apply_filters( 'gp_plugin_dir_url',   plugin_dir_url ( $this->file ) );

		// Includes
		$this->includes_dir = apply_filters( 'gp_includes_dir', trailingslashit( $this->plugin_dir . 'includes'  ) );
		$this->includes_url = apply_filters( 'gp_includes_url', trailingslashit( $this->plugin_url . 'includes'  ) );

		// Languages
		$this->lang_dir     = apply_filters( 'gp_lang_dir',     trailingslashit( $this->plugin_dir . 'languages' ) );

		// Templates
		$this->themes_dir   = apply_filters( 'gp_themes_dir',   trailingslashit( $this->plugin_dir . 'themes' ) );
		$this->themes_url   = apply_filters( 'gp_themes_url',   trailingslashit( $this->plugin_url . 'themes' ) );

		/** Misc **************************************************************/

		$this->domain         = 'glotpress';      // Unique identifier for retrieving translated strings

		/** Cache *************************************************************/

		// Add GlotPress to global cache groups
		wp_cache_add_global_groups( 'glotpress' );
	}

	/**
	 * Include required files
	 *
	 * @since 0.1
	 * @access private
	 * @uses is_admin() If in WordPress admin, load additional file
	 */
	private function includes() {
		require( $this->plugin_dir . 'includes/misc.php' );
		require( $this->plugin_dir . 'includes/template.php' );
		require( $this->plugin_dir . 'includes/locales.php' );
		require( $this->plugin_dir . 'includes/router.php' );
		require( $this->plugin_dir . 'includes/query.php' );
		require( $this->plugin_dir . 'includes/login.php' );

		require( $this->plugin_dir . 'includes/profile.php' );
	}

	/**
	 * Setup the classes
	 *
	 * @since 0.1
	 * @access private
	 */
	private function initialize_classes() {
		// Setup the GlotPress theme directory
		register_theme_directory( $this->themes_dir );

		new GlotPress_Login;
	}

	/**
	 * Setup the default hooks and actions
	 *
	 * @since 0.1
	 * @access private
	 * @uses add_action() To add various actions
	 */
	private function setup_actions() {
		// Rewrite rules
		add_filter( 'rewrite_rules_array', array( 'GlotPress_Router', 'rewrite_rules' ) );

		// Query vars
		add_filter( 'query_vars', array( 'GlotPress_Router', 'query_vars' ) );

		// Get data
		add_action( 'pre_get_posts', array( 'GlotPress_Router', 'pre_get_posts' ), 1 );

		// Switch template
		add_filter( 'template_include', array( 'GlotPress_Router', 'template_include' ), 1 );

		// Switch template
		add_filter( 'wp_title', array( 'GlotPress_Router', 'wp_title' ), 10, 3 );
	}

}

/**
 * The main function responsible for returning the one true GlotPress Instance
 * to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $gp = glotpress(); ?>
 *
 * @return The one true GlotPress Instance
 */
function glotpress() {
	return glotpress::instance();
}

glotpress();

endif; // class_exists check