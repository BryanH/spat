=== Subpage as Tabs Shortcode ===
Contributors: HBJitney LLC
Tags: navigation, pages, tabs, embedded content
Requires at least: 3.3.2
Tested up to: 3.4.2
Stable tag: trunk

Add the shortcode [subpages_tabs] to any parent page and all child pages will be rendered in that spot with a tabbed navigation layout.

== Description ==

Intended users: editors, authors, contributers

This plugin will allow you to display the content of subpages in the parent page in the form of tabs.

Simply add [spat] or [subpages_tabs] to any page and the subpages' content will be displayed at that location. See the screenshots.


== Installation ==
If you have a single file (ending in ".zip"), then use the *Upload* method. If you have multiple files, use the *Files* method. If you're installing from wordpress directly, just hit the big 'Install Plugin' button.
= Upload =
1. From the plugins, add new screen, choose upload
1. Navigate to where the .zip file is located and select it
1. Make sure to *activate* the plugin once it is installed

= Files =
1. Upload the entire directory (not just the files) to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Q. Does this work on posts? =

**A.** No, only on pages

= Q. I added the shortcode and nothing happened! =

**A1.** Make sure you spelled it correctly

**A2.** Make sure you added the shortcode to a **page**, not a **post**.

**A3.** Make sure your page has sub-pages.

**A4.** Verify that your parent page and all child pages are published (not draft or pending)

== Screenshots ==
1. Shortcode in parent post

2. Public view

3. Option screen

== Changelog ==

0.99 Beefed up child page retrieval code so now all child pages should be shown

0.97 Ensure the child content's shortcodes are processed

0.95 Added plural/singular tag to avoid confusion and easy-to-make mistakes

0.93 Made our Stylesheet/Javascript only show up on pages that have our shortcode, for speed.

0.90 Hard-coded stylesheet

0.70 Initial

== Upgrade Notice ==

= 0.99 =
Added robust child page retrieval code; corrected class name typo

==Readme Generator==

This Readme file was generated using <a href = 'http://sudarmuthu.com/wordpress/wp-readme'>wp-readme</a>, which generates readme files for WordPress Plugins.
