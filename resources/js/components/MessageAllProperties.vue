<template>
    <form @submit.prevent="submit">
        <div class="form-group">
            <label for="messageall">Message all properties</label>
            <textarea class="form-control" name="messageall" id="messageall" rows="2" v-model="fields.message"></textarea>
            <div v-if="errors && errors.message" class="text-danger">{{ errors.message[0] }}</div>
        </div>

        <button type="submit" class="btn btn-primary">Send to all</button>
        <div v-if="success" class="alert alert-success mt-3">
            Message sent!
        </div>
    </form>
</template>
<script>
export default {
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
            axios.post('/sendMessageToAllProperties', this.fields).then(response => {
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
