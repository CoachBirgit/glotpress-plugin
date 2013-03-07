<?php
class GP_Translation_Set {
	public $id;
	public $name;
	public $slug;
	public $project_id;
	public $locale;

	public function __construct( $data_slug_or_id ) {
		if( is_array( $data_slug_or_id ) || is_object( $data_slug_or_id ) )
			$this->set_fields( $data_slug_or_id );

	}

	public function set_fields( $fields ) {
		$fields = (array)$fields;

		foreach( $fields as $key => $value ) {
			$this->$key = $value;
		}
	}


	public function name_with_locale( $separator = '&rarr;' ) {
		$locale = GP_Locales::by_slug( $this->locale );
		$parts = array( $locale->english_name );

		if ( 'default' != $this->slug )
			$parts[] = $this->name;

		return implode( '&nbsp;' . $separator . '&nbsp;', $parts );
	}

	public function all_count() {
		if ( ! isset( $this->all_count ) )
			$this->update_status_breakdown();

		return $this->all_count;
	}

	public function current_count() {
		if ( ! isset( $this->current_count ) )
			$this->update_status_breakdown();

		return $this->current_count;
	}

	public function untranslated_count() {
		if ( ! isset( $this->untranslated_count ) )
			$this->update_status_breakdown();

		return $this->untranslated_count;
	}

	public function waiting_count() {
		if ( ! isset( $this->waiting_count ) )
			$this->update_status_breakdown();

		return $this->waiting_count;
	}

	public function percent_translated() {
		return sprintf( _x( '%d%%', 'language translation percent' ), $this->all_count() ? $this->current_count() / $this->all_count() * 100 : 0 );
	}



	private function update_status_breakdown() {
		$counts = GlotPress_Query::translation_set_counts( $this->id, $this->project_id );

		$statuses   = array('current', 'waiting', 'rejected', 'fuzzy', 'old', );  //GlotPress::$translation->get_static( 'statuses' );
		$statuses[] = 'warnings';
		$statuses[] = 'all';

		foreach( $statuses as $status ) {
			$this->{$status.'_count'} = 0;
		}

		$this->untranslated_count = 0;
		foreach( $counts as $count ) {
			if ( in_array( $count->translation_status, $statuses ) ) {
				$this->{$count->translation_status.'_count'} = $count->n;
			}
		}

		$this->untranslated_count = $this->all_count - $this->current_count;
	}

}