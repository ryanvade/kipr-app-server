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
          <a v-if="competition.schedule != null" class="card-footer-item" id="update" @click="updateSchedule">Update</a>
          <a v-else class="card-footer-item" id="update" @click="updateSchedule">Create</a>
        </p>
      </div>
    </nav>
    <div v-if="competition.schedule == null">
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
          <tr v-for="time in sortedSeeding">
            <td>{{ new Date(time.time).toLocaleTimeString() }}</td>
            <td v-for="match in time.matches"><a>{{ match.match_id }}</a></td>
          </tr>
          <tr>
              <td> BREAK </td>
          </tr>
          <tr v-for="time in sortedEliminiation">
            <td>{{ new Date(time.time).toLocaleTimeString() }}</td>
            <td v-for="match in time.matches"><a>{{ match.match_id }}</a></td>
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
        seeding: {},
        sortedSeeding: [],
        elimination: {},
        sortedEliminiation: []
    };
  },
  mounted() {
      console.log(this.competition.tables)
  },
  methods: {
      updateSchedule() {
          window.axios.post(`/api/competition/${this.competition.id}/updateSchedule`).then((response) => {
            this.competition.schedule = response.data;
            this.seeding = {};
            this.elimination = {};
            for (var i in this.competition.schedule.seeding) {
                var match = this.competition.schedule.seeding[i];
                match.start_time.date = Date.parse(match.start_time.date);

                if(!this.seeding[match.start_time.date])
                    this.seeding[match.start_time.date] = {time: match.start_time.date, matches: []};

                this.seeding[match.start_time.date].matches.push(match);
            }

            for (var i in this.competition.schedule.elimination) {
                var match = this.competition.schedule.elimination[i];

                if(!this.elimination[match.start_time.date])
                    this.elimination[match.start_time.date] = {time: match.start_time.date, matches: []};

                this.elimination[match.start_time.date].matches.push(match);
            }

            this.sortedSeeding = [];

            for(var timeslot in this.seeding) {
                this.sortedSeeding.push(this.seeding[timeslot]);
            }
            this.sortedSeeding.sort(function(a, b) {
                  return a.time - b.time;
              });

            this.sortedEliminiation = [];

            for(var timeslot in this.elimination) {
                this.sortedEliminiation.push(this.elimination[timeslot]);
            }
            this.sortedEliminiation.sort(function(a, b) {
                  return a.time - b.time;
              });

          }).catch((error) => {
            console.error(error);
          });
    }
  },
  directives: {
  }
}
</script>

<style lang="css">
</style>
