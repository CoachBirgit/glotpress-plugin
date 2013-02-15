<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title><?php echo wp_title(''); ?></title>
		<?php wp_head(); ?>
	</head>
	<body class="no-js">
	<script type="text/javascript">document.body.className = document.body.className.replace('no-js','js');</script>
		<div id="gp-js-message"></div>

		<h1>
			<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<img alt="<?php esc_attr( __( 'GlotPress logo', 'glotpress' ) ); ?>" src="<?php echo get_template_directory_uri(); ?>/images/glotpress-logo.png" />
			</a>
			<?php echo gp_breadcrumb(); ?>
			<span id="hello">
			<?php
			if ( is_user_logged_in() ):
				$user = wp_get_current_user();

				printf( __( 'Hi, %s.', 'glotpress' ), '<a href="' . esc_url( home_url( '/profile/' ) ) . '">' . $user->user_login . '</a>' );
				?>
				<a href="<?php echo wp_logout_url( home_url() ); ?>"><?php _e( 'Log out', 'glotpress' ); ?></a>
			<?php else: ?>
				<strong><a href="<?php echo wp_login_url( home_url() ); ?>"><?php _e( 'Log in', 'glotpress' ); ?></a></strong>
			<?php endif; ?>
			<?php do_action( 'gp_after_hello' ); ?>
			</span>
			<div class="clearfix"></div>
		</h1>

		<div class="clear after-h1"></div>
		<?php if ( gp_notice('error') ): ?>
			<div class="error">
				<?php echo gp_notice( 'error' ); ?>
			</div>
		<?php endif; ?>
		<?php if ( gp_notice() ): ?>
			<div class="notice">
				<?php echo gp_notice(); ?>
			</div>
		<?php endif; ?>
		<?php do_action( 'gp_after_notices' ); ?>

