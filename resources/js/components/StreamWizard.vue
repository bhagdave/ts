<template>
    <div class="row">
        <div class="col">
            <div class="card" >
                <div class="card-body">
                    <div class="card-header"><h3 class="card-title">Stream Wizard</h3></div>
                    <p></p>
                    <div v-if="stage == 1">
                        <h5 class="card-text">Click on the Property in the Menu. It is the big green block.</h5>
                    </div>
                    <div v-if="stage == 2">
                        <h5 class="card-text">To Send a message type something in the chat message box</h5>
                        <p class="card-text">It is the one with the thick green border below.</p>
                    </div>
                    <div v-if="stage == 3">
                        <h5 class="card-text">Now press the Send button.</h5>
                    </div>
                    <br/>
                    <a href="/" class="btn btn-primary">Close Wizard</a>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            closed: true,
            stage: 1
        }
    },   
    async mounted() {
        var that = this;
        var path = window.location.pathname;
        if (path == '/'){
            this.dashboard();
        }
        this.$root.$emit('update-stream-wizard');
        this.$root.$on('update-stream-wizard', function(){
            if (that.stage != 3){
                $('#btn-chat').css('borderWidth', '5px');
                that.updateStage(3);
            }
        });
        if (path.includes('stream')){
            $('#chatContainer').css('height', '28vh');
            $('.stream-wizard').css('borderWidth', '5px');
            $('.stream-wizard').addClass('border-success');
            this.$root.$emit('stream-wizard-stage-2');
            this.updateStage(2);
        }
    },
    methods:  {
        toggle: function() {
            this.closed = !this.closed;
        },
        dashboard: function() {
            $(".container").empty();
            if ($(".only-property")){
                this.$root.$emit('stream-wizard-stage-1');
            }
        },
        animate: function(selection) {
            $(selection).animate({
                borderWidth: "10px",
                fontSize: "1em",
            }, 1000, "linear", function() {
            });
        },
        updateStage: function(stage) {
            this.stage = stage;
            return;
        },
    },

}
</script>
