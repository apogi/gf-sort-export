/**
 * Sort Export for Gravity Forms plugin.
 * @since 1.0.0
 */
( function ( $ ) {
    /**
     * Sorts the list items by moving active items to the top.
     * @since 1.0.0
     * @param form_id The form id.
     * @param $list The items list element.
     */
    function sortItems( form_id, $list ) {
        const items = $( 'li', $list );
        items.sort( function ( a, b ) {
            // select all always on top.
            if ( $( b ).find( '#select_all' ).length > 0 ) {
                return 1;
            }

            a = $( a ).hasClass( 'active' );
            b = $( b ).hasClass( 'active' );

            return a === b ? 0 : ( a < b ? 1 : -1 );
        } );
        $list.append( items );
        $list.sortable( 'refresh' );

        storeOrder( form_id, $list );
    }

    /**
     * Stores the current sort order for the form.
     * @since 1.1.0
     * @param form_id The form id.
     * @param $list The items list element.
     */
    function storeOrder( form_id, $list ) {
        if ( ajaxurl === undefined ) {
            return;
        }

        $.post( ajaxurl, {
            action: 'gf-sort-export-store-order',
            form_id: form_id,
            order: $list.sortable( 'toArray', { attribute: 'rel' } )
        } );
    }

    /**
     * Sorts the list based on the stored order.
     * @since 1.1.0
     * @param form_id The form id.
     * @param $list The items list element.
     */
    function setListOrder( form_id, $list ) {
        if ( ajaxurl === undefined ) {
            return;
        }

        $.get( ajaxurl, {
            action: 'gf-sort-export-get-order',
            form_id: form_id,
            dataType: 'json'
        }, function ( order ) {
            if ( order.length === 0 ) {
                // Do nothing if we have no stored order.
                return;
            }

            var $items = $( 'li', $list );
            // Select all stored values
            $items.find( 'input[type=checkbox]' ).each( function () {
                if ( order.includes( $( this ).val() ) ) {
                    $( this ).attr( 'checked', true ).trigger( 'change', true );
                }
            } );

            // Reset the stored order
            $items.sort( function ( a, b ) {
                // select all always on top.
                if ( $( b ).find( '#select_all' ).length > 0 ) {
                    return 1;
                }

                // Get sort index. Will be -1 if the item is not in the order array.
                a = order.indexOf( $( a ).attr( 'rel' ) );
                b = order.indexOf( $( b ).attr( 'rel' ) );

                // No need to sort
                if ( a === b ) {
                    return 0;
                }

                // Left or right is not in the array. Push them to the bottom.
                if ( a === -1 || b === -1 ) {
                    return a === -1 ? 1 : -1;
                }

                // regular sort based on the index
                return a < b ? -1 : 1;
            } );
            $list.append( $items );
        } );
    }

    $( function () {
        let $list = $( '#export_field_list' ),
            $export_form = $( '#export_form' ),
            updating = false,
            timeout;

        $export_form
            .on( 'change', function () {
                // Keep track of whether we are updating the list.
                updating = true;
            } );

        const listObserver = new MutationObserver( mutations => {
            mutations
                .filter( ( m ) => m.type === 'childList' && m.addedNodes.length > 0 )
                .forEach( () => {
                    if ( !updating ) {
                        // Prevent endless loop. If we aren't updating don't trigger this event.
                        return;
                    }

                    // If the list is empty, we wait for it to fill up.
                    if ( $list.find( 'li' ).length > 0 ) {
                        updating = false;
                        setListOrder( $export_form.val(), $list );
                    }
                } );
        } );
        listObserver.observe( $list.get( 0 ), { childList: true } );

        $list
            // Only sort active items, and not the header.
            .sortable( {
                items: "> li:not(:first-child).active",
                cancel: "> li:not(.active)",
                update: function () {
                    storeOrder( $export_form.val(), $list )
                }
            } )
            // Add active class for selected items.
            .on( 'change', 'input[type=checkbox]', function ( e, is_propagated ) {
                $parent = $( this ).parent( 'li' );
                $parent.toggleClass( 'active', $( this ).is( ':checked' ) );
                $parent.attr( 'rel', $( this ).is( ':checked' ) ? $( this ).val() : null );

                // Trigger change event on all fields to update class.
                if ( $( this ).attr( 'id' ) === 'select_all' ) {
                    // Make known this is not the original event.
                    $list.find( 'input.gform_export_field' ).trigger( 'change', true );
                }
                // Only sort on the original event.
                if ( !is_propagated ) {
                    // People are allowed to change their mind.
                    clearTimeout( timeout );
                    timeout = setTimeout( function () {
                        sortItems( $export_form.val(), $list );
                    }, 1250 );
                }
            } );
    } );
} )( jQuery );
