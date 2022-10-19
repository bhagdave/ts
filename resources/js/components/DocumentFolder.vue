<template>
    <div class="row flex-column flex-md-row">
        <div class="border-right border-info border-top-0 border-bottom-0 bg-white rounded shadow-sm col-md-4 col-sm-12">
            <h3>Documents</h3>
            <div class="documents-list" v-for="property in properties">
                <div class="documents-list__item" data-toggle="collapse" v-bind:data-target="'#tenantsFolder-' + property.id" @click="setDocuments('property', property.id, property.propertyName)">
                    <span class="oi oi-folder pr-2"></span>
                    {{ property.propertyName }}
                </div>
                <div class="collapse" v-bind:id="'tenantsFolder-' + property.id">
                  <span v-if="property.tenants.length > 0" class="property-doc-folder" data-toggle="collapse" v-bind:data-target="'#tenants-' + property.id"><span class="oi oi-people pl-4 pr-2"></span> Tenants</span>
                  <div v-for="tenant in property.tenants" class="collapse property-doc" v-bind:id="'tenants-' + property.id">
                      <div @click="setDocuments('tenant',tenant.id, tenant.name)">
                          <span class="oi oi-person pl-5 pr-2"></span>
                          {{ tenant.name }}
                      </div>
                  </div>
                </div>

            </div>
        </div>
        <div class="col-md-8 col-sm-12">
          <div class="my-3 p-3">
            <h3>{{ property }}</h3>
            <div class="row d-flex">
                <document-table :page="page" :type="type" :id="id" :key="componentKey"></document-table>
                <document-upload :type="type" @documentUpload="documentUploaded($event)" :key="componentKey" :id="id"></document-upload>
            </div>
          </div>

        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                type: undefined,
                id: undefined,
                property: '',
                componentKey: 0,
                page: 'folder',
            }
        },
        props: ['properties'],
        methods: {
            setDocuments(type, id, propertyName){
                this.type = type;
                this.id = id;
                this.property = propertyName;
                this.$root.$emit('doc-wizard-stage-3');
            },
            documentUploaded(e){
                this.componentKey += 1;
            }
        },
    }
</script>
