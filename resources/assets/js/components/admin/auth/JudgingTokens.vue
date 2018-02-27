<template lang="html">
  <div class="">
    <div class="loader-wrapper" v-if="loading">
      <div class="loader"></div>
    </div>
    <div v-if="!loading">
      <!-- No Tokens Modal -->
      <modal v-if="!loading && tokens.length < 1">
        <div slot="header">
          <strong>No Judging Tokens</strong>
        </div>
        <div slot="body">
          No judging tokens are currently available.
        </div>
        <div class="missing-competition-modal" slot="footer">
          <router-link class="button is-info" :to="{ name: 'index', params: {} }">Go Home</router-link>
          <button @click="createToken" class="button is-primary">Create</button>
        </div>
      </modal>
      <!-- Token cards -->
      <div class="qr-codes" v-show="tokens.length > 0">
        <nav class="level">
          <div class="level-left">
            <div class="level-item">
              <p class="subtitle is-5">
                <strong>{{ tokens.length }}</strong> Judging Tokens
              </p>
            </div>
            <div class="level-item">
              <p class="has-text-grey">
                Click on a QR Code to enable judging.
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
              <a class="card-footer-item is-danger delete-button" @click="deleteToken(token)">Delete</a>
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
            <div class="subtitle">
              Scan the QR Code with the KIPR Mobile App to enable judging.
            </div>
          </div>
        </modal>
      </div>
    </div>
    <!-- No Competition Modal -->
    <div class="no-competition-modal-wrapper">
      <modal v-if="showNoCompetitions">
        <div class="" slot="header">
          <strong>Missing Competition</strong>
        </div>
        <div class="" slot="body">
          <div class="">
            Please create a competition or <a class="" @click="reloadPage">reload the page</a>.
          </div>
          <div class="">
            You may also <router-link class="" :to="{ name: 'index', params: {} }" exact>go back to the home page</router-link>.
          </div>
        </div>
        <div class="missing-competition-modal" slot="footer">
          <router-link class="button is-primary" :to="{ name: 'create_competition', params: {} }">Create Competition</router-link>
        </div>
      </modal>
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
      showNoCompetitions: false,
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
    if (comp == null || (window.moment(comp.end_time).unix() < window.moment().local().unix())) {
      console.log("Getting Current Competitions");
      this.getCurrentCompetition();
    } else {
      console.log("Store competition is not null");
      this.competition = comp;
    }
  },
  methods: {
    createToken() {
      // Create an Auth Token
      // Get all auth tokens
      const params = {
        name: window.user.name + ' Judging ' + (this.tokens.length + 1),
        scopes: ['judging']
      };
      window.axios.post('/oauth/personal-access-tokens', params).then((response) => {
        console.log(response);
        this.loading = true;
        this.getTokens();
        window.notification("success", "Authentication Token Created");
      }).catch((error) => {
        console.error(error);
        window.notification("danger", error.message);
      });
    },
    deleteToken(token) {
      let id = token.id;
      let self = this;
      console.log('/oauth/personal-access-tokens/' + id);
      window.axios.delete('/oauth/personal-access-tokens/' + id).then((response) => {
        console.log(response);
        self.loading = true;
        self.getTokens();
        window.notification("success", "Authentication Token Deleted");
      }).catch((error) => {
        console.error(error);
        window.notification("danger", error.message);
      });
    },
    reloadPage() {
      window.location.href = window.location.href;
    },
    prettyDate(date) {
      return window.moment(date).local().format('LLL');
    },
    fromNow(date) {
      return window.moment(date).local().fromNow();
    },
    getTokens() {
      let id = this.competition.id;
      this.tokens = [];
      let self = this;
      console.log("Getting competition tokens");
      window.axios.get(`/api/competition/${id}/tokens/judging`).then((result) => {
        console.log(result);
        result.data.tokens.forEach((token) => {
          if (token.revoked == false && token.scopes.includes('judging')) {
            self.tokens.push(token);
          }
        });
        this.loading = false;
      }).catch((error) => {
        console.error(error);
        // The current competition has been deleted for some reason...
        if(error.response.status == 404) {
          console.log("Response is 404, setting store competition to null");
          self.$store.commit('set_competition', null);
          // reloading page now...
          this.$router.push('/admin/tokens/judging');
        }
      });
    },
    getCurrentCompetition() {
      let self = this;
      window.axios.get('/api/competition/current').then((result) => {
        console.log(result);
        let comps = result.data.competitions;
        if (comps.length == 1) {
          self.competition = comps[0];
          self.$store.commit('set_competition', self.competition);
        } else if (comps.length > 1) {
          // Display 'Choose Current Competition' modal
        } else {
          // Display 'No Competitions, please make one' modal
          self.showNoCompetitions = true;
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
</style>
