<template lang="html">
    <div>
        <upload @map_changed="map_changed" v-bind:map_image="map_image"/>
        <settings v-if="map_image.src" @settings_changed="settings_changed"/>
        <zones @update="zones_changed" v-if="map_image.src" ref="zones" :map_image="map_image" v-bind:map_settings="map_settings"/>
        <elements @update="elements_changed" v-bind:map_settings="map_settings"/>
        <rules @update="rules_changed" v-if= "elements.length > 0 && zones.length > 0" :elements="elements" :zones="zones"/>
        <button v-if="rules.length > 0">Submit</button>
    </div>
</template>

<script>
import upload from "./map.vue"
import zones from "./zones.vue"
import Settings from "./Settings.vue"
import Elements from "./Elements.vue"
import Rules from "./Rules.vue"

export default {
  components: {
    'settings': Settings,
    'elements': Elements,
    'rules': Rules,
    zones,
    upload
  },
  data() {
    return {
        map_image: {src: null, width: 0, height: 0},
        map_settings: {mirror_x: false, mirror_y: false},
        zones: [],
        elements: [],
        rules: []
    }
  },
  methods: {
      map_changed(event) {
          this.map_image = event;
      },
      settings_changed(event) {
          this.map_settings = event;
      },
      elements_changed(event) {
          this.elements = event;
      },
      zones_changed(event) {
          this.zones = event;
      },
      rules_changed(event) {
          this.rules = event;
      }
  },
  mounted(){
  },
}
</script>

<style lang="css">
</style>

