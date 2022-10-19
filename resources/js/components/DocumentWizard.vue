<template>
    <div class="row">
        <div class="col">
            <div class="card" >
                <div class="card-body">
                    <div class="card-header"><h3 class="card-title">Document Wizard</h3></div>
                    <p></p>
                    <div v-if="stage == 1">
                        <h5 class="card-text">Click on the Documents in the Menu. It is highlighted in blue.</h5>
                    </div>
                    <div v-if="stage == 2">
                        <h5 class="card-text">1. Click on one of your properties in the left hand panel below.</h5>
                    </div>
                    <div v-if="stage == 3">
                        <h5 class="card-text">2. Type in the type of file you want to add in the File Type under Add File.</h5>
                    </div>
                    <div v-if="stage == 4">
                        <h5 class="card-text">3. Click on Choose file to open up a file dialog to pick the file you want to upload.</h5>
                        <p class="card-text">NB. You can drag files on to the box with the Choose File button in it instead.</p>
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
        if (path == '/documents'){
            this.stage = 2;
        };
        this.$root.$on('doc-wizard-stage-3', function(){
            that.updateStage(3);
        });
        this.$root.$on('doc-wizard-stage-4', function(){
            that.updateStage(4);
        });
        this.$root.$on('doc-wizard-stage-2', function(){
            that.updateStage(2);
        });
    },
    methods:  {
        toggle: function() {
            this.closed = !this.closed;
        },
        dashboard: function() {
            $(".container").empty();
            $(".doc-wizard").addClass('text-white');
            $(".doc-wizard").addClass('bg-info');
        },
        updateStage: function(stage) {
            this.stage = stage;
            return;
        },
    },

}
</script>
