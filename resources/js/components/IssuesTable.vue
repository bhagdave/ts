<template>
    <div v-bind:class="{'col-sm-12 col-md-9': narrow  , 'col-sm-12 col-md-12': usertype == 'Tenant' || narrow == false  }">
        <div class="my-3 bg-white rounded shadow-sm">
            <div class="border-bottom border-gray mb-0">
                <div class="vgt-global-search__input pt-2 pr-2">
                    <span class="input__icon"><div class="magnifying-glass"></div></span>
                    <input type="text"  v-model="searchTerm" class="vgt-input" placeholder="Search and Filter Issues">
                </div>
                <vue-good-table
                    :pagination-options="{
                        enabled: true,
                    }"
                    :rows="rows"
                    max-height="700px"
                    :fixed-header="false"
                    :columns="columns"
                    :sort-options="{
                        enabled: true,
                        initialSortBy: [
                            {field: 'created_at', type: 'desc'}, 
                            {field: 'attributes', type: 'asc'}]
                    ,}"
                    :search-options="{
                        enabled: true,
                        externalQuery: searchTerm,
                        placeholder: 'Search and Filter Issues',
                    }" 
                styleClass="vgt-table">
                    <template slot="table-row" slot-scope="props">
                        <span v-if="props.column.field == 'attributes'">
                             {{props.formattedRow[props.column.field]}} 
                             <a :href="'/issue/' +props.row.id" class="float-right">
                                <span class="oi oi-pencil"></span>
                             </a>
                        </span>
                        <span v-else-if="props.column.field == 'property'">
                            {{props.formattedRow[props.column.field]}}
                        </span>
                        <span v-else-if="props.column.field == 'created_at'">
                            {{props.row[props.column.field] | moment("MMMM Do YYYY")}}
                        </span>
                        <span v-else v-html="props.formattedRow[props.column.field]">
                        </span>

                    </template>
                </vue-good-table>
                <small class="d-block text-right py-3 pr-3">
                    <div class="btn btn-small" v-on:click="save">Export</div>
                </small>
            </div> 
        </div>
    </div>
</template>

<script>
export default {
    props: {
        filter : {
            type: String,
            dafult: null
        },
        propertyid : {
            type: String,
            default: null
        },
        usertype: {
            type: String,
            default: null
        },
    },
    data() {
        return {
            show_entries: "",
            currentPage: 1,
            startIndex: 0,
            endIndex: 10,
            perPage: 10,
            searchTerm: "",
            columns: [{
                    label: 'Property',
                    field: 'property',
                },
                {
                    label: 'Title',
                    field: 'extra_attributes.title',
                },
                {
                    label: 'Created',
                    field: 'created_at',
                    type: 'date',
                },
                {
                    label: 'Status',
                    field: 'attributes',
                },
            ],
            rows: [],
            search: '',
            narrow: false,
        };
    },
    async mounted() {
        var that = this;
        var path = window.location.pathname;
        if (path == '/issues'){
            this.narrow = true;
        }
        this.$root.$on('property-table-hide', function(){
            that.narrow = !that.narrow; 
            that.changeWidth();
        });
        if (this.filter){
            this.searchTerm = this.filter;
            this.search = this.filter;
        }
        this.$wait.start('searchIssues');
        var url = '/getIssues';
        if (this.propertyid !== null){
            url = '/getIssues/' + this.propertyid;
        }
        await axios.get(url).then(response => {
            this.rows = response.data;
        });
        this.$wait.end('searchIssues');
    },
    methods: {
        pagination: function(activePage) {
            this.currentPage = activePage;
            this.startIndex = (this.currentPage * 10) - 10;
            this.endIndex = this.startIndex + 10;
        },
        previous: function() {
            if (this.currentPage > 1) {
                this.pagination(this.currentPage - 1);
            }

        },
        nextCategory: function() {
            if (this.currentPage < this.totalCategory) {
                this.pagination(this.currentPage + 1);
            }
        },
        save: function() {
            var url = '/exportIssues';
            if (this.propertyid !== null){
                url = '/exportIssues/' + this.propertyid;
            }
            window.open(url);
        },
        changeWidth: function(){
            this.narrow = !this.narrow; 
        }
    },
    computed: {
        filteredRows() {
          return this.rows.filter(row => {
              return row.property.toLowerCase().includes(this.search.toLowerCase()) ||
                  row.description.toLowerCase().includes(this.search.toLowerCase())
          })
        },

        totalCategory: function() {
            return Math.ceil(this.rows.length / this.perPage)
        }
    }

};
</script>
