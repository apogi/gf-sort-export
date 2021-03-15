<?php

namespace Apogi\SortExport;

/**
 * Plugin entry point.
 * @since $ver$
 */
class SortExportPlugin
{
    /**
     * Registers the required hooks.
     * @since $ver$
     */
    public function __construct()
    {
        add_action('admin_enqueue_scripts', \Closure::fromCallable([$this, 'load_scripts']));
    }

    /**
     * Registers the required javascript.
     * @since $ver$
     */
    private function load_scripts()
    {
        if (rgget('view') !== 'export_entry') {
            return;
        }

        wp_enqueue_script('gf-sort-export',
            plugin_dir_url(GF_SORT_EXPORT_PLUGIN_FILE) . 'public/js/gf-sort-export.jquery.js',
            ['jquery', 'jquery-ui-sortable']
        );

        wp_enqueue_style('gf-sort-export',
            plugin_dir_url(GF_SORT_EXPORT_PLUGIN_FILE) . 'public/css/gf-sort-export.css'
        );
    }
}
