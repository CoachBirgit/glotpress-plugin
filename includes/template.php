<?php

function gp_breadcrumb( $breadcrumb = null, $args = array() ) {
	$defaults = array(
		/* translators: separates links in the navigation breadcrumb */
		'separator' => '<span class="separator">'._x('&rarr;', 'breadcrumb').'</span>',
		'breadcrumb-template' => '<span class="breadcrumb">{separator}{breadcrumb}</span>',
	);
	$args = array_merge( $defaults, $args );
	if ( !is_null( $breadcrumb ) ) {
		$breadcrumb = gp_array_flatten( $breadcrumb );
		$breadcrumb_string = implode( $args['separator'], array_filter( $breadcrumb ) );
		$whole_breadcrumb = str_replace( '{separator}', $args['separator'], $args['breadcrumb-template'] );
		$whole_breadcrumb = str_replace( '{breadcrumb}', $breadcrumb_string, $whole_breadcrumb );
		add_filter( 'gp_breadcrumb', lambda( '$x', '$whole_breadcrumb', compact( 'whole_breadcrumb' ) ), 5 );
	} else {
		return apply_filters( 'gp_breadcrumb', '' );
	}
}