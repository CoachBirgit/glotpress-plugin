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
		$project = $path;
		$project_match = 'gp_project=$matches[1]';
		$id = '(\d+)';
		$locale = '('.implode('|', array_map( create_function( '$x', 'return $x->slug;' ), GP_Locales::locales() ) ).')';
		$set = "$project/$locale/$dir";
		$set_match = 'gp_project=$matches[1]&gp_locale=$matches[2]&gp_type=$matches[3]';
		$new_rules = array (

			"new?" => 'index.php?gp_action=new_get',
			"new/post/?" => 'index.php?gp_action=new_post',

			'profile/?$' => 'index.php?gp_action=profile_get',
			'profile/post/?$' => 'index.php?gp_action=profile_post',
			"profile/$dir/?$" => 'index.php?gp_action=profile_get&gp_profile=$matches[1]',

			"$project/import-originals/?" => "index.php?gp_action=import_originals_get&$project_match",
			"$project/import-originals/post/?" => "index.php?gp_action=import_originals_post&$project_match",

			"$project/edit/?" => "index.php?gp_action=edit_get&$project_match",
			"$project/edit/post/?" => "index.php?gp_action=edit_post&$project_match",

			"$project/delete/?" => "index.php?gp_action=delete_get&$project_match",
			"$project/delete/post/?" => "index.php?gp_action=delete_post&$project_match",

			"$project/permissions/?" => "index.php?gp_action=permissions_get&$project_match",
			"$project/permissions/post/?" => "index.php?gp_action=permissions_post&$project_match",

			"$set/?" => "index.php?gp_action=set_get&$set_match",
			"$set/post/?" => "index.php?gp_action=set_post&$set_match"

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
		$vars[] = 'gp_profile';
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
		switch( get_query_var( 'gp_action' ) ) {

			case 'profile_get' :
			break;

			case 'profile_post' :
			break;

			case 'new_get' :
			break;

			case 'new_post' :
			break;

			case 'import_originals_get' :
			break;

			case 'import_originals_post' :
			break;

			case 'set_get' :
			break;

		}
	}
}
?>
