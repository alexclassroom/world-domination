<?php
/**
 * Shared Functions
 *
 * A group of functions shared across my plugins, for consistency.
 *
 * @package world-domination
 */

/**
 * Add meta to plugin details
 *
 * Add options to plugin meta line
 *
 * @version  1.1
 * @param    string $links  Current links.
 * @param    string $file   File in use.
 * @return   string         Links, now with settings added.
 */
function wod_plugin_meta( $links, $file ) {

	if ( false !== strpos( $file, 'world-domination.php' ) ) {

		$links = array_merge(
			$links,
			array( '<a href="https://github.com/dartiss/world-domination">' . __( 'Github', 'world-domination' ) . '</a>' ),
			array( '<a href="https://wordpress.org/support/plugin/world-domination">' . __( 'Support', 'world-domination' ) . '</a>' ),
			array( '<a href="https://artiss.blog/donate">' . __( 'Donate', 'world-domination' ) . '</a>' ),
			array( '<a href="https://wordpress.org/support/plugin/world-domination/reviews/?filter=5" title="' . __( 'Rate the plugin on WordPress.org', 'world-domination' ) . '" style="color: #ffb900">' . str_repeat( '<span class="dashicons dashicons-star-filled" style="font-size: 16px; width:16px; height: 16px"></span>', 5 ) . '</a>' ),
		);
	}

	return $links;
}

add_filter( 'plugin_row_meta', 'wod_plugin_meta', 10, 2 );

/**
 * Modify actions links.
 *
 * Add or remove links for the actions listed against this plugin
 *
 * @version  1.1
 * @param    string $actions      Current actions.
 * @param    string $plugin_file  The plugin.
 * @return   string               Actions, now with deactivation removed!
 */
function wod_action_links( $actions, $plugin_file ) {

	// Make sure we only perform actions for this specific plugin!
	if ( strpos( $plugin_file, 'world-domination.php' ) !== false ) {

		// Add link to the settings page.
		if ( current_user_can( 'manage_options' ) ) {
			array_unshift( $actions, '<a href="options-general.php">' . __( 'Settings', 'world-domination' ) . '</a>' );
		}
	}

	return $actions;
}

add_filter( 'plugin_action_links', 'wod_action_links', 10, 2 );

/**
 * WordPress Fork Check
 *
 * Deactivate the plugin if an unsupported fork of WordPress is detected.
 *
 * @version 1.0
 */
function wod_fork_check() {

	// Check for a fork.

	if ( function_exists( 'calmpress_version' ) || function_exists( 'classicpress_version' ) ) {

		// Grab the plugin details.

		$plugins = get_plugins();
		$name    = $plugins[ WORLD_DOMINATION_PLUGIN_BASE ]['Name'];

		// Deactivate this plugin.

		deactivate_plugins( WORLD_DOMINATION_PLUGIN_BASE );

		// Set up a message and output it via wp_die.

		/* translators: 1: The plugin name. */
		$message = '<p><b>' . sprintf( __( '%1$s has been deactivated', 'world-domination' ), $name ) . '</b></p><p>' . __( 'Reason:', 'world-domination' ) . '</p>';
		/* translators: 1: The plugin name. */
		$message .= '<ul><li>' . __( 'A fork of WordPress was detected.', 'world-domination' ) . '</li></ul><p>' . sprintf( __( 'The author of %1$s will not provide any support until the above are resolved.', 'world-domination' ), $name ) . '</p>';

		$allowed = array(
			'p'  => array(),
			'b'  => array(),
			'ul' => array(),
			'li' => array(),
		);

		wp_die( wp_kses( $message, $allowed ), '', array( 'back_link' => true ) );
	}
}

add_action( 'admin_init', 'wod_fork_check' );
