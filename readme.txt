=== Yakadanda Instagram ===
Contributors: Yakadanda.com
Donate link: http://www.yakadanda.com/
Tags: images, pictures, photos, instagram, yakadanda
Requires at least: 3.8
Tested up to: 4.0
Stable tag: 0.1.8
License: GPLv2 or later

A Wordpress plugin that pulls in Instagram images of user or hashtags.

== Description ==

A Wordpress plugin that pulls in Instagram images of user or hashtags.

= Features =
* Display your images or display other images in Instagram based on username, tags, feed, or liked
* Set up speed, frame rate, height, and directions (up and down) to adjust the way how Instagram images scrolling
* Responsive scrolling images
* Tooltip, lightboxes, and social network links feature
* Works in WordPressMU
* Instagram widget

== Installation ==

Installation as usual.

1. Unzip and Upload all files to a sub directory in "/wp-content/plugins/".
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Enter client id and client secret in YInstagram settings, follow the instructions for creating your own client for Instagram.
4. Add [yinstagram] shortcode in post/page or add yinstagram widget to sidebar.

== Frequently Asked Questions ==

= Do I need an account on Instagram? =
You only need an Instagram account to embed feeds but not to embed a single Instagram.

= How do I change default style to my preferences? =
Use yakadanda-instagram.css in yakadanda-instagram/css/yakadanda-instagram.css as reference. Copy that file to your active-theme/css/ as yakadanda-instagram.css

= Why are the images not showing? =
Not connected or possibility you have entered tags that does not exist in Instagram.

= Shortcode Reference =

**Examples**

* `[yinstagram]`
* `[yinstagram display_images="liked"]`
* `[yinstagram username="motogp"]`
* `[yinstagram hashtags="#supercar, #hypercar"]`

**Attributes**

No attribute will retrieves Instagram images based on plugin settings, and if have attribute will override plugin settings.

1. display_images	=	Get the authenticated user's `feed`, list of media they've `liked`, or get the most `recent` media published by a user.
2. username	=	Get the most recent images published by a username, e.g. `motogp`
3. hashtags	=	Get a list of recently tagged media, e.g. `#supercar`, `#hypercar`

== Screenshots ==

1. Scrolling images using shortcode
2. Images widget
3. Profile widget

== Changelog ==

= 0.1.8 =
* Add qtip2 feature
* Improve menu icon
* Update Colorbox

= 0.1.7 =
* Change request the access_token method
* Improve equeue stylesheets and javascripts

= 0.1.6 =
* Update setup documentation
* Add load more button to infinite scroll
* Add Instagram badge to profile widget
* Upgrade ColorBox

= 0.1.5 =
* Improve resize feature

= 0.1.4 =
* Add attributes feature to the shortcode
* Add ability to use multiple widget and shortcode in same page

= 0.1.3 =
* Add ThickBox
* Upgrade ColorBox
* Add image scale on modal dialog
* Filter the scripts and the styles if plugin features is enable or disable

= 0.1.2 =
* Improve logout feature and reset feature
* Update enqueue script function to pass scripting guard from Codestyling Localization plugin
* Add order/sort feature
* Upgrade ColorBox

= 0.1.1 =
* Update display options page
* Improve social links

= 0.1.0 =
* Add reset feature to plugin settings
* Add infinite scroll type to shortcode
* Move colorbox options from widget form to display options page
* Add colorbox feature to infinite scroll
* Provide settings link on plugins page
* Update manual page

= 0.0.90 =
* Improve fetch function which get instagram images for shortcode and widget 
* Change menu icon
* Remove security exploit
* Change the way of plugin to connect with instagram API

= 0.0.80 =
* Expand recent Instagram images feature

= 0.0.70 =
* Update settings page and display options page
* Add number of images options for shortcode to settings page
* Add profile widget
* Separate widget settings and shortcode settings
* Update colorbox

= 0.0.60 =
* Update colorbox
* Fix widget http://wordpress.org/support/topic/bug-a-couple-more-bugs-with-widgetphp

= 0.0.51 =
* Update deprecated function, http://wordpress.org/support/topic/bug-scriptjs-using-deprecatedremoved-jquery-function

= 0.0.50 =
* Change the way instagram images loaded on shorcode and widget
* Improve authorization and authentication to Instagram API
* Move manual/guidance to help tab
* Update colorbox

= 0.0.40 =
* Upgrade Colorbox
* Fix bugs

= 0.0.31 =
* Support PHP 5.2.* version

= 0.0.30 =
* Add autochecked feature on settings page
* Add widget
* Add image size settings for shortcode on display options

= 0.0.20 =
* Optimize yistagram backend
* Change the way to display and scroll instagram images
* Change yinstagram preferences/configuration in display options page
* Add new options, display instagram images by user feed and by user liked

= 0.0.12 =
* Add social links option on display options page
* Add shortcode information on settings page

= 0.0.11 =
* Update to fit with WordPress 3.5 and Twenty Twelve theme

== Upgrade Notice ==

= 0.1.8 =
* -

= 0.1.7 =
* Fix auth on widget

= 0.1.6 =
* Add failed message on failure connection

= 0.1.5 =
* Fix lightbox feature on widget

= 0.1.4 =
* Fix resize bug on profile widget
* Fix links to help tab

= 0.1.3 =
* Improve performance

= 0.1.2 =
* Several bug fixes

= 0.1.1 =
* Fix social links on infinite type

= 0.1.0 =
* -

= 0.0.90 =
* Fix bugs

= 0.0.80 =
* -

= 0.0.70 =
* Remove failed response on display options page

= 0.0.60 =
* Clear up bugs http://wordpress.org/support/topic/bug-a-couple-more-bugs-with-widgetphp

= 0.0.51 =
* Update deprecated code on script.js

= 0.0.50 =
* Remove bugs on display options page, and posted data functions
* Clear up notices and warnings on frontend and backend

= 0.0.40 =
* Fix hastag error message
* Fix bugs on autochecked feature in settings page
* Filter the load of colorbox options
* Clear up Undefined index notices on frontend and backend

= 0.0.31 =
* Fix Parse error: syntax error, unexpected T_FUNCTION

= 0.0.30 =
* Fix flexible issue on shortcode

= 0.0.20 =
* Fix Warning: call_user_func_array()
* Improve save yinstagram preferences 
* Improve connection to instagram API
* Update styles on manual page
* Remove header color and background color

= 0.0.12 =
* Fix header issues

= 0.0.11 =
* -
