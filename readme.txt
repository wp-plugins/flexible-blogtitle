=== Flexible Blogtitle ===
Contributors: thaikolja, evader
Tags: flexible blogtitle, flexible, blogtitle, blog title, site title, custom blog title, custom site title
Tested up to: 3.8.1
Stable tag: 0.1
Requires at least: 3.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Flexible Blogtitle is a lightweight and user friendly WordPress plugin that allows you to define a custom blog title for certain posts and pages.

== Description ==

**Flexible Blogtitle** is a lightweight and user friendly WordPress plugin that allows you to define a custom blog title for certain posts and pages.

It comes with an intuitive settings page that lets you define *rules* under which a certain blog title should be displayed, including:

* Post ID
* Page ID
* Post/page title
* Category
* Taxonomy
* Term

Each object can be assigned with one of the available *operators*:

* is <Value>
* is not <Value>
* contains <Value>

Finally, you can set the new blog title that should be displayed if a rule matches the page the viewer is currently viewing.

Feel free to make Flexible Blogtitle easier to use for foreign users by [help translating Flexible Blogtitle on Transifex](https://www.transifex.com/projects/p/plugin-flexible-blogtitle/).

**Please note that this is an early version, there still may be bugs. If you encounter any problems or misbehavours while using Flexible Blogtitle, please take a minute to report it via mail to kolja.nolte@gmail.com so that the fix can be implemented in the next version.**

== Installation ==

**For a more detailled documentation, please see www.koljanolte.com/wordpress/plugins/flexible-blogtitle/**

= Install =

1. Install the plugin either through WordPress' automatic plugin installer found under *Plugins > Add New* or download and extract the folder *flexible-blogtitle* from the flexible_blogtitle.zip to the */wp-content/plugins/* folder of your WordPress installation.
2. Activate Flexible Blogtitle through *Plugins > Installed Plugins*.
3. Go to *Settings > Flexible Blogtitle* and add your rules.

= Add a new rule =
To add a new rule, go to the settings page under *Settings > Flexible Blogtitle* and fill out the four forms right of *New rule*.

The *object* defines the condition that is to be checked for the current rule. The *operator* lets you set how the condition is to be handled. In the text field next to it enter the value the object needs to be in order to display the custom site title. Finally, enter your new title in the field after *change blogtitle to* and press *Add rule*. The page should reload and your rule listed in the table below.

= Delete a rule =
Deleting a rule can be easily done by going to *Settings > Flexible Blogtitle* and clicking *Delete* in the row of the rule you want to delete.

= Delete multiple rules =
To delete multiple rules, select the desired rules by checking the box on the in the beginning of the each row. To select all items, check the box in the top of the table. Chose "Delete selected items" below the table and hit *Apply*.

= Change the settings =
Flexible Blogtitle allows you to customize its behaviour on the settings page under *Settings > Flexible* Blogtitle.

If activated, *Automatically show in page title* replaces WordPress' native site title with the new custom title. Note that the title will only be shown if the page you are viewing matches the set rules. If not, the native title will be used.

*Replace blogname with the current title* replaces the name of the blog usually shown in the header of the site with the custom title defined in the specific rule.

== Frequently Asked Questions ==

= I've set a rule but the blog title doesn't change =
Check if the post, page or area you are viewing matches the set rules. Don't forget that you also have to either activate the *Automatically show in page title* function on the settings page or insert `<?php the_flexible_blogtitle(); ?>` within the `<title>...</title>` attribute inside the header.php of your active theme.

== Screenshots ==

1. The plugin's settings page lets you define custom rules.

== Changelog ==

= 0.1 =
Initial release.

== Upgrade Notice ==

= 0.1 =
This is the first release of Flexible Blogtitle.