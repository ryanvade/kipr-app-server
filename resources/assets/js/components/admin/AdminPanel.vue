<!-- Copyright (c) 2018 KISS Institute for Practical Robotics

BSD v3 License

All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

* Redistributions of source code must retain the above copyright notice, this
  list of conditions and the following disclaimer.

* Redistributions in binary form must reproduce the above copyright notice,
  this list of conditions and the following disclaimer in the documentation
  and/or other materials provided with the distribution.

* Neither the name of KIPR Scoring App nor the names of its
  contributors may be used to endorse or promote products derived from
  this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE. -->
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
          <div class="navbar-burger burger" data-target="navMenu" @click="toggleMobileMenu">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
          </div>
        </div>
        <div id="navMenu" :class="navClass">
          <!-- Left side of Navbar -->
          <div class="navbar-start is-hidden-desktop">
            <ul class="menu-list">
              <li>
                <router-link class="navbar-item" :to="{ name: 'index', params: {} }" exact>Admin Home</router-link>
              </li>
              <li>
                <router-link class="" :to="{ name: 'view_all_competitions', params: {} }" exact>View All Competitions</router-link>
              </li>
              <li>
                <router-link class="" :to="{ name: 'create_competition', params: {} }">Create Competition</router-link>
              </li>
              <li>
                <router-link class="" :to="{ name: 'view_all_teams', params: {} }" exact>View All Teams</router-link>
              </li>
              <li>
                <router-link class="" :to="{ name: 'create_team', params: {} }" exact>Create Team</router-link>
              </li>
              <li>
                <router-link class="" :to="{ name: 'ruleset_create', params: {} }" exact>Create Ruleset</router-link>
              </li>
              <li>
                <router-link class="" :to="{ name: 'view_all_documents', params: {} }" exact>View All Documents</router-link>
              </li>
              <li>
                <router-link class="" :to="{ name: 'create_document', params: {} }" exact>Upload Document</router-link>
              </li>
              <li>
                <router-link class="" :to="{ name: 'judging_tokens', params: {} }" exact>Judging Authentication</router-link>
              </li>
              <li>
                <router-link class="" :to="{ name: 'signin_tokens', params: {} }" exact>Sign In Authentication</router-link>
              </li>
            </ul>
          </div>
          <!-- Right side of Navbar -->
          <div class="navbar-right">
            <div class="navbar-item">
              <div class="field is-grouped">
                <p class="control">
                  <a class="button is-active" @click="logout">
                    <span class="icon">
                      <i class="fa fa-sign-out" aria-hidden="true"></i>
                    </span>
                    <span>
                      logout
                    </span>
                  </a>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </nav>
    <div class="container adminpanel-content">
      <!-- Side Nav -->
      <div class="columns">
        <div class="column is-3 is-hidden-touch">
          <aside class="menu">
            <p class="menu-label has-text-weight-bold">Competitions</p>
            <ul class="menu-list">
              <li>
                <router-link class="" :to="{ name: 'view_all_competitions', params: {} }" exact>View All Competitions</router-link>
              </li>
              <li>
                <router-link class="" :to="{ name: 'create_competition', params: {} }">Create Competition</router-link>
              </li>
            </ul>
            <p class="menu-label has-text-weight-bold">Teams</p>
            <ul class="menu-list">
              <li>
                <router-link class="" :to="{ name: 'view_all_teams', params: {} }" exact>View All Teams</router-link>
              </li>
              <li>
                <router-link class="" :to="{ name: 'create_team', params: {} }" exact>Create Team</router-link>
              </li>
              <!-- <li>
                <router-link class="" :to="{ name: 'upload_teams', params: {} }" exact>Upload Teams</router-link>
              </li> -->
            </ul>
            <p class="menu-label has-text-weight-bold">Rulesets</p>
            <ul class="menu-list">
              <li>
                <router-link class="" :to="{ name: 'ruleset_create', params: {} }" exact>Create Ruleset</router-link>
              </li>
            </ul>
            <p class="menu-label has-text-weight-bold">Competition Documents</p>
            <ul class="menu-list">
              <li>
                <router-link class="" :to="{ name: 'view_all_documents', params: {} }" exact>View All Documents</router-link>
              </li>
              <li>
                <router-link class="" :to="{ name: 'create_document', params: {} }" exact>Upload Document</router-link>
              </li>
            </ul>
            <p class="menu-label has-text-weight-bold">Authentication</p>
            <ul class="menu-list">
              <li>
                <router-link class="" :to="{ name: 'judging_tokens', params: {} }" exact>Judging Authentication</router-link>
              </li>
              <li>
                <router-link class="" :to="{ name: 'signin_tokens', params: {} }" exact>Sign In Authentication</router-link>
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
  data() {
    return {
      isActive: false
    };
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
        if (error.response.status == 401) {
          // redirect to login page
          window.notification("warning", "You have been logged out due to inactivity.");
          document.cookie = "notification=danger|You have been logged out due to inactivity";
          window.location.href = "/login";
        }
      });
    },
    toggleMobileMenu() {
      this.isActive = !this.isActive;
    }
  },
  computed: {
    navClass() {
      return (this.isActive)? "navbar-menu is-active": "navbar-menu";
    }
  }
}
</script>

<style lang="css">
</style>
