<template>
    <div class="row media-body mb-0 lh-125 ">
        <div class="col-12">
            <textarea ref="message" id="btn-input" required class="form-control" placeholder="Type your message here..." name="message" v-model="newMessage" @keyup.enter="sendMessage" rows="5" cols="80"></textarea>
            <div class="float-right mt-3">
                <button type="button" class="border border-secondary btn  btn-default" @click="thumbsUp" >
                   &#x1f44d;
                </button>
                <button class="btn btn-outline-primary " id="btn-chat" @click="sendMessage">
                    Send
                </button>
            </div>  
        </div>
    </div>
</template>
<script>
    export default {
        props: ['user', 'recipient'],
        data() {
            return {
                newMessage: ''
            }
        },
        methods: {
            sendMessage() {
                var currentdate = new Date();
                var datetime = currentdate.getDate() + "/"
                    + (currentdate.getMonth()+1)  + "/"
                    + currentdate.getFullYear() + " @ "
                    + currentdate.getHours() + ":"
                    + currentdate.getMinutes();
                this.$emit('directmessage', {
                    message: this.newMessage,
                    updated_at: datetime
                });
                this.newMessage = ''
            },
            thumbsUp() {
                this.newMessage = this.newMessage + "\ud83d\udc4d";
                this.$refs.message.focus();
            }

        }
    }
</script>
