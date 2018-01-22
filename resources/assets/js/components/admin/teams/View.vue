<template lang="html">
<div class="team-page">
  <div class="loader-wrapper" v-if="loading">
    <div class="loader"></div>
  </div>
  <div class="" v-if="!loading">
    <div class="card">
      <header class="card-header">
        <p class="card-header-title">
          {{ team.name }}
        </p>
      </header>
      <div class="card-content">
        <div class="content">
          <p>Created On: {{ prettyDate(team.created_at) }}</p>
          <p>Email: {{ team.email }}</p>
          <p>Code: {{ team.code }}</p>
        </div>
      </div>
      <footer class="card-footer">
        <a class="card-footer-item" @click="$router.push('/admin/teams/' + team.id + '/edit')">Edit</a>
        <a class="card-footer-item is-danger" @click="showWarning = true">Delete</a>
      </footer>
    </div>
    <!-- Warning Modal -->
    <div class="delete-team-modal-wrapper">
      <modal v-if="showWarning" v-on:close="showWarning = false">
        <div class="" slot="header">
          Delete Team
        </div>
        <div class="" slot="body">
          <div class="has-text-weight-bold">Are you sure you wish to delete {{ team.name }}?</div>
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
  <!-- Missing Team Modal -->
  <div class="missing-team-modal">
    <modal v-if="showMissingTeam">
      <div class="" slot="header">
        Unknown Team
      </div>
      <div class="" slot="body">
        <div class="has-text-weight-bold">Cannot find team with id {{ $route.params.id }}.</div>
      </div>
      <div class="buttons has-addons is-centered" slot="footer">
        <button class="button is-outlined" @click="$router.push('/admin/')" style="width: 50%;">Admin Home</button>
        <button class="button is-outlined" style="width: 50%;" @click="$router.push('/admin/teams')">View All Teams</button>
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
      team: null,
      loading: true,
      showWarning: false,
      showMissingTeam: false,
    };
  },
  mounted() {
    let id = this.$route.params.id;
    this.getTeam(id);
  },
  methods: {
    getTeam(id) {
      window.axios.get(`/api/team/${id}`).then((response) => {
        console.log(response);
        this.team = response.data;
        this.loading = false;
      }).catch((error) => {
        console.error(error);
        if(error.response.status == 404) {
          this.showMissingTeam = true;
        }
      });
    },
    prettyDate(date) {
      return moment(date).format('M/D/YYYY h:mmA');;
    },
    submitDelete() {
      let id = this.team.id;
      window.axios.delete(`/api/team/${id}`).then((response) => {
        this.$router.push('/admin/teams');
      }).catch((error) => {
        console.error(error);
        this.$router.push('/admin/teams');
      });
    }
  }
}
</script>

<style lang="css">
</style>
