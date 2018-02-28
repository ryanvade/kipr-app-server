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
