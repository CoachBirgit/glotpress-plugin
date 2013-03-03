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

	public static function project_by_id( $project_slug ) {
		global $wpdb;

		$project = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . self::$prefix . "projects WHERE id = '%s'", trim( $project_slug, '/' ) ) );

		return $project;
	}

	public static function project_by_slug( $project_slug ) {
		global $wpdb;

		$project = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . self::$prefix . "projects WHERE path = '%s'", trim( $project_slug, '/' ) ) );

		return $project;
	}


	public function translation_set_by_project_id( $project_id ) {
		global $wpdb;

		$cache_key = 'translation_set_project_' . absint( $project_id );
		$projects  = wp_cache_get( $cache_key );

		if ( false === $projects ) {
			$projects = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . self::$prefix . "translation_sets WHERE project_id = %d ORDER BY name ASC", $project_id ) );
			wp_cache_set( $cache_key, $projects );
		}

		return $projects;
	}

	public function translation_set_counts( $project_id ) {
		global $wpdb;

		$counts = wp_cache_get( $project_id, 'translation_set_status_breakdown' );

		if ( ! is_array( $counts ) ) {
			/*
			 * TODO:
			 *  - calculate weighted coefficient by priority to know how much of the strings are translated
			 * 	- calculate untranslated
			 */
			$t = self::$prefix . 'translations';
			$o = self::$prefix . 'originals';

			$counts = $wpdb->get_results( $wpdb->prepare("
				SELECT t.status as translation_status, COUNT(*) as n
				FROM $t AS t INNER JOIN $o AS o ON t.original_id = o.id WHERE t.translation_set_id = %d AND o.status LIKE '+%%' GROUP BY t.status", $project_id ) );

			$warnings_count = $wpdb->get_var( $wpdb->prepare("
				SELECT COUNT(*) FROM $t AS t INNER JOIN $o AS o ON t.original_id = o.id
				WHERE t.translation_set_id = %d AND o.status LIKE '+%%' AND (t.status = 'current' OR t.status = 'waiting') AND warnings IS NOT NULL", $project_id ) );

			$all_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $o WHERE project_id= %d AND status = '+active'", $project_id ) );

			$counts[] = (object)array( 'translation_status' => 'warnings', 'n' => $warnings_count );
			$counts[] = (object)array( 'translation_status' => 'all', 'n' => $all_count );
			wp_cache_set( $this->id, $counts, 'translation_set_status_breakdown' );
		}

		return $counts;
	}
}