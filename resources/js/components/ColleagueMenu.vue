<template>
    <div>
        <div v-if="!$wait.waiting('getAgents')">
            <a href="/dmPage" v-if="agents.length > 10" class=" border-dark list-group-item-action border-right border-left-0 border-dark border-top-0 border-bottom-0 list-group-item" >
                <span class="text-dark oi oi-comment-square pr-2"></span>
                Direct Message
            </a> 
            <li v-else class="border-right border-left-0 border-dark border-top-0 border-bottom-0 list-group-item" appear data-target="#agents" v-on:click="toggle">
                <span class="text-dark oi oi-comment-square pr-2"></span>
                Direct Message 
                <span v-if="closed" class="text-dark oi oi-chevron-bottom" style="float: right;"></span>
                <span v-else class="text-dark oi oi-chevron-top" style="float: right;"></span>
            </li>
            <div v-if="agents.length > 10" v-show="!closed" v-for="agent in agents"> 
                <ul class="list-group" id="agents">
                    <a v-bind:href="'/directmessage/agent/'+ agent.id"  class="list-group-item list-group-item-action">{{ agent.name}}</a>
                </ul>
            </div>
      </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            agents: [],
            closed: true,
        }
    },   
    methods:  {
        toggle: function() {
            this.closed = !this.closed;
        }
    },
    async mounted() {
        this.$wait.start('getAgents');
        await axios.get('/agency/agents').then(response => {
            if (response.data !== 'Err') {
                this.agents = response.data;
            }
        });
        this.$wait.end('getAgents');
    },


}
</script>
