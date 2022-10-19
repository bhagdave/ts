<template>
    <div class="row">
        <div class="col">
            <div class="card" >
                <div class="card-body">
                    <div class="card-header"><h3 class="card-title">Property Wizard</h3></div>
                    <p></p>
                    <div v-if="stage == 1">
                        <h5 class="card-text">Click on Add Property in the Menu. It is the big green block.</h5>
                    </div>
                    <div v-if="stage == 2">
                        <h5 class="card-text">Add the property details.</h5>
                        <p class="card-text">All the fields with a red border are required.  If you are not sure what type of property to use just leave it as the default.  You will be asked to add a tenant after creating the property.</p>
                    </div>
                    <div v-if="stage == 3">
                        <h5 class="card-text">Invite a tenant</h5>
                        <p class="card-text">The required fields have a red border around them.  If you do not want to add a tenant at the moment just press the cancel button.</p>
                    </div>
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
        if (path == '/property/create'){
            this.highlightFields();
            this.updateStage(2);
        }
        if (path.includes('tenant/create')){
            this.highlightFields();
            this.updateStage(3);
        }
    },
    methods:  {
        toggle: function() {
            this.closed = !this.closed;
        },
        dashboard: function() {
            $(".container").empty();
            if ($(".property-wizard")){
                $('.property-wizard').addClass('text-white');
                $('.property-wizard').addClass('bg-success');
                this.$root.$emit('property-wizard-stage-1');
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
        },
        highlightFields: function(){
            $('input,textarea,select').filter('[required]:visible').addClass('border-danger');
        },
    },

}
</script>
