<?php
	get_header();
	$default_sort = gp_sort_for_page();
?>

	<h2><?php _e( 'Profile', 'glotpress' ); ?></h2>
	<form method="post">
		<table class="form-table">
			<tr>
				<th><label for="per_page"><?php _e( 'Number of items per page:', 'glotpress' ); ?></label></th>
				<td><input type="number" id="per_page" name="per_page" value="<?php echo gp_limit_for_page(); ?>"/></td>
			</tr>
			<tr>
				<th><label for="default_sort[by]"><?php _e( 'Default Sort By:', 'glotpress' ) ?></label></th>
				<td><?php echo gp_radio_buttons('default_sort[by]',
					array(
						'original_date_added' => __( 'Date added (original)', 'glotpress' ),
						'translation_date_added' => __( 'Date added (translation)', 'glotpress' ),
						'original' => __( 'Original string', 'glotpress' ),
						'translation' => __( 'Translation', 'glotpress' ),
						'priority' => __( 'Priority', 'glotpress' ),
						'references' => __( 'Filename in source', 'glotpress' ),
						'random' => __( 'Random', 'glotpress' ),
					), $default_sort['by'] ); ?></td>
			</tr>
			<tr>
				<th><label for="default_sort[how]"><?php _e( 'Default Sort Order:', 'glotpress' ) ?></label></th>
				<td><?php echo gp_radio_buttons('default_sort[how]',
					array(
						'asc' => __( 'Ascending', 'glotpress' ),
						'desc' => __( 'Descending', 'glotpress' ),
					), $default_sort['how'] );
				?></td>
			</tr>
		</table>
		<br>
		<input type="submit" name="submit" value="<?php esc_attr_e( 'Change Settings', 'glotpress' ); ?>">
	</form>

<?php get_footer(); ?>