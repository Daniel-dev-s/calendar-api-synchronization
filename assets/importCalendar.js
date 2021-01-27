import $ from 'jquery';
import {Calendar} from "@fullcalendar/core";
import interactionPlugin from "@fullcalendar/interaction";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";
$(document).ready(function(){
    $('#showImportCalendarModal').on('click',function(){
        $('.import-calendar').show();
    });
    $('#close-modal').on('click',function(){
        $('.modal').hide();
    });
    $('.calendar-entity').on('click',function(){
        $.ajax({
            url: '/add_calendar',
            type: 'post',
            data: {"id":$(this)[0].id},
            error: function(er){
                console.log(er);
            }
        }).done(function(){
            $('.modal').hide();
            window.location.href ='/';
        });
    });
    $('.calendar-select').on('click',function(){
        $.ajax({
            url:'/getEvents',
            type: 'post',
            data:{"id":$(this)[0].id},
            error: function(er){
                console.log(er);
            }
        }).done(function(data){
            let jsonToFullCalendar = [];
            data.forEach(function(value, index,array){
                let event = {
                    'title':value.name,
                    'start':value.start.date,
                    'end':value.endTime.date
                };
                jsonToFullCalendar.push(event);
            });
            setCalendar(jsonToFullCalendar);
        });

    });
});

function setCalendar(events){
    const calendarEl = document.getElementById('calendar');
    const calendar = new Calendar(calendarEl, {
        plugins: [interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin],
        headerToolbar: {
            start: 'prev,next today',
            center: 'title',
            end: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        themeSystem: 'bootstrap',
        navLinks: true, // can click day/week names to navigate views
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        events: events
    });

    calendar.render();
}
