<template>
    <div class="col-lg-6 col-sm-12">
        <div v-if="documents.length==0 && page=='folder'">
            <h3>No folder selected!</h3>
            <p>Select one of your folders from the left hand side and you will get a form over here where you can see what files are already stored against that property.  It will also give you a form to add a new file.</p>
            <p>If you want to add a file to a tenant then click on the property they live at and then click the folder Tenants underneath. This will show you a list of tenants at that address. Select one and you can add a file to their records</p>
        </div>
        <div v-else-if="documents.length==0">
            <h3>No files uploaded yet</h3>
        </div>
        <div v-else>
            <h4>Documents</h4>
            <div class="table-responsive pt-3">
                <table class="table document-table">
                <tbody>
                    <thead>
                        <tr class="d-flex">
                            <th scope="col">Document</th>
                            <th scope="col">Created</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                        <tr v-for="document in documents">
                            <td>
                                <a v-bind:href="'/' +  type + '/' +  id  + '/document/download/' +  document.id ">{{ document.file_type  }}</a>
                            </td>
                            <td>
                                {{ document.created_at | moment("MMMM Do YYYY ") }}
                            </td>
                            <td>
                                <button v-if="page !== 'your'" class="btn btn-outline-primary" @click="deleteFile(document.id)">Delete</button>
                            </td>
                        </tr>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['page', 'type', 'id'],
        data() {
            return {
                documents: [],
                componentKey: 0,
            }
        },
        async mounted() {
            this.getDocuments();
            var that = this;
            this.$root.$on('documentUpload', data => {
                that.getDocuments();
            });
        },
        methods: {
            deleteFile(fileId){
                if (confirm("Do you really want to delete the file?")){
                    var that = this;
                    axios.get('/' + this.type + '/' + this.id + '/document/delete/' + fileId).then(response => {
                        if (response.data.message == 'File deleted!'){
                            that.getDocuments();
                        }
                        $('.alert').text(response.data.message);
                    }
                    );
                }
            },
            async getDocuments() {
                await axios.get('/getDocuments/' + this.type + '/' + this.id ).then(response => {
                    this.documents = response.data;
                });
           }
        },
        watch : {
            id: function (newVal, oldVal){
                if (oldVal != newVal){
                    this.getDocuments();
                }
            }
        },
    }
</script>
