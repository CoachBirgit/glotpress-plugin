<?php

function gp_breadcrumb() {
	return apply_filters( 'gp_breadcrumb', '' );
}

function gp_limit_for_page() {
	$per_page = false;

	if( is_user_logged_in() )
		$per_page = get_user_meta( get_current_user_id(), 'gp_per_page', true );

	if ( ! $per_page )
		$per_page = 15;

	return apply_filters( 'gp_limit_for_page', $per_page );
}

function gp_sort_for_page() {
	$default_sort = false;

	if( is_user_logged_in() )
		$default_sort = get_user_meta( get_current_user_id(), 'gp_default_sort', true );

	if ( ! is_array( $default_sort ) ) {
		$default_sort = array(
			'by' => 'priority',
			'how' => 'desc'
		);
	}

	return apply_filters( 'gp_sort_for_page', $default_sort );
}

function gp_radio_buttons( $name, $radio_buttons, $checked_key ) {
	$res = '';
	foreach( $radio_buttons as $value => $label ) {
		$checked = $value == $checked_key? " checked='checked'" : '';
		// TODO: something more flexible than <br />
		$res .= "\t<input type='radio' name='$name' value='".esc_attr( $value )."' $checked id='{$name}[{$value}]'/>&nbsp;";
		$res .= "<label for='{$name}[{$value}]'>".esc_html( $label )."</label><br />\n";
	}

	return $res;
}




/*
	@todo need check if login template exists. Otherwise use wp_login_url
*/
function gp_login_url( $redirect = '' ) {
	if ( '' != get_option('permalink_structure') )
		$url = esc_url( home_url( '/login/' ) );
	else
		$url = esc_url( home_url( '/index.php?gp_action=login' ) );

	if ( ! empty( $redirect ) )
		$url = add_query_arg( 'redirect_to', urlencode( $redirect ), $url );

	$url = set_url_scheme( $url, 'login_post' );

	return apply_filters( 'gp_login_url', $url, $redirect );
}


function gp_profile_url() {
	if ( '' != get_option('permalink_structure') )
		$url = esc_url( home_url( '/profile/' ) );
	else
		$url = esc_url( home_url( '/index.php?gp_action=profile' ) );

	return apply_filters( 'gp_profile_url', $url );
}

function gp_register_url() {
	if ( '' != get_option('permalink_structure') )
		$url = esc_url( home_url( '/register/' ) );
	else
		$url = esc_url( home_url( '/index.php?gp_action=register' ) );

	return apply_filters( 'gp_register_url', $url );
}


/**
 * Display the Registration link.
 *
 * Display a link which allows the user to navigate to the registration page if
 * not logged in and registration is enabled.
 *
 * @since 1.5.0
 * @uses apply_filters() Calls 'register' hook on register / admin link content.
 *
 * @param string $before Text to output before the link.
 * @param string $after Text to output after the link.
 * @param boolean $echo Default to echo and not return the link.
 * @return string|null String when retrieving, null when displaying.
 */
function gp_register( $before = '', $after = '', $echo = true ) {
	$link = '';

	if ( ! is_user_logged_in() ) {
		if ( get_option('users_can_register') )
			$link = $before . '<a href="' . gp_register_url() . '">' . __( 'Register', 'glotpress' ) . '</a>' . $after;
	}

	if ( $echo )
		echo apply_filters( 'gp_register', $link );
	else
		return apply_filters( 'gp_register', $link );
}

