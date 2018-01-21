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
          <label class="label">Code</label>
          <div class="control">
            <input :class="codeInputClass" type="text" name="code" v-model="code" @blur="verifyLocation">
            <p class="help is-danger" v-if="badLocation != ''">{{ badLocation }}</p>
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
      code: ''
    };
  }
}
</script>

<style lang="css">
</style>
