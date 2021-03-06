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
            <p class="help is-success" v-if="validName">Name is available.</p>
          </div>
        </div>
        <div class="field">
          <label class="label">Location</label>
          <div class="control">
            <input :class="locationInputClass" type="text" name="location" v-model="location" @blur="verifyLocation">
            <p class="help is-danger" v-if="badLocation != ''">{{ badLocation }}</p>
          </div>
        </div>
        <div class="field">
          <label class="label">Start Date</label>
          <div class="control">
            <datetimepicker v-on:change="changeStartDate" :initial="start_date" name="start_date"></datetimepicker>
            <p class="help is-danger" v-if="badStartDate != ''">{{ badStartDate }}</p>
          </div>
        </div>
        <div class="field">
          <label class="label">End Date</label>
          <div class="control">
            <datetimepicker v-on:change="changeEndDate" :initial="end_date" name="end_date"></datetimepicker>
            <p class="help is-danger" v-if="badEndDate != ''">{{ badEndDate }}</p>
          </div>
        </div>
        <div class="field is-grouped is-grouped-right">
          <div class="control">
            <a class="button is-warning" @click="$router.push('/admin/competitions/' + competition.id)">Cancel</a>
          </div>
          <div class="control">
            <button class="button is-primary" @click="submit">Update</button>
          </div>
        </div>
    </div>
  </div>

</template>

<script>
import datetimepicker from '../../util/DateTimePicker.vue';
export default {
  components: {
    datetimepicker
  },
  data() {
    return {
      competition: null,
      loading: true,
      name: '',
      location: '',
      start_date: '',
      end_date: '',
      badName: '',
      badLocation: '',
      badStartDate: '',
      badEndDate: '',
      names: null,
      validName: false
    };
  },
  mounted() {
    let id = this.$route.params.id;
    this.getCompetition(id);
  },
  methods: {
    submit() {
      if (!this.verifyName() || !this.verifyLocation() || !this.verifyDates()) {
        console.error("Cannot submit the form");
        return;
      }
      let id = this.competition.id;
      window.axios.patch(`/api/competition/${id}`, {
        name: this.name,
        location: this.location,
        startDate: this.start_date,
        endDate: this.end_date
      }).then((response) => {
        console.log(response);
        window.notification("success", "Competition Updated");
        this.$router.push('/admin/competitions/' + response.data.competition.id);
      }).catch((error) => {
        window.notification("danger", error.message);
        if (error.response.status == 422) {
          let self = this;
          error.response.data.errors.name.forEach((error) => {
            if (error == "The name has already been taken.") {
              this.validName = false;
              this.badName = "The name has already been taken.";
            }
          });
        } else {
          console.error(error);
          if (error.response.status == 401) {
            // redirect to login page
            window.notification("warning", "You have been logged out due to inactivity.");
            document.cookie = "notification=danger|You have been logged out due to inactivity";
            window.location.href = "/login";
          }
        }
      });

    },
    changeStartDate(date) {
      this.start_date = date;
      this.verifyDates();
    },
    changeEndDate(date) {
      this.end_date = date;
      this.verifyDates();
    },
    async verifyName() {
      this.badName = '';
      if (this.names == null) {
        this.names = await this.getNames();
      }

      if (this.name == '') {
        this.badName = "Name cannot be empty.";
        return false;
      } else if (this.names.includes(this.name) && this.name != this.competition.name) {
        this.validName = false;
        this.badName = "The name has already been taken.";
        return false;
      }
      this.validName = true;
      return true;
    },
    verifyLocation() {
      this.badLocation = '';
      if (this.location == '') {
        this.badLocation = 'Location cannot be empty.';
        return false;
      }
      return true;
    },
    verifyDates() {
      this.badStartDate = "";
      this.badEndDate = "";

      if (this.end_date == this.start_date) {
        this.badEndDate = "End date cannot be the same as the start date";
        return false;
      } else if (moment(this.end_date, 'M/D/YYYY h:mA').unix() < moment(this.start_date, 'M/D/YYYY h:mA').unix()) {
        this.badEndDate = "End date must be after the start date.";
        return false;
      }

      if (this.start_date == this.end_date) {
        this.badStartDate = "Start date cannot be the same as the end date";
        return false;
      } else if (moment(this.start_date, 'M/D/YYYY h:mA').unix() > moment(this.end_date, 'M/D/YYYY h:mA').unix()) {
        this.badStartDate = "Start date must be before the end date.";
        return false;
      }
      return true;
    },
    async getNames() {
      const response = await window.axios.get('/api/competition/names');
      // console.log(response);
      return response.data;
    },
    getCompetition(id) {
      window.axios.get(`/api/competition/${id}`).then((response) => {
        console.log(response);
        this.competition = response.data;
        this.name = this.competition.name;
        this.start_date = this.competition.start_date;
        this.end_date = this.competition.end_date;
        this.location = this.competition.location;
        this.loading = false;
      }).catch((error) => {
        console.error(error);
        if (error.response.status == 401) {
          // redirect to login page
          window.notification("warning", "You have been logged out due to inactivity.");
          document.cookie = "notification=danger|You have been logged out due to inactivity";
          window.location.href = "/login";
        }
        window.notification("danger", error.message);
        if (error.response.status == 404) {
          this.showMissingCompetition = true;
        }
      });
    },
  },
  computed: {
    validLocation() {
      return this.location != '' && this.badLocation == '';
    },
    nameInputClass() {
      if (this.validName) {
        return 'input is-success';
      } else if (this.badName != '') {
        return 'input is-danger';
      }
      return 'input';
    },
    locationInputClass() {
      if (this.badLocation != '') {
        return 'input is-danger';
      } else if (this.location != '' && this.badLocation == '') {
        return 'input is-success';
      }
      return 'input';
    }
  },
  watch: {

  }
}
</script>

<style lang="css">
</style>
