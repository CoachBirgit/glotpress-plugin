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

			if ( ! is_wp_error( $user ) ) {
				wp_safe_redirect( $redirect_to );
				exit;
			}

			self::handle_errors( $user );

			return $user;
		}
	}

	public static function check_register() {
		if( isset( $_POST['user_login'], $_POST['user_email'] ) ) {
			$user_login = $_POST['user_login'];
			$user_email = $_POST['user_email'];
			$errors = self::register_new_user( $user_login, $user_email );

			if ( ! is_wp_error( $errors ) ) {
				$redirect_to = ! empty( $_POST['redirect_to'] ) ? $_POST['redirect_to'] : site_url( 'wp-login.php?checkemail=registered' );
				wp_safe_redirect( $redirect_to );
				exit();
			}

			self::handle_errors( $errors );

			return $errors;
		}
	}


	/**
	 * Handles registering a new user. Copy from wp-login.php
	 *
	 * @param string $user_login User's username for logging in
	 * @param string $user_email User's email address to send password and add
	 * @return int|WP_Error Either user's ID or error on failure.
	 */
	private function register_new_user( $user_login, $user_email ) {
		$errors = new WP_Error();

		$sanitized_user_login = sanitize_user( $user_login );
		$user_email = apply_filters( 'user_registration_email', $user_email );

		// Check the username
		if ( $sanitized_user_login == '' ) {
			$errors->add( 'empty_username', __( '<strong>ERROR</strong>: Please enter a username.' ) );
		} elseif ( ! validate_username( $user_login ) ) {
			$errors->add( 'invalid_username', __( '<strong>ERROR</strong>: This username is invalid because it uses illegal characters. Please enter a valid username.' ) );
			$sanitized_user_login = '';
		} elseif ( username_exists( $sanitized_user_login ) ) {
			$errors->add( 'username_exists', __( '<strong>ERROR</strong>: This username is already registered. Please choose another one.' ) );
		}

		// Check the e-mail address
		if ( $user_email == '' ) {
			$errors->add( 'empty_email', __( '<strong>ERROR</strong>: Please type your e-mail address.' ) );
		} elseif ( ! is_email( $user_email ) ) {
			$errors->add( 'invalid_email', __( '<strong>ERROR</strong>: The email address isn&#8217;t correct.' ) );
			$user_email = '';
		} elseif ( email_exists( $user_email ) ) {
			$errors->add( 'email_exists', __( '<strong>ERROR</strong>: This email is already registered, please choose another one.' ) );
		}

		do_action( 'register_post', $sanitized_user_login, $user_email, $errors );

		$errors = apply_filters( 'registration_errors', $errors, $sanitized_user_login, $user_email );

		if ( $errors->get_error_code() )
			return $errors;

		$user_pass = wp_generate_password( 12, false );
		$user_id = wp_create_user( $sanitized_user_login, $user_pass, $user_email );
		if ( ! $user_id ) {
			$errors->add( 'registerfail', sprintf( __( '<strong>ERROR</strong>: Couldn&#8217;t register you... please contact the <a href="mailto:%s">webmaster</a> !' ), get_option( 'admin_email' ) ) );
			return $errors;
		}

		update_user_option( $user_id, 'default_password_nag', true, true ); //Set up the Password change nag.

		wp_new_user_notification( $user_id, $user_pass );

		return $user_id;
	}

	private static function handle_errors( $wp_error ) {
		$errors = '';
		$messages = '';

		foreach ( $wp_error->get_error_codes() as $code ) {
			$severity = $wp_error->get_error_data( $code );

			foreach ( $wp_error->get_error_messages( $code ) as $error ) {
				if ( 'message' == $severity )
					$messages .= '	' . $error . "<br />\n";
				else
					$errors .= '	' . $error . "<br />\n";
			}
		}

		if ( ! empty( $errors ) )
			gp_notice_set( '<div id="login_error">' . apply_filters( 'login_errors', $errors ) . '</div>', 'error' );
		if ( ! empty( $messages ) )
			gp_notice_set(  '<p class="message">' . apply_filters( 'login_messages', $messages ) . '</p>' );
	}

}