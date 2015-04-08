=== Plugin Name ===
Contributors: Rustaurius, EtoileWebDesign
Tags: frequently asked questions, FAQ, easy FAQ, knowledge base, simple FAQ, FAQ categories, FAQs, knowledgebase, answer, answers, faq page, FAQ Plugin, help, help desk, helpdesk, questions, wordpress faq, faq list, custom post type with accordion, faq list, faq with accordion, jquery ui accordion, jquery-ui, shortcodes, wordpress, WordPress Plugin, Categories, social media
Requires at least: 3.5.0
Tested up to: 4.1
License: GPLv3
License URI:http://www.gnu.org/licenses/gpl-3.0.html

A plugin that lets you create FAQs (frequently asked questions), organize them, publicize them, etc. and insert them using shortcodes

== Description == 

Create, organize and publicize your Frequently Asked Questions in no time through your Wordpress Admin Panel with this easy to use plugin.

= Key Features =

* Create FAQ categories
* Create FAQ posts and assign to categories to them
* An AJAX search form
* Export all FAQs to a PDF to create a user manual
* Insert custom CSS to style your FAQ posts
* Select animation options for displaying FAQ posts
* Toggle accordion (close open FAQ when a new one is opened) behaviour on/off
* Share on social media 

= Shortcodes =

* [ultimate-faqs]: display all FAQs, or only specific categories using include_category and exclude_category attributes (both take a comma-separated list of category slugs)
* [select-faq]: display specific FAQ posts, using the attributes faq_name, faq_slug and faq_id which take comma-separated lists of post names, slugs and ids respectively.
* [ultimate-faq-search]: display a search form that allows users to find FAQs with a specific string in the title or body of the post.

Check out our Frequently Asked Questions here:
<https://wordpress.org/plugins/ultimate-faqs/faq/>

Please head to the "Support" forum to report issues or make suggestions:
<https://wordpress.org/support/plugin/ultimate-faqs>



--------------------------------------------------------------




== Frequently Asked Questions ==

= How do I get my FAQs to show up on my page? =

Try adding the shortcode [ultimate-faqs] to whatever page you'd like to display it on. 

= What are the current FAQ shortcodes? =

* [ultimate-faqs]: display all FAQs, or only specific categories using "include_category" and "exclude_category" attributes (both take a comma-separated list of category slugs) 
* [select-faq]: display specific FAQ posts, using the attributes “faq_name”, “faq_slug” and “faq_id” which take comma-separated lists of post names, slugs and ids respectively.
* [ultimate-faq-search]: display a search form that allows users to find FAQs with a specific string in the title or body of the post.

= What attributes does the [ultimate-faqs] shortcode accept? =

The shortcode accepts two attributes, “include_category? and “exclude_category”, both take a comma-separated list of category slugs.

= Can I hide my FAQ categories? =

Yes, you can choose to display or hide categories on the FAQ settings page.

= Is it possible to re-order my FAQS? =

Currently you can choose between ascending or descending ordering by either Title, Date Created, or Date Modified. 

= How can I make my FAQS sharable over social media? =

On the FAQ settings page you can choose to link to twitter, facebook and more!

= How do I make my FAQ?s searchable? =

You can use the shortcode, [ultimate-faq-search], which displays an AJAX search form.

= How do I customize my FAQs, for example, to change the font? =

You can customize the plugin by adding code to the Custom CSS box on the settings page, go to the "Custom CSS" box. For example to change the font you might want to add something like:

.ufaq-faq-title h4, .ufaq-faq-category-title h4  {font-family:"Palatino Linotype", "Book Antiqua", Palatino, serif;}

For more questions and support you can post in the support forum:
<https://wordpress.org/support/plugin/ultimate-faqs>

1. Admin area
2. Sample FAQ page

== Changelog ==
= 0.8 =
- Fixed a small messaging error

= 0.7 =
- Fixed an unclosed div tag
- 'Share' text is hidden when no social media selected
- Fixed a display message error

= 0.6 =
- Fixed a bug with the AJAX search feature

= 0.5 =
- Added a whole bunch of new features
- Most important new feature is a shortcode, ultimate-faq-search, that displays an AJAX search form

= 0.4 =
- Added the option to "Hide Categories" from FAQ posts

= 0.3 =
- Added the ability to export all FAQs to a PDF document (to create a user manual, for instance)

= 0.2 =
- Added a number of grouping and ordering options to the "Options" page

= 0.1 =
- Initial beta version. Please make comments/suggestions in the "Support" forum.

== Upgrade Notice ==

