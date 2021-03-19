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
        add_action('gform_form_export_page', \Closure::fromCallable([$this, 'disable_inactive_subfields']));
    }

    /**
     * Registers the required javascript.
     * @since $ver$
     */
    private function load_scripts(): void
    {
        if (rgget('page') !== 'gf_export' || !in_array(rgget('view'), ['', 'export_entry'], true)) {
            return;
        }

        wp_enqueue_script(
            'gf-sort-export',
            plugin_dir_url(GF_SORT_EXPORT_PLUGIN_FILE) . 'public/js/gf-sort-export.jquery.js',
            ['jquery', 'jquery-ui-sortable']
        );

        wp_enqueue_style(
            'gf-sort-export',
            plugin_dir_url(GF_SORT_EXPORT_PLUGIN_FILE) . 'public/css/gf-sort-export.css'
        );
    }

    /**
     * Removes hidden (inactive) subfields form the export page.
     * @since $ver$
     * @param mixed[] $form The form object.
     * @return mixed[] The updated form object.
     */
    private function disable_inactive_subfields(array $form): array
    {
        foreach ($form['fields'] as $i => $field) {
            if (is_array($field->inputs)) {
                $form['fields'][$i]->inputs = array_filter($field->inputs, static function (array $input): bool {
                    return !($input['isHidden'] ?? false);
                });
            }
        }

        return $form;
    }
}
