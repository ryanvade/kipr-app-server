window.Vue = require('vue');
window.events = new Vue();

window.notification = function(type, message) {
  window.events.$emit(`is-${type}`, message);
};

Vue.component('notification', require('./components/util/Notification.vue'));


const app = new Vue({
  el: '#notification'
});

if (document.cookie.includes("notification")) {
  console.log("Display Notification From Cookie");
  cookie_value = document.cookie.match(new RegExp("notification" + '=([^;]+)'));
  parts = cookie_value[1].split("|");
  window.notification(parts[0], parts[1]);
  document.cookie = "notification=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
}
