<template>
    <div>
        <div v-if="streams.length==1">
            <div v-for="stream in streams"> 
                <ul class="list-group" id="streams">
                        <a v-if="wizard" v-bind:href="'/stream/'+ stream.streamid" class="only-property list-group-item list-group-item-action bg-success text-white border-success">
                            <span class="text-success oi oi-home pr-2"></span>
                            {{ stream.propertyName  }}
                        </a>
                        <a v-else v-bind:href="'/stream/'+ stream.streamid" class="only-property list-group-item list-group-item-action border-top-0 border-bottom-0 border-right border-success">
                            <span class="text-success oi oi-home pr-2"></span>
                            {{ stream.propertyName  }}
                        </a>
                </ul>
            </div>
        </div>
        <div v-else>
            <li class="property-menu border-right border-top-0 border-bottom-0 border-success list-group-item" data-toggle="collapse" v-on:click="toggle" data-target="#streams">
                <span class="text-success oi oi-home pr-2"></span> 
                Properties
                <span v-if="closed" class="text-success oi oi-chevron-bottom" style="float: right"></span> 
                <span v-else class="text-success oi oi-chevron-top" style="float: right"></span> 
            </li>
            <div v-for="(stream, index) in streams"> 
                <ul v-if="wizard && index == 1" class="list-group" id="streams">
                    <a v-bind:href="'/stream/'+ stream.streamid" class="first-property list-group-item bg-success text-white list-group-item-action">{{ stream.propertyName  }}</a>
                </ul>
                <ul v-else class="list-group collapse" id="streams">
                    <a v-bind:href="'/stream/'+ stream.streamid" class="list-group-item list-group-item-action">{{ stream.propertyName  }}</a>
                </ul>
            </div>
       </div>
    </div>
</template>
<script>
export default {
    props: ['streams'],
    data() {
        return {
            closed: true,
            wizard: false,
        }
    },   
    async mounted() {
        var that = this;
        this.$root.$on('reminder-wizard-stage-1', function(){
            that.expandOneProperty();
        });
        this.$root.$on('stream-wizard-stage-1', function(){
            that.expandOneProperty();
        });
    },
    methods:  {
        toggle: function(){ 
            this.closed = !this.closed;
        },
        expandOneProperty(){
            this.wizard = true;
            this.toggle();
        }
    },
}
</script>
