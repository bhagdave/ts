<template>
    <div class="row">
        <div class="col">
            <div class="card" >
                <div class="card-body">
                    <div class="card-header"><h3 class="card-title">Reminder Wizard</h3></div>
                    <p></p>
                    <div v-if="stage == 1">
                        <h5 class="card-text">Click on the Property in the Menu. It is the big green block.</h5>
                    </div>
                    <div v-if="stage == 2">
                        <h5 class="card-text">This is your normal property stream. You can see in the little menu a link to add a reminder.</h5>
                        <p class="card-text">It's the Big Blue Button.</p>
                    </div>
                    <div v-if="stage == 3">
                        <h5 class="card-text">Now you just need to give your reminder a name. A Start date and pick if you want it repeating.</h5>
                        <p class="card-text">The repeating pattern can be Weekly, Monthly or Annually.  This will automtically create the reminders.</p>
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
        if (path.includes('stream')){
            this.updateStage(2);
            $(".add-reminder").addClass("btn btn-primary");
        }
        if (path.includes('create')){
            this.updateStage(3);
        }
    },
    methods:  {
        toggle: function() {
            this.closed = !this.closed;
        },
        dashboard: function() {
            $(".container").empty();
            if ($(".only-property")){
                this.$root.$emit('reminder-wizard-stage-1');
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
