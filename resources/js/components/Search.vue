<template>
    <div>
      <div class="input-group">
       <input type="search" class="form-control" placeholder="Search" v-model="query">  
     </div>

     <template>
         <small v-if="$wait.waiting('search')">Loading</small>
    </template>

      <div v-if="results.length > 0 && query" class="overflow-auto pl-2" style="max-height:300px">
        <span v-for="result in results.slice(0,10)" :key="result.id">
          <div class="row pb-2 pt-2 pl-2 mb-0">


            <span class="" v-if="result.type === 'tenants'"><span class="oi oi-people pr-2"></span></span> 
             <span class="" v-if="result.type === 'landlords'"><span class="oi oi-person pr-2"></span></span> 
              <span class="" v-if="result.type === 'properties'"><span class="oi oi-home pr-2"></span></span> 
           
            <a :href="result.url">  <span class="text-dark text-decoration-none" v-text="result.title"></span>
            </a>
           </div>
        </span>
      </div>
      </div>
</template>

<script>
export default {
  data() {
  return {
    query: null,
    results: []
  };
},
watch: {
  query(after, before) {
    this.search();
  }
},
methods: {
  async search() {
    this.$wait.start('search');
    this.results = [];
    await axios.get('/search/data', { params: { query: this.query } })
    .then(response => this.results = response.data)
    .catch(error => {});
     this.$wait.end('search');
  }
}
}
</script>