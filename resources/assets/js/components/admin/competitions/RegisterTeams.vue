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
    getUnregisteredTeams(id) {
      window.axios.get(`/api/team?registered=${id},0`).then((response) => {
        console.log(response);
        this.teams = response.data.data;
      }).catch((error) => {
        window.notification("danger", error.message);
        console.error(error);
        if (error.response.status == 401) {
          // redirect to login page
          window.notification("warning", "You have been logged out due to inactivity.");
          document.cookie = "notification=danger|You have been logged out due to inactivity";
          window.location.href = "/login";
        }
      });
    },
    cancel() {
      let id = this.compId;
      this.$router.push(`/admin/competitions/${id}`);
    },
    submit() {
      if (this.teamids.length > 0) {
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
          if (error.response.status == 401) {
            // redirect to login page
            window.notification("warning", "You have been logged out due to inactivity.");
            document.cookie = "notification=danger|You have been logged out due to inactivity";
            window.location.href = "/login";
          }
          window.notification("danger", error.message);
          this.loading = false;
        });
      } else {
        console.error("No Teams Selected");
        window.notification("warning", "No Teams Selected");
      }
    }
  }
}
</script>

<style lang="css">
</style>
