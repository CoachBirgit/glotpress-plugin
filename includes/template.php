<?php

function gp_breadcrumb( $args = array() ) {
	$defaults = array(
		'separator' => '<span class="separator">'._x('&rarr;', 'breadcrumb').'</span>',
		'breadcrumb_before' => '<span class="breadcrumb"><span class="separator">'._x('&rarr;', 'breadcrumb').'</span>',
		'breadcrumb_after' => '</span>',
	);
	$args = array_merge( $defaults, $args );

	$crumbs = array();

	if( $project = get_query_var( 'gp_project' ) ) {
		$path_from_root = array_reverse( $project->path_to_root() );
		// dvar_dump( $path_from_root );
		$crumbs[] = '<a href="' . gp_projects_url() . '">' . __( 'Projects', 'glotpress' ) . '</a>';

		foreach( $path_from_root as $project ) {
			$crumbs[] = '<a href="' . gp_project_url( $project ) . '">' . esc_html( $project->name ) . '</a>';
		}
	}
	else if( 'project' == get_query_var( 'gp_action' ) )
		$crumbs[] = __( 'Create New Project', 'glotpress' );
	else if( 'project-new' == get_query_var( 'gp_action' ) )
		$crumbs[] = __( 'Create New Project', 'glotpress' );
	else if( 'projects' == get_query_var( 'gp_action' ) )
		$crumbs[] = __( 'Projects', 'glotpress' );
	else if( 'profile' == get_query_var( 'gp_action' ) )
		$crumbs[] = __( 'Profile', 'glotpress' );
	else if( 'login' == get_query_var( 'gp_action' ) )
		$crumbs[] = __( 'Login', 'glotpress' );
	else if( 'register' == get_query_var( 'gp_action' ) )
		$crumbs[] = __( 'Register', 'glotpress' );

	$crumbs = apply_filters( 'gp_breadcrumb_crumbs', $crumbs );

	if( ! $crumbs )
		return '';

	$breadcrumb = implode( $args['separator'], array_filter( $crumbs ) );
	$breadcrumb = $args['breadcrumb_before'] . $breadcrumb . $args['breadcrumb_after'];

	return apply_filters( 'gp_breadcrumb', $breadcrumb );
}


function gp_project() {
	return get_query_var( 'gp_project' );
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
		$url = home_url( '/login/' );
	else
		$url = home_url( '/index.php?gp_action=login' );

	if ( ! empty( $redirect ) )
		$url = add_query_arg( 'redirect_to', urlencode( $redirect ), $url );

	$url = set_url_scheme( $url, 'login_post' );

	return esc_url( apply_filters( 'gp_login_url', $url, $redirect ) );
}


function gp_profile_url() {
	if ( '' != get_option('permalink_structure') )
		$url = home_url( '/profile/' );
	else
		$url = home_url( '/index.php?gp_action=profile' );

	return esc_url( apply_filters( 'gp_profile_url', $url ) );
}

function gp_register_url() {
	if ( '' != get_option('permalink_structure') )
		$url = home_url( '/register/' );
	else
		$url = home_url( '/index.php?gp_action=register' );

	return esc_url( apply_filters( 'gp_register_url', $url ) );
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


function gp_projects_url() {
	if ( '' != get_option('permalink_structure') )
		$url = home_url( '/projects/' );
	else
		$url = home_url( '/index.php?gp_action=projects' );

	return esc_url( apply_filters( 'gp_projects_url', $url ) );
}

function gp_project_url( $project, $action = '' ) {
	if ( '' != get_option('permalink_structure') ) {
		$url = home_url( trailingslashit( '/projects/' . $project->path ) );

		if( $action )
			$url .=  $action . '/';
	}
	else {
		$url = home_url( '/index.php?gp_project=' . $project->path );

		if( $action )
			$url .=  '&gp_action=' . $action;
	}

	return esc_url( apply_filters( 'gp_project_url', $url, $project ) );
}

function gp_project_edit_url( $project ) {
	if( ! $project )
		return;

	if ( '' != get_option('permalink_structure') )
		$url = home_url( '/projects/' . $project->path . '/-edit' );
	else
		$url = home_url( '/index.php?gp_project=' . $project->path );

	return esc_url( apply_filters( 'gp_project_edit_url', $url, $project ) );
}

function gp_project_new_url( $parent_id = '' ) {
	if ( '' != get_option('permalink_structure') )
		$url = home_url( '/projects/-new' );
	else
		$url = home_url( '/index.php?gp_action=project-new' );

	return esc_url( apply_filters( 'gp_project_new_url', $url ) );
}

function gp_translation_set_url( $project, $translation_set, $query = '' ) {
	if( ! $translation_set )
		return;

	$project_url = gp_project_url( $project );

	if ( '' != get_option('permalink_structure') )
		$url = $project_url . $translation_set->locale . '/' . $translation_set->locale . '/';
	else
		$url = $project_url . '&gp_locale=' . $translation_set->locale . '&gp_type=' . $translation_set->locale;

	return esc_url( apply_filters( 'gp_project_edit_url', $url, $translation_set ) );
}




function gp_project_edit( $project, $title = '', $class = 'action edit' ) {
	if ( ! current_user_can( 'gp_project_edit', $project->id ) )
		return '';

	if( ! $title )
		$title = '(' . __( 'edit', 'glotpress' ) . ')';

	return '<a href="' . gp_project_edit_url( $project ) . '" class="' . $class . '">' . $title . '</a>';
}


function gp_html_excerpt( $str, $count, $ellipsis = '&hellip;') {
	$excerpt = trim( wp_html_excerpt( $str, $count ) );

	if ( $str != $excerpt )
		$excerpt .= $ellipsis;

	return $excerpt;
}

/**
 * Returns a function, which returns the string "odd" or the string "even" alternatively.
 */
function gp_parity_factory() {
	return create_function( '', 'static $parity = "odd"; if ($parity == "even") $parity = "odd"; else $parity = "even"; return $parity;');
}

function gp_map( $results, $class ) {
	$mapped = array();

	foreach( $results as $result )
		$mapped[] = new $class( $result );

	return $mapped;
}