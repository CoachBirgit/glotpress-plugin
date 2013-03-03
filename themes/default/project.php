<?php
get_header();

$project = gp_project();

$edit_link = gp_project_edit( $project );
$parity = gp_parity_factory();
?>

	<h2><?php echo esc_html( $project->name ); ?> <?php echo $edit_link; ?></h2>
	<p class="description">
		<?php echo $project->description; ?>
	</p>

	<?php if ( ! current_user_can( 'gp_project_edit', $project->id ) ): ?>

	<div class="actionlist">
		<a href="#" class="project-actions" id="project-actions-toggle"><?php _e( 'Project actions &darr;', 'glotpress' ); ?></a>
		<div class="project-actions hide-if-js">
			<ul>

				<li><a href="<?php echo gp_project_url( $project, 'import-originals' ); ?>"><?php _e( 'Import originals', 'glotpress' ); ?></a></li>
				<li><a href="<?php echo gp_project_url( $project, '-permissions' ); ?>"><?php _e( 'Permissions', 'glotpress' ); ?></a></li>
				<li><a href="<?php echo gp_project_new_url( $project->id ); ?>"><?php _e( 'New Sub-Project', 'glotpress' ); ?></a></li>
				<li><a href="/sets/-new"><?php _e( 'New Translation Set', 'glotpress' ); ?></a></li>
				<li><a href="<?php echo gp_project_url( $project, '-mass-create-sets' ); ?>"><?php _e('Mass-create Translation Sets', 'glotpress' ); ?></a></li>
				<li><a class="confirm" href="<?php echo gp_project_url( $project, '-delete' ); ?>"><?php _e( 'Delete Project', 'glotpress' ); ?></a></li>

				<?php if ( $project->translation_sets() ): ?>
				<li>
					<a href="#" class="personal-options" id="personal-options-toggle"><?php _e( 'Personal project options &darr;', 'glotpress' ); ?></a>
					<div class="personal-options">
						<form action="<?php echo gp_project_url( $project, '-personal' ); ?>" method="post">
						<dl>
							<dt><label for="source-url-template"><?php _e( 'Source file URL', 'glotpress' );  ?></label></dt>
							<dd>
								<input type="text" value="<?php echo esc_html( $project->source_url_template() ); ?>" name="source-url-template" id="source-url-template" />
								<small><?php _e('URL to a source file in the project. You can use <code>%file%</code> and <code>%line%</code>. Ex. <code>http://trac.example.org/browser/%file%#L%line%</code>'); ?></small>
							</dd>
						</dl>
						<p>
							<input type="submit" name="submit" value="<?php echo esc_attr( __( 'Save &rarr;', 'glotpress' ) ); ?>" id="save" />
							<a class="ternary" href="#" onclick="jQuery('#personal-options-toggle').click();return false;"><?php _e( 'Cancel', 'glotpress' ); ?></a>
						</p>
						</form>
					</div>
				</li>
			<?php endif; ?>
			</ul>
		</div>
	</div>
	<?php endif; ?>


<?php if ( $project->sub_projects() ): ?>
<div id="sub-projects">
<h3><?php _e( 'Sub-projects', 'glotpress' ); ?></h3>
<dl>
<?php foreach( $project->sub_projects() as $sub_project ): ?>
	<dt>
		<a href="<?php echo gp_project_url( $sub_project ); ?>" title="esc_attr( sprintf( __( 'Project: %s', 'glotpress' ), $sub_project->name ) ); ?>"><?php echo esc_html( $sub_project->name );?></a>
		<?php echo gp_project_edit( $sub_project, __( 'Edit', 'glotpress' ), 'action edit bubble' ); ?>
		<?php
			if ( $sub_project->active )
				echo '<span class="active bubble">' . __( 'Active', 'glotpress' ) . '</span>';
		?>
	</dt>
	<dd>
		<?php echo esc_html( gp_html_excerpt( $sub_project->description, 111 ) ); ?>
	</dd>
<?php endforeach; ?>
</dl>
</div>
<?php endif; ?>

<?php if ( $project->translation_sets() ): ?>
<div id="translation-sets">
	<h3><?php _e( 'Translations', 'glotpress' ); ?></h3>
	<table class="translation-sets">
		<thead>
			<tr>
				<th><?php _e( 'Language', 'glotpress' ); ?></th>
				<th><?php echo _x( '%', 'language translation percent header', 'glotpress' ); ?></th>
				<th><?php _e( 'Translated', 'glotpress' ); ?></th>
				<th><?php _e( 'Untranslated', 'glotpress' ); ?></th>
				<th><?php _e( 'Waiting', 'glotpress' ); ?></th>
				<th><?php _e( 'Extra', 'glotpress' ); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach( $project->translation_sets() as $set ): ?>
			<tr class="<?php echo $parity(); ?>">
				<td>
					<strong><a href="<?php echo gp_translation_set_url( $project, $set ); ?>"><?php echo $set->name_with_locale(); ?></a></strong>
					<?php if ( $set->current_count() && $set->current_count() >= $set->all_count() * 0.9 ):
							$percent = floor( $set->current_count() / $set->all_count() * 100 );
					?>
						<span class="bubble morethan90"><?php echo $percent; ?>%</span>
					<?php endif; ?>
				</td>
				<td class="stats percent"><?php echo $set->percent_translated(); ?></td>

				<td class="stats translated" title="translated">
					<a href="<?php echo gp_translation_set_url( $project, $set, array( 'filters[translated]' => 'yes', 'filters[status]' => 'current') ); ?>">
						<?php echo $set->current_count(); ?>
					</a>
				</td>
				<td class="stats untranslated" title="untranslated">
					<a href="<?php echo gp_translation_set_url( $project, $set, array( 'filters[status]' => 'untranslated' ) ); ?>">
						<?php echo $set->untranslated_count(); ?>
					</a>
				</td>
				<td class="stats waiting">
					<a href="<?php echo gp_translation_set_url( $project, $set, array( 'filters[translated]' => 'yes', 'filters[status]' => 'waiting') ); ?>">
						<?php echo $set->waiting_count(); ?>
					</a>
				</td>
				<td>
					<?php do_action( 'gp_project_template_translation_set_extra', $set, $project ); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php elseif ( ! $project->sub_projects() ): ?>
	<p><?php _e( 'There are no translations of this project.', 'glotpress' ); ?></p>
<?php endif; ?>

<div class="clear"></div>


<script type="text/javascript" charset="utf-8">
	$gp.showhide('a.personal-options', 'div.personal-options', {
		show_text: '<?php _e( 'Personal project options', 'glotpress' ); ?> &darr;',
		hide_text: '<?php _e( 'Personal project options', 'glotpress' ); ?> &uarr;',
		focus: '#source-url-template',
		group: 'personal'
	});
	jQuery('div.personal-options').hide();
	$gp.showhide('a.project-actions', 'div.project-actions', {
		show_text: '<?php _e( 'Project actions', 'glotpress' ); ?> &darr;',
		hide_text: '<?php _e( 'Project actions', 'glotpress' ); ?> &uarr;',
		focus: '#source-url-template',
		group: 'project'
	});
</script>

<?php get_footer();