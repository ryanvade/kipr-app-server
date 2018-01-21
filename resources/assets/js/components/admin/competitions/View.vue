<template lang="html">
<div class="competition-page">
  <div class="loader-wrapper" v-if="loading">
    <div class="loader"></div>
  </div>
  <div class="" v-if="!loading">
    <div class="card">
      <header class="card-header">
        <p class="card-header-title">
          {{ competition.name }}
        </p>
      </header>
      <div class="card-content">
        <div class="content">
          <p>Location: {{ competition.location }}</p>
          <p>Start Date: {{ prettyDate(competition.start_date) }}</p>
          <p>End Date: {{ prettyDate(competition.end_date) }}</p>
        </div>
      </div>
      <footer class="card-footer">
        <a class="card-footer-item">Edit</a>
        <a class="card-footer-item is-danger" @click="showWarning = true">Delete</a>
      </footer>
    </div>
    <!-- Warning Modal -->
    <div class="delete-competition-modal-wrapper">
      <modal v-if="showWarning" v-on:close="showWarning = false">
        <div class="" slot="header">
          Delete Competition
        </div>
        <div class="" slot="body">
          <div class="has-text-weight-bold">Are you sure you wish to delete {{ competition.name }}?</div>
          <p class="is-5 is-italic">
            All related match information will also be deleted. Rulesets and teams will
            be unaffected.
          </p>
        </div>
        <div class="buttons has-addons is-centered" slot="footer">
          <button class="button is-outlined" @click="showWarning = false" style="width: 50%;">Cancel</button>
          <button class="button is-danger is-outlined" style="width: 50%;" @click="submitDelete">Delete</button>
        </div>
      </modal>
    </div>
  </div>
  <!-- Missing Competition Modal -->
  <div class="missing-competition-modal">
    <modal v-if="showMissingCompetition">
      <div class="" slot="header">
        Unknown Competition
      </div>
      <div class="" slot="body">
        <div class="has-text-weight-bold">Cannot find competition with id {{ $route.params.id }}.</div>
      </div>
      <div class="buttons has-addons is-centered" slot="footer">
        <button class="button is-outlined" @click="$router.push('/admin/')" style="width: 50%;">Admin Home</button>
        <button class="button is-outlined" style="width: 50%;" @click="$router.push('/admin/competitions')">View All Competitions</button>
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
      loading: true,
      showWarning: false,
      showMissingCompetition: false,
    };
  },
  mounted() {
    let id = this.$route.params.id;
    this.getCompetition(id);
  },
  methods: {
    getCompetition(id) {
      window.axios.get(`/api/competition/${id}`).then((response) => {
        console.log(response);
        this.competition = response.data;
        this.loading = false;
      }).catch((error) => {
        console.error(error);
        if(error.response.status == 404) {
          this.showMissingCompetition = true;
        }
      });
    },
    prettyDate(date) {
      return moment(date).format('M/D/YYYY h:mmA');;
    },
    submitDelete() {
      let id = this.competition.id;
      window.axios.delete(`/api/competition/${id}`).then((response) => {
        this.$router.push('/admin/competitions');
      }).catch((error) => {
        console.error(error);
        this.$router.push('/admin/competitions');
      });
    }
  }
}
</script>

<style lang="css">
</style>
