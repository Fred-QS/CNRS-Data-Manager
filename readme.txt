=== CNRS Data Manager ===
Contributors: CNRS, Epoc, QS Conseils
Tags: data manager
Requires at least: 6.2
Tested up to: 6.2
Stable tag: 1.0.0
Requires PHP: 8.1
License: GPLv2 or later
This program is distributed for the sole use by the CNRS IS.

== Description ==

> The CNRS Data Manager extension was developed as part of the redesign of the UMR CNRS sites, in order to be able to use the data transmitted via the CNRS SI, use categories filters, import projects and use configurable 3D map.

It provides to Wordpress:

* Agents
* Teams
* Services
* Platforms

This extension can operate outside of these permit constraints.

For complete details visit [Github Repository](https://github.com/Fred-QS/CNRS-Data-Manager).

= Please Note =
Please note that this extension only works in the UMR EPOC and CNRS ecosystem.

== Screenshots ==

1. Main Interface on admin side
2. Providing data by category
3. Interactive map settings
4. Render agents on Frontend
5. Render map on Frontend
6. Import projects

== Frequently Asked Questions ==

= Does CNRS Data Manager have a knowledge base or FAQ? =
Yes. Please see [Documentation](https://github.com/Fred-QS/CNRS-Data-Manager/documentation) on Github.

= Installation Instructions =
1. Upload `cnrs-data-manager` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Click on the My JRU link from the main menu

The CNRS Data Manager requires php 8.1 or higher.

= Is this plugin compatible with WordPress multisite (MU)? =
CNRS Data Manager isn't compatible with MU.

= Where can I get more help and support for this plugin? =
Go to the [Documentation](https://github.com/Fred-QS/CNRS-Data-Manager/documentation)


== Changelog ==

> 1.0.0

 - Implemented URL checking for SOAP API
 - Creation of all shortcodes and their insertion logic in WordPress
 - Export of customizable assets for displaying shortcode renderings
 - Logic for assigning XML entities received from the API to entities created in WordPress
 - Addition in ./libs of the dependencies necessary for importing projects via ZIP file
 - Checking the integrity of the imported file
 - Logic for Installing/Uninstalling the plugin
 - Implementation of silent paging
 - Category.php page template exportable to the active theme

== Upgrade Notice ==
