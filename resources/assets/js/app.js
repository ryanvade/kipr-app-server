// Copyright (c) 2018 KISS Institute for Practical Robotics
//
// BSD v3 License
//
// All rights reserved.
//
// Redistribution and use in source and binary forms, with or without
// modification, are permitted provided that the following conditions are met:
//
// * Redistributions of source code must retain the above copyright notice, this
//   list of conditions and the following disclaimer.
//
// * Redistributions in binary form must reproduce the above copyright notice,
//   this list of conditions and the following disclaimer in the documentation
//   and/or other materials provided with the distribution.
//
// * Neither the name of KIPR Scoring App nor the names of its
//   contributors may be used to endorse or promote products derived from
//   this software without specific prior written permission.
//
// THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
// AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
// IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
// DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
// FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
// DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
// SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
// CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
// OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
// OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.





require('./bootstrap');

window.Vue = require('vue');
window.moment = require('moment-timezone');

import AdminPanel from './components/admin/AdminPanel.vue';
import createPersistedState from 'vuex-persistedstate';
import Modal from './components/Modal.vue';
import VueRouter from 'vue-router';
import Echo from "laravel-echo";
import Vuex from 'vuex';

Vue.use(VueRouter);
Vue.use(Vuex);

window.Echo = new Echo({
  broadcaster: 'socket.io',
  host: window.location.hostname + ":3000",
  namespace: "KIPR.Events"
});

const store = new Vuex.Store({
  plugins: [createPersistedState()],
  state: {
    access_token: null,
    expires_at: null,
    refresh_token: null,
    token_type: null,
    map_image: null,
    scoring_zones: [],
    competition: null,
  },
  mutations: {
    access_token(state, token) {
      state.access_token = token;
    },
    expires_at(state, date) {
      state.expires_at = date;
    },
    refresh_token(state, token) {
      state.refresh_token = token;
    },
    token_type(state, type) {
      state.token_type = type;
    },
    update_map(state, image) {
      state.map_image = image;
    },
    update_zones(state, zone_list) {
      state.zones = zone_list;
    },
    set_competition(state, competition) {
      state.competition = competition;
    }
  }
});

const routes = [{
    path: '/admin',
    name: 'index',
    component: require('./components/admin/index.vue')
  },
  {
    path: '/admin/ruleset/create',
    name: 'ruleset_create',
    component: require('./components/admin/rulesets/NewRuleset.vue')
  },
  {
    path: '/admin/ruleset/zones',
    name: 'ruleset_zones',
    component: require('./components/admin/rulesets/zones.vue')
  },
  {
    path: '/admin/tokens/judging',
    name: 'judging_tokens',
    component: require('./components/admin/auth/JudgingTokens.vue')
  },
  {
    path: '/admin/tokens/signin',
    name: 'signin_tokens',
    component: require('./components/admin/auth/SignInTokens.vue')
  },
  {
    path: '/admin/competitions',
    name: 'view_all_competitions',
    component: require('./components/admin/competitions/ViewAll.vue')
  },
  {
    path: '/admin/competitions/create',
    name: 'create_competition',
    component: require('./components/admin/competitions/CreateCompetition.vue')
  },
  {
    path: '/admin/competitions/:id',
    name: 'view_competition',
    component: require('./components/admin/competitions/View.vue')
  },
  {
    path: '/admin/competitions/:id/edit',
    name: 'edit_competition',
    component: require('./components/admin/competitions/Edit.vue')
  },
  {
    path: '/admin/competitions/:id/teams/register',
    name: 'register_teams',
    component: require('./components/admin/competitions/RegisterTeams.vue')
  },
  {
    path: '/admin/matches/:id',
    name: 'view_match',
    component: require('./components/admin/matches/View.vue')
  },
  {
    path: '/admin/teams',
    name: 'view_all_teams',
    component: require('./components/admin/teams/ViewAll.vue')
  },
  {
    path: '/admin/teams/upload',
    name: 'upload_teams',
    component: require('./components/admin/teams/MassUpload.vue')
  },
  {
    path: '/admin/teams/:id',
    name: 'view_team',
    component: require('./components/admin/teams/View.vue')
  },
  {
    path: '/admin/teams/:id/competitions/register',
    name: 'register_competition',
    component: require('./components/admin/teams/RegisterCompetitions.vue')
  },
  {
    path: '/admin/teams/:id/edit',
    name: 'edit_team',
    component: require('./components/admin/teams/Edit.vue')
  },
  {
    path: '/admin/teams/create',
    name: 'create_team',
    component: require('./components/admin/teams/CreateTeam.vue')
  },
  {
    path: '/admin/documents',
    name: 'view_all_documents',
    component: require('./components/admin/documents/ViewAll.vue')
  },
  {
    path: '/admin/documents/create',
    name: 'create_document',
    component: require('./components/admin/documents/CreateDocument.vue')
  },
  {
    path: '/admin/documents/:id',
    name: 'view_document',
    component: require('./components/admin/documents/View.vue')
  }
];

const router = new VueRouter({
  mode: 'history',
  routes: routes
});

const app = new Vue({
  components: {
    'admin': AdminPanel,
  },
  router,
  store,
  data: {
    user: null,
  },
  created() {},
  methods: {}
}).$mount('#app');

window.setInterval(() => {
  window.axios.get('/oauth/personal-access-tokens').then((response) => {
    console.log(response);
  }).catch((error) => {
    console.error(error);
    if (error.response.status == 401 || error.response.status == 404) {
      document.cookie = "notification=danger|You have been logged out due to inactivity";
      window.location.href = "/login";
    }
  });
}, 300000);
