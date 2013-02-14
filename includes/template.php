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