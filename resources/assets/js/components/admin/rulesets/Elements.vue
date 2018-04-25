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
