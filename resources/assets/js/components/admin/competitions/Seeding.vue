<template lang="html">
    <div class="box">
    <nav class="level">
      <div class="level-left">
        <p class="subtitle has-text-centered">
          <strong>Schedule</strong>
        </p>
      </div>
      <div class="level-right">
        <p class="level-item">
          <a v-if="schedule != null" class="card-footer-item" id="update" @click="updateSchedule">Update</a>
          <a v-else class="card-footer-item" id="update" @click="updateSchedule">Create</a>
        </p>
      </div>
    </nav>
    <div v-if="schedule == null">
        A schedule has not been created yet.
    </div>
      <table v-else class="table is-hoverable is-fullwidth">
        <thead>
          <tr>
            <th>Time</th>
            <th v-for="table in 3">Table {{ table }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="time in sorted">
            <td>{{ new Date(time.time).toLocaleTimeString() }}</td>
            <td v-for="match in time.matches" @click="gotoMatch(match)"><a>{{ match.id }}</a></td>
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
        sorted: [],
        schedule: null
    };
  },
  mounted() {
      console.log(this.competition.tables)
      this.getSchedule();
  },
  methods: {
      updateSchedule() {
          window.axios.post(`/api/competition/${this.competition.id}/updateSchedule`).then((response) => {
            this.competition.schedule = response.data;
            this.displaySchedule();

          }).catch((error) => {
            console.error(error);
          });
    },
    displaySchedule() {
        this.matches = {};

        for (var i in this.schedule) {
            var match = this.schedule[i];
            match.match_time = Date.parse(match.match_time);

            if(!this.matches[match.match_time])
                this.matches[match.match_time] = {time: match.match_time, matches: []};

            this.matches[match.match_time].matches.push(match);
        }

        this.sorted = [];

        for(var timeslot in this.matches) {
            this.sorted.push(this.matches[timeslot]);
        }

        this.sorted.sort(function(a, b) {
              return a.time - b.time;
          });
      console.log(this.sorted);
    },
    gotoMatch(match) {
        this.$router.push(`/admin/matches/${match.id}`);
    },
    getSchedule() {
      window.axios.get(`/api/competition/${this.competition.id}/matches`).then((response) => {
        this.schedule = response.data;
        this.loading = false;
        this.displaySchedule();
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
  },
  directives: {
  }
}
</script>

<style lang="css">
</style>
