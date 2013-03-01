<?php

class GlotPress_Query {
	public static $prefix = '';

	public static function projects() {
		global $wpdb;

		$projects = wp_cache_get( 'gp_projects' );

		if ( false === $projects ) {
			$projects = $wpdb->get_results( "SELECT * FROM " . self::$prefix . "projects WHERE parent_project_id IS NULL ORDER BY name ASC" );
			wp_cache_set( 'gp_projects', $projects );
		}

		return $projects;
	}

	public static function sub_projects( $project_id ) {
		global $wpdb;

		$cache_key = 'gp_projects_' . absint( $project_id );
		$projects  = wp_cache_get( $cache_key );

		if ( false === $projects ) {
			$projects = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . self::$prefix . "projects WHERE parent_project_id = %d ORDER BY active DESC, id ASC", $project_id ) );
			wp_cache_set( $cache_key, $projects );
		}

		return $projects;
	}
}