/**
 * Sort Export for Gravity Forms plugin.
 * @since 1.0.0
 */
(function ($) {
    /**
     * Sorts the list items by moving active items to the top.
     */
    function sortItems(list) {
        var items = $('li', list);
        items.sort(function (a, b) {
            // select all always on top.
            if ($(b).find('#select_all').length > 0) {
                return 1;
            }

            a = $(a).hasClass('active');
            b = $(b).hasClass('active');

            return a === b ? 0 : (a < b ? 1 : -1);
        });
        list.append(items);
        list.sortable('refresh');
    }

    $(function () {
        var $list = $('#export_field_list');
        var timeout;

        $list
            // Only sort active items, and not the header.
            .sortable({items: "> li:not(:first-child).active"})
            // Add active class for selected items.
            .on('change', 'input[type=checkbox]', function (e, is_propagated) {
                $(this).parent('li').toggleClass('active', $(this).is(':checked'));

                // Trigger change event on all fields to update class.
                if ($(this).attr('id') === 'select_all') {
                    // Make known this is not the original event.
                    $list.find('input.gform_export_field').trigger('change', true);
                }
                // Only sort on the original event.
                if (!is_propagated) {
                    // People are allowed to change their mind.
                    clearTimeout(timeout);
                    timeout = setTimeout(function () {
                        sortItems($list);
                    }, 1250);
                }
            });
    });
})(jQuery);
