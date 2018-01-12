<template lang="html">
  <div class="adminpanel-wrapper">
    <!-- Top Panel -->
    <nav class="navbar" role="navigation" aria-label="main navigation">
      <div class="container">
        <div class="navbar-brand">
          <a class="navbar-item brand-text" href="/">
            <img src="/images/botguy.png" alt="Botguy" style="height: 40px!important; margin-right: 3px;">
            KIPR Scoring App
          </a>
          <div class="navbar-burger burger" data-target="navMenu">
            <span></span>
            <span></span>
            <span></span>
          </div>
        </div>
        <div id="navMenu" class="navbar-menu">
          <!-- Left side of Navbar -->
          <div class="navbar-start">
            <router-link class="navbar-item" :to="{ name: 'index', params: {} }" exact>Admin Home</router-link>
          </div>
          <!-- Right side of Navbar -->
          <div class="navbar-right">
            <div class="">
              <div class="navbar-item">
                  <button type="submit" name="logout" class="button is-text logout-button" @click="logout">logout</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </nav>
    <div class="container">
      <!-- Side Nav -->
      <div class="columns">
        <div class="column is-3 is-hidden-touch">
          <aside class="menu">
            <p class="menu-label has-text-weight-bold">Competitions</p>
            <ul class="menu-list">
              <li>
                <router-link class="" :to="{ name: 'create_competition', params: {} }">Create Competition</router-link>
              </li>
            </ul>
            <p class="menu-label has-text-weight-bold">Rulesets</p>
            <ul class="menu-list">
              <li>
                <router-link class="" :to="{ name: 'ruleset_create', params: {} }">Create Ruleset</router-link>
              </li>
            </ul>
            <p class="menu-label has-text-weight-bold">Authentication</p>
            <ul class="menu-list">
              <li>
                <router-link class="" :to="{ name: 'judging_tokens', params: {} }">Judging Authentication</router-link>
              </li>
              <li>
                <router-link class="" :to="{ name: 'signin_tokens', params: {} }">Sign In Authentication</router-link>
              </li>
            </ul>
          </aside>
        </div>
        <div class="column is-9 admin-panel-content">
          <!-- http://via.placeholder.com/350x150 -->
          <!-- Router View -->
          <router-view></router-view>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
  },
  methods: {
    logout() {
      window.axios.post('/logout').then((response) => {
        console.log(response);
        if (response.status == 200 && response.statusText == "OK") {
          window.location.href = window.location.origin;
        }
      }).catch((error) => {
        console.error(error);
      });
    }
  }
}
</script>

<style lang="css">
</style>
