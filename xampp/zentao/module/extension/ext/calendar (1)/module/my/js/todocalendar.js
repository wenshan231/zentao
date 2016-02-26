$(document).ready(function() { 
link = createLink('my', 'ajaxGetTodo');
$('#calendar').
fullCalendar({ 
	draggable: true, 
	events:link, 
	eventDrop: function(event, delta) { alert(event.title + ' was moved ' + delta + ' days\n' + '(should probably update your database)'); }, 
	loading: function(bool) { if (bool) $('#loading').show(); else $('#loading').hide(); } }); 
}); 