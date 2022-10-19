<template>
    <div class="my-3 p-3 border-right border-danger bg-white rounded shadow-sm">
        <div class="d-flex align-items-center justify-content-between">
            <h4>Recent Messages</h4>
            <small class="text-muted"> 
                <span class="oi oi-clock"></span> Last Updated {{ updated }}
            </small>
            <small v-on:click="toggle" v-if="show" class="text-muted"><span class="oi oi-chevron-top"></span></small>
            <small v-on:click="toggle" v-else class="text-muted"><span class="oi oi-chevron-bottom"></span></small>
        </div>
        <transition name="slide-fade">
            <div v-if="show" class="">
                <div v-if="!$wait.waiting('getActivities')">
                     <div v-for="activity in activities"> 
                         <div class="row pt-3 py-2 media-body pb-2 mb-0 ml-3 border-bottom mr-1">
                             <div class="col">
                                 <span class="badge badge-pill badge-light">
                                     {{ activity.type }}
                                 </span>
                                 <small v-if="activity.name" class="badge badge-pill badge-light">
                                     {{ activity.name }}
                                 </small>
                                 <small class="text-muted">
                                     {{ activity.updated_at | moment("MMMM Do YYYY, h:mm a") }}
                                 </small>
                                 <div class="row">
                                     <div class="col">
                                         <p class="card-text">
                                            <em class="pl-2">{{activity.description.substring(0,50)}}</em>
                                            <small v-if="activity.link" class="float-right">
                                                <a v-if="activity.type == 'Stream message' || activity.type == 'New Direct Message'" v-bind:href="'/' + activity.link">Reply</a>
                                                <a v-else v-bind:href="'/' + activity.link">See More</a>
                                            </small>
                                         </p>
                                     </div>
                                 </div>
                             </div>
                         </div>
                    </div>
                </div>
                <div v-else>
                    <p>Awaiting messages to load</p>
                </div>
            </div>
        </transition>
    </div>
</template>
<script>
export default {
    data() {
        return {
            activities: [],
            show: true,
            updated: '........'
        }
    },   
    methods:  {
        toggle: function() {
            this.show = !this.show;
        },
        async receivedMessage(message){
            var event = new Date;
            var eventDateString = event.toLocaleString('en-GB', { timeZone: 'Europe/London' });
            this.activities.unshift({
                'name' : message.from,
                'description' : message.message,
                'updated_at' : eventDateString,
                'link' : message.link,
                'type' : 'New Direct Message'
            });
            this.updatedTime();
            this.$forceUpdate();
        },
        async streamUpdated(message){
            var event = new Date;
            var eventDateString = event.toLocaleString('en-GB', { timeZone: 'Europe/London' });
            message['type'] = 'New Stream Message';
            message['updated_at'] = eventDateString;
            this.activities.unshift(message);
            this.updatedTime();
            this.$forceUpdate();
        },
        async updatedTime(){
            var event = new Date;
            var eventDateString = event.toLocaleString('en-GB', { timeZone: 'Europe/London' });
            this.updated = eventDateString;
        }
    },
    async mounted() {
        this.$wait.start('getActivities');
        await axios.get('/getActivities').then(response => {
            if (response.data !== 'Err') {
                this.activities = response.data;
            }
        });
        this.$wait.end('getActivities');
        this.updatedTime();
    },


}
</script>
