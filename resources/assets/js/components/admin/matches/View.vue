<template lang="html">
<div class="team-page">
  <div class="loader-wrapper" v-if="loading">
    <div class="loader"></div>
  </div>
  <div class="" v-if="!loading">

  <div class="box">
    <p class="is-size-5 is-grey">
      <strong>Match {{ match.id }}</strong>
    </p>
    <div v-if="match.match_type=='seeding'">
        <p>Seeding Match</p>
        <p>Team: <a @click="goToTeam(match.team_A)">{{match.team_a.name}}</a></p>
    </div>
    <div v-else-if="match.match_type=='WW' || match.match_type=='WL' || match.match_type=='LL'">
        <p>Elimination Match</p>

        <p v-if="!match.team_A">
            <span v-if="match.match_type=='WW' || match.match_type=='WL'">
                Team A: <a>Winner of {{match.match_A}}</a>
            </span>
            <span v-else-if="match.match_type=='LL'">
                Team A: <a>Loser of {{match.match_A}}</a>
            </span>
        </p>
        <p v-else>
            Team A: <a @click="goToTeam(match.team_a.id)">{{match.team_a.name}}</a>
        </p>

        <p v-if="!match.team_B">
            <span v-if="match.match_type=='LL' || match.match_type=='WL'">
                Team B: <a>Loser of {{match.match_B}}</a>
            </span>
            <span v-else-if="match.match_type=='WW'">
                Team B: <a>Winner of {{match.match_B}}</a>
            </span>
        </p>
        <p v-else>
            Team B: <a @click="goToTeam(match.team_b.id)">{{match.team_b.name}}</a>
        </p>
    </div>
  </div>

    <div v-if="match.results" class="box">
      <p class="is-size-5 is-grey">
        <strong>Results</strong>
      </p>
      <p v-if="match.results.winner == match.team_A">{{match.team_a.name}} Wins</p>
      <p v-else>{{match.team_b.name}} Wins</p>
      <div>
        <strong>{{ match.team_a.name }}</strong>
        <table class="table is-hoverable is-fullwidth">
          <tbody>
              <tr><th>Rule</th><th>Count</th><th>Score</th></tr>
            <tr v-for="row in match.results.score_table.a">
                <td v-for="col in row">{{ col }}</td>
            </tr>
            <tr>
                <td><strong>Total</strong></td>
                <td/>
                <td>{{match.results.score_a}}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-if="match.team_b">
          <strong>{{ match.team_b.name }}</strong>
        <table class="table is-hoverable is-fullwidth">
          <tbody>
              <tr><th>Rule</th><th>Count</th><th>Score</th></tr>
            <tr v-for="row in match.results.score_table.b">
                <td v-for="col in row">{{ col }}</td>
            </tr>
            <tr>
                <td><strong>Total</strong></td>
                <td/>
                <td>{{match.results.score_b}}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div v-else class="box">
      <p class="is-size-5 is-grey">
        <strong>Upcoming</strong>
        <p>Time: {{match.match_time}}</p>
        <p>Table: {{match.match_table}}</p>
      </p>
    </div>
  </div>
</div>
</template>

<script>
export default {
  components: {
  },
  data() {
    return {
      match: null,
      loading: true
    };
  },
  mounted() {
    let id = this.$route.params.id;
    this.getMatch(id);
  },
  methods: {
    getMatch(id) {
      window.axios.get(`/api/match/${id}`).then((response) => {
        console.log(response);
        this.match = response.data;
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
      });
    },
    goToTeam(team_id) {
      this.$router.push(`/admin/teams/${team_id}`);
    }
  }
}
</script>

<style lang="css">
</style>
