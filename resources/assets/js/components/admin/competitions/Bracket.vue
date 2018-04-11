<template lang="html">
  <div class="box">
    <nav class="level">
      <div class="level-left">
        <p class="subtitle has-text-centered">
          <strong>Winners Bracket</strong>
        </p>
      </div>
    </nav>
    <div v-if="schedule == null">
        A schedule has not been created yet.
    </div>
    <div v-else>
        <div class="columns">
            <div class="column" v-for="round in rounds['de_winner']" v-bind:style="{'padding-top': ontop(round[0].round) + 'em'}">
                <div v-for="match in round" class="match" :class="{focused: highlighed==match.id}" :ref="match.id"
                    v-bind:style="{ 'margin-bottom': between(match.round) + 'em'}">
                    <div>
                        <div class="team_a" v-if="match.team_a">{{ match.team_a.name }}</div>
                        <div class="team_a" v-else-if="match.match_A"><a @mouseover="highlighed=match.match_A" @click="gotoMatch(match.match_A)">Winner of: {{ match.match_A }}</a></div>
                        <div class="team_a" v-else>BYE</div>
                    </div>
                    <div class="match_number" v-bind:style="{'line-height': '1em'}">
                        {{ match.id }})
                    </div>
                    <div>
                        <div class="team_b" v-if="match.team_b">{{ match.team_b.name }}</div>
                        <div class="team_b" v-else-if="match.match_B"><a @mouseover="highlighed=match.match_B" @click="gotoMatch(match.match_B)">Winner of: {{ match.match_B }}</a></div>
                        <div class="team_b" v-else>BYE</div>
                    </div>
                </div>
            </div>
        </div>
        <nav class="level">
          <div class="level-left">
            <p class="subtitle has-text-centered">
              <strong>Consolation Bracket</strong>
            </p>
          </div>
        </nav>
        <div class="columns">
            <div class="column" v-for="round in rounds['de_loser']" v-bind:style="{'padding-top': ontop(Math.floor(round[0].round/2)) + 'em'}">
                <div v-for="match in round" class="match" :class="{focused: highlighed==match.id}" :ref="match.id"
                    v-bind:style="{ 'margin-bottom': between(Math.floor(match.round/2)) + 'em'}">
                    <div>
                        <div class="team_a" v-if="match.team_a">{{ match.team_a.name }}</div>
                        <div class="team_a" v-else-if="match.match_A"><a @mouseover="highlighed=match.match_A" @click="gotoMatch(match.match_A)">
                                <span v-if="match.match_type=='WW' || match.match_type=='WL'">Winner</span>
                                <span v-else-if="match.match_type=='LL'">Loser</span>
                                of: {{ match.match_A }}</a></div>
                        <div class="team_a" v-else>BYE</div>
                    </div>
                    <div class="match_number" v-bind:style="{'line-height': '1em'}">
                        {{ match.id }})
                    </div>
                    <div>
                        <div class="team_b" v-if="match.team_b">{{ match.team_b.name }}</div>
                        <div class="team_b" v-else-if="match.match_B"><a @mouseover="highlighed=match.match_B" @click="gotoMatch(match.match_B)">
                                <span v-if="match.match_type=='LL' || match.match_type=='WL'">Loser</span>
                                <span v-else-if="match.match_type=='WW'">Winner</span>
                                of: {{ match.match_B }}</a></div>
                        <div class="team_b" v-else>BYE</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</template>

<script type="text/javascript" src="jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="jquery.bracket.min.js"></script>
<script>
export default {
  props: ["competition"],
  data() {
    return {
        schedule: null,
        bracket: null,
		rounds: null,
        highlighed: -1,
    };
  },
  mounted() {
      console.log(this.competition.tables)
      this.getSchedule();
  },
  methods: {
    displayBracket() {
        this.rounds = {de_winner: {}, de_loser: {}, de_finals: {}};
        var teams = {};
        var teamIndex = {};
		for (var match of this.schedule) {
			if(match.match_type != "seeding") {
				if(!this.rounds[match.bracket_type][match.round])
					this.rounds[match.bracket_type][match.round] = [];
				console.log(match.bracket_type);
				this.rounds[match.bracket_type][match.round].push(match);
			}
		}
    },
    gotoMatch(match) {
        console.log(this.$refs[match]);
        this.highlighed = match;
        this.$refs[match][0].scrollIntoView();
    },
    getSchedule() {
      window.axios.get(`/api/competition/${this.competition.id}/matches`).then((response) => {
        this.schedule = response.data;
        this.loading = false;
        this.displayBracket();
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
    ontop(r) {
        if(r <= 0) return 0;
        if(r == 1) return 2.0;
        return this.ontop(r-1) + this.between(r-2);
        //return 0;
    },
    between(r) {
        if(r == 0) return 1;
        return (1 << (r + 1));
        //return 1;
    }
  },
  directives: {
  }
}
</script>

<style lang="css">
div.match {
    border-right: 1px solid black;
    border-top: 1px solid black;
    border-bottom: 1px solid black;
    padding-left: 5px;
    padding-right: 5px;
}
div.match_number {
    text-align: right;
}

div.focused {
    background-color: #efefef;
}

div.team_a {
}
div.team_b {
}
div.spacer {
	display: block;
}
</style>
