document.addEventListener('DOMContentLoaded', function () {
	var calendarEl = document.getElementById('calendar');
	var calendar = new FullCalendar.Calendar(calendarEl, {
		headerToolbar: {
			left: 'prev,next today',
			center: 'title',
			right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
		},
		initialDate: new Date(),
		navLinks: true, // can click day/week names to navigate views
		nowIndicator: true,
		weekNumbers: true,
		editable: false,
		selectable: true,
        businessHours: true,
		dayMaxEvents: true, // allow "more" link when too many events
        events: {
			url: base_url + "hr/get-vacation-data-for-calendar-user",
			method: 'POST',
			failure: function () {
				document.getElementById('script-warning').style.display = 'block'
			}
		},
		loading: function (bool) {
			document.getElementById('loading').style.display = bool ? 'block' : 'none';
		}
	});
	calendar.render();
});
