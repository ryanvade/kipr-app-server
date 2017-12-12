/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router'

import Vuex from 'vuex'

Vue.use(VueRouter)
Vue.use(Vuex)

// Vue.component('example-component', require('./components/ExampleComponent.vue'));
const routes = [
  {
  path: '/login',
  name: 'login',
  component: require('./components/login.vue')
  },
  {
  path: '/',
  name: 'panel',
  component: require('./components/panel.vue')
  },
  {
  path: '/ruleset/create',
  name: 'ruleset_create',
  component: require('./components/map.vue')
  },
  {
  path: '/ruleset/zones',
  name: 'zones',
  component: require('./components/zones.vue')
  }
];

const router = new VueRouter({
  routes
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
  router,
  store,
  data: {
    user: null,
  },
  created() {
    axios.get('/api/user').then((response) => {
      // console.log(response);
    }).catch((error) => {
      console.log("Need to login");
      this.$router.push('/login');
    });
  },
  methods: {
  }
}).$mount('#app')
