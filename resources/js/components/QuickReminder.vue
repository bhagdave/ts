<template>
    <form @submit.prevent="submit">
        <div class="form-group">
            <label for="property">Property</label>
            <select class="form-control" id="type_id" v-model="fields.type_id" name="type_id">
                <option value="">Select a property</option>
                <option v-for="property in properties" v-bind:value="property.id">{{property.propertyName}}</option>
            </select>
            <input type="date" class="mt-2 form-control" name="start_date" id="date" v-model="fields.start_date"></input>
            <input type="text" placeholder="Reminder text" class="mt-2 form-control" name="reminder" id="reminder" v-model="fields.name"></input>
        </div>

        <button type="submit" class="btn btn-primary">Set reminder</button>
        <div v-if="success" class="alert alert-success mt-3">
            Reminder set!
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
        var that = this;
        if (this.loaded) {
            this.loaded = false;
            this.success = false;
            this.errors = {};
            let newReminder = {
                name: this.fields.name,
                start_date: this.fields.start_date,
                end_date: this.fields.start_date,
                type: 'property',
                type_id: this.fields.type_id,
            };
            axios.post('/reminders/api/add', {
                reminder: newReminder
            }).then(response => {
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
