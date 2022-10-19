<template>
    <form @submit.prevent="submit">
        <table>
            <tr>
                <td><label for="name">Name</label></td>
                <td><label for="email">Email</label></td>
            </tr>
            <tr v-for="(row, n) in rows">
                <td class="form-group pt-2">
                    <input type="text" v-model="row.name" :key="n" name='name[]' class="form-control" id="name" aria-describedby="nameHelp" placeholder="Alex Example" required>
                </td>

                <td class="form-group pt-2 pl-2">
                    <input type="email" v-model="row.email" :key="n" name='email[]' class="form-control" id="email" aria-describedby="emailHelp" required>
                </td>
            </tr>
        </table>
        <button class="btn mt-2" @click="addRow">
            <span class="oi oi-plus pt-1 pr-2" style="float: left"> 
            </span>
                Another Colleague
        </button>
        <hr>
        <button type="submit" class="btn btn-primary">Send Invites</button>
        <div v-if="success" class="alert alert-success mt-3">
            Invites sent!
        </div>
        <div v-if="error" class="alert alert-success mt-3">
            There was a problem sending the invites. Please change and try again.
        </div>
    </form>
</template>
<script>
  export default {
      data() {
        return {
            rows: [{name:'', email:''}],
            success: false,
            loaded: true,
            error: false,
        }
      },
      methods: {
          addRow: function() {
              this.rows.push({ name: '', email: ''});
          },
          submit: function() {
              if (this.loaded) {
                  this.loaded = false;
                  this.success = false;
                  this.error = false;
                  axios.post('/invite/user', this.rows).then(response => {
                      this.rows = [{name: "", email: ""}]; //Clear input fields.
                      this.loaded = true;
                      this.success = true;
                  }).catch(error => {
                      this.loaded = true;
                      if (error.response.status === 422) {
                          this.error = true;
                      }
                  });
              }
          }
      }
  };
</script>
<style>
table, tbody {
    width: 100%;
}
</style>
