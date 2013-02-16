<?php
	get_header();

	$user_login = isset( $_POST['user_login'] ) ? esc_attr( stripslashes( $_POST['user_login'] ) ) : '';
	$user_email = isset( $_POST['user_email'] ) ? esc_attr( stripslashes( $_POST['user_email'] ) ) : '';
?>

	<h2><?php _e( 'Register', 'glotpress' ); ?></h2>
	<form method="post">
		<table class="form-table">
			<tr>
				<th><label for="user_login"><?php _e( 'Username:', 'glotpress' ); ?></label></th>
				<td><input type="text" id="user_login" name="user_login" value="<?php echo $user_login; ?>"/></td>
			</tr>
			<tr>
				<th><label for="user_email"><?php _e( 'E-mail:', 'glotpress' ); ?></label></th>
				<td><input type="text" id="user_email" name="user_email" value="<?php echo $user_email; ?>"/></td>
			</tr>
		</table>
		<p id="reg_passmail"><?php _e( 'A password will be e-mailed to you.' ) ?></p>
		<br>
		<input type="submit" name="submit" value="<?php esc_attr_e( 'Register', 'glotpress' ); ?>">
	</form>

<?php get_footer(); ?>