=== FreshMail For Wordpress ===
Donate link: http://freshmail.com
Contributors: FreshMail.com, borbismedia
Tags: email marketing, newsletter, subscription, email, email form, subscribers, forms, signup, form, checkbox, pop-up, ecommerce, popup, plugin, form builder, mailing list, signup-forms, widget, optin form, signup form
Requires at least: 3.0
Tested up to: 4.2.2
Stable tag: 2.1.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily add sign-up forms and sign-up checkboxes to your WordPress website. Get reports on the sources of registration.


== Description ==

= Features of the plugin include: =


* Define popup layouts by choosing predefined styles or creating your own style based on dozens of options! Live previews of the final project as it’s being created.
* Include custom-made sign-up forms in popups and define when it will be displayed.
* Enabling automatic sign-up to a subscription with the use of a checkbox added to other standard WordPress forms like: adding comments, user registration as well as forms of other plugins: WooCommerce, Easy Digital Download, bbPress, MultiSite, BuddyPress.
* Adding a checkbox to any other form with a few lines of code.
* Get reports on the sources of users’ registration in a selected period and check which form or checkbox generates the most new subscribers.
* Plugin integration with Google Analytics.

If you're not already a [FreshMail.com](http://freshmail.com) user you can [create a free account](https://app.freshmail.com/en/).

