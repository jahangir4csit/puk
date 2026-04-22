/* global pukProjectOrder */
jQuery( function( $ ) {

	var table_selector = 'table.wp-list-table',
		item_selector  = 'tbody tr:not(.inline-edit-row)',
		id_selector    = '.check-column input[type="checkbox"]';

	// Add drag handle column to each row
	$( table_selector ).find( 'tr:not(.inline-edit-row)' ).each( function() {
		if ( ! $( this ).find( 'td.puk-drag-handle' ).length ) {
			$( this ).prepend( '<td class="puk-drag-handle" style="width:20px;cursor:move;padding:8px 4px;" title="Drag to reorder">&#9776;</td>' );
		}
	} );

	// Keep handles after inline-edit AJAX
	$( document ).ajaxComplete( function( event, request, options ) {
		if ( options.data && 0 <= options.data.indexOf( '_inline_edit' ) ) {
			$( table_selector ).find( 'tr:not(.inline-edit-row)' ).each( function() {
				if ( ! $( this ).find( 'td.puk-drag-handle' ).length ) {
					$( this ).prepend( '<td class="puk-drag-handle" style="width:20px;cursor:move;padding:8px 4px;" title="Drag to reorder">&#9776;</td>' );
				}
			} );
		}
	} );

	$( table_selector ).sortable( {
		items:               item_selector,
		cursor:              'move',
		handle:              '.puk-drag-handle',
		axis:                'y',
		forcePlaceholderSize: true,
		helper:              'clone',
		opacity:             0.65,
		scrollSensitivity:   40,
		start: function( event, ui ) {
			ui.item.css( 'outline', '1px solid #aaa' );
			ui.item.children( 'td, th' ).css( 'border-bottom-width', '0' );
		},
		stop: function( event, ui ) {
			ui.item.removeAttr( 'style' );
			ui.item.children( 'td, th' ).css( 'border-bottom-width', '1px' );
		},
		update: function( event, ui ) {
			var id     = ui.item.find( id_selector ).val();
			var previd = ui.item.prev().find( id_selector ).val() || 0;
			var nextid = ui.item.next().find( id_selector ).val() || 0;

			if ( ! id ) {
				return;
			}

			// Spinner
			var $spinner = $( '<span class="spinner is-active" style="float:none;margin:0 4px;"></span>' );
			ui.item.find( '.puk-drag-handle' ).append( $spinner );

			$.post( pukProjectOrder.ajaxurl, {
				action:   'puk_save_project_order',
				security: pukProjectOrder.nonce,
				id:       id,
				previd:   previd,
				nextid:   nextid,
			} ).always( function() {
				$spinner.remove();
			} );
		},
	} );

} );
