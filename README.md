## Restrict Email Domain
Prevent people from registering on Wordpress with blacklisted email domains from a remote list.

## Description

I was looking for a way/plugin to easily disable spam&temp email domains when new users register on my Wordpress site. I don't want to customize my own blacklist. Instead, I prefer a simple way - find a blacklist on Github, and just use it. Fortunately, I found [Ban Hammer](https://wordpress.org/plugins/ban-hammer/) - a referrence Wordpress plugin and [disposable](https://github.com/disposable/disposable) - the very list I need.

This plugin will block email domains in the above disposable list from registering on your Wordpress site. You can also provide your own blacklist URL, but make sure the content is in plain text and one line per domain.

## Privacy Policy

This plugin does not track data outside of what WordPress already collects. It utilizes the submitted email address to validate the domain and compares it to the list of prohibited domains and emails. No additional data is processed.

## Credits

This plugin is a modification of [Ban Hammer](https://wordpress.org/plugins/ban-hammer/), while utilizing the function of reading blacklist from remote URL, and removed the multisite and buddypress part.

The default blacklist is read from [disposable](https://github.com/disposable/disposable)

## Changelog

### 1.0
- Initial release.

## Installation
Copy the whole directory into your wordpress's wp-content/plugins folder

## Screenshots

1. Default Error message

![](https://github.com/dale0525/image_host/raw/master/restrict-email-domain-screenshot-1.png)
2. Admin screen

![](https://github.com/dale0525/image_host/raw/master/restrict-email-domain-screenshot-2.png)

## Frequently Asked Questions
### Does this plugin work with Multisite, BuddyPress or Woocommerce?
> No, this plugin is mainly for my own use so I will not pay any effort on further development (unless I myself need). You can refer to the Ban Hammer plugin to realize your own need.

### Can I restrict a whole email instead of a domain?
> Sure, but you need to submit your own blacklist URL in the plugin settings page. The plugin simply uses the "stripos" function to check if the blacklist contains the email submitted by the user.

### Is wildcard (*) supported?
> No, but you can modify the plugin with your own risk. Simply uncomment the wildcard part in the restrict_email_domain_drop function and comment the don't use wildcard part.