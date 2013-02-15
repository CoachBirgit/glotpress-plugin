<?php
	get_header();
	$redirect_to = apply_filters( 'login_redirect', ! empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '' );
?>

	<h2>Login</h2>

	<?php do_action( 'gp_before_login_form' ); ?>

	<form action="<?php echo gp_login_url(); ?>" method="post">
		<dl>
			<dt><label for="user_login"><?php _e( 'Username', 'glotpress' ); ?></label></dt>
			<dd><input type="text" value="" id="user_login" name="user_login" /></dd>

			<dt><label for="user_pass"><?php _e( 'Password', 'glotpress' ); ?></label></dt>
			<dd><input type="password" value="" id="user_pass" name="user_pass" /></dd>
		</dl>

		<p><input type="submit" name="submit" value="<?php _e( 'Login', 'glotpress' ); ?>" id="submit"></p>
		<input type="hidden" value="<?php echo esc_attr( $redirect_to ); ?>" id="redirect_to" name="redirect_to" />
	</form>

	<?php do_action( 'gp_after_login_form' ); ?>

	<script type="text/javascript" charset="utf-8">
		document.getElementById('user_login').focus();
	</script>

<?php get_footer();
