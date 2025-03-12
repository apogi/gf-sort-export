=== Sort Export for Gravity Forms ===
Contributors: apogi, doekenorg
Plugin URI: https://apogi.dev/
Donate link: https://www.paypal.me/doekenorg
Tags: gravity forms, export, sort, order, drag-n-drop
Requires at least: 4.0
Requires PHP: 7.1
Tested up to: 6.6
Stable tag: 1.1.2
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Control (and persist) the order of the fields during the export of entries.

== Description ==

This plugin provides the ability to reorder the fields during an export in Gravity Forms.

After selecting the fields you wish to export, the plugin will group these fields, after which you can drag & drop them in the desired order. Then click "Download Export File". The fields in the CSV output will now be in that order.

The provided sort order is also persisted for every form. So you don't have to resort it for every export.

To limit visual clutter, this plugin also removes all disabled subfields from the export list.

## More Apogi Add-ons

- **[MailPoet for Gravity Forms](https://apogi.dev/mailpoet-for-gravity-forms):** Adds a MailPoet subscription field to the form editor to seamlessly integrate MailPoet with Gravity Forms. Subscribe, unsubscribe, connect custom fields, bulk subscribe through bulk actions and have subscriber insights inside your entry details. This add-on does it all.

== Screenshots ==
1. Simply drag & drop the fields in the desired order.

== Changelog ==

= 1.1.2 - 2024-09-20
* Fixed: Allow script and styles on Gravity Forms No Conflict mode.

= 1.1.1 - 2024-08-22 =
* Changed: Replaced deprecated DomEvents with MutationObserver.

= 1.1.0 - 2021-09-29 =
* Feature: Store the export sort order for every form.

= 1.0.0 - 2021-08-18
* Feature: Drag & sort the export fields.
* Feature: Disables all hidden (inactive) subfields from the export list.
