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
  <div class="box">
    <nav class="level">
      <div class="level-left">
        <p class="subtitle has-text-centered">
          <strong>Create Scoring Rules</strong>
        </p>
      </div>
    </nav>
    <div>
        <div class="box" v-for="group in rules">
            <div v-if="group.default">
                <strong>Default rules</strong>
            </div>
            <div v-else class="level">
                <div class="level-left">
                    <strong class=level-item>If</strong>
                    <select class="level-item" v-model="group.element1">
                        <option v-for="element in elements">
                            {{ element.name }}
                        </option>
                    </select>
                    <strong class="level-item">In</strong>
                    <select class="level-item" v-model="group.zone1">
                        <option v-for="zone in zones">
                            {{ zone.name }}
                        </option>
                    </select>
                    <select class="level-item" v-model="group.equality">
                        <option>==</option>
                        <option>!=</option>
                    </select>
                    <select class="level-item" v-model="group.element2">
                        <option v-for="element in elements">
                            {{ element.name }}
                        </option>
                        <option>Constant</option>
                    </select>
                    <div class="level-left" v-if="group.element2 != 'Constant'">
                        <strong class="level-item">In</strong>
                        <select class="level-item" v-model="group.zone2">
                            <option v-for="zone in zones">
                                {{ zone.name }}
                            </option>
                        </select>
                    </div>
                    <div v-else>
                        <input type="number"/>
                    </div>
                    <strong class="level-item">Then</strong>
                </div>
                <div class="level-right">
                    <!--<p class="level-item">-->
                        <!--<a>Edit</a>  -->
                    <!--</p>-->
                    <p class="level-item">
                        <a>Delete</a>
                    </p>
                </div>
            </div>
            <table class="table is-hoverable is-fullwidth">
              <tbody>
                <tr v-for="rule in group.rules">
                    <strong>Each</strong>
                    <select v-model="rule.element_name">
                        <option>Everything</option>
                        <option v-for="element in elements">
                            {{ element.name }}
                        </option>
                    </select>
                    <strong>In</strong>
                    <select v-model="rule.zone_name">
                        <option>Anywhere</option>
                        <option v-for="zone in zones">
                            {{ zone.name }}
                        </option>
                    </select>
                    <select v-model="rule.score_type">
                        <option>Is worth</option>
                        <option>Is multiplied by</option>
                    </select>
                    <input type="number" v-model="rule.value"/>
                    <a>Delete</a>
                </tr>
                <tr><a @click="add_rule(group)">Add Rule</a></tr>
              </tbody>
            </table>
        </div>
        <a @click="add_group">Add Group</a>
    </div>
  </div>
</template>

<script>
export default {
  props: ["elements", "zones"],
  data() {
    return {
        rules: [{default: true, rules:[]}]
    };
  },
  mounted() {
  },
  methods: {
      update() {
        this.$emit("update", this.rules);
      },
      add_rule(group) {
          group.rules.push({element_name:this.elements[0].name, zone_name:this.zones[0].name, score_type:"Is worth", value: 1})
          this.update();
      },
      add_group(event) {
          this.rules.push({default: false, element1: this.elements[0].name, zone1: this.zones[0].name, element2: this.elements[0].name, zone2: this.zones[0].name, equality:"==", rules: []});
          this.update();
      }
  },
  directives: {
  }
}
</script>

<style lang="css">
select {
    /*background-color: lightgray;*/
}
table {
    left-margin: 20px;
}
</style>
