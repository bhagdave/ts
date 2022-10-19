<template>
    <div class="my-3 p-3 border-right border-info bg-white rounded shadow-sm">
        <div class="d-flex align-items-center justify-content-between">
            <h4>Tenants</h4>
            <small v-on:click="toggle" v-if="show" class="text-muted"><span class="oi oi-chevron-top"></span></small>
            <small v-on:click="toggle" v-else class="text-muted"><span class="oi oi-chevron-bottom"></span></small>
        </div>
        <transition name="slide-fade">
            <div v-if="show" class="">
                <div class="bg-white rounded shadow-sm pb-3 ">
                    <div class="pb-1 mb-0 d-flex">
                        <a href="/tenants?filter=pending" class="text-center btn btn-outline-primary btn-block mt-2 mr-1">
                            <h3>{{pendingTenants}}</h3>
                            <small>Pending</small>
                        </a>
                        <a href="/tenants?filter=confirmed" class="text-center btn btn-outline-primary btn-block mt-2 mr-1">
                            <h3>{{confirmedTenants}}</h3>
                            <small>Tenants</small>
                        </a>
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
            show: true,
            pendingTenants: 0,
            confirmedTenants: 0,
        };
    },
    methods: {
        toggle: function() {
            this.show = !this.show;
        },
    },
    async mounted(){
        this.$wait.start('getCounts');
        await axios.get('/getTenantCounts').then(response => {
            if (response.data !== 'Err') {
                this.pendingTenants = response.data.pendingTenants;
                this.confirmedTenants = response.data.confirmedTenants;
            }
        });
        this.$wait.end('getCounts');
    },
};
</script>
<style>
.slide-fade-enter-active {
  transition: all .2s ease;
}
.slide-fade-leave-active {
  transition: all .5s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.slide-fade-enter, .slide-fade-leave-to {
  transform: translatey(50px);
  opacity: 0;
}
</style>
