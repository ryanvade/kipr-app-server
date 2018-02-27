window.Vue = require('vue');
window.events = new Vue();

window.notification = function(type, message) {
  window.events.$emit(`is-${type}`, message);
};

Vue.component('notification', require('./components/util/Notification.vue'));


const app = new Vue({
  el: '#notification'
});
