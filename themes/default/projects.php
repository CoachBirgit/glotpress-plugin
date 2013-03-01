<?php get_header(); ?>

	<h2>Projects</h2>

	<ul>
	<?php foreach( $projects as $project ): ?>
		<li>
			<a href="<?php echo gp_project_url( $project ); ?>" title="Project: <?php echo esc_attr( $project->name ); ?>"><?php echo esc_html( $project->name );?></a>
			<a href="<?php echo gp_project_edit_url( $project ); ?>" class="action edit bubble"><?php _e( 'Edit', 'glotpress' ); ?></a>
		</li>
	<?php endforeach; ?>
	</ul>

	<?php if ( ! current_user_can( 'gp_project_write' ) ): ?>
		<p class="actionlist secondary"><a href="<?php echo gp_project_new_url(); ?>"><?php _e( 'Create a New Project', 'glotpress' ); ?></a></p>
	<?php endif; ?>

<?php get_footer(); ?>