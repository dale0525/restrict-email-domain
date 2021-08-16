<?php
/*
Plugin Name: Restrict Email Domain
Plugin URI: https://github.com/dale0525/restrict-email-domain
Description: Prevent people from registering on Wordpress with blacklisted email domains from a remote list.
Version: 1.0.0
Author: Logic Tan
Author URI: https://www.logiconsole.com
Network: true
Text Domain: restrict-email-domain

Copyright 2021 Logiconsole (email: a@logiconsole.com)

	This file is part of Restrict Email Domain, a plugin for WordPress.

	Restrict Email Domain is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 2 of the License, or
	(at your option) any later version.

	Restrict Email Domain is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with WordPress. If not, see <http://www.gnu.org/licenses/>.

*/

class RestrictEmailDomain {

	// Holds option data.
	public $option_name = 'restrict_email_domain_options';
	public $option_defaults;
	public $options;

	// Instance
	public static $instance;

	/**
	 * Construct
	 *
	 * @since 1.0
	 * @access public
	 */
	public function __construct() {

		//allow this instance to be called from outside the class
		self::$instance = $this;

		add_action( 'init', array( &$this, 'init' ) );
		add_action( 'admin_init', array( &$this, 'admin_init' ) );

		//add admin panel
        add_action( 'admin_menu', array( &$this, 'admin_menu' ) );

		// Setting plugin defaults here:
		$this->option_defaults = array(
			'message' => __( '<strong>ERROR</strong>: Sorry, but the email you used is not allowed.', 'restrict-email-domain' ),
            'urllist' => 'https://raw.githubusercontent.com/disposable/disposable-email-domains/master/domains.txt'
		);

		// Fetch and set up options.
		$this->options = wp_parse_args( get_site_option( $this->option_name ), $this->option_defaults, false );
	}

	/**
	 * Init
	 *
	 * @since 1.0
	 * @access public
	 */
	public function init() {
		// The magic sauce
		add_action( 'register_post', array( &$this, 'restrict_email_domain_drop' ), 1, 3 );
	}

	/**
	 * Admin init Callback
	 *
	 * @since 1.0
	 */
	public function admin_init() {

		// Settings links
		$plugin = plugin_basename( __FILE__ );
		add_filter( 'plugin_action_links_$plugin', array( &$this, 'settings_link' ) );

		// Register Settings
		$this->register_settings();
	}

	/**
	 * Admin Notices
	 *
	 * Display admin notices as needed.
	 *
	 * @since 1.0
	 */
	public function admin_notices() {
		printf( '<div class="notice notice-%1$s"><p>%2$s</p></div>', esc_attr( $this->notice_class ), wp_kses_post( $this->notice_message ) );
	}

    /**
	 * Upgrade options
	 *
	 * @since 1.0
	 */
	public function upgrade() {
		update_site_option( $this->option_name, $this->options );
	}

	/**
	 * Admin Menu
	 *
	 * @since 1.0
	 * @access public
	 */
	public function admin_menu() {
		add_management_page( __( 'Restrict Email Domain', 'restrict-email-domain' ), __( 'Restrict Email Domain', 'restrict-email-domain' ), 'moderate_comments', 'restrict-email-domain', array( &$this, 'options' ) );
	}

	/**
	 * Register Admin Settings
	 *
	 * @since 1.0
	 */
	public function register_settings() {
		register_setting( 'restrict-email-domain', 'restrict_email_domain_options', array( &$this, 'restrict_email_domain_sanitize' ) );

		// The main section
		add_settings_section( 'restrict-email-domain-settings', '', array( &$this, 'restrict_email_domain_settings_callback' ), 'restrict-email-domain-settings' );

		// The Fields
		add_settings_field( 'message', __( 'Blocked Notice', 'restrict-email-domain' ), array( &$this, 'message_callback' ), 'restrict-email-domain-settings', 'restrict-email-domain-settings' );
        add_settings_field( 'listurl', __( 'The Blacklist URL', 'restrict-email-domain' ), array( &$this, 'listurl_callback' ), 'restrict-email-domain-settings', 'restrict-email-domain-settings' );
	}

