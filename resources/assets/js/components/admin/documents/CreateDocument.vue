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
        $router.push('/admin/documents/' + response.document.id);
      }).catch((error) => {
        console.error(error);
      });
    }
  }
}
</script>

<style lang="css">
</style>
