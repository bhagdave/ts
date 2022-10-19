<template>
    <div class="">
        <h4><span class=" oi oi-comment-square pr-2"></span>Direct Message Tenants</h4> 
        <div v-if="sent">Message Sent</div>
        <select-search ref="selectedtenant" :returns="'sub'" :value="''" :items="tenants" :shows='"name"'></select-search>
        <textarea 
            id="btn-input" 
            required 
            class="form-control" 
            placeholder="Type your message here..." 
            name="message" 
            v-model="message" 
            @keyup.enter="sendMessage" 
            rows="4" 
            cols="80">
        </textarea>
        <button class="btn btn-outline-primary" @click="sendMessage">
            Send
        </button>
    </div>
</template>
<script>
export default {
    props: ['tenants'],
    data() {
        return {
            message: '',
            sent: false,
        }
    },   
    methods:  {
        async sendMessage() {
            this.sent = false;
            if (this.message !== ''){
                var that = this;
                await axios.post('/senddirectmessage', {
                    recipient: this.$refs.selectedtenant.selectedItem,
                    message: this.message
                }).then(response => {
                    that.sent = true;
                });
                this.message = ''
            }
        },
    },
    async mounted() {
    },


}
</script>
