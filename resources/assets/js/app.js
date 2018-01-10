/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import AdminPanel from './components/admin/AdminPanel.vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';

Vue.use(VueRouter)
Vue.use(Vuex)

// Vue.component('example-component', require('./components/ExampleComponent.vue'));
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
  }
];

const router = new VueRouter({
  mode: 'history',
  routes: routes
});

const store = new Vuex.Store({
  state: {
    access_token: null,
    expires_at: null,
    refresh_token: null,
    token_type: null,
    map_image: null,
    scoring_zones: [],
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
    }
  }
})

const app = new Vue({
  components: {
    'admin': AdminPanel
  },
  router,
  store,
  data: {
    user: null,
  },
  created() {},
  methods: {}
}).$mount('#app');
