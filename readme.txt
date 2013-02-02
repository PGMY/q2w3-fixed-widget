=== Q2W3 Fixed Widget (Sticky Widget) ===
Contributors: Max Bond
Donate link: http://www.q2w3.ru/q2w3-fixed-widget-wordpress-plugin/#donate
Tags: sidebar, widget, scroll, fixed, floating, sticky, russian, q2w3
Requires at least: 3.0
Tested up to: 3.5.1
Stable tag: 2.1

Fixes positioning of the selected widgets, when the page is scrolled down. 

== Description ==

Enable "Fixed widget" option on ANY active widget (see [screenshot](http://wordpress.org/extend/plugins/q2w3-fixed-widget/screenshots/)) and it will be always in sight when page is scrolled down.

There is no problem to "Fix" (or "Stick") more than one widget.

From version 2.0 you can customize top and bottom margins (Appearance -> Fixed Widget Options). 

Form version 2.1 you can define custom widget IDs and disable plugin on mobile devices. 

[Watch the demo](http://store.places-finder.com/cp-ajax-post-load).
Right sidebar, last three widgets. Scroll down to the bottom.

Supported languages: English, Russian

Mobile detection is based on [Mobile-Detect project](https://github.com/serbanghita/Mobile-Detect/). 

== Installation ==

1. Follow standard WordPress plugin installation procedure
2. Activate the plugin through the Plugins menu in WordPress
3. Go to Appearance -> Widgets, enable "Fixed Widget" option on any active widget ([screenshot](http://wordpress.org/extend/plugins/q2w3-fixed-widget/screenshots/)) 
4. Fine tune fixed widget margins on Appearance -> Fixed Widget Options page

== Frequently Asked Questions ==

= How to prevent overlaping with the footer? =

Make sure you have updated plugin to version 2.x. Go to WP admin area, Appearance -> Fixed Widget Options. Here you can define top and bottom margins. Set bottom margin value >= footer height. Check the result.

= Why plugin is not working? =

There are several reasons:

1. Widgets have no unique IDs. How to check. Place two text widgets in your sidebar. Then look at html source of your site. If these two widgets have the same IDs (widget_text) or they have no IDs at all - that's the problem. How to fix. Find `register_sidebar()` function (look first at functions.php file). Parameter `before_widget` should be like this: `<li id="%1$s" class="widget-container %2$s">`. Attention to this part: `id="%1$s"`.    
2. Javascript errors on page. Commonly caused by buggy plugins. Check javascript console of your browser. If you find errors, try to locate and fix its source. 
3. No `wp_head()` and `wp_footer()` functions in template. Check header.php and footer.php files of your active theme.


== Screenshots ==

1. Widget with enabled "Fixed widget" option
2. Fixed Widget Options
3. Margin top
4. Margin bottom

== Other Notes ==

* [Code Insert Manager](http://wordpress.org/extend/plugins/q2w3-inc-manager/)
* [Q2W3 Post Order](http://wordpress.org/extend/plugins/q2w3-post-order/)

== Changelog ==

= 2.1 =
* New option to define custom widget IDs for static sidebars and etc.
* New option to disable plugin on mobile devices.
* Fixed javascript error when no sidebars exists on a page.

= 2.0 =
* Fixed footer overlaping problem! Now users can customize top and bottom margins for the fixed widgets from the admin area (Appearance -> Fixed Widget Options).
* Added localization support

= 1.0.3 =
* Normalized plugin behavior when sidebar is longer then main content. Note: possible overlaping with footer is still exists.

= 1.0.2 =
* Fixed problem with widgets displayed only on certain pages.
* Optimized javascript code.

= 1.0.1 =
* Improved compatibility with Webkit based browsers (like Chrome and Safari).
* Removed unnecessary CSS.


= 1.0 =
* First public release.
