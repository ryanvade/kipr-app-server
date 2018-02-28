<template lang="html">
  <button v-if="editing == false" class="zone_button button is-primary is-medium is-fullwidth" @mouseover="hover" @dblclick="edit">
      {{ zoneName }}
  </button>
  <div v-else-if="editing == true" class="zone_button button is-primary is-medium is-fullwidth">
    <input ref="name" class="input" type="text" placeholder="Zone Name" v-model="zoneName" @keyup.enter="setName" @mouseover="hover">
  </div>
</template>

<script>
export default {
  props: ["region"],
  data() {
    return {
      zoneName: "",
      editing: true
    };
  },
  mounted() {
    this.edit();
  },
  methods: {
    edit() {
      this.editing = true;
      this.$refs.name.focus();
    },
    setName() {
      this.$refs.name.blur();
      this.editing = false;
    },
    hover() {
      this.$emit("preview", {
        name: this.zoneName,
        region: this.region
      });
    }
  },
}
</script>
