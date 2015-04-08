=== Internal Link Building ===
Contributors: im-ninjas
Donate link: http://tools.seochat.com/tools/interlinking-plugin/
Tags: SEO, link building, google rankings, search engine optimization, rankings, affiliate, nofollow, links
Requires at least: 3.0.1
Tested up to: 3.8
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin will let you easily link to your old articles, pages or other sites to improve their rankings in search engines and generate more clicks.

== Description ==

This interlinking plugin for Wordpress will let you easily and meaningfully link to your old articles, pages or other sites to improve their rankings in search engines and generate more clicks.

The Internal Link Building plugin lets you assign keywords to given destination URLs. This way your website will link within itself like it's done in Wikipedia – every time a keyword occurs, it links to the page you specified in the plugin dashboard.

You can:
* set the words to be case sensitive (to only interlink your brand name for example), 
* make links nofollow (makes sense when you link to affiliate products), 
* upload keywords in bulk, etc



Note: Please do use the plugin in moderation to avoid over-optimization. Meaningful and user-friendly inter-linking goes a long way!

This plugin is supported by [Internet Marketing Ninjas](http://www.internetmarketingninjas.com/).


== Installation ==

Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page.

== Frequently Asked Questions ==

= Will this plugin overwrite links in older posts using keywords I set to link to particular pages? =

Where a post already links out using given keywords, the plugin will NOT overwrite it.

= Can we make this link out in a post only a set number of times? =

Yes, this is possible.

= Can we make a set of URLs to link to, and have it link at random? =

Yes, this is possible. You just need to separate the URLs with | symbols in the URL field.

= How is this plugin different from other similar plugins? =

They do not have the post-level override functionality. They also don’t have mass upload through a CSV copy-paste functionality. A third difference is that multiple keywords can link to a given URL, or one keyword can link to multiple URLs (see next questions for more).

= Where is the post-level override? =

It’s in a box below the main content area in /wp-admin/post.php (or post-new.php). I.e. When you write/edit individual posts, scroll down and you’ll see it.

= How do I link multiple keywords to URL, or one keyword to multiple URLs? =

Type the different keywords or URLs out with a pipe symbol ( | ) in between them. It’s found just above the enter key on most qwerty keyboards; you press shift+| to make it appear (without shift you’ll get a backslash: \ ) .

== Screenshots ==

1. Admin Panel

== Changelog ==

= 1.0.0 =
* First version of the plugin
= 1.1 =
* better plugin description text
* renamed menu from Keywords into Internal linking

== Arbitrary section ==

The settings page is under the Posts menu -> Keywords in 2.7+. It’s under Manage -> Keywords, in older versions of WP (up to 2.5 or 2.6) .

1. This plugin allows you to mass-upload keywords. So you just need to prepare a CSV file in Excel and then copy-paste it into the mass-upload box in your admin panel and then click upload! This is great for larger sites targeting multiple keywords that want to speed things up.

2. It’s up to you whether you make cAsE ReLeVaNT to your links. Check the “Exact Match” checkbox for it to only match when the case is the same as you typed the word into the admin panel. Leave it unchecked to have all cases link to your selected page.

3. Post-level overrides of globally-assigned keywords. 

4. Link to sources or affiliate links whom you reference a lot. 

5. Build internal links automatically to pages you regularly reference. 

6. Make a link nofollow. For example, if you link to particular Wikipedia pages a lot then set the nofollow to be automatic.

7. Link multiple keywords to a single URL. Target all a page’s keywords at once! Just put a space, a  pipe (eg |) and a space between the multiple keywords. E.g. affiliate | affiliate marketing. More efficiency! More time savings!

8. Pick the number of times a keyword will link to a particular URL. If you only want the words social media to link to your social media category once, you can do that. Notice that social gets linked next, since I also have that keyword assigned to link to the same page.

== Upgrade Notice ==

This plugin has only one version so far.
