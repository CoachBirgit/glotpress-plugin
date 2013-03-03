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
			'login/?$' => 'index.php?gp_action=login',
			'register/?$' => 'index.php?gp_action=register',

			"$projects/-new/?$" => "index.php?gp_action=project-new",
			"$projects/?$" => "index.php?gp_action=projects",

			"$set/(-bulk|import-translations|-discard-warning|-set-status|export-translations)/?$" => 'index.php?gp_project=$matches[1]&gp_locale=$matches[2]&gp_type=$matches[3]&gp_action=$matches[4]',
			"$set/?$" => 'index.php?gp_set=set&gp_project=$matches[1]&gp_locale=$matches[2]&gp_type=$matches[3]',

			"$project/(import-originals|-edit|-delete|-personal|-permissions|-mass-create-sets|-mass-create-sets/preview)/?$" => 'index.php?gp_project=$matches[1]&gp_action=$matches[2]',
			"$project/?$" => 'index.php?gp_project=$matches[1]'
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
	 * @todo all other matches and maybe pass the query along.
	 */
	function pre_get_posts( $query ) {
		if( ! $query->is_main_query() )
			return;

		if( get_query_var( 'gp_project' ) ) {
			$project = new GP_Project( get_query_var( 'gp_project' ) );

			if( $project )
				$query->set( 'gp_project', $project );
			else
				self::set_404( $query );
		}
		else if( 'login' == get_query_var( 'gp_action' ) && is_user_logged_in() ) {
			self::set_404( $query );
		}
		else if( 'profile' == get_query_var( 'gp_action' ) && ! is_user_logged_in() ) {
			self::set_404( $query );
		}
		else if( 'register' == get_query_var( 'gp_action' ) && ( is_user_logged_in() || ! get_option('users_can_register') ) ) {
			self::set_404( $query );
		}
	}

	/**
	 * Sets the 404 property, 404 header and cleans gp_action
	 *
	 * @since 2.0.0
	 */
	function set_404( $query ) {
		$query->set( 'gp_action', '' );
		$query->set( 'gp_project', '' );
		$query->set_404();
		status_header( 404 );
	}

	/**
	 * Loads the correct template based on the visitor's url
	 *
	 * @since 0.1
	 */
	function template_include( $template ) {
		global $project;

		if( $project = get_query_var( 'gp_project' ) ) {

			if( get_query_var( 'gp_locale' ) )
				$template = get_query_template( 'set' );
			else if( '-edit' == get_query_var( 'gp_action' ) )
				$template = get_query_template( 'project-edit' );
			else
				$template = get_query_template( 'project' );
		}
		else if( 'project-new' == get_query_var( 'gp_action' ) ) {
			$template = get_query_template( 'project-new' );
		}
		else if( 'projects' == get_query_var( 'gp_action' ) ) {
			global $projects;
			$projects = GlotPress_Query::projects();

			$template = get_query_template( 'projects' );
		}
		else if( 'profile' == get_query_var( 'gp_action' ) ) {
			GlotPress_Profile::update_profile();

			$template = get_query_template( 'profile' );
		}
		else if( 'login' == get_query_var( 'gp_action' ) ) {
			GlotPress_Login::check_login();

			$template = get_query_template( 'login' );
		}
		else if( 'register' == get_query_var( 'gp_action' ) ) {
			GlotPress_Login::check_register();

			$template = get_query_template( 'register' );
		}

		return $template;
	}

	/**
	 * Change the page title for all GlotPress areas.
	 *
	 * @since 0.1
	 */
	function wp_title( $title, $sep, $seplocation ) {
		if( is_404() )
			return $title;

		if( ! $sep )
			$sep = '&lt;';

		if( get_query_var( 'gp_project' ) ) {
			$project = get_query_var( 'gp_project' );
			return esc_html( $project->name ) . ' ' . $sep . ' ' . get_bloginfo('name');
		}
		else if( 'project-new' == get_query_var( 'gp_action' ) )
			return __( 'Create New Project', 'glotpress' ) . ' ' . $sep . ' ' . get_bloginfo('name');
		else if( 'projects' == get_query_var( 'gp_action' ) )
			return __( 'Projects', 'glotpress' ) . ' ' . $sep . ' ' . get_bloginfo('name');
		else if( 'profile' == get_query_var( 'gp_action' ) )
			return __( 'Profile', 'glotpress' ) . ' ' . $sep . ' ' . get_bloginfo('name');
		else if( 'login' == get_query_var( 'gp_action' ) )
			return __( 'Login', 'glotpress' ) . ' ' . $sep . ' ' . get_bloginfo('name');
		else if( 'register' == get_query_var( 'gp_action' ) )
			return __( 'Register', 'glotpress' ) . ' ' . $sep . ' ' . get_bloginfo('name');

		return $title;
	}

}
