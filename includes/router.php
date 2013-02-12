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
	 */
	function rewrite_rules( $current_rules = array() ) {
		$new_rules = array (
			'login/?$'  => 'index.php?glotpress=login',
			'profile/?$'  => 'index.php?glotpress=profile',

			'projects/([^/]+)/-mass-create-sets/preview/?$' => 'index.php?glotpress=project&gp_project=$matches[1]&gp_type=-mass-create-sets-preview',
			'projects/([^/]+)/(import-originals|-edit|-delete|-personal-permissions|-mass-create-sets)/?$' => 'index.php?glotpress=project&gp_project=$matches[1]&gp_type=$matches[2]',
			'projects/([^/]+)/?$' => 'index.php?glotpress=project&gp_project=$matches[1]',
			'projects/-new/?$'		 => 'index.php?glotpress=project&gp_type=new',
			'projects/?$'		 => 'index.php?glotpress=project'
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
		$vars[] = 'glotpress';
		$vars[] = 'gp_type';
		$vars[] = 'gp_project';
		
		return $vars;
	}

	/**
	 * Redirect to query according to the query vars
	 *
	 * @since 0.1
	 */
	function pre_get_posts() {
		if( 'project' == get_query_var( 'glotpress' ) ) {
			if( $project = get_query_var( 'gp_project' ) )
				return gp_project( $project );
			else
				gp_projects_home();
		}
		elseif( 'profile' == get_query_var( 'glotpress' ) )
			return gp_profile();
	}
}
?>
