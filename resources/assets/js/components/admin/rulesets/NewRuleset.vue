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
    <div>
        <upload @map_changed="map_changed" v-bind:map_image="map_image"/>
        <settings v-if="map_image.src" @settings_changed="settings_changed"/>
        <zones @update="zones_changed" v-if="map_image.src" ref="zones" :map_image="map_image" v-bind:map_settings="map_settings"/>
        <elements @update="elements_changed" v-bind:map_settings="map_settings"/>
        <rules @update="rules_changed" v-if= "elements.length > 0 && zones.length > 0" :elements="elements" :zones="zones"/>
        <!--<div class="box">-->
            <!--<nav class="level">-->
                <!--<div class="level-left">-->
                    <!--<p class="subtitle has-text-centered">-->
                        <!--<strong>Create Scoring Rules</strong>-->
                        <!--<button class="button">Submit</button>-->
                    <!--</p>-->
                <!--</div>-->
            <!--</nav>-->
            <button type="button" class="button is-warning" @click="cancel">Cancel</button>
            <button type="button" class="button is-primary" @click="submit">Submit</button>
        <!--</div>-->
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
      },
      submit(event) {
          console.log(this.elements);
          console.log(this.rules);
          var elements = {};

          for (var element of this.elements) {
              elements[element.name] = {count: element.quantity};
          }

          var events = {};
          var rules = [];
          for (var group of this.rules) {
              for (var rule of group.rules) {
                  var rule_name = rule.element_name +" in " + rule.zone_name;
                  events[rule_name] = {min: 0, max: elements[rule.element_name].count};

                  rules.push({type: "multiplier", value: rule.value, target:rule_name});
              }
          }
          window.axios.post('/api/ruleset', {
            name: "NEW RULESET",
            events: events,
            rules: rules
          }).then((response) => {
            console.log(response);
            window.notification("success", "Ruleset Created");
            this.$router.push('/admin/');
          }).catch((error) => {
            window.notification("danger", error.message);
            console.error(error);
            if (error.response.status == 401) {
              // redirect to login page
              window.notification("warning", "You have been logged out due to inactivity.");
              document.cookie = "notification=danger|You have been logged out due to inactivity";
              window.location.href = "/login";
            }
          });
      },
      cancel(event) {
          this.$router.push('/admin/');
      }
  },
  mounted(){
  },
}
</script>

<style lang="css">
</style>
