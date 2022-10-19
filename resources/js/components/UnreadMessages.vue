<template>
    <div>
        <div v-if="!$wait.waiting('getUnread')">
            <div v-if="messages.length>0 || streams.length>0">
                <li class="border-left-0 border-right border-top-0 border-bottom-0 border-danger list-group-item" data-toggle="collapse" data-target="#unread" v-on:click="toggle">
                    <span class="text-danger oi oi-layers pr-2"></span>
                    Unread Messages 
                    <span v-if="closed" class="text-danger oi oi-chevron-bottom" style="float: right;"></span>
                    <span v-else class="text-danger oi oi-chevron-top" style="float: right;"></span>
                </li>
                <div v-for="message in messages"> 
                    <ul class="list-group collapse" id="unread">
                        <a v-bind:href="'/directmessage/' + message.creator_type  + '/' + message.creator_type_id"  class="list-group-item list-group-item-action">{{ message.firstName}} {{ message.lastName}} ({{ message.userType }})</a>
                    </ul>
                </div>
                <div v-for="stream in streams"> 
                    <ul class="list-group collapse" id="unread">
                        <a v-if="stream.propertyName" v-bind:href="'/stream/' + stream.log_name"  class="list-group-item list-group-item-dark list-group-item-action"><strong>{{ stream.propertyName}} </strong></a>
                        <a v-else v-bind:href="'/stream/' + stream.log_name"  class="list-group-item list-group-item-dark list-group-item-action"><strong>{{ stream.streamyName}} </strong></a>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props:[ 
        'messages',
        'streams',
    ],
    data() {
        return {
            closed: true,
        }
    },   
    methods:  {
        toggle: function() {
            this.closed = !this.closed;
        }
    },
}
</script>
