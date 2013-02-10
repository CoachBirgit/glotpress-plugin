<?php

	/**
	 * Set rewrite rules
	 *
	 * @since 0.1
	 * @return New set of rewrite rules
	 */
	function gp_rewrite_rules( $current_rules = array() ) {
		$new_rules = array (
			'^projects/?$'		 => 'index.php?gp_projects_home=1',
			'^projects/([^/]+)/?$' => 'index.php?gp_projects=$matches[1]',
			'^profile/([^/]+)/?$'  => 'index.php?gp_profile=$matches[1]'
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
	function gp_query_vars ( $current_vars ) {
		$new_vars = array();
		$rules = gp_rewrite_rules();
		foreach( $rules as $rule ) {
			parse_str( preg_replace( '/.+\?/', '', $rule ), $vars);
			$new_vars = array_merge( $new_vars, array_keys( $vars ) );
		}
		return apply_filters( 'gp_query_vars', $new_vars );
	}

	/**
	 * Redirect to query according to the query vars
	 *
	 * @since 0.1
	 */
	function gp_pre_get_posts() {

		if( get_query_var( 'gp_projects_home' ) )
			return gp_projects_home();

		elseif( $project = get_query_var( 'gp_projects' ) )
			return gp_project( $project );

		elseif( $profile = get_query_var( 'gp_profile' ) )
			return gp_profile( $profile );

	}
?>
