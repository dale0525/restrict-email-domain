=== Restrict Email Domain ===
Contributors: logictan
Tags: email, ban, registration
Requires at least: 5.5
Tested up to: 5.8
Stable tag: 1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Prevent people from registering on Wordpress with blacklisted email domains from a remote list.

== Description ==

This plugin will block email domains in a remote list from registering on your Wordpress site. You can use the default one hosted on Github, or provide your own blacklist URL, but make sure the content is in plain text and one line per domain.

= Privacy Policy =

This plugin does not track data outside of what WordPress already collects. It utilizes the submitted email address to validate the domain and compares it to the list of prohibited domains and emails. No additional data is processed.

= Credits =

This plugin is a modification of [Ban Hammer](https://wordpress.org/plugins/ban-hammer/), while utilizing the function of reading blacklist from remote URL, and removed the multisite and buddypress part.

The default blacklist is read from [disposable](https://github.com/disposable/disposable)

==Changelog==

= 1.0.0 =
* Initial release.

== Installation ==

After installation, go to **Tools > Restrict Email Domain** to customize the error message (and remote URL if you prefer your own).

== Frequently Asked Questions ==

= Does this plugin work with Multisite, BuddyPress or Woocommerce? =

No.

= Can I restrict a whole email instead of a domain? =

Sure, but you need to submit your own blacklist URL in the plugin settings page. The plugin simply uses the "stripos" function to check if the blacklist contains the email submitted by the user.

= Is wildcard (*) supported? =

No, but you can modify the plugin with your own risk. Simply uncomment the wildcard part in the restrict_email_domain_drop function and comment the don't use wildcard part.