<template>
    <div  v-if="typeof(id) != 'undefined'"class="col-lg-6 col-sm-12 document-upload">
        <form id="form" v-on:submit.prevent="submit" class="submit-once p-3" v-bind:action="'/' + type + '/' + id + '/document/upload'" method="post" enctype="multipart/form-data">

            <div class="">
                <h4>Add file</h4>
                <input type="hidden" name="type" v-bind:value="'type'">
                <input type="hidden" name="_token" :value="csrf">
                <label for="fileType">File type</label>
                <input type="text" v-model="fileType" v-on:blur="emitStageUpdate()" class="form-control" required placeholder="Type or select from list" list="fileTypes" name="fileType" />
                <datalist v-if="type == 'property'" id="fileTypes">
                    <option value="EPC">EPC</option>
                    <option value="Electrical Certificate">Electrical Certificate</option>
                    <option value="Gas Certificate">Gas Certificate</option>
                    <option value="House Rules">House Rules</option>
                    <option value="Inspection Report">Inspection Report</option>
                    <option value="Insurance">Insurance</option>
                    <option value="HMO License">HMO License</option>
                </datalist>
                <datalist v-if="type == 'tenant'" id="fileTypes">
                    <option value="Tenancy agreement">Tenancy agreement</option>
                    <option value="How to rent">How to rent</option>
                    <option value="Inspection Report">Inspection Report</option>
                </datalist>
                <br/>
                <label for="document">Document</label>
                <input v-cloak @dragover.prevent id="document" type="file" class="form-control" name="document" >
                <p class="form-text text-muted justify-content-center">You can drag files onto the input if you prefer.</p>
           </div>

            <div class="question  py-3">
            </div>
            <div class="submit py-3">
                <button type="submit"  class="disable-on-submit btn btn-primary btn-lg">Submit </button>
            </div>
        </form>
    </div>
</template>
<script>
    export default {
        props: ['type', 'id'],
        data() {
            return {
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                file: undefined,
                fileType: '',
                componentkey : 0,
            }
        },
        methods: {
            submit(e){
                var form = document.getElementById('form');
                var formData = new FormData(form);
                var url = "/" + this.type + "/" + this.id + "/document/upload"
                axios.post(url, formData).then((response) =>{
                    this.$emit('documentUpload');
                    this.$root.$emit('documentUpload');
                }, (response) => {
                    this.$emit('documentUpload');
                    this.$root.$emit('documentUpload');
                }
                );
                this.fileType = '';
                this.file = undefined;
                this.$root.$emit('doc-wizard-stage-2');
            },
            emitStageUpdate(){
                this.$root.$emit('doc-wizard-stage-4');
            }
        }
    }
</script>
