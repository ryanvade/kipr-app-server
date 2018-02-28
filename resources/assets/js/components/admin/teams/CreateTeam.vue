<template lang="html">
  <div class="">
    <div class="loader-wrapper" v-if="loading">
      <div class="loader"></div>
    </div>
    <div class="" v-if="!loading">
        <div class="field">
          <label class="label">Name</label>
          <div class="control">
            <input :class="nameInputClass" type="text" name="name" v-model="name" @blur="verifyName">
            <p class="help is-danger" v-if="badName != ''">{{ badName }}</p>
          </div>
        </div>
        <div class="field">
          <label class="label">Email</label>
          <div class="control">
            <input :class="emailInputClass" type="email" name="location" v-model="email" @blur="verifyEmail">
            <p class="help is-danger" v-if="badEmail != ''">{{ badEmail }}</p>
          </div>
        </div>
        <div class="field">
          <label class="label">Code</label>
          <div class="control">
            <input :class="codeInputClass" type="text" name="code" v-model="code" @blur="verifyCode">
            <p class="help is-danger" v-if="badCode != ''">{{ badCode }}</p>
          </div>
        </div>
        <div class="field is-grouped is-grouped-right">
          <div class="control">
            <router-link class="button is-warning" :to="{ name: 'index', params: {} }" exact>Cancel</router-link>
          </div>
          <div class="control">
            <button class="button is-primary" @click="submit">Submit</button>
          </div>
        </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      name: '',
      email: '',
      code: '',
      badName: '',
      badEmail: '',
      badCode: '',
      loading: true,
      EMAIL_REGEX: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
    };
  },
  mounted() {
    this.loading = false;
  },
  methods: {
    verifyName() {
      this.badName = '';
      if (this.name == '') {
        this.badName = "The name cannot be empty.";
        return false;
      }
      return true;
    },
    async verifyCode() {
      this.badCode = '';
      if (this.code == '') {
        this.badCode = "The code cannot be empty.";
        return false;
      }
      return await window.axios.get('/api/team/?code=' + this.code).then((data) => {
        if (data.data.data.length > 0) {
          this.badCode = "The code is taken.";
          return false;
        }
        return true;
      }).catch((error) => {
        console.error(error);
        if (error.response.status == 401) {
          // redirect to login page
          window.notification("warning", "You have been logged out due to inactivity.");
          document.cookie = "notification=danger|You have been logged out due to inactivity";
          window.location.href = "/login";
        }
        window.notification("danger", error.message);
        return false;
      });
    },
    verifyEmail() {
      this.badEmail = '';
      if (this.email == '') {
        this.badEmail = "The email cannot be empty.";
        return false;
      } else if (!this.EMAIL_REGEX.test(this.email.toLowerCase())) {
        this.badEmail = "Not a valid email address."
        return false;
      }
      return true;
    },
    submit() {
      if (!this.verifyName() || !this.verifyCode() || !this.verifyEmail()) {
        return;
      }
      window.axios.post('/api/team', {
        name: this.name,
        email: this.email,
        code: this.code
      }).then((response) => {
        console.log(response);
        let id = response.data.team.id;
        this.$router.push(`/admin/teams/${id}`);
        window.notification('success', "Team Created");
      }).catch((error) => {
        console.error(error);
        if (error.response.status == 401) {
          // redirect to login page
          window.notification("warning", "You have been logged out due to inactivity.");
          document.cookie = "notification=danger|You have been logged out due to inactivity";
          window.location.href = "/login";
        }
        window.notification("danger", error.message);
      });
    }
  },
  computed: {
    nameInputClass() {
      if (this.name != '' && this.badName == '') {
        return 'input is-success';
      } else if (this.badName != '') {
        return 'input is-danger';
      }
      return 'input';
    },
    emailInputClass() {
      if (this.email != '' && this.badEmail == '') {
        return 'input is-success';
      } else if (this.badEmail != '') {
        return 'input is-danger';
      }
      return 'input';
    },
    codeInputClass() {
      if (this.code != '' && this.badCode == '') {
        return 'input is-success';
      } else if (this.badCode != '') {
        return 'input is-danger';
      }
      return 'input';
    }
  }
}
</script>

<style lang="css">
</style>
