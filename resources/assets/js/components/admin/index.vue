<template lang="html">
  <div class="">
    <div class="loader-wrapper" v-if="loading">
      <div class="loader"></div>
    </div>
    <section class="hero welcome is-small is-primary is-bold" v-if="!loading">
      <div class="hero-body">
        <div class="container">
          <h1 class="title">{{ welcomeText }}</h1>
        </div>
      </div>
    </section>
    <div class="index-content">
      <nav class="level">
        <div class="level-item has-text-centered">
          <div class="box">
            <p class="heading">Competitions</p>
            <p class="title">{{ competitionCount }}</p>
          </div>
        </div>
        <div class="level-item has-text-centered">
          <div class="box">
            <p class="heading">Matches</p>
            <p class="title">{{ matchCount }}</p>
          </div>
        </div>
        <div class="level-item has-text-centered">
          <div class="box">
            <p class="heading">Teams</p>
            <p class="title">{{ teamCount }}</p>
          </div>
        </div>
      </nav>
      <div class="columns">
        <div class="column is-6">
          <div class="card events-card">
            <header class="card-header">
              <p class="card-header-title">
                Events
              </p>
            </header>
            <div class="card-table">
              <div class="content">
                <table class="table is-fullwidth is-striped">
                  <tbody>
                    <tr v-for="e in events">
                      <td width="5%"><i :class="e.fontawesome"></i></td>
                      <!-- Competition Created Event -->
                      <td v-if="e.name == 'competitioncreated'">Competition Created</td>
                      <td v-if="e.name == 'competitioncreated'">
                        <router-link class="button is-small is-info" :to="{ path: '/admin/competition/' + e.competition.id }">View</router-link>
                      </td>
                      <!-- Match Created Event -->
                      <td v-if="e.name == 'matchcreated'">Match Created</td>
                      <td v-if="e.name == 'matchcreated'">
                        <router-link class="button is-small is-info" :to="{ path: '/admin/match/' + e.match.id }">View</router-link>
                      </td>
                      <!-- Match Scored Event -->
                      <td v-if="e.name == 'matchscored'">Match Scored</td>
                      <td v-if="e.name == 'matchscored'">
                        <router-link class="button is-small is-info" :to="{ path: '/admin/match/' + e.match.id }">View</router-link>
                      </td>
                      <!-- Match Sent To Table Event -->
                      <td v-if="e.name == 'matchsenttotable'">Match Sent to Table</td>
                      <td v-if="e.name == 'matchsenttotable'">
                        <router-link class="button is-small is-info" :to="{ path: '/admin/match/' + e.match.id }">View</router-link>
                      </td>
                      <!-- Ruleset Created Event -->
                      <td v-if="e.name == 'rulesetcreated'">Ruleset Created</td>
                      <td v-if="e.name == 'rulesetcreated'">
                        <router-link class="button is-small is-info" :to="{ path: '/admin/ruleset/' + e.ruleset.id }">View</router-link>
                      </td>
                      <!-- Team Created Event -->
                      <td v-if="e.name == 'teamcreated'">Team Created</td>
                      <td v-if="e.name == 'teamcreated'">
                        <router-link class="button is-small is-info" :to="{ path: '/admin/team/' + e.team.id }">View</router-link>
                      </td>
                      <!-- Team Signed In Event -->
                      <td v-if="e.name == 'teamsignedin'">Team Signed In</td>
                      <td v-if="e.name == 'teamsignedin'">
                        <button @click="signOutTeam(e)" class="button is-small is-danger">Undo</button>
                      </td>
                      <!-- Team Summoned To Table -->
                      <td v-if="e.name == 'teamsummonedtotable'">Team Summoned To Table</td>
                      <td v-if="e.name == 'teamsummonedtotable'">
                        <router-link class="button is-small is-info" :to="{ path: '/admin/team/' + e.team.id }">View</router-link>
                      </td>
                    </tr>
                    <tr v-if="events.length == 0">
                      <td>No Events to Display</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <footer class="card-footer">
              <router-link class="card-footer-item" :to="{ name: 'view_all_events', params: {} }">View All</router-link>
            </footer>
          </div>
        </div>
        <div class="column is-6">
          <div class="card">
            <header class="card-header">
              <p class="card-header-title">Teams</p>
            </header>
            <div class="card-content">
              <div class="content">
                <div class="control has-icons-left field has-addons">
                  <div class="control">
                    <input class="input is-medium" placeholder="team name" type="text" v-model="teamname" @keyup="teamSearch" @blur="clearSearch">
                    <span class="icon is-medium is-left">
                      <i class="fa fa-search"></i>
                    </span>
                  </div>
                  <div class="control">
                  <button class="button is-primary is-medium" style="margin:0px;" @click="goToTeamSearch">Search</button>
                </div>
                </div>
                <div class="dropdown is-active" v-if="teams.length > 0">
                  <div class="dropdown-menu">
                    <div class="dropdown-content">
                      <a v-for="team in teams" class="dropdown-item" @click="goToTeam(team)">{{ team.name }}</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <header class="card-header">
              <p class="card-header-title">Competitions</p>
            </header>
            <div class="card-content">
              <div class="content">
                <div class="control has-icons-left field has-addons">
                  <div class="control">
                    <input class="input is-medium" placeholder="competition name" type="text" v-model="competitionname" @keyup="competitionSearch" @blur="clearSearch">
                    <span class="icon is-medium is-left">
                      <i class="fa fa-search"></i>
                    </span>
                  </div>
                  <div class="control">
                  <button class="button is-primary is-medium" style="margin:0px;" @click="goToCompetitionSearch">Search</button>
                </div>
                </div>
                <div class="dropdown is-active" v-if="competitions.length > 0">
                  <div class="dropdown-menu">
                    <div class="dropdown-content">
                      <a v-for="comp in competitions" class="dropdown-item" @click="goToCompetition(comp)">{{ comp.name }}</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
