<template lang="html">
<div class="team-page">
  <div class="loader-wrapper" v-if="loading">
    <div class="loader"></div>
  </div>
  <div class="" v-if="!loading">

    <nav class="level">
      <div class="level-left">
        <p class="subtitle has-text-centered">
          <strong>{{ team.name }}</strong> {{ team.code }}
        </p>
      </div>
      <div class="level-right">
        <p class="level-item">
          <a class="card-footer-item" id="edit" @click="$router.push('/admin/teams/' + team.id + '/competitions/register')">Register with Competitions</a>
        </p>
        <p class="level-item">
          <a class="card-footer-item" id="edit" @click="$router.push('/admin/teams/' + team.id + '/edit')">Edit</a>
        </p>
        <p class="level-item">
          <a class="card-footer-item is-danger" id="delete" @click="showWarning = true">Delete</a>
        </p>
      </div>
    </nav>

    <div class="box">
      <p class="is-size-5 is-grey">
        <strong>Created On:</strong> {{ prettyDate(team.created_at) }}
        <strong>Email:</strong> {{ team.email }}
      </p>
      <h3>Registered Competitions:</h3>
      <table class="table is-hoverable is-fullwidth">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Locatin</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="comp in competitions">
            <td>{{ comp.competition_id }}</td>
            <td>{{ comp.name }}</td>
            <td>{{ comp.location }}</td>
            <td>{{ prettyDate(comp.start_date) }}</td>
            <td>{{ prettyDate(comp.end_date) }}</td>
            <td><button type="button" class="button is-danger" @click="deregister(comp)">Un-Register</button></td>
          </tr>
        </tbody>
      </table>
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
      competitions: [],
      showWarning: false,
      showMissingTeam: false,
    };
  },
  mounted() {
    let id = this.$route.params.id;
    this.getTeam(id);
    this.getRegisteredCompetitions(id);
  },
  methods: {
    getTeam(id) {
      window.axios.get(`/api/team/${id}`).then((response) => {
        console.log(response);
        this.team = response.data;
        this.loading = false;
      }).catch((error) => {
        console.error(error);
        if (error.response.status == 401) {
          // redirect to login page
          window.notification("warning", "You have been logged out due to inactivity.");
          document.cookie = "notification=danger|You have been logged out due to inactivity";
          window.location.href = "/login";
        }
        window.notification("danger", error.message);
        if (error.response.status == 404) {
          this.showMissingTeam = true;
        }
      });
    },
    getRegisteredCompetitions(id) {
      window.axios.get(`/api/competition?registered=${id},1`).then((response) => {
        console.log(response);
        this.competitions = response.data.data;
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
    prettyDate(date) {
      return moment(date).format('M/D/YYYY h:mmA');;
    },
    deregister(competition) {
      let self = this;
      let compid = competition.competition_id;
      let teamid = this.team.id;
      window.axios.post(`/api/competition/${compid}/team/deregister`, {
        'team_ids': [teamid]
      }).then((response) => {
        window.notification("success", "Team Unregistered");
        self.getRegisteredCompetitions(teamid);
      }).catch((error) => {
        console.error(error, compid, teamid);
        window.notification("danger", error.message);
      });
    },
    submitDelete() {
      let id = this.team.id;
      window.axios.delete(`/api/team/${id}`).then((response) => {
        window.notification("success", "Team Deleted");
        this.$router.push('/admin/teams');
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
    }
  }
}
</script>

<style lang="css">
</style>
