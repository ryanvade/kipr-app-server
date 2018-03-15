<template lang="html">
  <div class="box">
    <nav class="level">
      <div class="level-left">
        <p class="subtitle has-text-centered">
          <strong>Upload map image</strong>
        </p>
      </div>
    </nav>
 <form enctype="multipart/form-data">
     <div>
        <input accept="image/*" type="file" @change="update_image($event.target.name, $event.target.files)" required>
     </div>
    <img id="preview"/>
  </form>
  </div>
</template>

<script>
export default {
  components: {
  },
  data() {
    return {};
  },
  mounted() {},
  methods: {
    update_image(name, file) {
      var url = window.URL.createObjectURL(file[0]);
      document.getElementById('preview').src = url;
      this.$store.commit('update_map', url);
      this.map_image = url;
      this.$emit("map_changed", {src: url, width: 400, height: 400});
    }
  }
}
</script>

<style lang="css">

form #preview {
    display: inline-block;
    width: 300px;
    height: 300px;
    border: 1px solid black;
    margin: auto 0;
}

.button {
    margin: 10px auto;
}

</style>