</template>

<script>
export default {
  data() {
    return {
      welcomeText: "Hello",
      competitionCount: 0,
      matchCount: 0,
      teamCount: 0,
      competitionsLoading: true,
      matchesLoading: true,
      teamsLoading: true,
      eventsLoading: true,
      teams: [],
      teamname: "",
      competitions: [],
      competitionname: "",
      events: []
    };
  },
  mounted() {
    // Can access window global at this point
    this.welcomeText = "Hello, " + window.user.name;
    this.getCompetitionCount();
    this.getMatchCount();
    this.getTeamCount();
    this.getEvents();
  },
  methods: {
    getEvents() {
      window.Echo.channel('admin').listen('CompetitionCreated', (event) => {
        event.name = "competitioncreated";
        let found = false;
        this.events.forEach((e) => {
          console.log(e, event);
          if(e.name == event.name && e.competition.id == event.competition.id) {
            found = true;
          }
        });
        if(!found) {
          if(this.events.length == 4) {
            this.events.slice(0, this.events.length - 1);
          }
          this.events.unshift(event);
        }
      }).listen('MatchCreated', (event) => {
        event.name = "matchcreated";
        let found = false;
        this.events.forEach((e) => {
          console.log(e, event);
          if(e.name == event.name && e.match.id == event.match.id) {
            found = true;
          }
        });
        if(!found) {
          if(this.events.length == 4) {
            this.events.slice(0, this.events.length - 1);
          }
          this.events.unshift(event);
        }
      }).listen('MatchScored', (event) => {
        event.name = "matchscored";
        let found = false;
        this.events.forEach((e) => {
          console.log(e, event);
          if(e.name == event.name && e.match.id == event.match.id) {
            found = true;
          }
        });
        if(!found) {
          if(this.events.length == 4) {
            this.events.slice(0, this.events.length - 1);
          }
          this.events.unshift(event);
        }
      }).listen('MatchSentToTable', (event) => {
        event.name = "matchsenttotable";
        let found = false;
        this.events.forEach((e) => {
          console.log(e, event);
          if(e.name == event.name && e.match.id == event.match.id) {
            found = true;
          }
        });
        if(!found) {
          if(this.events.length == 4) {
            this.events.slice(0, this.events.length - 1);
          }
          this.events.unshift(event);
        }
      }).listen('RulesetCreated', (event) => {
        event.name = "rulesetcreated";
        let found = false;
        this.events.forEach((e) => {
          console.log(e, event);
          if(e.name == event.name && e.ruleset.id == event.ruleset.id) {
            found = true;
          }
        });
        if(!found) {
          if(this.events.length == 4) {
            this.events.slice(0, this.events.length - 1);
          }
          this.events.unshift(event);
        }
      });
      window.Echo.channel('admin').listen('TeamCreated', (event) => {
        event.name = "teamcreated";
        let found = false;
        this.events.forEach((e) => {
          console.log(e, event);
          if(e.name == event.name && e.team.id == event.team.id) {
            found = true;
          }
        });
        if(!found) {
          if(this.events.length == 4) {
            this.events.slice(0, this.events.length - 1);
          }
          this.events.unshift(event);
        }
      }).listen('TeamSignedIn', (event) => {
        event.name = "teamsignedin";
        let found = false;
        this.events.forEach((e) => {
          console.log(e, event);
          if(e.name == event.name && e.team.id == event.team.id) {
            found = true;
          }
        });
        if(!found) {
          if(this.events.length == 4) {
            this.events.slice(0, this.events.length - 1);
          }
          this.events.unshift(event);
        }
      }).listen('TeamSummonedToTable', (event) => {
        event.name = "teamsummonedtotable";
        let found = false;
        this.events.forEach((e) => {
          console.log(e, event);
          if(e.name == event.name && e.team.id == event.team.id) {
            found = true;
          }
        });
        if(!found) {
          if(this.events.length == 4) {
            this.events.slice(0, this.events.length - 1);
          }
          this.events.unshift(event);
        }
      });
      this.eventsLoading = false;
    },
    getCompetitionCount() {
      window.axios.get('/api/competition/count').then((result) => {
        console.log(result);
        this.competitionCount = result.data.competition_count;
        this.competitionsLoading = false;
      }).catch((error) => {
        console.error(error);
        if (error.response.status == 401) {
          // redirect to login page
          window.notification("warning", "You have been logged out due to inactivity.");
          document.cookie = "notification=danger|You have been logged out due to inactivity";
          window.location.href = "/login";
        }
      });
    },
    getMatchCount() {
      window.axios.get('/api/match/count').then((result) => {
        console.log(result);
        this.matchCount = result.data.match_count;
        this.matchesLoading = false;
      }).catch((error) => {
        console.error(error);
        if (error.response.status == 401) {
          // redirect to login page
          window.notification("warning", "You have been logged out due to inactivity.");
          document.cookie = "notification=danger|You have been logged out due to inactivity";
          window.location.href = "/login";
        }
      });
    },
    getTeamCount() {
      window.axios.get('/api/team/count').then((result) => {
        console.log(result);
        this.teamCount = result.data.team_count;
        this.teamsLoading = false;
      }).catch((error) => {
        console.error(error);
        if (error.response.status == 401) {
          // redirect to login page
          window.notification("warning", "You have been logged out due to inactivity.");
          document.cookie = "notification=danger|You have been logged out due to inactivity";
          window.location.href = "/login";
        }
      });
    },
    teamSearch() {
      this.teams = [];
      if(this.teamname.length > 0) {
        window.axios.get("/api/team?name=" + this.teamname).then((response) => {
          this.teams = response.data.data;
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
    goToTeam(team) {
      let id = team.id;
      this.$router.push(`/admin/teams/${id}`);
    },
    competitionSearch() {
      this.competitions = [];
      if(this.competitionname.length > 0) {
        window.axios.get("/api/competition?name=" + this.competitionname).then((response) => {
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
      }
    },
    goToCompetition(comp) {
      let id = comp.id;
      this.$router.push(`/admin/competitions/${id}`);
    },
    clearSearch() {
      setTimeout(() => {
        this.competitionname = "";
        this.teamname = "";
        this.competitions = [];
        this.teams = [];
      }, 300);
    },
    goToTeamSearch() {
      if(this.teamname.length > 0)
      {
        this.$router.push(`/admin/teams/search?search=${this.teamname}`);
      }
    },
    goToCompetitionSearch() {
      if(this.competitionname.length > 0)
      {
        this.$router.push(`/admin/competitions/search?search=${this.competitionname}`);
      }
    }
  },
  computed: {
    loading() {
      return this.competitionsLoading || this.matchesLoading || this.teamsLoading || this.eventsLoading;
    }
  }
}
</script>

<style lang="css">
</style>
