<template>
    <div>
        <div v-if="!$wait.waiting('getContacts')">
            <li class="border-right border-left-0 border-dark border-top-0 border-bottom-0 list-group-item" data-toggle="collapse" data-target="#contacts" v-on:click="toggle">
                <span class="text-dark oi oi-comment-square pr-2"></span>
                Direct Message 
                <span v-if="closed" class="text-dark oi oi-chevron-bottom" style="float: right;"></span>
                <span v-else class="text-dark oi oi-chevron-top" style="float: right;"></span>
            </li>
             <div v-for="contact in contacts"> 
                <ul class="list-group collapse" id="contacts">
                    <a v-bind:href="'/directmessage/' + contact.type + '/' + contact.id"  class="list-group-item list-group-item-action">{{ contact.name}} ({{ contact.type }})</a>
                </ul>
            </div>
      </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            contacts: [],
            closed: true,
        }
    },   
    methods:  {
        toggle: function() {
            this.closed = !this.closed;
        }
    },
    async mounted() {
        this.$wait.start('getContacts');
        await axios.get('/landlords/getContacts').then(response => {
            if (response.data !== 'Err') {
                this.contacts = response.data;
            }
        });
        this.$wait.end('getContacts');
    },


}
</script>
