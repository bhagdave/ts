<template>
    <div>
        <button v-on:click="toggle">
            <small v-if="show" class="text-muted">Hide <span class="oi oi-chevron-bottom"></span></small>
            <small v-else class="text-muted">Properties <span class="oi oi-chevron-top"></span></small>
        </button>
        <transition name="slide-fade">
            <div v-if="show" class="">
                <div class=" bg-white rounded shadow-sm pb-3 ">
                    <div class="pb-1 mb-0">
                        <div class="row no-gutters align-items-center p-2">
                            <div class="col-auto">
                                <i class="fas fa-search h4 text-body"></i>
                            </div> 
                            <div class="col">
                                <input type="search" v-model="searchTerm" placeholder="Find a Property" class="form-control form-control form-control-borderless">
                            </div> 
                            <div class="col-auto"></div>
                        </div>

                        <vue-good-table
                            :pagination-options="{
                                enabled: false,
                                nextLabel: '',
                                prevLabel: '',
                                rowsPerPageLabel: 'Rows Per Page',
                                ofLabel: 'of',
                              }"
                            :rows="rows"
                            max-height="700px"
                            :columns="columns"
                            :search-options="{
                                enabled: true,
                                externalQuery: searchTerm
                            }" 
                            styleClass="vgt-table property">
                            <div slot="emptystate">
                                <div class="text-center" v-if="!$wait.waiting('searchProperties')">
                                    <h5 class="font-weight-light pt-2">Property Not Found</h5>
                                </div>
                                <div class="text-center" v-if="$wait.waiting('searchProperties')">
                                    <h5 class="font-weight-light pt-2">Loading Properties</h5>
                                </div>
                            </div>
                            <template slot="table-row" slot-scope="props">
                                <span v-if="props.column.field == 'profileImage'">
                                    <div class="row no-gutters pt-1 media-body">
                                        <div class="col-3">
                                            <div v-if="props.row.profileImage">
                                                <img :src="props.row.profileImage" width="40" height="40" class="rounded-circle mr-3">
                                            </div>
                                            <div v-else style="width: 40px;height: 40px;" class="rounded-circle mr-3 bg-dark" >
                                                <svg data-v-1084b650="" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300 300">
                                                    <rect data-v-1084b650="" fill="#fff" fill-opacity="0.0" x="0" y="0" width="300px" height="300px" class="logo-background-square"></rect>
                                                    <g data-v-1084b650="" id="aaf6eb69-4e66-8918-4040-caa36d392423" fill="#fff" stroke="none" transform="matrix(0.7999999999999998,0,0,0.7999999999999998,25.894802856445338,30.00000000000003)">
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
                                                            <path d="M67.899,23.977l0.007-0.011L35.056,5L13.443,43.155l5.19,2.997v29.88l32.617,18.831l0.055,0.093l0.056-0.027  L51.484,95v-0.141l12.481-6.832V77.509l8.9,5.138l11.6-6.131V40.641l5.513-3.184L67.899,23.977z M83.791,40.251v35.874  l-10.925,5.759V58.207l-9.562,5.521v6.314h-0.01V87.64l-11.81,6.448V59.125l-5.197,2.997l21.28-37.557l21.09,12.876L83.791,40.251z">
                                                            </path>
                                                        </svg>
                                                    </g>
                                                </svg>
                                            </div>
                                        </div>

                                        <div class="col-9">
                                            <a :href="'/propertyissues/'+props.row.id">
                                                <strong>{{props.row.propertyName}}</strong>
                                            </a>
                                            <p>{{props.row.inputAddress}}</p>
                                        </div>
                                    </div>
                                </span>
                                <span v-else>
                                    {{props.formattedRow[props.column.field]}}
                                </span>
                            </template>
                        </vue-good-table>
                    </div>
                </div>
            </div>
        </transition>
    </div> 
</template>

<script>
export default {
    data(){
        return {  
            searchTerm: "",
            columns: [
                { label: 'profileImage', field: 'profileImage'},
                { label: 'propertyName', field: 'propertyName', hidden: true},
                { label: 'inputPostCode',field: 'inputPostCode',hidden: true},
                { label: 'id',           field: 'id',           hidden: true},
                { label: 'inputAddress', field: 'inputAddress', hidden: true},
                { label: 'inputAddress2',field: 'inputAddress2',hidden: true},
                { label: 'inputCity',    field: 'inputCity',    hidden: true},
            ],
            rows: [],
            show: true,
        };
    },
    methods: {
        toggle: function() {
            this.show = !this.show;
            this.$root.$emit('property-table-hide');
        },
    },
    async mounted(){
        this.$wait.start('searchProperties');
        await axios.get('/property/data').then(response => {
            this.rows = response.data.data;
            if(window.location.pathname.split('/')[2]){
                this.searchTerm = "";
            }
        });
        this.$wait.end('searchProperties');
    },
};
</script>
<style>
.slide-fade-enter-active {
  transition: all .3s ease;
}
.slide-fade-leave-active {
  transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.slide-fade-enter, .slide-fade-leave-to {
  transform: translatey(50px);
  opacity: 0;
}
</style>
