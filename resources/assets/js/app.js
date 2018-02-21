/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.moment = require('moment-timezone');

import AdminPanel from './components/admin/AdminPanel.vue';
import createPersistedState from 'vuex-persistedstate';
import Modal from './components/Modal.vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';

Vue.use(VueRouter);
Vue.use(Vuex);

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
    component: require('./components/admin/rulesets/map.vue')
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
    path: '/admin/teams/:id/edit',
    name: 'edit_team',
    component: require('./components/admin/teams/Edit.vue')
  },
  {
    path: '/admin/teams/create',
    name: 'create_team',
    component: require('./components/admin/teams/CreateTeam.vue')
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
