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
      <form >
        <div class="field">
          <div class="control">
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
                <tr v-for="team in teams">
                  <td>{{ team.code }}</td>
                  <td>{{ team.name }}</td>
                  <td>{{ team.email }}</td>
                  <td>
                    <input type="checkbox" :value="team.id" v-model="teamids" >
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="field is-grouped is-grouped-right">
          <div class="control">
            <button type="button" class="button is-warning" @click="cancel">Cancel</button>
          </div>
          <div class="control">
            <button type="button" class="button is-primary" @click="submit">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      competition: null,
      loading: true,
      teams: [],
      teamids: [],
      compId: 0
    };
  },
  mounted() {
    let id = this.$route.params.id;
    this.compId = id;
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
        window.notification("danger", error.message);
        if(error.response.status == 404) {
          this.showMissingCompetition = true;
        }
      });
    },
    getUnregisteredTeams(id) {
      window.axios.get(`/api/team?registered=${id},0`).then((response) => {
        console.log(response);
        this.teams = response.data.data;
      }).catch((error) => {
        window.notification("danger", error.message);
        console.error(error);
      });
    },
    cancel() {
      let id = this.compId;
      this.$router.push(`/admin/competitions/${id}`);
    },
    submit() {
      if(this.teamids.length > 0) {
        let id = this.compId;
        this.loading = true;
        window.axios.post(`/api/competition/${id}/team/register`, {
          'team_ids': this.teamids
        }).then((response) => {
          console.log(response);
          this.getUnregisteredTeams(this.compId);
          this.loading = false;
          window.notification("success", "Teams Registered");
        }).catch((error) => {
          console.error(error);
          window.notification("danger", error.message);
          this.loading = false;
        });
      }else {
        console.error("No Teams Selected");
        window.notification("warning", "No Teams Selected");
      }
    }
  }
}
</script>

<style lang="css">
</style>
