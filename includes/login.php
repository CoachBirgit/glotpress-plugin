<?php

class GlotPress_Login {

	public static function check_login() {
		if( isset( $_POST['user_login'], $_POST['user_pass'] ) ) {
			nocache_headers();
			$secure_cookie = '';

			// If the user wants ssl but the session is not ssl, force a secure cookie.
			if ( ! force_ssl_admin() ) {
				$user_name = sanitize_user( $_POST['user_login'] );

				if ( $user = get_user_by('login', $user_name ) ) {
					if ( get_user_option( 'use_ssl', $user->ID ) ) {
						$secure_cookie = true;
						force_ssl_admin( true );
					}
				}
			}

			if ( isset( $_REQUEST['redirect_to'] ) )
				$redirect_to = $_REQUEST['redirect_to'];
			else
				$redirect_to = home_url();

			if ( ! $secure_cookie && is_ssl() && force_ssl_login() && ! force_ssl_admin() )
				$secure_cookie = false;

			$creds = array();
			$creds['user_login']    = $_POST['user_login'];
			$creds['user_password'] = $_POST['user_pass'];
			$user = wp_signon( $creds, $secure_cookie );

			if ( $user && ! is_wp_error( $user ) ) {
				wp_safe_redirect( $redirect_to );
				exit;
			}

			return $user;
		}
	}
}