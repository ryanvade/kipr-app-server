<template lang="html">
    <div class="box">
    <nav class="level">
      <div class="level-left">
        <p class="subtitle has-text-centered">
          <strong>Seeding</strong>
        </p>
      </div>
    </nav>
    <div v-if="schedule == null">
        A schedule has not been created yet.
    </div>
      <table v-else class="table is-hoverable is-fullwidth">
        <thead>
          <tr>
            <th>Team</th>
            <th v-for="match in 3">Round {{ match }}</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="team in teams">
            <td>{{ team.name }}</td>
            <td v-for="match in team.matches" @click="gotoMatch(match)"><a v-if="match.results" >{{ match.results.score }}</a></td>
            <td> 0 </td>
          </tr>
        </tbody>
      </table>
    </div>
</template>

<script>
export default {
  props: ["competition"],
  data() {
    return {
        schedule: null,
        teams: {},
    };
  },
  mounted() {
      console.log(this.competition.tables)
      this.getSchedule();
  },
  methods: {
    displaySeeding() {
		for (var match of this.schedule) {
			if(match.match_type == "seeding") {
                if(!this.teams[match.team_A]) {
				    this.teams[match.team_A] = match.team_a;
                    this.teams[match.team_A].matches = [];
                }
				this.teams[match.team_A].matches.push(match);
			}
		}
    },
    gotoMatch(match) {
        console.log(this.$refs[match]);
        this.highlighed = match;
        this.$refs[match][0].scrollIntoView();
    },
    getSchedule() {
      window.axios.get(`/api/competition/${this.competition.id}/matches`).then((response) => {
        this.schedule = response.data;
        this.loading = false;
        this.displaySeeding();
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
    ontop(r) {
        if(r <= 0) return 0;
        if(r == 1) return 2.0;
        return this.ontop(r-1) + this.between(r-2);
        //return 0;
    },
    between(r) {
        if(r == 0) return 1;
        return (1 << (r + 1));
        //return 1;
    }
  },
  directives: {
  }
}
</script>

<style lang="css">
</style>
