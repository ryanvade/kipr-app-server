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
    <!-- Token cards -->
    <div class="card" v-for="token in tokens" style="max-width: 200px;">
      <div class="card-image">
        <figure class="image">
          <img :src="token.image" alt="QR Code">
        </figure>
      </div>
      <div class="card-content">
        <div class="media">
          <div class="media-content">
          </div>
        </div>

        <div class="content">
          Created At: {{ token.created_at }}
          <br>
          Expires At: {{ token.expires_at}}
        </div>
        <footer class="card-footer">
          <a href="#" class="card-footer-item is-danger">Delete</a>
        </footer>
      </div>
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
  created() {
    // Get Current Competition
    this.competition = this.$store.state.competition;
    let competition = this.$store.state.competition;
    if(competition == null || (competition.end_data < Date.now())) {
      window.axios.get('/api/competition/current').then((response) => {
        if(response.data.status == "success") {
          const competitions = response.data.competitions;
          if(competitions.length == 1) {
            this.competition = competitions[0];
            this.$store.commit('set_competition', response.data.competitions[0]);
            this.getTokens();
          } else if(competitions.length > 1) {
            // Display Modal to ask for the current competition
          } else {
            // Redirect to Competition Creation page
          }
        }
      }).catch((error) => {
        console.error(error);
      });
    }
  },
  methods: {
    createToken() {
      // Create an Auth Token
      // Get all auth tokens
    },
    getTokens() {
      let id = this.competition.id;
      window.axios.get(`/api/competition/${id}/tokens/judging`).then((result) => {
        console.log(result);
        this.tokens = result.data.tokens;
      }).catch((error) => {
        console.error(error);
      });
    }
  }
}
</script>

<style lang="css">
</style>
