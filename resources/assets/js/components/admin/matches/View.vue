<template lang="html">
<div class="team-page">
  <div class="loader-wrapper" v-if="loading">
    <div class="loader"></div>
  </div>
  <div class="" v-if="!loading">

    <nav class="level">
      <div class="level-left">
        <p class="subtitle has-text-centered">
          <strong>Match {{ match.id }}</strong>
        </p>
      </div>
      <strong>Team A:</strong> {{match.teamA}}
      <strong>Team B:</strong> {{match.teamB}}
    </nav>

    <div v-if="match.score" class="box">
      <p class="is-size-5 is-grey">
        <strong>Results</strong>
      </p>
      <strong>Team A</strong>
      <table class="table is-hoverable is-fullwidth">
        <tbody>
          <tr v-for="">
              <td>{{ match.score.teamA }}</td>
          </tr>
        </tbody>
      </table>
      <div v-if="match.teamB">
        <strong>Team B</strong>
        <table class="table is-hoverable is-fullwidth">
          <tbody>
            <tr v-for="">
                <td>{{ match.score.teamA }}</td>
            </tr>
          </tbody>
        </table>
      </div>
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
      loading: true,
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
    prettyDate(date) {
      return moment(date).format('M/D/YYYY h:mmA');;
    }
  }
}
</script>

<style lang="css">
</style>
