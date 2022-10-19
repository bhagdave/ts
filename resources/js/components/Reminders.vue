<script>
import FullCalendar from '@fullcalendar/vue'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
export default {
    props: ['reminders', 'type', 'typeid', 'usertype'],
    async mounted() {
        FullCalendar.events = this.events;
        FullCalendar.renderId = 1;
    },
    components: {
        FullCalendar // make the <FullCalendar> tag available
    },
    data() {
        return {
            calendarOptions: {
                plugins: [ dayGridPlugin, interactionPlugin ],
                initialView: 'dayGridMonth',
                selectable: true,
                selectMirror: true,
                initialEvents: this.reminders,
                select: this.handleDateSelect,
                eventAdd: this.handleAddReminder,
                eventClick: this.handleClick,
                dayMaxEvents: true,
            },
            eventId : 0,
        }
    },
    methods: {
        handleDateSelect(selectedInfo) {
            if (this.usertype == 'Agent'){
                if (typeof this.type !== 'undefined'){
                    let title=prompt("Please enter the name for the reminder");
                    let calendarApi = selectedInfo.view.calendar;

                    calendarApi.unselect();

                    if (title){
                        calendarApi.addEvent({
                            id : String(this.eventId++),
                            title,
                            start: selectedInfo.startStr,
                            end: selectedInfo.endStr,
                            allDay: selectedInfo.allDay
                        });
                    }
                }
            } else {
                alert('You are not able to add a reminder.' + this.userType);
            }
        },
        handleAddReminder(addedInfo){
            let newReminder = {
                name: addedInfo.event.title,
                start_date: addedInfo.event.startStr,
                end_date: addedInfo.event.endStr,
                type: this.type,
                type_id: this.typeid,
            };
            axios.post('/reminders/api/add', {
                reminder: newReminder
            });
        },
        handleClick(event){
            document.location.href = "/reminders/edit/" + event.event.id;
        },
    },
};
</script>
<template>
    <FullCalendar :options="calendarOptions" />
</template>

