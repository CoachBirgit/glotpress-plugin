<?php
/**
 * GP-Default theme functions and definitions
 *
 * @package GlotPress
 * @subpackage GP-Default
 * @since 1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class GP_Default {
	private $version = '1.0';

	function __construct() {
		// If GlotPress is not activated, switch back to the default WP theme and bail out
		if ( ! function_exists( 'glotpress' ) ) {
			switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );
			return;
		}

		add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_styles' ) );
	}

	function after_setup_theme() {
		// This theme comes with all the GlotPress goodies
		add_theme_support( 'glotpress' );

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );
	}

	function wp_enqueue_scripts() {
		wp_enqueue_script( 'jquery' );

		if( get_query_var( 'gp_project' ) )
			wp_enqueue_script( 'gp-common', get_template_directory_uri() . '/js/common.js', array( 'jquery' ), $this->version );
	}

	function wp_enqueue_styles() {
		wp_enqueue_style( 'glotpress', get_template_directory_uri() . '/css/style.css', array(), $this->version );
	}
}

new GP_Default;