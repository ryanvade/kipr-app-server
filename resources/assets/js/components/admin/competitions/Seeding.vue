<!-- Copyright (c) 2018 KISS Institute for Practical Robotics

BSD v3 License

All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

* Redistributions of source code must retain the above copyright notice, this
  list of conditions and the following disclaimer.

* Redistributions in binary form must reproduce the above copyright notice,
  this list of conditions and the following disclaimer in the documentation
  and/or other materials provided with the distribution.

* Neither the name of KIPR Scoring App nor the names of its
  contributors may be used to endorse or promote products derived from
  this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE. -->
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
