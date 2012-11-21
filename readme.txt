=== Q2W3 Fixed Widget (Sticky Widget) ===
Contributors: Max Bond
Donate link: http://www.q2w3.ru/q2w3-fixed-widget-wordpress-plugin/#donate
Tags: q2w3, widget, fixed, scroll, fixed scroll, floating, floating widget, fixed widget, sticky, sticky widget, sidebar
Requires at least: 3.0
Tested up to: 3.4.2
Stable tag: 1.0

Fixes positioning of the selected widgets, when the page is scrolled down. 

== Description ==

This is a very lightweight plugin with greate functionality! )

Enable "Fixed widget" option on ANY active widget (see [screenshot](http://wordpress.org/extend/plugins/q2w3-fixed-widget/screenshots/)) and it will be always in sight when page is scrolled down.

There is no problem to "Fix" (or "Stick") more than one widget, but remember about browser window height! Too many widgets can overlap with footer or always be out of sight.

Watch the demo: [places-finder.com](http://www.places-finder.com/)
Right sidebar, last widget (Google Ads). Scroll down to the bottom (may need to resize browser window to make scroll longer).

== Installation ==

1. Follow standard WordPress plugin installation procedure
2. Activate the plugin through the Plugins menu in WordPress
3. Go to Appearence -> Widgets, enable "Fixed Widget" option on any active widget ([screenshot](http://wordpress.org/extend/plugins/q2w3-fixed-widget/screenshots/)) 

== Frequently Asked Questions ==

= Why plugin is not working? =

There are several reasons:

1. No wp_head() and wp_footer() functions in template. Check header.php and footer.php of your current template.
2. Widgets have no unique IDs. 
How to check. Place two text widgets in your sidebar. Then look at html source of your site. If these two widgets will have the same IDs (widget_text) - that's the root of the problem.
How to fix. Find `register_sidebar()` function (functions.php). Parameter `before_widget` should be like this: `<li id="%1$s" class="widget-container %2$s">`. Note this part `id="%1$s"`.    
3. jQuery errors on page.


== Screenshots ==

1. Widget with enabled "Fixed widget" option

== Other Notes ==


* [Code Insert Manager](http://wordpress.org/extend/plugins/q2w3-inc-manager/)
* [Q2W3 Post Order](http://wordpress.org/extend/plugins/q2w3-post-order/)

== Changelog ==

= 1.0 =
* First public release.
