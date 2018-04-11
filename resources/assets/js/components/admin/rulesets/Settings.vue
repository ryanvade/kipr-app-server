<template lang="html">
  <div class="box">
    <nav class="level">
      <div class="level-left">
        <p class="subtitle has-text-centered">
          <strong>Settings</strong>
        </p>
      </div>
    </nav>
    <div>
      <input type="checkbox" v-model="map_settings.mirror_x" @change="update"/><label>Mirror on X</label>
      <input type="checkbox" v-model="map_settings.mirror_y" @change="update"/><label>Mirror on Y</label>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Settings',
  props: ["settings"],
  data() {
    return {
        map_settings: {mirror_x: false, mirror_y: false}
    };
  },
  mounted() {
  },
  methods: {
      update() {
        this.$emit("settings_changed", this.map_settings);
      }
  },
  directives: {
  }
}
</script>

<style lang="css">
</style>
