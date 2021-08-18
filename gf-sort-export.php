<?php
/**
 * Plugin Name:     Gravity Forms Sort Export
 * Description:     Control the order of the fields during export
 * Author:          Apogi
 * Author URI:      https://paypal.me/doekenorg
 * Plugin URI:      https://apogi.dev
 * License:         GPL2
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Version:         1.0.0
 */

defined('ABSPATH') or die('No direct access!');

use Apogi\SortExport\SortExportPlugin;

if (!defined('GF_SORT_EXPORT_PLUGIN_FILE')) {
    define('GF_SORT_EXPORT_PLUGIN_FILE', __FILE__);
}

require_once __DIR__ . '/src/SortExportPlugin.php';

add_action('gform_loaded', static function () {
// start plugin
    new SortExportPlugin();
});
