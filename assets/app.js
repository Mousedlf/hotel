import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';


import { Calendar } from '@fullcalendar/core';
import interactionPlugin from '@fullcalendar/interaction';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';


document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');


    // var pg = require('pg')
    // var connectionString = "postgresql://caroline:caroline@127.0.0.1:5432/hotel?serverVersion=15&charset=utf8";
    // var pgClient = new pg.Client(connectionString);


    var calendar = new Calendar(calendarEl, {
        plugins: [ interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin ],
        headerToolbar: {
            left: 'title',
            center: '',
            right: 'prev next'
        },
        eventColor: '#000',
        events: [
            {
                start: '2023-12-12',
                end: '2023-12-13',
                display: 'background'
            }
        ]
    });

    calendar.render();
});

// https://fullcalendar.io/docs/event-display : How to control the appearance of events on your calendar.