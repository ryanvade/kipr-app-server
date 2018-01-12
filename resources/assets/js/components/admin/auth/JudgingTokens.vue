<template lang="html">
  <div class="">
    <div class="loader-wrapper" v-if="loading">
      <div class="loader"></div>
    </div>
    <div v-if="!loading">
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
      <div class="qr-codes" v-show="tokens.length > 0">
        <nav class="level">
          <div class="level-left">
            <div class="level-item">
              <p class="subtitle is-5">
                <strong>{{ tokens.length }}</strong> Judging Tokens
              </p>
            </div>
          </div>
          <div class="level-right">
            <p class="level-item"><a class="button is-primary" @click="createToken">Create</a></p>
          </div>
        </nav>
        <div class="card" v-for="token in tokens">
          <div class="card-image">
            <figure class="image">
              <img class="qr-token" @click="displayToken(token)" :src="token.image" alt="QR Code">
            </figure>
          </div>
          <div class="card-content">
            <div class="media">
              <div class="media-content">
              </div>
            </div>

            <div class="content">
              <div class="is-6"><strong>Created</strong></div>
              {{ prettyDate(token.created_at) }}
              <div class="is-6"><strong>Expires</strong></div>
              {{ prettyDate(token.expires_at) }}
            </div>
            <footer class="card-footer">
              <a href="#" class="card-footer-item is-danger">Delete</a>
            </footer>
          </div>
        </div>
      </div>
      <!-- Token Modal -->
      <div class="token-modal-wrapper">
        <modal v-if="showToken" v-on:close="showToken = false">
          <div class="" slot="header">
            <div class="is-5"><strong>Expires</strong> {{ fromNow(token.expires_at) }}</div>
          </div>
          <div class="" slot="body">
            <img :src="token.image" alt="Judging Token" class="large-qr-code">
          </div>
        </modal>
      </div>
    </div>
  </div>
</template>

<script>
import Modal from '../../Modal.vue';
export default {
  components: {
    'modal': Modal,
  },
  data() {
    return {
      competition: null,
      tokens: [],
      token: null,
      showToken: false,
      loading: true
    };
  },
  watch: {
    competition() {
      this.getTokens();
    }
  },
  created() {
    console.log("Judging Tokens Mounted");
    let comp = this.$store.state.competition;
    if(comp == null || (window.moment(comp.end_time).unix() < window.moment().local().unix())) {
      console.log("Getting Current Competitions");
      this.getCurrentCompetition();
    }else {
      console.log("Store competition is not null");
      this.competition = comp;
    }
  },
  methods: {
    createToken() {
      // Create an Auth Token
      // Get all auth tokens
    },
    prettyDate(date) {
      return window.moment(date).local().format('LLL');
    },
    fromNow(date) {
      return window.moment(date).local().fromNow();
    },
    getTokens() {
      let id = this.competition.id;
      window.axios.get(`/api/competition/${id}/tokens/judging`).then((result) => {
        console.log(result);
        this.tokens = result.data.tokens;
        this.loading = false;
      }).catch((error) => {
        console.error(error);
      });
    },
    getCurrentCompetition() {
      let self = this;
      window.axios.get('/api/competition/current').then((result) => {
        console.log(result);
        let comps = result.data.competitions;
        if(comps.length == 1) {
          self.competition = comps[0];
          self.$store.commit('set_competition', self.competition);
        }else if(comps.length > 1) {
          // Display 'Choose Current Competition' modal
        }else {
          // Display 'No Competitions, please make one' modal
        }
      }).catch((error) => {
        console.error(error);
      });
    },
    displayToken(token) {
      console.log(token.id);
      this.token = token;
      this.showToken = true;
    }
  }
}
</script>

<style lang="css">
.qr-codes .card {
  max-width: 200px!important;
}

.qr-codes .level {
  background: white;
  padding: 15px;
}

.qr-token {
  cursor: pointer;
  max-width: 200px!important;
}

.qr-token:hover {
  filter: brightness(95%);
}

.token-modal-wrapper .modal-container {
    width: 50vw;
    height: 70vh;
  }

.large-qr-code {
  display: block;
  margin: 0 auto;
}

.loader-wrapper {
  height: 90vh;
  padding-top: 29vh;
  padding-left: 20vw;
}
</style>
