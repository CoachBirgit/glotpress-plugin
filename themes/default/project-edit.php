<?php get_header(); ?>

	<h2><?php echo wptexturize( sprintf( __( 'Edit project "%s"', 'glotpress' ), esc_html( $project->name ) ) ); ?></h2>
	
	<form action="" method="post">
		<?php get_template_part('project-form'); ?>

		<p>
			<input type="submit" name="submit" value="<?php esc_attr_e( 'Save', 'glotpress' ); ?>" id="submit" />
			<span class="or-cancel">or <a href="<?php gp_project_url( $project ); ?>"><?php _e( 'Cancel', 'glotpress' ); ?></a></span>
		</p>
	</form>

<?php get_footer();