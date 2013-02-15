<?php

class GlotPress_Profile {

	function update_profile() {
		if ( isset( $_POST['submit'] ) && is_user_logged_in() ) {
			$per_page = absint( $_POST['per_page'] );
			update_user_meta( get_current_user_id(), 'gp_per_page', $per_page );

			$default_sort = $_POST['default_sort'];
			update_user_meta( get_current_user_id(), 'gp_default_sort', $default_sort );

			gp_notice_set( __( 'Profile is updated', 'glotpress' ) );
		}
	}
}
