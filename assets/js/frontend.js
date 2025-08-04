jQuery( function ( $ ) {
	const calendarEl = document.getElementById( 'ibc-frontend-calendar' );
	if ( ! calendarEl ) { return; }

	const calendar = new FullCalendar.Calendar( calendarEl, {
		initialView: 'timeGridWeek',
		headerToolbar: {
			left: 'prev,next today',
			center: 'title',
			right: 'dayGridMonth,timeGridWeek,timeGridDay'
		},
		events: function ( info, success ) {
			$.get( ibcObj.ajax, {
				action: 'ibc_frontend_get_events',
				nonce:  ibcObj.nonce,
				start:  info.startStr,
				end:    info.endStr
			}, success );
		},
		selectable: true,
		select: function ( info ) {
			const title = prompt( 'Title?' );
			if ( ! title ) { return; }
			$.post( ibcObj.ajax, {
				action: 'ibc_frontend_book',
				nonce:  ibcObj.nonce,
				title:  title,
				start:  info.startStr,
				end:    info.endStr,
				category_id: 1 // TODO: dynamic
			}, () => calendar.refetchEvents() );
		},
		eventClick: function ( info ) {
			if ( confirm( 'Delete this booking?' ) ) {
				$.post( ibcObj.ajax, {
					action: 'ibc_frontend_delete',
					nonce:  ibcObj.nonce,
					id:     info.event.id
				}, () => calendar.refetchEvents() );
			}
		}
	} );
	calendar.render();
} );