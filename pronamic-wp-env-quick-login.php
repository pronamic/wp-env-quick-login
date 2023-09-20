<?php
/**
 * Pronamic `wp-env` Quick Login
 *
 * @package           PluginPackage
 * @author            Pronamic
 * @copyright         2023 Pronamic
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Pronamic `wp-env` Quick Login
 * Plugin URI:        https://wp.pronamic.directory/plugins/pronamic-wp-env-quick-login/
 * Description:       Quickly log in as a specified WordPress user in your `wp-env` environment.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Pronamic
 * Author URI:        https://www.pronamic.eu/
 * Text Domain:       pronamic-wp-env-quick-login
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        https://wp.pronamic.directory/plugins/pronamic-wp-env-quick-login/
 */

add_action( 'init', function() {
	if ( ! array_key_exists( 'pronamic_auto_login', $_REQUEST ) ) {
		return;
	}

	$user_id = sanitize_text_field( wp_unslash( $_REQUEST['pronamic_auto_login'] ) );

	$user = get_user_by( 'id', $user_id );

	if ( false === $user ) {
		return;
	}

	wp_clear_auth_cookie();

	wp_set_current_user( $user->ID );

	wp_set_auth_cookie( $user->ID, false, is_ssl() );

	do_action( 'wp_login', $user->user_login, $user );

	$redirect_to = user_admin_url();

	wp_safe_redirect( $redirect_to );

	exit();
} );

add_action( 'login_footer', function() {
	?>
	<style>
		#pronamic-one-click-login {
			width: 320px;
			margin: auto;
			padding-bottom: 24px;
		}
	</style>

	<div id="pronamic-one-click-login">
		<form method="post" action="">
			<div>
				<label for="pronamic-login-user">One-click login</label>

				<?php

				wp_dropdown_users(
					[
						'name' => 'pronamic_auto_login',
						'id'   => 'pronamic-login-user',
					]
				);

				?>
			</div>

			<button class="button button-primary button-large" type="submit">Log In</button>
		</form>
	</div>
	<?php
} );
