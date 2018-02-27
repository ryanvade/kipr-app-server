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
            <a class="button is-warning" @click="$router.push('/admin/teams/' + team.id)">Cancel</a>
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
      team: null,
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
    let id = this.$route.params.id;
    this.getTeam(id);
  },
  methods: {
    getTeam(id) {
      window.axios.get(`/api/team/${id}`).then((response) => {
        console.log(response);
        this.team = response.data;
        this.name = this.team.name;
        this.email = this.team.email;
        this.code = this.team.code;
        this.loading = false;
      }).catch((error) => {
        console.error(error);
        window.notification("danger", error.message);
        if (error.response.status == 404) {
          this.showMissingTeam = true;
        }
      });
    },
    verifyName() {
      this.badName = '';
      if (this.name == '') {
        this.badName = "The name cannot be empty.";
        return false;
      }
      return true;
    },
    verifyCode() {
      this.badCode = '';
      if (this.code == '') {
        this.badCode = "The code cannot be empty.";
        return false;
      }
      return true;
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
      let id = this.team.id;
      window.axios.patch(`/api/team/${id}`, {
        name: this.name,
        email: this.email,
        code: this.code
      }).then((response) => {
        window.notification("success", "Team Updated");
        console.log(response);
        let id = response.data.team.id;
        this.$router.push(`/admin/teams/${id}`);
      }).catch((error) => {
        console.error(error);
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
