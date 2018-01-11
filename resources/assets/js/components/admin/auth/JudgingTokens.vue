<template lang="html">
  <div class="">
      <div class="no-tokens-wrapper" v-if="tokens.length < 1">
        <div class="card">
          <header class="card-header">
            <p class="card-header-title">
              Judging Tokens
            </p>
          </header>
          <div class="card-content">
            <h3>No judging tokens currently available. Click the button below to create one.</h3>
          </div>
          <footer class="card-footer">
            <a @click="createToken" class="card-footer-item">Create</a>
          </footer>
        </div>
      </div>
    <div v-for="token in tokens" class="card">
      <header class="card-header">
        <p class="card-header-title">
          Created on {{ token.created_at }}
        </p>
      </header>
      <div class="card-content">
        <div class="content">
          {{ token.id }}

        </div>
      </div>
      <footer class="card-footer">
        <a href="#" class="card-footer-item">Get QR code</a>
        <a href="#" class="card-footer-item is-danger">Delete</a>
      </footer>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      competition: {},
      tokens: []
    };
  },
  mounted() {
    // Get Current Competition
    this.competition = this.$store.state.competition;
    if(this.competition != null) {
      // Get the Auth Token QR Codes for the competition
      window.axios.get('/api/competition/' + this.competition.id + '/auth/tokens').then((results) => {
        console.log(results);
      }).catch((error) => {
        console.error(error);
      });
    }
  },
  methods: {
    createToken() {
      // Create an Auth Token
      // Get all auth tokens
    }
  }
}
</script>

<style lang="css">
</style>
