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
  <section class="hero is-fullheight is-medium">
        <div class="hero-body">
          <div class="container">
            <div class="columns is-centered"">
              <article class="card is-rounded">
                <div class="card-content">
                  <h1 class="title is-flex">
                    <span>Login</span>
                    <!-- <img src="/images/kipr_logo.jpg" alt="logo" class="loginlogo"> -->
                  </h1>
                  <!-- <p class="control has-icon">
                    <input class="input" type="email" placeholder="Email">
                    <i class="fa fa-envelope"></i>
                  </p> -->
                  <div class="field">
                    <label class="label">Username</label>
                    <div class="control has-icons-left has-icons-right">
                      <input class="input" type="text" placeholder="username" v-model="username">
                      <span class="icon is-small is-left">
                        <i class="fa fa-user"></i>
                      </span>
                      <span class="icon is-small is-right">
                        <i class="fa fa-check"></i>
                      </span>
                    </div>
                    <p class="help is-success is-hidden">This username is available</p>
                  </div>
                  <div class="field">
                    <label class="label">Password</label>
                    <div class="control has-icons-left has-icons-right">
                      <input class="input" type="password" placeholder="" v-model="password">
                    </div>
                  </div>
                  <p class="control">
                    <button class="button is-primary is-medium is-fullwidth" @click="login">
                      <i class="fa fa-user"></i>
                      Login
                    </button>
                  </p>
                </div>
              </article>
            </div>
          </div>
    </div>
  </section>
</template>

<script>
export default {
  data() {
    return {
      username: '',
      password: '',
      client_id: '',
      client_secret: ''
    }
  },
  created() {
    this.client_id = document.head.querySelector('meta[name="client_id"]').content;
    this.client_secret = document.head.querySelector('meta[name="client_secret"]').content;
  },
  methods: {
    login() {
      axios.post('/oauth/token', {
        'grant_type': 'password',
        'client_id': this.client_id,
        'client_secret': this.client_secret,
        'username': this.username,
        'password': this.password,
        'scope': '*',
      }).then((response) => {
        console.log(response);
        store.commit('acess_token', response.data.access_token);
        store.commit('expires_at', (new Date()).getTime() + response.data.expires_in);
        store.commit('refresh_token', response.data.refresh_token);
        store.commit('token_type', response.data.token_type);
      }).catch(this.showError(error));
    },
    showError(error) {
      console.error(error);
      if (error.response.status == 401) {
        // redirect to login page
        window.notification("warning", "You have been logged out due to inactivity.");
        document.cookie = "notification=danger|You have been logged out due to inactivity";
        window.location.href = "/login";
      }
    }
  }
}
</script>

<style lang="css">
.loginlogo {
width: 115px;
height: 115px;
margin: 0 auto;
}
</style>
