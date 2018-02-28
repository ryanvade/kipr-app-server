<template lang="html">
  <div class="team-mass-upload-page">
    <div class="card" v-if="!processing">
      <header class="card-header">
        <p class="card-header-title">
          Upload Teams
        </p>
      </header>
      <div class="card-content">
        <div class="content">
          <p>
            Upload a file with comma seperated teams, one per line.
          </p>
          <div class="file has-name is-boxed">
            <label class="file-label">
              <input class="file-input" type="file" name="csv" @change="onFileChange">
              <span class="file-cta">
                <span class="file-icon">
                  <i class="fa fa-upload"></i>
                </span>
                <span class="file-label">
                  Choose a file…
                </span>
              </span>
              <span class="file-name" v-if="fileName != ''">
                {{ fileName }}
              </span>
            </label>
          </div>
          <p class="subtitle has-text-danger" v-if="badFile != ''">{{ badFile }}</p>
        </div>
      </div>
      <footer class="card-footer">
        <a class="card-footer-item is-warning" @click="$router.push('/admin/teams')">Cancel</a>
        <a class="card-footer-item is-primary" @click="submit">Submit</a>
      </footer>
    </div>
    <div class="processing-div" v-if="processing">
      <h3>Processing Files</h3>
      <progress class="progress is-primary" :value="percentProcessing" max="100">{{ percentProcessing }}%</progress>
    </div>

  </div>
</template>

<script>
export default {
  data() {
    return {
      fileName: '',
      file: null,
      badFile: '',
      processing: false,
      percentProcessing: 0
    };
  },
  methods: {
    onFileChange(event) {
      this.badFile = '';
      let files = event.target.files || event.dataTransfer.files;
      this.file = files[0];
      this.fileName = this.file.name;
      this.validateFile();
    },
    validateFile() {
      let validTypes = [
        'text/plain',
      ];
      if (this.file == null) {
        return false;
      }

      if (!validTypes.includes(this.file.type)) {
        console.log("invalid file type");
        this.file = null;
        this.fileName = '';
        this.badFile = "Invalid file type.";
        return false;
      }
      return true;
    },
    submit() {
      if (!this.validateFile()) {
        return;
      }
      this.processing = true;
      var data = new FormData();
      data.append('file', this.file);
      let self = this;
      var config = {
        onUploadProgress: function(progressEvent) {
          self.percentProcessing = Math.round((progressEvent.loaded * 100) / progressEvent.total);
          console.log(Math.round((progressEvent.loaded * 100) / progressEvent.total));
        }
      };
      window.axios.post('/api/team/file', data, config).then((response) => {
        console.log(response);
        // this.processing = false;
      }).catch((error) => {
        console.error(error);
        if (error.response.status == 401) {
          // redirect to login page
          window.notification("warning", "You have been logged out due to inactivity.");
          document.cookie = "notification=danger|You have been logged out due to inactivity";
          window.location.href = "/login";
        }
      });
    }
  }
}
</script>

<style lang="css">

</style>
