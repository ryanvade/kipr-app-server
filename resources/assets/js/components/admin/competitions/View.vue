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
<div class="competition-page">
  <div class="loader-wrapper" v-if="loading">
    <div class="loader"></div>
  </div>
  <div class="" v-if="!loading">
    <nav class="level">
      <div class="level-left">
        <p class="subtitle has-text-centered">
          <strong>{{ competition.name }}</strong>
        </p>
      </div>
      <div class="level-right">
        <p class="level-item">
          <a class="card-footer-item" id="edit" @click="$router.push('/admin/competitions/' + competition.id + '/teams/register')">Register Teams</a>
        </p>
        <p class="level-item">
          <a class="card-footer-item" id="edit" @click="$router.push('/admin/competitions/' + competition.id + '/edit')">Edit</a>
        </p>
        <p class="level-item">
          <a class="card-footer-item is-danger" id="delete" @click="showWarning = true">Delete</a>
        </p>
      </div>
    </nav>

    <div class="box">
      <p class="is-size-5 is-grey">
        <strong>Start Date:</strong> {{ prettyDate(competition.start_date) }}
        <strong>End Date:</strong> {{ prettyDate(competition.end_date) }}
      </p>
      <h3>Registered Teams:</h3>
      <table class="table is-hoverable is-fullwidth">
        <thead>
          <tr>
            <th>Code</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="team in teams">
            <td>{{ team.code }}</td>
            <td>{{ team.name }}</td>
            <td>{{ team.email }}</td>
            <td><button type="button" class="button is-danger" @click="deregister(team)">Un-Register</button></td>
          </tr>
        </tbody>
      </table>
    </div>
    <schedule v-bind:competition="competition"/>
    <seeding v-bind:competition="competition"/>
    <bracket v-bind:competition="competition"/>
    <div class="delete-competition-modal-wrapper">
      <modal v-if="showWarning" v-on:close="showWarning = false">
        <div class="" slot="header">
          Delete Competition
        </div>
        <div class="" slot="body">
          <div class="has-text-weight-bold">Are you sure you wish to delete {{ competition.name }}?</div>
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
  <!-- Missing Competition Modal -->
  <div class="missing-competition-modal">
    <modal v-if="showMissingCompetition">
      <div class="" slot="header">
        Unknown Competition
      </div>
      <div class="" slot="body">
        <div class="has-text-weight-bold">Cannot find competition with id {{ $route.params.id }}.</div>
      </div>
      <div class="buttons has-addons is-centered" slot="footer">
        <button class="button is-outlined" @click="$router.push('/admin/')" style="width: 50%;">Admin Home</button>
        <button class="button is-outlined" style="width: 50%;" @click="$router.push('/admin/competitions')">View All Competitions</button>
      </div>
    </modal>
  </div>
</div>
</template>

<script>
import Modal from '../../Modal.vue';
import Schedule from './Schedule.vue';
import Bracket from './Bracket.vue';
import Seeding from './Seeding.vue';
export default {
  components: {
    'modal': Modal,
    'schedule': Schedule,
    'bracket': Bracket,
    'seeding': Seeding
  },
  data() {
    return {
      competition: null,
      teams: [],
      loading: true,
      showWarning: false,
      showMissingCompetition: false,
    };
  },
  mounted() {
    let id = this.$route.params.id;
    this.getCompetition(id);
    this.getRegisteredTeams(id);
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
    getRegisteredTeams(id) {
      window.axios.get(`/api/team?registered=${id},1`).then((response) => {
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
    prettyDate(date) {
      return moment(date).format('M/D/YYYY h:mmA');;
    },
    deregister(team) {
      let self = this;
      let compid = this.competition.id;
      let teamid = team.id;
      window.axios.post(`/api/competition/${compid}/team/deregister`, {
        'team_ids': [teamid]
      }).then((response) => {
        window.notification("success", "Team Unregistered");
        self.getRegisteredTeams(compid);
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
    submitDelete() {
      let id = this.competition.id;
      window.axios.delete(`/api/competition/${id}`).then((response) => {
        window.notification("success", "Competition Deleted");
        this.$router.push('/admin/competitions');
      }).catch((error) => {
        console.error(error);
        if (error.response.status == 401) {
          // redirect to login page
          window.notification("warning", "You have been logged out due to inactivity.");
          document.cookie = "notification=danger|You have been logged out due to inactivity";
          window.location.href = "/login";
        }
        window.notification("danger", error.message);
        this.$router.push('/admin/competitions');
      });
    }
  }
}
</script>

<style lang="css">
</style>
