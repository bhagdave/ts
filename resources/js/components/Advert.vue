<script>
export default {
    props: ['ad'],
    methods: {
        recordClick(event){
            // send click through to back end
            axios.post('adverts/recordClick/' + this.ad.id, {});
            // open new tab with link in
            window.open(this.ad.link, '_blank');
        },
    },
};
</script>
<template>
    <div class="justify-content-cente my-3 p-3 ">
        <a v-on:click.prevent="recordClick" v-bind:href="ad.link">
            <img class="d-block m-auto" v-bind:src="ad.image_path" />
        </a>
    </div>
</template>

