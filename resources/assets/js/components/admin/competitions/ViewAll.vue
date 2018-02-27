<template lang="html">
  <div class="competitions-page">
    <div class="loader-wrapper" v-if="loading">
      <div class="loader"></div>
    </div>

    <nav class="level">
      <div class="level-left">
        <div class="level-item">
          <p class="subtitle is-5">
            <strong>{{ total }}</strong> Competitions
          </p>
        </div>
        <div class="level-item">
          <p class="has-text-grey" v-if="competitions.length > 0">
            Click on a competition for more options.
          </p>
        </div>
      </div>
      <div class="level-right">
        <p class="level-item">
          <router-link class="button is-primary" :to="{ name: 'create_competition', params: {} }">Create</router-link>
        </p>
      </div>
    </nav>

    <table class="table is-hoverable is-fullwidth" v-if="!loading">
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
        <tr v-for="comp in competitions" @click="goToCompetition(comp)">
          <td>{{ comp.id }}</td>
          <td>{{ comp.name }}</td>
          <td>{{ comp.location }}</td>
          <td>{{ prettyDate(comp.start_date) }}</td>
          <td>{{ prettyDate(comp.end_date) }}</td>
        </tr>
      </tbody>
    </table>
    <nav class="level" v-if="showPagination">
      <div class="level-left">
        <button class="buton previous-page-button" v-show="showPreviousPagination" @click="currentPage--">Previous Page</button>
      </div>
      <div class="level-right">
        <button class="buton next-page-button" v-show="showNextPagination" @click="currentPage++">Next Page</button>
      </div>
    </nav>
  </div>
</template>

<script>
import moment from 'moment-timezone';
export default {
  data() {
    return {
      loading: false,
      competitions: [],
      currentPage: 0,
      totalPages: null,
      total: 0
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
      this.getCompetitions();
    }
  },
  mounted() {
    this.currentPage = 1;
  },
  methods: {
    getCompetitions() {
      this.loading = true;
      let page = this.currentPage;
      window.axios.get(`/api/competition?page=${page}`).then((response) => {
        console.log(response);
        this.competitions = response.data.data;
        this.currentPage = response.data.current_page;
        this.total = response.data.total;
        if(response.data.last_page != 1) {
          let perPage = response.data.per_page;
          this.totalPages = Math.ceil(this.total / perPage);
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
    goToCompetition(comp) {
      this.$router.push(`/admin/competitions/${comp.id}`);
    }
  }
}
</script>

<style lang="css">
.table td {
  cursor: pointer;
}
</style>
