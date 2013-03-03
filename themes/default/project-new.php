<?php get_header(); ?>

	<h2><?php _e( 'Create New Project', 'glotpress' ); ?></h2>

	<form action="" method="post">
		<?php get_template_part('project-form'); ?>

		<p>
			<input type="submit" name="submit" value="<?php echo esc_attr( __('Create') ); ?>" id="submit" />
			<span class="or-cancel">or <a href="javascript:history.back();">Cancel</a></span>
		</p>
	</form>

<?php get_footer();