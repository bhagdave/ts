<template>
    <div>
        <div v-if="!$wait.waiting('getDocs')">
            <div v-if="documents.length!=0">
                <li class="border-right border-info border-top-0 border-bottom-0 list-group-item" data-toggle="collapse" v-on:click="toggle" data-target="#documents">
                    <span class="text-info oi oi-document pr-2"></span> 
                    Documents 
                    <span v-if="closed" class="text-info oi oi-chevron-bottom" style="float: right;"></span>
                    <span v-else class="text-info oi oi-chevron-top" style="float: right;"></span>
                </li>
                 <div v-for="document in documents"> 
                    <ul class=" list-group collapse" id="documents">
                        <a v-bind:href="'/tenant/'+ tenantid  + '/document/download/' + document.id" class="text-info list-group-item list-group-item-action">{{ document.file_type  }}</a>
                    </ul>
                </div>
            </div>
      </div>
    </div>
</template>
<script>
export default {
    props: ['tenantid'],
    data() {
        return {
            closed: true,
            documents: []
        }
    },   
    async mounted() {
        var that = this;
        this.$wait.start('getDocs');
        await axios.get('/getMyDocuments').then(response => {
            that.documents = response.data;
        });
        this.$wait.end('getDocs');
    },
    methods:  {
        toggle: function() {
            this.closed = !this.closed;
        }
    },

}
</script>
