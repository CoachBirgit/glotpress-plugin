<?php
	get_header();
	$default_sort = gp_sort_for_page();
?>

	<h2><?php _e( "Profile" ); ?></h2>
	<form method="post">
		<table class="form-table">
			<tr>
				<th><label for="per_page"><?php _e( "Number of items per page:" ); ?></label></th>
				<td><input type="number" id="per_page" name="per_page" value="<?php echo gp_limit_for_page(); ?>"/></td>
			</tr>
			<tr>
				<th><label for="default_sort[by]"><?php _e("Default Sort By:") ?></label></th>
				<td><?php echo gp_radio_buttons('default_sort[by]',
					array(
						'original_date_added' => __('Date added (original)'),
						'translation_date_added' => __('Date added (translation)'),
						'original' => __('Original string'),
						'translation' => __('Translation'),
						'priority' => __('Priority'),
						'references' => __('Filename in source'),
						'random' => __('Random'),
					), $default_sort['by'] ); ?></td>
			</tr>
			<tr>
				<th><label for="default_sort[how]"><?php _e("Default Sort Order:") ?></label></th>
				<td><?php echo gp_radio_buttons('default_sort[how]',
					array(
						'asc' => __('Ascending'),
						'desc' => __('Descending'),
					), $default_sort['how'] );
				?></td>
			</tr>
		</table>
		<br>
		<input type="submit" name="submit" value="<?php esc_attr_e("Change Settings"); ?>">
	</form>

<?php get_footer(); ?>