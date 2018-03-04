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
                Click on a QR Code to enable judging for {{ competition.name }}
              </p>
            </div>
          </div>
          <div class="level-right">
            <p class="level-item"><a class="button is-info" @click="askForCompetitions = true; loading = true; tokens = []; competition = null;">Choose a different competition</a></P>
            <p class="level-item"><a class="button is-primary" @click="createToken">Create</a></p>
          </div>
        </nav>
        <!-- Display the QR Tokens -->
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
      <modal v-if="askForCompetitions">
        <div class="" slot="header">
          <strong>Choose A Competition</strong>
        </div>
        <div class="" slot="body">
          <p>Click on a competition to choose it</p>
          <table class="table is-narrow is-hoverable">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Location</th>
                <!-- <th>Start Date</th>
                <th>End Date</th> -->
              </tr>
            </thead>
            <tbody>
              <tr v-for="comp in competitions" @click="chooseCompetition(comp)" class="chooser-row">
                <td>{{ comp.id }}</td>
                <td>{{ comp.name }}</td>
                <td>{{ comp.location }}</td>
                <!-- <td>{{ comp.start_date }}</td>
                <td>{{ comp.end_date }}</td> -->
              </tr>
            </tbody>
          </table>
          <button type="button" class="button" v-if="page > 1" @click="previousPage"><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
          <button type="button" class="button" v-if="page < maxPages" @click="nextPage"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
        </div>
        <div class="missing-competition-modal" slot="footer">
          <router-link class="button is-warning" :to="{ path: '/admin' }">Cancel</router-link>
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
      competitions: [],
      tokens: [],
      token: null,
      showToken: false,
      askForCompetitions: true,
      loading: true,
      page: 1,
      maxPages: 1
    };
  },
  watch: {
    competition() {
      this.getTokens();
    }
  },
  created() {
    // Get all competitions
    this.getCompetitions();
  },
  methods: {
    /**
     * Create Token - Perform an AJAX request to create a new Judging token
     * @return {void}
     */
    createToken() {
      // Create an Auth Token
      // Get all auth tokens
      const params = {
        name: "Competition " + this.competition.id + ' Judging #' + (this.tokens.length + 1),
        scopes: ['judging']
      };
      window.axios.post('/oauth/personal-access-tokens', params).then((response) => {
        console.log(response);
        this.loading = true;
        this.getTokens();
        window.notification("success", "Authentication Token Created");
      }).catch((error) => {
        console.error(error);
        if (error.response.status == 401) {
          // redirect to login page
          window.notification("warning", "You have been logged out due to inactivity.");
          document.cookie = "notification=danger|You have been logged out due to inactivity";
          window.location.href = "/login";
        }
        window.notification("danger", error.message);
      });
    },
    /**
     * Delete Token - Perform an AJAX request to delete the specified token
     * @param  {Token} token token to delete
     * @return {void}
     */
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
        if (error.response.status == 401) {
          // redirect to login page
          window.notification("warning", "You have been logged out due to inactivity.");
          document.cookie = "notification=danger|You have been logged out due to inactivity";
          window.location.href = "/login";
        }
        window.notification("danger", error.message);
      });
    },
    /**
     * Reload the page
     * @return {void}
     */
    reloadPage() {
      window.location.href = window.location.href;
    },
    /**
     * Pretty Date - Format a given date to look good
     * @param  {String|Moment} date date to format
     * @return {String}             Formated Date String
     */
    prettyDate(date) {
      return window.moment(date).local().format('LLL');
    },
    /**
     * From Now - Get the amount of time from now until the specified Date
     *            in a pretty format
     * @param  {String|Moment} date date to format
     * @return {String}             Time From Now
     */
    fromNow(date) {
      return window.moment(date).local().fromNow();
    },
    /**
     * Get Tokens - Perform an AJAX request to get all of the Judging auth
     *              token for the current user
     * @return {void}
     */
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
        if (error.response.status == 401) {
          // redirect to login page
          window.notification("warning", "You have been logged out due to inactivity.");
          document.cookie = "notification=danger|You have been logged out due to inactivity";
          window.location.href = "/login";
        }
        // The current competition has been deleted for some reason...
        if (error.response.status == 404) {
          console.log("Response is 404, setting store competition to null");
          self.$store.commit('set_competition', null);
          // reloading page now...
          this.$router.push('/admin/tokens/judging');
        }
      });
    },
    async getCompetitions() {
      window.axios.get(`/api/competition?page=${this.page}`).then((response) => {
        this.competitions = response.data.data;
        this.maxPages = response.last_page;
      }).catch((error) => {
        console.error(error);
        window.notification("danger", error.message);
      });
    },
    /**
     * Display Token - Sets the current token and displays it in a modal
     * @param  {Token} token Token to display
     * @return {void}
     */
    displayToken(token) {
      console.log(token.id);
      this.token = token;
      this.showToken = true;
    },
    chooseCompetition(comp) {
      this.competition = comp;
      console.log(comp);
      this.askForCompetitions = false;
      this.loading = false;
    },
    previousPage() {
      this.page = this.page - 1;
      this.getCompetitions();
    },
    nextPage() {
      this.page = this.page + 1;
      this.getCompetitions();
    }
  }
}
</script>

<style lang="css">
.chooser-row {
  cursor: pointer;
}
</style>
