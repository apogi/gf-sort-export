<?php

namespace Apogi\SortExport;

/**
 * Plugin entry point.
 * @since 1.0.0
 */
class SortExportPlugin
{
    /**
     * Registers the required hooks.
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action('admin_enqueue_scripts', \Closure::fromCallable([$this, 'loadScripts']));
        add_action('gform_form_export_page', \Closure::fromCallable([$this, 'disableInactiveSubfields']));
        add_action('wp_ajax_gf-sort-export-store-order', \Closure::fromCallable([$this, 'storeOrder']));
        add_action('wp_ajax_gf-sort-export-get-order', \Closure::fromCallable([$this, 'getOrder']));
    }

    /**
     * Registers the required javascript.
     * @since 1.0.0
     */
    private function loadScripts(): void
    {
        if (rgget('page') !== 'gf_export' || !in_array(rgget('view'), ['', 'export_entry'], true)) {
            return;
        }

        $min = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) || isset($_GET['gform_debug']) ? '' : '.min';
        $plugin_asset_dir = plugin_dir_url(GF_SORT_EXPORT_PLUGIN_FILE) . 'public/';

        wp_enqueue_script(
            'gf-sort-export',
            $plugin_asset_dir . 'js/gf-sort-export.jquery' . $min . '.js',
            ['jquery', 'jquery-ui-sortable']
        );

        wp_enqueue_style(
            'gf-sort-export',
            $plugin_asset_dir . 'css/gf-sort-export' . $min . '.css'
        );
    }

    /**
     * Removes hidden (inactive) subfields form the export page.
     * @since 1.0.0
     * @param mixed[] $form The form object.
     * @return mixed[] The updated form object.
     */
    private function disableInactiveSubfields(array $form): array
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

    /**
     * Returns the stored order (if any) for a specific form.
     * @since $ver$
     */
    private function getOrder(): void
    {
        $option = sprintf('gf-sort-export-order-%d', $_GET['form_id'] ?? 0);
        wp_send_json(get_option($option, []));
    }

    /**
     * Stores the sort order for the provided form.
     * @since $ver$
     */
    private function storeOrder(): void
    {
        $allowed_fields = ['form_id', 'order'];
        $data = array_intersect_key($_POST, array_flip($allowed_fields));

        $form = \GFAPI::get_form($form_id = $data['form_id'] ?? 0);
        if (!$form) {
            wp_send_json_error(null, 404);

            return;
        }

        $option = sprintf('gf-sort-export-order-%d', $form_id);
        update_option($option, $data['order'] ?: [], false);

        wp_send_json_success();
    }
}
