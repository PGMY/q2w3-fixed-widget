=== Q2W3 Fixed Widget (Sticky Widget) ===
Contributors: Max Bond
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Q36H2MHNVVP7U
Tags: sidebar, widget, scroll, fixed, floating, sticky, russian, q2w3
Requires at least: 3.0
Tested up to: 3.5.1
Stable tag: 2.3

Fixes positioning of the selected widgets, when the page is scrolled down. 

== Description ==

Enable "Fixed widget" option in widget settings (see [screenshot](http://wordpress.org/extend/plugins/q2w3-fixed-widget/screenshots/)) and it will be always in sight when page is scrolled down.

There is no problem to "Fix" (or "Stick") more than one widget in a single sidebar. 

New in version 2.2. Now the plugin is able to reflect dynamic page content changes (infinite scroll, ajax basket and other javascript stuff)!!!

New in version 2.1. Added option to disable plugin on mobile devices. 
Mobile detection is based on [Mobile-Detect project](https://github.com/serbanghita/Mobile-Detect/).

[Watch the demo](http://store.places-finder.com/cp-ajax-post-load).
Right sidebar, last three widgets. Scroll down to the bottom.

Supported languages: English, Russian 

Note. The plugin is doing its best in "single sidebar -> multiple widgets" environment. You may try to fix widgets in different sidebars, but in most cases the result will be 	
inadequate.

Note two. The plugin is not working with all themes. Theme requirements:

* Widgets must have unique IDs (see FAQ).
* No JavaScript errors
* jQuery 1.7 or later

In some cases theme CSS changes required.

== Installation ==

1. Follow standard WordPress plugin installation procedure
2. Activate the plugin through the Plugins menu in WordPress
3. Go to Appearance -> Widgets, enable "Fixed Widget" option on any active widget ([screenshot](http://wordpress.org/extend/plugins/q2w3-fixed-widget/screenshots/)) 
4. Fine tune fixed widget margins on Appearance -> Fixed Widget Options page

== Frequently Asked Questions ==

= Why plugin is not working? =

There are several reasons:

1. Widgets have no unique IDs. How to check. Place two text widgets in your sidebar. Then look at html source of your site. If these two widgets have the same IDs (widget_text) or they have no IDs at all - that's the problem. How to fix. Find `register_sidebar()` function (look first at functions.php file). Parameter `before_widget` should be like this: `<li id="%1$s" class="widget-container %2$s">`. Attention to this part: `id="%1$s"`.    
2. Javascript errors on page. Commonly caused by buggy plugins. Check javascript console of your browser. If you find errors, try to locate and fix its source. 
3. No `wp_head()` and `wp_footer()` functions in template. Check header.php and footer.php files of your active theme.

= How to prevent overlapping with the footer? =

Make sure you have updated plugin to version 2.x. Go to WP admin area, Appearance -> Fixed Widget Options. Here you can define top and bottom margins. Set bottom margin value >= footer height. Check the result.

= What does Refresh Interval option? =

This option defines (in milliseconds, 1 sec = 1000 ms) how often plugin recalculates sticky widgets parameters. Required by sites with dynamic content (infinite scroll, image lazy load and other javascript stuff). The option have impact on the site performance (client side). Recommended values: 250 - 1500 milliseconds. If you don't have dynamic content, set Refresh interval = 0. 

== Screenshots ==

1. Widget with enabled "Fixed widget" option
2. Fixed Widget Options
3. Margin top
4. Margin bottom

== Other Notes ==

* [Code Insert Manager](http://wordpress.org/extend/plugins/q2w3-inc-manager/)
* [Q2W3 Post Order](http://wordpress.org/extend/plugins/q2w3-post-order/)

== Changelog ==

= 2.3 =
* Now user can disable plugin, when browser window width is less then specified value (check plugin options). 

= 2.2.4 =
* This version is jQuery 1.9 compatible

= 2.2.3 =
* Little internal improvments
* Mobile Detect updated to version 2.6.0

= 2.2.2 =
* Fixed PHP [Error](http://wordpress.org/support/topic/breakes-with-php-53)
* Mobile Detect updated to version 2.5.8

= 2.2.1 =
* Fixed PHP [Warning](http://wordpress.org/support/topic/error-with-the-new-update-22)

= 2.2 =
* Now the plugin is able to reflect dynamic page content changes (infinite scroll, ajax basket and other javascript stuff)!!!
* Added new option to plugin settings: Refresh interval. Recommended values between 250 - 1500 milliseconds. Note: setting have impact on the site performance (client side). If you don't have dynamic content, set Refresh interval = 0. 
* Mobile Detect class updated to version 2.5.7

= 2.1 =
* New option to define custom widget IDs for static sidebars and etc.
* New option to disable plugin on mobile devices.
* Fixed javascript error when no sidebars exists on a page.

= 2.0 =
* Fixed footer overlapping problem! Now users can customize top and bottom margins for the fixed widgets from the admin area (Appearance -> Fixed Widget Options).
* Added localization support

= 1.0.3 =
* Normalized plugin behavior when sidebar is longer then main content. Note: possible overlapping with footer is still exists.

= 1.0.2 =
* Fixed problem with widgets displayed only on certain pages.
* Optimized javascript code.

= 1.0.1 =
* Improved compatibility with Webkit based browsers (like Chrome and Safari).
* Removed unnecessary CSS.


= 1.0 =
* First public release.
