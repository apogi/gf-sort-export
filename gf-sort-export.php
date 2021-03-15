<?php
/**
 * Plugin Name:     Gravity Forms Sort Export
 * Description:     Control the order of the fields during export
 * Author:          Doeke Norg
 * Author URI:      https://paypal.me/doekenorg
 * Plugin URI:      https://apogi.dev/plugins/gf-sort-export
 * License:         GPL2
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Version:         0.1.0-alpha
 */

defined('ABSPATH') or die('No direct access!');

use Apogi\SortExport\SortExportPlugin;

if (!defined('GF_SORT_EXPORT_PLUGIN_FILE')) {
    define('GF_SORT_EXPORT_PLUGIN_FILE', __FILE__);
}

require_once __DIR__ . '/src/SortExportPlugin.php';

// start plugin
new SortExportPlugin();