[Go to the plugin page](http://freshmail.com/plugin/wordpress-newsletter/)

= FreshMail - create, send and track email marketing campaigns that work =

[youtube https://www.youtube.com/watch?v=wWpD2hVdaGc]

FreshMail is a web app for creating, sending and tracking email marketing campaigns. The system provides users in an intuitive tool for newsletter creation - FreshMail Designer - based on drag&drop method and rich in beautiful ready-to-use [email newsletter templates](http://freshmail.com/newsletter-templates/). Many tests available in the system help to optimize campaigns and the report section allows to analyse their results. FreshMail provides customers with an excellent customer service.

Find out more: [FreshMail website ](http://freshmail.com) | [FreshMail on Twitter ](https://twitter.com/FreshMail_APP)

== Installation ==

= Installing the plugin =
This plugin requires at last: **WordPress v 3.0** and **PHP v 5.3**.

The installation works in the conventional way.

1. In your WordPress admin panel, go to **Plugins > Add new**, search for **FreshMail for Wordpress** and click **Install**.
2. Alternatively, download the plugin and upload the contents of **FreshMail_for_Wordpress.zip** to your plugins directory, which usually is **/wp-content/plugins/**.
3. Another way is to download the plugin, log into WordPress and go to **Plugins > Add new > Upload plugin**. Find the downloaded zip file and then click **Install**.
4. When installed, click on **Activate Plugin**.


= Connecting Plugin to the FreshMail account =

Go to **FreshMail tab > Connect with FreshMail** and enter the [API key and API secret](https://app.freshmail.com/en/settings/integration/) in order to enable WordPress integration with FreshMail.

* [Where do you find the API information](http://freshmail.com/help-and-knowledge/help/account-settings/what-is-an-api-key-and-where-can-you-find-it/)?
* If you're not already a [FreshMail.com](http://freshmail.com) user you can [create a free account](https://app.freshmail.com/en/).

After entering the correct data move to the **Add New Form** tab and select your list. Here the list of custom fields that you will be able to use in FreshMail will appear.

In **Properties**, **Layout** and **Messages** you will find additional possibilities of sign-up form configuration.


= Adding Sign-Up Form =

If you want to add the plugin to a template you should enter the **Appearance > Widgets** tab on the admin panel. In available widgets you will find the **FreshMail** option that is responsible for the sign-up form. For proper operation of the plugin just drag a widget into the selected spot on your template and select from the list previously configured sign-up form.

The plugin could be also inserted with the use of shortcode into any post, page or text widget. For example:

`
[FM_form id=”1”]
`

You can also display a sign-up form from your theme files by using one line of PHP code:

`
<?php echo do_shortcode( '[FM_form id="1"]' ); ?>
`


= Configuring Pop-Up  =

To display popup you have to check **Show the sign-up form in a popup** by the form configuration. The advanced settings of popups can be found in **All Forms > Edit Form > Popup Properties tab**.

You can define the criteria of popup display:

* after a defined process of page scrolling
* after a defined time spent on the website
* before an attempt to close the browser

and block the popup:

* for smartphones
* for defined UTM tags
* after reaching the maximum number of displays per user
* at a defined frequency

You can also set up forwarding to any page after sign-up to newsletter.

= Configuring Sign-Up Checkboxes =

You also have the option of enabling automatic sign-up to a subscription with the use of a checkbox added to other standard WordPress forms like:

* adding comments
* user registration

as well as forms of other plugins:

* WooCommerce (checkout)
* Easy Digital Download (checkout)
* bbPress
* MultiSite
* BuddyPress (registration)

How to Configuring Checkboxes?

1. Go to **FreshMail for Wordpress > Checkboxes**.
2. Select at least one of your FreshMail lists to subscribe to.
3. Select the forms you want to add a sign-up checkbox to, eg your comment form.

There is also the possibility to add a checkbox to any other form with the use of a few lines of code.
`
<p class="comment-form-fm-sign">
<label for="fm-sign">
<input id="fm-sign" name="fm-sign" type="checkbox" /> Label txt
</label>
<input type="hidden" name="fm-sign-referer" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
</p>
`
== Frequently Asked Questions ==

= How to display a form in post or page? =

Use the **[FM_form id=”1”]** shortcode.


= How to integrate the plugin with Google Analitycs? =

You just need to add a below code into a source of website and configured it precisely to follow the activities in Google Analytics.

`
<script type="text/javascript">
var forms = document.querySelectorAll('
form.form_subscribe');
for( var i = 0; i < forms.length; i++ ) {
forms[i].onsubmit = function() {
// ga.js
_gaq.push(['_trackEvent', 'Forms', 'Submit', 'Sign-up form submission']);
// analytics.js
ga('send', 'event', 'button', 'click', 'Sign-up form submission');
}
}
</script>
`

= How to display a form in widget areas like a sidebar? =

Use the **FreshMail** Widget that comes with the plugin.

= Where can I find my FreshMail API keys? =

[You can find your FreshMail API Keys here](http://freshmail.com/help-and-knowledge/help/account-settings/what-is-an-api-key-and-where-can-you-find-it/)?

== Screenshots ==

1. WISYWIG sign-up form edior
2. You can display the sign-up form as a popup and define the criteria of its display
3. Connecting to Freshmail account
4. Sign-up form in editor
5. You can customize all messages and change language
6. Gain valuable reports on the sources of visitors’ subscribe in a selected period and check which form or checkbox generates the most new subscribers.
7. Creating sign-up forms is easy.
8. You have the option of enabling automatic sign-up to a subscription with the use of a checkbox added to other standard WordPress forms as well as forms of other plugins: WooCommerce, Easy Digital Download, bbPress, MultiSite, BuddyPress.

== Changelog ==

- 2.1.5 - 2015-09-22
    * Fix: Add possibility to disable SSL verification.

- 2.1.4 - 2015-09-01
    * Fix: Now ApiKey and ApiSecret are checking only once per day or if they were changed.

- 2.1.3 - 2015-07-24
    * Add: Option to display popout on every single page, post, product.
    * Fix: Checkboxes in freshmail form now are avaliable to uncheck.
    * Fix: Redirection works properly in shortcode form.

- 2.1.2 - 2015-07-20
    * Add: Google url.

- 2.1.1 - 2015-06-29
    * Fix: alert about php version

- 2.1 - 2015-06-23
    * Add: Margins in fields
    * Add: Remove all options and database tables during uninstall
    * Fix: Remove old options from 1.x version
    * Fix: Removed textarea from contact form 7 checkbox

- 2.0.2 - 2015-06-22
    * Fix: Info about required PHP version (5.3 or higher)

- 2.0.1 - 2015-06-17
    * Fix: Displaying shortcode in custom place of content
    * Fix: Freshmail version compare
    * Fix: Database converting to freshmail 2.x standard

- 2.0 - 2015-06-15
    * NEW: redeveloped stable version
    * Fix: validation secret and API keys
    * Fix: checkboxes and integrations with: registration, comments, multisite form, WooCommerce, Easy Dig, bbPress, buddyPress, Contact Form 7

- 1.6 - 2015-05-06
    * Fix: major security update

- 1.5.8 - 2015-04-24
    * Fix: compatible with WordPress 4.2

- 1.5.7 - 2015-04-23
    * Fix: fixed duplicating form
    * Fix: plugins conflict while including REST API classes

- 1.5.6 - 2015-04-14
    * Fix: fixed issue with popup

- 1.5.5 - 2015-03-18
    * Fix: updated rest api class

- 1.5.4 - 2015-02-26
    * Fix: issue with already subscribed e-mail

* 1.5.3 - 2015-02-16
    * Fix: info about authorization/connection errors

* 1.5.2 - 2015-02-11
    * Fix: removed short php tags

* 1.5.1 - 2015-02-03
    * Fix: removed error with unknown variable

* 1.5 - 2015-02-02
    * Add: Pop-up on selected pages

* 1.4.3 - 2015-01-27
    * Fix: counting displayed pop-ups for non logged users

* 1.4.2 - 2015-01-19
    * Fix: trimming connection data

* 1.4.1 - 2015-01-15
    * Fix: Displaying authorization errors

* 1.4 - 2015-01-12
    * Add: New default themes
    * Fix: Displaying fields without labels and placeholders

* 1.3 - 2015-01-09
    * Add: placeholder in fields
    * Fix: database tables set to UTF8 encoding

* 1.2.10 - 2014-12-22
    * Fix: adding first form
    * Fix: save themes

* 1.2.9 - 2014-12-19
    * Fix: save messages

* 1.2.8 - 2014-12-03
    * Fix: doubled save custom theme
    * Fix: numbers of custom theme

* 1.2.7 - 2014-12-02
    * Fix: display names default themes
    * Fix: Save custom themes

* 1.2.6 - 2014-12-02
    * Update: Default themes

* 1.2.5 - 2014-11-26
    * Fix: Wp color picker,
    * Fix: Custom theme repair

* 1.0 2014-11-19
    * Initial realise