	/**
	 * Restrict Email Domain Settings Callback
	 *
	 * @since 1.0
	 */
	public function restrict_email_domain_settings_callback() {
		?>
		<p><?php esc_html_e( 'Customize your Restrict Email Domain experience via the settings below.', 'restrict-email-domain' ); ?></p>
		<?php
	}

	/**
	 * Message Callback
	 *
	 * @since 1.0
	 */
	public function message_callback() {
		?>
		<p><?php esc_html_e( 'The message below is displayed to users who\'s email domain is blocked. Remember to keep it simple.', 'restrict-email-domain' ); ?></p>
		<p><textarea name="restrict_email_domain_options[message]" id="restrict_email_domain_options[message]" cols="80" rows="2"><?php echo esc_html( $this->options['message'] ); ?></textarea></p>
		<?php
	}

	/**
	 * List URL Callback
	 *
	 * @since 1.0
	 */
	public function listurl_callback() {
		?>
		<p><?php esc_html_e( 'The URL containing the domain blacklist. You can provide your own URL, but make sure the content is in plain text and one line per domain.', 'restrict-email-domain' ); ?></p>
		<p><input type="url" name="restrict_email_domain_options[urllist]" id="restrict_email_domain_options[urllist]" size="100" value="<?php echo esc_html( $this->options['urllist'] ); ?>" /></p>
		<?php
	}

	/**
	 * Options
	 *
	 * The options page.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function options() {
		?>
		<div class="wrap">

		<h1><?php esc_html_e( 'Restrict Email Domain', 'restrict-email-domain' ); ?></h1>

		<?php
		settings_errors();
        ?>
			<form action="options.php" method="POST" >

			<?php
                settings_fields( 'restrict-email-domain' );
                do_settings_sections( 'restrict-email-domain-settings' );
                submit_button( '', 'primary', 'update' );
            ?>

			</form>
		</div>
		<?php
	}

	/**
	 * Options sanitization and validation
	 *
	 * @param $input the input to be sanitized
	 * @since 1.0
	 */
	public function restrict_email_domain_sanitize( $input ) {
		// Get current options
		$options = $this->options;

		// Message
		if ( $input['message'] !== $options['message'] && wp_kses_post( $input['message'] === $input['message'] ) ) {
			$input['message'] = wp_kses_post( $input['message'] );
		} else {
			$input['message'] = $options['message'];
		}

		return $input;
	}

	/**
	 * The basic plugin
	 *
	 * @since 1.0
	 * @access public
	 */
	public function restrict_email_domain_drop( $user_login, $user_email, $errors ) {
		$bannedlist_string = file_get_contents($this->options['urllist']);
		$bannedlist_array  = explode( "\n", $bannedlist_string );
		$bannedlist_size   = count( $bannedlist_array );

		// Go through bannedlist
		for ( $i = 0; $i < $bannedlist_size; $i++ ) {
			$bannedlist_current = trim( $bannedlist_array[ $i ] );

            #region don't use wildcard
			if ( stripos( $user_email, $bannedlist_current ) !== false ) {

				$errors->add( 'invalid_email', $this->options['message'] );
                return true;
			}
            #endregion

            #region support wildcard
            // if (function_exists('fnmatch'))
            // {
            //     if ( fnmatch( $bannedlist_current, $user_email )) {

            //         $errors->add( 'invalid_email', $this->options['message'] );
            //         return true;
            //     }
            // }
            #endregion
		}
	}

	/**
	 * Settings link
	 *
	 * Adds link to settings page on the plugins page
	 *
	 * @access public
	 */
	public function settings_link( $links ) {
        $settings_link = admin_url( 'tools.php?page=restrict-email-domain' );

		$settings_link = '<a href="' . $settings_link . '">' . __( 'Settings', 'restrict-email-domain' ) . '</a>';
		if ( user_can( get_current_user_id(), 'moderate_comments' ) ) {
			array_unshift( $links, $settings_link );
		}

		return $links;
	}

}

new RestrictEmailDomain();
