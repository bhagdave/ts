<template>
    <form @submit.prevent="submit">
        <div class="form-group">
            <label for="message">Message a property</label>
            <select id="stream" v-model="fields.property" name="stream">
                <option value="">Select a property</option>
                <option v-for="property in properties" v-bind:value="property.id">{{property.propertyName}}</option>
            </select>
            <textarea class="form-control" name="message" id="message" rows="2" v-model="fields.message"></textarea>
            <div v-if="errors && errors.message" class="text-danger">{{ errors.message[0] }}</div>
        </div>

        <button type="submit" class="btn btn-primary">Send message</button>
        <div v-if="success" class="alert alert-success mt-3">
            Message sent!
        </div>
    </form>
</template>
<script>
export default {
  props: ['properties'],
  data() {
    return {
      fields: {},
      errors: {},
      success: false,
      loaded: true,        
    }
  },
  methods: {
    submit() {
        if (this.loaded) {
            this.loaded = false;
            this.success = false;
            this.errors = {};
            axios.post('/sendMessageToProperty', this.fields).then(response => {
                this.fields = {}; //Clear input fields.
                this.loaded = true;
                this.success = true;
            }).catch(error => {
                this.loaded = true;
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
            });
        }
    },      
  },
}
</script>
