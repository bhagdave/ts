<template>
    <div class="d-flex flex-column justify-content-between chat-outer">
        <div id="chatContainer" class="panel panel-default">
            <div class="panel-body">
                <div v-if="!messages.length">
                    <div class="text-center">
                        <h5 class="font-weight-light pt-2">This is the start of your chat with {{ recipientname }}</h5>
                    </div>
                </div>
                <div class="chat">
                    <div class="row pt-3 media-body pb-2 mb-0" v-if="messages.length > 100">
                        <div class="col-12">
                            <small class="text-center">This chat is longer than 100 messages. Only 100 display here.</small>
                        </div>
                    </div>

                    <div v-for="message in messages">
                        <div class="row w-100 media-body mb-0 " >
                            <div class="col">
                                <span class="text-muted pl-1"> {{message.updated_at | moment("MMMM Do YYYY, h:mm a")}} </span>
                                <p v-if="recipient !== message.creator_sub">
                                    <small>You</small>: {{message.message}}
                                </p>
                                <p v-else>
                                    <small>{{recipientname}}</small>:{{message.message}}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="latest">
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex rounded chatInput" id="chatInput">
            <div v-if="subscribed" class="p-2 flex-grow-1">
                <direct-chatform  v-on:directmessage="sendMessage" ></direct-chatform>
            </div>
            <div v-else class="p-2 flex-grow-1">
                Your trial has come to and end and you have not subscribed so will not be able to add any more documents.<br>  Please <a href="payment">click here</a> to subscribe.
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['subscribed','messages','waiting', 'recipient', 'user', 'recipientname'],
        async mounted() {
            this.scrollChat();
        },
        methods: {
            async sendMessage(message) {
                if (message.message){
                    var that = this;
                    await axios.post('/senddirectmessage', {
                        recipient: this.recipient,
                        message: message.message
                    }).then(response => {
                        that.updateMessageList(response.data);
                    });
                    that.scrollChat();
                }
            },
            async receivedMessage(message){
                if (message.from_sub == this.recipient) {
                    this.markMessageAsRead(message.message_id);
                    this.updateMessageList(message);
                    this.$nextTick(function () {
                        this.scrollChat();
                    });
                }
            },
            updateMessageList(message){
                this.$set(this.messages, this.messages.length, message);
                this.$forceUpdate();
                this.scrollChat();
            },
            scrollChat(){
                var container = this.$el.querySelector("#chatContainer");
                container.scrollTop = container.scrollHeight;
            },
            async markMessageAsRead(message_id){
                axios.get('/directmessageread/' + message_id);
            },
        },
    };
</script>
