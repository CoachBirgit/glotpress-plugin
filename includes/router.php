<?php

class GlotPress_Router {

	/**
	 * Set rewrite rules
	 *
	 * @since 0.1
	 * @return New set of rewrite rules
	 *
	 * @see http://glotpress.trac.wordpress.org/browser/trunk/gp-includes/router.php
	 *
	 * @todo import get:/$project/-permissions/-delete/$dir and all $set stuff
	 * @todo project match parser
	 */
	function rewrite_rules( $current_rules = array() ) {
		$dir = '([^_/][^/]*)';
		$path = '(.+?)';
		$projects = 'projects';
		$project = "$projects/$path";
		$id = '(\d+)';
		$locale = '('.implode('|', array_map( create_function( '$x', 'return $x->slug;' ), GP_Locales::locales() ) ).')';
		$set = "$project/$locale/$dir";
		$new_rules = array (
			'profile/?$' => 'index.php?gp_action=profile',
			"$project/(import-originals|-edit|-delete|-personal|-permissions|-mass-create-sets|-mass-create-sets/preview|-new)/?$" => 'index.php?gp_project=$matches[1]&gp_action=$matches[2]',
			"$projects/?$" => "index.php?gp_project=project&gp_action=index",
			"$set/(-bulk|import-translations|-discard-warning|-set-status|export-translations)/?$" => 'index.php?gp_project=$matches[1]&gp_locale=$matches[2]&gp_type=$matches[3]&gp_action=$matches[4]',
			"$set/?$" => 'index.php?gp_set=set&gp_project=$matches[1]&gp_locale=$matches[2]&gp_type=$matches[3]',
			"$project/?$" => 'index.php?gp_project=$matches[1]&gp_action=single'
		);
		return apply_filters( 'gp_rewrite_rules', $new_rules + $current_rules );
	}

	/**
	 * Set query vars
	 *
	 * @since 0.1
	 * @uses gp_rewrite_rules() to get the query vars
	 * @return New set of query vars
	 */
	function query_vars( $vars ) {
		$vars[] = 'gp_action';
		$vars[] = 'gp_project';
		$vars[] = 'gp_set';
		$vars[] = 'gp_locale';
		return $vars;
	}

	/**
	 * Redirect to query according to the query vars
	 *
	 * @since 0.1
	 *
	 * @todo all other matches
	 */
	function pre_get_posts() {
		if( get_query_var( 'gp_set' ) ) {
			return gp_set( get_query_var( 'gp_project' ),
				get_query_var( 'gp_locale' ),
				get_query_var( 'gp_type' ),
				get_query_var( 'gp_action' ) );
		}
		elseif( get_query_var( 'gp_project' ) ) {
			return gp_project( get_query_var( 'gp_project' ),
				get_query_var( 'gp_action' ) );
		}
		elseif( 'profile' == get_query_var( 'gp_action' ) ) {
			return gp_profile();
		}
	}

	/**
	 * Loads the correct template based on the visitor's url
	 *
	 * @since 0.1
	 */
	function template_include( $template ) {
		if( 'profile' == get_query_var( 'gp_action' ) ) {
			if( is_user_logged_in() )
				return get_stylesheet_directory() . '/profile.php';
			else
				return get_404_template();
		}

		return $template;
	}

	/**
	 * Change the page title for all GlotPress areas.
	 *
	 * @since 0.1
	 */
	function wp_title( $title ) {
		if( 'profile' == get_query_var( 'gp_action' ) )
			return 'Profile &lt; GlotPress';
	}

}
?>
