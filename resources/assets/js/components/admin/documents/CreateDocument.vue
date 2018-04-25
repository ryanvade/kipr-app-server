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
  <div class="create-document-page">
    <div class="field">
      <label class="label">Document Name</label>
      <div class="control">
        <input type="text" class="input" name="name" v-model="name">
      </div>
    </div>
    <div class="file has-name">
      <label class="file-label">
        <input class="file-input" type="file" name="file" @change="onFileChange">
        <span class="file-cta">
          <span class="file-icon">
            <i class="fas fa-upload"></i>
          </span>
          <span class="file-label">
            Choose a file…
          </span>
        </span>
        <span class="file-name" v-if="file != null">
          {{ fileName }}
        </span>
      </label>
    </div>
    <div class="field is-grouped is-grouped-right">
      <p class="control">
        <button class="button is-warning" @click="$router.push('/admin/documents')">Cancel</button>
      </p>
      <p class="control">
        <button class="button is-primary" @click="submit">Submit</button>
      </p>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      name: '',
      file: null,
      fileName: ""
    };
  },
  methods: {
    onFileChange(event) {
      let files = event.target.files || event.dataTransfer.files;
      this.fileName = files[0].name;
      this.file = files[0];
      this.validateFile();
    },
    validateFile() {
      let parts = this.fileName.split('.');
      if(parts.length < 2 || parts[1] != "pdf") {
        this.file = null;
        this.fileName = "";
        return false;
      }
      return true;
    },
    submit() {
      if(!this.validateFile()) {
        return;
      }
      let data = new FormData();
      data.append('file', this.file);
      data.append('name', this.name);
      let config = {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      };
      window.axios.post('/api/document', data, config).then((response) => {
        console.log(response);
        window.notification("success", "Document Uploaded");
        this.$router.push('/admin/documents/' + response.data.document.id);
      }).catch((error) => {
        console.error(error);
        window.notification("danger", error.message);
      });
    }
  }
}
</script>

<style lang="css">
</style>
