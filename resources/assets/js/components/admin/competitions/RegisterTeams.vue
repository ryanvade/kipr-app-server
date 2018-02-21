<template lang="html">
  <div class="register-teams-page">
    <div class="loader-wrapper" v-if="loading">
      <div class="loader"></div>
    </div>
    <div class="" v-if="!loading">
      <nav class="level">
        <div class="level-left">
          <p class="subtitle has-text-centered">
            Register teams for <strong>{{ competition.name }}</strong>
          </p>
        </div>
        <div class="level-right">
          <p class="level-item">
            click the checkbox to add a team
          </p>
        </div>
      </nav>
      <table class="table is-hoverable is-fullwidth">
        <thead>
          <tr>
            <th>Code</th>
            <th>Name</th>
            <th>Email</th>
            <th>Register</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="team in competition.teams">
            <td>{{ team.code }}</td>
            <td>{{ team.name }}</td>
            <td>{{ team.email }}</td>
            <td>
              <input type="checkbox" @change="handleChange(team, $event)">
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      competition: null,
      loading: true,
      teams: []
    };
  },
  mounted() {
    let id = this.$route.params.id;
    this.getCompetition(id);
    this.getUnregisteredTeams(id);
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
    getUnregisteredTeams(id) {
      window.axios.get(`/api/competition/${id}/team/notregistered`).then((response) => {
        console.log(response);
        this.teams = response.data.data;
      }).catch((error) => {
        console.error(error);
      });
    },
    handleChange(team, $event) {
      if($event.target.checked) {
        let self = this;
        let compid = this.competition.id;
        let teamid = team.id;
        window.axios.post(`/api/competition/${compid}/team/${teamid}/register`).then((response) => {
          console.log("Registered Team");
        }).catch((error) => {
          console.error(error);
        });
      }else {
        let self = this;
        let compid = this.competition.id;
        let teamid = team.id;
        window.axios.post(`/api/competition/${compid}/team/${teamid}/deregister`).then((response) => {
          console.log("De-Registered Team");
        }).catch((error) => {
          console.error(error);
        });
      }
    }
  }
}
</script>

<style lang="css">
</style>
