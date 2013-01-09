=== Hupso Share Buttons for Twitter, Facebook & Google+ ===
Contributors: kasal
Donate link: http://www.hupso.com/
Tags: twitter, facebook, google+, social, sharing, stumbleupon, addthis, sharethis, sexybookmarks, addtoany, lockerz, shareaholic 
Requires at least: 2.8
Tested up to: 3.5
Stable tag: 3.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Help visitors share your posts on the most popular social networks: Twitter, Facebook, Google Plus, Linkedin, StumbleUpon, Digg, Reddit and others.

== Description ==

Add simple social sharing buttons to your articles. Your visitors will be able to easily share your content on the most popular social networks: Twitter, Facebook, Google Plus, Linkedin, StumbleUpon, Digg, Reddit, Bebo and Delicous. 

These services are used by millions of people every day, so sharing your content there will increase traffic to your website.

**Main features / advantages**

* Slick, minimalistic design.
* Very small and fast. The code for sharing button is very small (only a few KB), so share buttons will not slow down your website - even on devices with slow network connections.
* All major social networks are supported: Twitter, Facebook (Facebook Share / Facebook Like / Facebook Send), Google Plus, Linkedin, StumbleUpon, Digg, Reddit, Bebo, Delicious.
* Social media counters: Twitter Tweet, Facebook Like, Google +1, Linkedin Share. 
* Compatible with all major web browsers: Firefox, Chrome, Internet Explorer, Safari, Opera.
* Share toolbar works with desktop and mobile devices (mobile phones and tablets). Tested with PC, Apple iOS / iPhone / iPad and Google Android devices.
* Real-time button preview in admin settings.
* Support for right-to-left (RTL) scripts / languages (Arabic, Persian, Urdu, Hebrew, Yiddish, Syriac, Thaana/Dhivehi, N'Ko, Chinese, Japanese).
* Hide or show buttons for specific posts / pages (see Shortcodes below)
* Hide or show buttons for posts / pages / front page / categories
* Option to add "via @yourprofile" to tweets (Twitter)
* Localized counters: Tweet, Facebook Like, Facebook Share, Google +1 buttons can use translated versions (support for 73 languages)
* Hide share buttons for specific categories
* Hide "Share" image or use translated image (20+ languages available)
* Sidebar widget
* Use of shortcodes inside template files


Share Buttons are very easy to configure. Just select button type, size, position and which social networking services do you want to offer to your visitors.
Buttons will appear below your articles or on top of them as you choose.

**Shortcodes**

* Use [hupso_hide] anywhere in post's text to hide buttons for specific post. 
* Use [hupso] anywhere in post's text to show buttons at custom position inside the post. Buttons will be shown exactly where this shortcode appears.
* Shortcodes inside template files: Add this PHP code inside template files at position where you want to show share buttons: 
echo do_shortcode('[hupso]'); 
You can configure share buttons in plugin settings.
* Shortcodes inside widget text: Just include [hupso] anywhere in widget text area and share buttons will be displayed there.

[Share Buttons Demo](http://www.hupso.com/share/) | [FAQ](http://wordpress.org/extend/plugins/hupso-share-buttons-for-twitter-facebook-google/faq/) | [Feedback](http://www.hupso.com/share/feedback/)


== Installation ==

1. Download plugin file (.zip)
2. Extract zip file and upload folder /hupso-share-buttons-for-twitter-facebook-google/ to /wp-content/plugins/
3. Go to "Plugins" and activate the plugin


== Frequently Asked Questions ==

= How do I change settings? =

From the Wordpress Administration go to "Plugins" and click on "Settings" under plugin name

= How can I hide/show share buttons for specific posts? =

You can hide share buttons for specific post using shortcode [hupso_hide]. Add [hupso_hide] anywhere in your post's text and buttons will be hidden.
You can show share buttons for specific post at custom position using shortcode [hupso]. Add [hupso] in your post's text where you want the buttons to appear.

= Which social networks are supported? =

All major social networks are supported: Twitter, Facebook, Google+, Linkedin, StumbleUpon, Digg, Reddit, Bebo and Delicious.

= Can I show share buttons in sidebar? =

Yes. There is a sidebar widget included with the plugin. Go to WP Administration then click on "Widgets" under "Appearance" menu.
Then drag Hupso Share Buttons Widget from left and drop it on the sidebar on the right.

= How can I show share buttons inside template files? =

Add this PHP code inside template files at position where you want to show share buttons: echo do_shortcode('[hupso]'); 
You can configure share buttons in plugin settings.

= Can I use shortcodes inside widget text? =

Yes, you can. Just include [hupso] anywhere in widget text area and share buttons will be displayed there.

= Buttons are not working properly. What can I do? =

Please upgrade the plugin to the latest version. If that does not help then try to reinstall the plugin (uninstall it and install it again).
If you still have problems then send bug report [using this feedback form](http://www.hupso.com/share/feedback/).

= Buttons are not working with one post. Only "Share" image in shown, but no social icons. They work correctly on other posts. What can I do? =

HTML of your post in not valid. You need to fix the text inside the post. Perhaps you forgot to close a p or div tag at the end. Perhaps you have some other HTML error in it. Use HTML validator if you cannot find an error.

= What settings are available? =

From Settings screen you are able to choose: button type (share button, share toolbar, counters), button size, social sharing services, menu type, button position (above or below your posts), display options.

Please look at *Screenshots* for more information.

= Are share buttons using Javascript? =

Yes. Javascript is required for sharing buttons to function properly and it must be enabled. Menu/toolbar/preview interface for share buttons is loaded at run-time from our servers so we can add minor enhancements and fix browser bugs the moment they are discovered without forcing you to upgrade the plugin all the time. Some button images are loaded from your local Wordpress installation and some from our servers. Counters load javascript code from Twitter, Facebook, Google and Linkedin.

= Are share buttons free? =

Yes. Thay are free and will always be free. And you do not need to open any account to use them.

= When will floating toolbars be available? =

[Hupso Share Buttons](http://www.hupso.com/share/) provide other button types including floating toolbars. We plan to implement those in next versions of this plugin.

= Why is featured post image not used as thumbnail with Facebook on new posts? =

Please wait up to 24 hours for Facebook to fetch the new thumbnail. After that it should work.

= Found a bug? Have a suggestion? =

Please send bug reports and suggestion using [this feedback form](http://www.hupso.com/share/feedback/).


== Screenshots ==

1. Share Toolbar and Counters on the same page (demo)
2. Counters (Twitter Tweet, Facebook Like, Google +1, Linkedin Share) 
3. Share Toolbar (big)
4. Share Buttons with drop down menu (icons and service names)
5. Share Buttons with drop down menu (icons only)
6. Settings in Wordpress Administration (with real-time button preview)
7. Share buttons under post, sidebar widget and text widget - English version (73 languages available)
8. Share buttons under post, sidebar widget and text widget - Spanish version (73 languages available)
9. Share buttons under post, sidebar widget and text widget - Chinese version (73 languages available)

== Changelog ==


= 3.6 =
* Sidebar widget
* Shortcodes can now be used directly from template files (see FAQ)
* Option to show share buttons above and below your posts
* Share buttons are not shown on password protected pages
* Czech "Share" image
* Bugfix: Share image is shown/hidden properly

= 3.5 =
* Option to add "via @yourprofile" to tweets (Twitter)
* Localized counters: Tweet, Facebook Like, Facebook Share, Google +1 buttons can now use translated versions (support for 73 languages)
* Chinese "Share" image
* Fix for Facebook Like in Internet Explorer 8
* Option to add CSS style to share buttons

= 3.4 =
* Option to hide "Share" image
* Option to use translated "Share" image (20 languages)

= 3.3 =
* Option to show/hide share buttons for (all) posts/pages
* Option to hide share buttons for specific categories
* Option to select which title gets used when sharing (title of post or title of current web page)

= 3.2 =
* It is now possible to hide buttons for specific post using shortcode [hupso_hide]
* It is now possible to show buttons for specific post at custom position using shortcode [hupso] (buttons will be displayed exactly where this shortcode appears)
* Text for social networks is now pulled from post's title
* Fixed a bug where share buttons did not load inside posts for some themes

= 3.1 =
* Bugfix for Share button not loading properly
* Settings are now deleted on plugin uninstall

= 3.0 =
* New button type: Counters (support for Facebook Like, Facebook Send, Twitter Tweet, Google +1, Linkedin Share)
* Featured post image (post thumbnail) is now used as Facebook thumbnail when sharing to Facebook
* Fix for empty excerpts

= 2.3 =
* Fixed diplay of excerpts (post summaries)

= 2.2 =
* Fixed option to display share buttons inside categories and tags

= 2.1 =
* Better support for right-to-left (RTL) scripts/languages (Arabic, Persian, Urdu, Hebrew, Yiddish, Syriac, Thaana/Dhivehi, N'Ko, Chinese, Japanese)

= 2.0 =
* New button type: share toolbar
* Real-time button preview in admin settings

= 1.3 =
* Added options to show/hide social buttons on frontpage and inside categories

= 1.2 =
* Improved default settings

= 1.1 =
* Fixed plugin path

= 1.0 =
* Initial release





