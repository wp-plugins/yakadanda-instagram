=== Yakadanda Instagram ===
Contributors: Yakadanda.com
Donate link: http://www.yakadanda.com/
Tags: images, pictures, photos, instagram, yakadanda
Requires at least: 3.8
Tested up to: 3.9.1
Stable tag: 0.1.4
License: GPLv2 or later

A Wordpress plugin that pulls in Instagram images of user or hashtags.

== Description ==

A Wordpress plugin that pulls in Instagram images of user or hashtags.

= Features =
* Display your images or display other images in Instagram based on tags
* Set up speed, frame rate, height, and directions (up and down) to adjust the way how Instagram images scrolling
* Social network links
* Responsive scrolling images
* Works in WordPressMU
* Yakadanda Instagram widget

== Installation ==

Installation as usual.

1. Unzip and Upload all files to a sub directory in "/wp-content/plugins/".
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Enter client id and client secret in YInstagram settings, follow the instructions for creating your own client for Instagram.
4. Add [yinstagram] shortcode in post/page or add yinstagram widget to sidebar.

== Frequently Asked Questions ==

= Do I need an account on Instagram? =
You only need an Instagram account to embed feeds but not to embed a single Instagram.

= Why are the images not showing? =
Not connected or possibility you have entered tags that does not exist in Instagram.

= Shortcode Reference =

**Shortcode Examples**

* `[yinstagram]`
* `[yinstagram display_images="liked"]`
* `[yinstagram username="motogp"]`
* `[yinstagram hashtags="#supercar, #hypercar"]`

**Attributes**

No attribute will retrieves Instagram images based on plugin settings, and if have attribute will override plugin settings.
1. display_images	=	Get the authenticated user's "feed", list of media they've "liked", or get the most "recent" media published by a user.
2. username	=	Get the most recent images published by a username, e.g. "motogp"
3. hashtags	=	Get a list of recently tagged media, e.g. "#supercar, #hypercar"

== Screenshots ==

1. Scrolling images using shortcode
2. Images widget
3. Profile widget

== Changelog ==

= 0.1.4 =
* Added attributes feature to the shortcode
* Added ability to use multiple widget and shortcode in same page

= 0.1.3 =
* Added ThickBox
* Upgraded ColorBox
* Added image scale on modal dialog
* Filter the scripts and the styles if plugin features is enable or disable

= 0.1.2 =
* Improved logout feature and reset feature
* Updated enqueue script function to pass scripting guard from Codestyling Localization plugin
* Added order/sort feature
* Upgraded ColorBox

= 0.1.1 =
* Updated display options page
* Improved social links

= 0.1.0 =
* Added reset feature to plugin settings
* Added infinite scroll type to shortcode
* Moved colorbox options from widget form to display options page
* Added colorbox feature to infinite scroll
* Provided settings link on plugins page
* Updated manual page

= 0.0.90 =
* Improved fetch function which get instagram images for shortcode and widget 
* Changed menu icon
* Removed security exploit
* Change the way of plugin to connect with instagram API

= 0.0.80 =
* Expanded recent Instagram images feature

= 0.0.70 =
* Updated settings page and display options page
* Added number of images options for shortcode to settings page
* Added profile widget
* Seperated widget settings and shortcode settings
* Updated colorbox

= 0.0.60 =
* Updated colorbox
* Fixed widget http://wordpress.org/support/topic/bug-a-couple-more-bugs-with-widgetphp

= 0.0.51 =
* Updated deprecated function, http://wordpress.org/support/topic/bug-scriptjs-using-deprecatedremoved-jquery-function

= 0.0.50 =
* Changed the way instagram images loaded on shorcode and widget
* Improved authorization and authentication to Instagram API
* Moved manual/guidance to help tab
* Updated colorbox

= 0.0.40 =
* Upgraded Colorbox
* Fixed bugs

= 0.0.31 =
* Supported PHP 5.2.* version

= 0.0.30 =
* Added autochecked feature on settings page
* Added widget
* Added image size settings for shortcode on display options

= 0.0.20 =
* Optimized yistagram backend
* Changed the way to display and scroll instagram images
* Changed yinstagram preferences/configuration in display options page
* Added new options, display instagram images by user feed and by user liked

= 0.0.12 =
* Added social links option on display options page
* Added shortcode information on settings page

= 0.0.11 =
* Updated to fit with WordPress 3.5 and Twenty Twelve theme

== Upgrade Notice ==

= 0.1.4 =
* Fixed resize bug on profile widget
* Fixed links to help tab

= 0.1.3 =
* Improved performance

= 0.1.2 =
* Several bug fixes

= 0.1.1 =
* Fixed social links on infinite type

= 0.1.0 =
* -

= 0.0.90 =
* Fixed bugs

= 0.0.80 =
* -

= 0.0.70 =
* Removed failed response on display options page

= 0.0.60 =
* Cleared up bugs http://wordpress.org/support/topic/bug-a-couple-more-bugs-with-widgetphp

= 0.0.51 =
* Updated deprecated code on script.js

= 0.0.50 =
* Removed bugs on display options page, and posted data functions
* Cleared up notices and warnings on frontend and backend

= 0.0.40 =
* Fixed hastag error message
* Fixed bugs on autochecked feature in settings page
* Filtered the load of colorbox options
* Cleared up Undefined index notices on frontend and backend

= 0.0.31 =
* Fixed Parse error: syntax error, unexpected T_FUNCTION

= 0.0.30 =
* Fixed flexible issue on shortcode

= 0.0.20 =
* Fixed Warning: call_user_func_array()
* Improved save yinstagram preferences 
* Improved connection to instagram API
* Updated styles on manual page
* Removed header color and background color

= 0.0.12 =
* Fixed header issues

= 0.0.11 =
* -
