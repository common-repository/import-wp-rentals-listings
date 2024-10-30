=== Import WP Rentals Listings ===
Contributors: soflyy, wpallimport
Tags: wp rentals, import property listings, import wp rentals properties, import wp rentals owner, import wp rentals
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 4.9.0
Tested up to: 6.6
Stable tag: 1.0.0
Requires PHP: 7.4

Easily import listings from any XML or CSV file to the WP Rentals theme with the WP Rentals Add-On for WP All Import.

== Description ==

The WP Rentals Add-On for [WP All Import](http://wordpress.org/plugins/wp-all-import "WordPress XML & CSV Import") makes it easy to bulk import your listings to the WP Rentals theme in less than 10 minutes.

The left side shows all of the fields that you can import to and the right side displays the listings from your XML/CSV file. Then you can simply drag & drop the data from your XML or CSV into the WP Rentals fields to import it.

The importer is so intuitive it is almost like manually adding a listing in WP Rentals.

We have several other real estate add-ons available, each specific to a different theme.

= Why you should use the WP Rentals Add-On for WP All Import =

* Instead of using the Custom Fields section of WP All Import, you are shown the fields like Property Address, Price, etc. in plain English.

* Automatically find the listing location using either the listing address or the latitude and longitude. For geocoding functionality, we use [Google's Geocoding API](https://developers.google.com/maps/documentation/geocoding/overview). By using this functionality, you agree to the [Google Maps Terms of Use](https://cloud.google.com/maps-platform/terms/) And [Google's Terms of Service](http://www.google.com/intl/en/policies/terms). You can review Google's Privacy Policy [here](http://www.google.com/policies/privacy).

* Complete support for all listing and owner fields enabled by the WP Rentals theme.

* Easily import slider images, files & documents, and property videos.

* Supports files in any format and structure. There are no requirements that the data in your file be organized in a certain way. CSV imports into WP Rentals are easy no matter the structure of your file.

* Supports files of practically unlimited size by automatically splitting them into chunks. WP All Import is limited solely by your server settings.

= WP All Import Professional Edition =

The WP Rentals Add-On for WP All Import is fully compatible with [the free version of WP All Import](http://wordpress.org/plugins/wp-all-import "WordPress XML & CSV Import"). 

However, [the professional edition of WP All Import](http://www.wpallimport.com/order-now/?utm_source=free-plugin&utm_medium=dot-org&utm_campaign=wp-rentals) includes premium support and adds the following features:

* Import files from a URL: Download and import files from external websites, even if they are password protected with HTTP authentication. You need to provide the feed URL. 

* Cron Job/Recurring Imports: WP All Import Pro can check periodically check a file for updates, and add, edit, delete, and update your listings.

* Custom PHP Functions: Pass your data through custom functions by using [my_function({data[1]})] in your import template. WP All Import will pass the value of {data[1]} through my_function and use whatever it returns.

* Access to premium technical support.

[Upgrade to the professional edition of WP All Import now.](http://www.wpallimport.com/order-now/?utm_source=free-plugin&utm_medium=dot-org&utm_campaign=wp-rentals)

= Developers: Create Your Own Add-On =
This Add-On was created using the [Rapid Add-On API](https://www.wpallimport.com/documentation/addon-dev-overview/) for WP All Import. We've made it really easy to write your own Add-On. 

= Related Plugins =
[Import Properties into Real Places Theme](https://wordpress.org/plugins/realplaces-xml-csv-property-listings-import/)  
[Import Properties into RealHomes Theme](https://wordpress.org/plugins/realhomes-xml-csv-property-listings-import/)  
[Import Properties into the Reales WP Theme](https://wordpress.org/plugins/reales-wp-xml-csv-property-listings-import/)  
[Import Property Listings into Realia](https://wordpress.org/plugins/realia-xml-csv-property-listings-import/)  
[Import Property Listings into WP Residence](https://wordpress.org/plugins/wp-residence-add-on-for-wp-all-import/)

== Screenshots ==

1. Select the Listings Post Type to start the import. 
2. Main import screen where users map their CSV/XML data to the Listings fields.
3. Map address and API keys for geocoding.
4. Select the Owners Post Type to start the import. 
5. Main import screen where users map their CSV/XML data to the Owners fields.

== Installation ==

First, install [WP All Import](http://wordpress.org/plugins/wp-all-import "WordPress XML & CSV Import").

Then install the WP Rentals Add-On.

To install the WP Rentals Add-On, either:

* Upload the plugin from the Plugins page in WordPress

* Unzip import-listings-into-wp-rentals.zip and upload the contents to /wp-content/plugins/, and then activate the plugin from the Plugins page in WordPress

The WP Rentals Add-On will appear in the Step 3 of WP All Import.

== Frequently Asked Questions ==

= WP All Import works with any theme, so what’s the point of using the WP Rentals Add-On? =

Aside from making your import easier and simpler, the WP Rentals Add-On will fully support your theme’s slider images and file attachments as well as allow you to easily import location data.

= Can I import location data for my properties? =

The WP Rentals Add-On for WP All Import uses the Google Maps API to import your location data. You have to input your own Google API key to make it work. 

== Changelog ==

= 1.0.0 =
* Initial release on WP.org.

== Support ==

We do not handle support in the WordPress.org community forums.

We do try to handle support for our free version users at the following e-mail address:

E-mail: support@wpallimport.com

Support for free version customers is not guaranteed and based on ability. For premium support, purchase [WP All Import Pro](http://www.wpallimport.com/order-now/?utm_source=free-plugin&utm_medium=dot-org&utm_campaign=wp-rentals).