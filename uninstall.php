<?php
/*
 * Copyright 2021 Logiconsole (email: a@logiconsole.com)
 *
 * This file is part of Restrict Email Domain, a plugin for WordPress.
 */

// This is the uninstall script.

if ( ! defined( 'ABSPATH' ) && ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_site_option( 'restrict_email_domain_options' );
