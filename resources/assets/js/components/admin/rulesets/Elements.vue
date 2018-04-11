<template lang="html">
  <div class="box">
    <nav class="level">
      <div class="level-left">
        <p class="subtitle has-text-centered">
          <strong>Upload Game Elements</strong>
        </p>
      </div>
    </nav>
    <div>
        <table class="table is-hoverable is-fullwidth">
          <thead>
            <tr>
              <th>Element Name</th>
              <th>Quantity</th>
              <th>Image</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="element in elements">
                <td><input type="text" v-model="element.name"/></td>
                <td><input type="number" v-model="element.quantity" min="1" max="99"/></td>
              <td>
             <form enctype="multipart/form-data">
                 <div class="level">
                     <div class="level-left">
                    <img class="level-item preview" :src="element.image"/>
                    <input class="level-item" accept="image/*" type="file" @change="update_image(element, $event.target.files)" required>
                     </div>
                     <a class="level-right" @click="_delete(element)">Delete</a>
                 </div>
              </form>
              </td>
            </tr>
            <tr>
                <a @click="add_element">Add element</a>
            </tr>
          </tbody>
        </table>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Settings',
  props: ["settings"],
  data() {
    return {
        elements: []
    };
  },
  mounted() {
  },
  methods: {
      update() {
        this.$emit("update", this.elements);
      },
      update_image(element, files) {
          var url = window.URL.createObjectURL(files[0]);
          element.image = url;
          this.update();
      },
      add_element(event) {
          var id = this.elements.length;
          this.elements.push({name:"Element " + id, quantity:1, image:null, id:id});
          this.update();
      },
      _delete(element) {
          var elements = []
          for(var e of this.elements) {
              console.log(e)
              if(e.id != element.id) {
                  e.id = elements.length
                  elements.push(e)
              }
          }
          this.elements = elements;
          this.update();
      }
  },
  directives: {
  }
}
</script>

<style lang="css">
img.preview {
    height: 75px;
}
</style>
