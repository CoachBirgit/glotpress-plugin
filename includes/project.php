<?php
class GP_Project {
	public $id;
	public $name;
	public $slug;
	public $path;
	public $description;
	public $parent_project_id;
	public $source_url_template;
	public $active;

	private $sub_projects;
	private $translation_sets;

	public function __construct( $data_slug_or_id ) {
		if( is_array( $data_slug_or_id ) )
			$fields = $data_slug_or_id;
		else if ( is_numeric( $data_slug_or_id ) )
			$fields = GlotPress_Query::project_by_id( $data_slug_or_id );
		else
			$fields = GlotPress_Query::project_by_slug( $data_slug_or_id );

		if( isset( $fields ) )
			$this->set_fields( $fields );
	}

	public function set_fields( $field ) {
		$field = (array)$field;

		if ( isset( $field['parent_project_id'] ) )
			$field['parent_project_id'] = $field['parent_project_id'] ? $field['parent_project_id'] : null;

		if ( isset( $field['slug'] ) && ! $field['slug'] )
			$field['slug'] = gp_sanitize_for_url( $field['name'] );

		if ( ( isset( $field['path'] ) && ! $field['path'] ) || ! isset( $field['path'] ) || is_null( $field['path'] ) )
			unset( $field['path'] );

		if ( isset( $field['active'] ) ) {
			if ( 'on' == $field['active'] )
				$field['active'] = 1;

			if ( ! $field['active'] )
				$field['active'] = 0;
		}

		foreach( $field as $key => $value ) {
			$this->$key = $value;
		}
	}

	public function sub_projects() {
		if( ! $this->sub_projects )
			$this->sub_projects = GlotPress_Query::sub_projects( $this->id );

		return $this->sub_projects;
	}

	public function translation_sets() {
		if( ! $this->translation_sets ) {
			$translation_sets = GlotPress_Query::translation_set_by_project_id( $this->id );
			usort( $translation_sets, array( $this, 'translation_sets_sort' ) );

			$this->translation_sets = gp_map( $translation_sets, 'GP_Translation_Set' );
		}

		return $this->translation_sets;
	}

	private function translation_sets_sort( $a, $b ) {
		return $a->current_count < $b->current_count;
	}


	public function source_url_template() {
		if ( isset( $this->user_source_url_template ) )
			return $this->user_source_url_template;
		else {
			if ( $this->id && is_user_logged_in() && ( $templates = get_user_meta( get_current_user_id(), 'source_url_templates', true ) )
					 && isset( $templates[ $this->id ] ) ) {
				$this->user_source_url_template = $templates[$this->id];
				return $this->user_source_url_template;
			}
			else {
				return $this->source_url_template;
			}
		}
	}

	public function path_to_root() {
		$path = array();

		if ( $this->parent_project_id ) {
			$parent_project = new GP_Project( $this->parent_project_id );
			$path = $parent_project->path_to_root();
		}

		return array_merge( array( $this ), $path );
	}
}