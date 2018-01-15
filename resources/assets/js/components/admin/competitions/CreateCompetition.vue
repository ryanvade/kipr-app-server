<template lang="html">
  <div class="">
    <div class="loader-wrapper" v-if="loading">
      <div class="loader"></div>
    </div>
    <div class="" v-if="!loading">
        <div class="field">
          <label class="label">Name</label>
          <div class="control">
            <input class="input" type="text" name="name" v-model="name">
          </div>
        </div>
        <div class="field">
          <label class="label">Location</label>
          <div class="control">
            <input class="input" type="text" name="location" v-model="location">
          </div>
        </div>
        <div class="field">
          <label class="label">Start Date</label>
          <div class="control">
            <datetimepicker v-on:change="changeStartDate"></datetimepicker>
          </div>
        </div>
        <div class="field">
          <label class="label">End Date</label>
          <div class="control">
            <datetimepicker v-on:change="changeEndDate"></datetimepicker>
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
import datetimepicker from '../../util/DateTimePicker.vue';
export default {
  components: { datetimepicker },
  data() {
    return {
      loading: true,
      name: '',
      location: '',
      start_date: '',
      end_date: '',

    };
  },
  mounted() {
    this.loading = false;

  },
  methods: {
    submit() {

    },
    changeStartDate(date) {
      this.start_date = date;
    },
    changeEndDate(date) {
      this.end_date = date;
    }
  },
  computed: {

  },
  watch: {

  }
}
</script>

<style lang="css">
</style>
