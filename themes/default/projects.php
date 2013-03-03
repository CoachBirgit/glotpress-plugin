<?php get_header(); ?>

	<h2>Projects</h2>

	<ul>
	<?php foreach( $projects as $project ): ?>
		<li>
			<a href="<?php echo gp_project_url( $project ); ?>" title="<?php echo esc_attr( sprintf( __( 'Project: %s', 'glotpress' ), $project->name ) ); ?>"><?php echo esc_html( $project->name );?></a>
			<?php echo gp_project_edit( $project, __( 'Edit', 'glotpress' ), 'action edit bubble' ); ?>
		</li>
	<?php endforeach; ?>
	</ul>

	<?php if ( ! current_user_can( 'gp_project_write' ) ): ?>
		<p class="actionlist secondary"><a href="<?php echo gp_project_new_url(); ?>"><?php _e( 'Create a New Project', 'glotpress' ); ?></a></p>
	<?php endif; ?>

<?php get_footer(); ?>