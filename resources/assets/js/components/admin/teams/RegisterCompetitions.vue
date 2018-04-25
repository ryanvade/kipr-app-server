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
  <div class="register-teams-page">
    <div class="loader-wrapper" v-if="loading">
      <div class="loader"></div>
    </div>
    <div class="" v-if="!loading">
      <nav class="level">
        <div class="level-left">
          <p class="subtitle has-text-centered">
            Register competitions for <strong>{{ team.name }}</strong>
          </p>
        </div>
        <div class="level-right">
          <p class="level-item">
            click the checkbox to add the team to the competition
          </p>
        </div>
      </nav>
      <form >
        <div class="field">
          <div class="control">
            <table class="table is-hoverable is-fullwidth">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Location</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="comp in competitions">
                  <td>{{ comp.id }}</td>
                  <td>{{ comp.name }}</td>
                  <td>{{ comp.location }}</td>
                  <td>{{ prettyDate(comp.start_date) }}</td>
                  <td>{{ prettyDate(comp.end_date) }}</td>
                  <td>
                    <input type="checkbox" :value="comp.id" v-model="compids" >
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
      team: null,
      loading: true,
      competitions: [],
      compids: [],
      teamId: 0
    };
  },
  mounted() {
    let id = this.$route.params.id;
    this.teamdId = id;
    this.getTeam(id);
    this.getUnregisteredCompetitions(id);
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
          this.showMissingCompetition = true;
        }
      });
    },
    prettyDate(date) {
      return moment(date).format('M/D/YYYY h:mmA');;
    },
    getUnregisteredCompetitions(id) {
      window.axios.get(`/api/competition?registered=${id},0`).then((response) => {
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
    cancel() {
      let id = this.team.id;
      this.$router.push(`/admin/teams/${id}`);
    },
    submit() {
      if (this.compids.length > 0) {
        let id = this.compId;
        this.loading = true;
        this.compids.forEach((compid) => {
          console.log(compid);
          window.axios.post(`/api/competition/${compid}/team/register`, {
            'team_ids': [this.team.id]
          }).then((response) => {
            console.log(response);
            window.notification("success", "Teams Registered");

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
        });
        this.getUnregisteredCompetitions(this.team.id);
        this.loading = false;

      } else {
        // flash warning
        console.error("No Teams Selected");
        window.notification("warning", "No Teams Selected");
      }
    }
  }
}
</script>

<style lang="css">
</style>
