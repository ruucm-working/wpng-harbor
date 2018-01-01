jQuery(function($) {
	$('#calendar').fullCalendar({
		googleCalendarApiKey: 'AIzaSyBFvPLgdiazNapJXXCbB4dxjoO9FK1ncUM',
		events: {
			googleCalendarId: '9844b3onrgnue2t7v2llio9sg4@group.calendar.google.com'
		},
		editable: false, // Don't allow editing of events
		handleWindowResize: true,
		defaultView: 'agendaWeek', // Only show week view
		// header: false, // Hide buttons/titles
		minTime: '07:30:00', // Start time for the calendar
		maxTime: '22:00:00', // End time for the calendar
		displayEventTime: true, // Display event time
		locale: 'ko',
		eventClick: function(calEvent, jsEvent, view) {
			alert('Event: ' + calEvent.title);
			console.log('calEvent : ');
			console.log(calEvent);
			$(this).css('border-color', 'red');
			$("body").prepend("<div id='cal-data'>" +
				"<div class='cal-data-title' style='height: 50px; margin-top: -50px;'>" + calEvent.title + "</div>" +
				"<div class='cal-data-start' style='height: 50px; margin-top: -50px;'>" + calEvent.start + "</div>" +
				"<div class='cal-data-end' style='height: 50px; margin-top: -50px;'>" + calEvent.end + "</div>" +
				"</div>");
		},
		eventRender: function(event, element) {
			element.on('click', function(e) {
				e.preventDefault();
			});
		}
	});
	$('#growingmom-calendar-button').click(function(e) {
		show_growingmom_calendar(e);
	});
	$(document).keydown(function(e) {
		switch (e.which) {
			case 37: // left
				$('#calendar').fullCalendar('prev');
				break;
			case 27: // esc
				close_growingmom_calendar();
				break;
			case 39: // right
				$('#calendar').fullCalendar('next');
				break;
			default:
				return; // exit this handler for other keys
		}
		e.preventDefault(); // prevent the default action (scroll / move caret)
	});
	$(document).mouseup(function(e) {
		var container = $("#calendar");
		if (!container.is(e.target) && container.has(e.target).length === 0)
			close_growingmom_calendar();
	});

	function show_growingmom_calendar(e) {
		$('#calendar').addClass('loaded');
		$('#calendar').fullCalendar('option', 'height', 700);
		$('#calendar').fullCalendar('option', 'contentHeight', 500);
		$('.overlay').removeClass('blur-out');
		$('.overlay').addClass('blur-in');
		e.preventDefault();
	}

	function close_growingmom_calendar() {
		$('#calendar').removeClass('loaded');
		$('.overlay').removeClass('blur-in');
		$('.overlay').addClass('blur-out');
	}
});
