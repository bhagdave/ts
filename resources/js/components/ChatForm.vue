// resources/assets/js/components/ChatForm.vue
<template>
<div class="row media-body mb-0 lh-125 ">
    <div class="col-12">
      <textarea v-if="cafe" id="btn-input" ref="message" required class="form-control" placeholder="Type your question to other members here..." name="message" v-model="newMessage" @keyup.enter="sendMessage" rows="4" cols="80"></textarea>
      <textarea v-else-if="user.userType === 'Tenant'" id="btn-input" ref="message" required class="form-control" placeholder="Ask questions about the property here..." name="message" v-model="newMessage" @keyup.enter="sendMessage" rows="4" cols="80"></textarea>
      
      <textarea v-else-if="wizard" id="btn-input" ref="message" required class="stream-wizard form-control" placeholder="TYPE HERE" name="message" v-model="newMessage"  v-on:input="updateWizard" @keyup.enter="sendMessage" rows="4" cols="80"></textarea>
      
      <textarea v-else id="btn-input" ref="message" required class="stream-wizard form-control" placeholder="Type your message here..." name="message" v-model="newMessage" @keyup.enter="sendMessage" rows="4" cols="80"></textarea>
      
      <div class="float-right mt-3">
        <button type="button" class="border border-secondary btn btn-default" data-toggle="modal" data-target="#uploadModal">
            <span class="oi oi-paperclip"></span>
        </button>
        <button type="button" class="border border-secondary btn btn-default" @click="thumbsUp" >
           &#x1f44d;
        </button>
        <button class="btn btn-outline-primary" id="btn-chat" @click="sendMessage">
            Send
        </button>
      </div>

    </div>
</div>
</template>
<script>
export default {
    props: ['cafe', 'user', 'stream_id', 'wizard'],
    data() {
        return {
            newMessage: '',
        }
    },
    async mounted() {
        var that = this;
        this.$root.$on('stream-wizard-stage-2', function(){
            that.streamWizard();
        });
        this.$root.streamId = this.stream_id;
    },
    methods: {
        sendMessage() {
            if (this.newMessage !== ''){
                this.$emit('messagesent', {
                    user: this.user,
                    message: this.newMessage,
                    streamId: this.stream_id
                });
                this.newMessage = ''
            }
        },
        thumbsUp() {
            this.newMessage = this.newMessage + "\ud83d\udc4d";
            this.$refs.message.focus();
        },
        streamWizard() {
            this.wizard = true;
        },
        updateWizard(){
            this.$root.$emit('update-stream-wizard');
        }
    }
}
</script>
