<template lang="html">
  <div class="teams-page">
    <div class="loader-wrapper" v-if="loading">
      <div class="loader"></div>
    </div>

    <nav class="level">
      <div class="level-left">
        <div class="level-item">
          <p class="subtitle is-5">
            <strong>{{ teams.length }}</strong> Teams
          </p>
        </div>
        <div class="level-item">
          <p class="has-text-grey" v-if="teams.length > 0">
            Click on a team for more options.
          </p>
        </div>
      </div>
      <div class="level-right">
        <p class="level-item">
          <router-link class="button is-primary" :to="{ name: 'create_team', params: {} }">Create</router-link>
        </p>
      </div>
    </nav>

    <div class="table-wrapper" v-if="!loading">
      <table class="table is-hoverable is-fullwidth">
        <thead>
          <tr>
            <th>ID</th>
            <th>Code</th>
            <th>Name</th>
            <th>Email</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="team in teams" @click="goToTeam(team)">
            <td>{{ team.id }}</td>
            <td>{{ team.code }}</td>
            <td>{{ team.name }}</td>
            <td>{{ team.email }}</td>
          </tr>
        </tbody>
      </table>
      <nav class="level" v-if="showPagination">
        <div class="level-left">
          <button class="" v-show="showPreviousPagination" @click="currentPage--">Previous Page</button>
        </div>
        <div class="level-right">
          <button class="" v-show="showNextPagination" @click="currentPage++">Next Page</button>
        </div>
      </nav>
    </div>
  </div>
</template>

<script>
import moment from 'moment-timezone';
export default {
  data() {
    return {
      loading: false,
      teams: [],
      currentPage: 0,
      totalPages: null,
    };
  },
  computed: {
    showPagination() {
      return this.totalPages != null && this.totalPages > 1;
    },
    showPreviousPagination() {
      return this.currentPage > 1;
    },
    showNextPagination() {
      return this.currentPage < this.totalPages;
    }
  },
  watch: {
    currentPage() {
      this.getTeams();
    }
  },
  mounted() {
    this.currentPage = 1;
  },
  methods: {
    getTeams() {
      this.loading = true;
      let page = this.currentPage;
      window.axios.get(`/api/team?page=${page}`).then((response) => {
        console.log(response);
        this.teams = response.data.data;
        this.currentPage = response.data.current_page;
        if (response.data.last_page != 1) {
          let total = response.data.total;
          let perPage = response.data.per_page;
          this.totalPages = Math.ceil(total / perPage);
        }
        this.loading = false;
      }).catch((error) => {
        console.error(error);
        window.notification("danger", error.message);
      })
    },
    prettyDate(date) {
      return moment(date).format('M/D/YYYY h:mmA');
    },
    goToTeam(team) {
      this.$router.push(`/admin/teams/${team.id}`);
    }
  }
}
</script>

<style lang="css">
.table td {
  cursor: pointer;
}
</style>
