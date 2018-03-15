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
        <div v-for="group in rules">
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
                    </select>
                    <strong class="level-item">In</strong>
                    <select class="level-item" v-model="group.zone2">
                        <option v-for="zone in zones">
                            {{ zone.name }}
                        </option>
                    </select>
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
                        <option v-for="element in elements">
                            {{ element.name }}
                        </option>
                    </select>
                    <strong>In</strong>
                    <select v-model="rule.zone_name">
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
            <hr>
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
        this.$emit("rules_changed", this.rules);
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
</style>
