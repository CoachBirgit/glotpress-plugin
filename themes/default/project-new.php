<?php get_header(); ?>

	<h2><?php _e( 'Create New Project', 'glotpress' ); ?></h2>

	<form action="" method="post">
		<?php get_template_part('project-form'); ?>

		<p>
			<input type="submit" name="submit" value="<?php esc_attr_e( 'Create', 'glotpress' ); ?>" id="submit" />
			<span class="or-cancel">or <a href="<?php gp_projects_url(); ?>"><?php _e( 'Cancel', 'glotpress' ); ?></a></span>
		</p>
	</form>

<?php get_footer();