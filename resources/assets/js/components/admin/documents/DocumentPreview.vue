<template lang="html">
  <div class="card document-preview" v-if="!deleted">
    <div class="loader-wrapper" v-if="loading">
      <div class="loader"></div>
    </div>
    <div class="card-image" v-show="!loading && !deleted">
      <canvas class="document-canvas" :id="documentPreviewId" @click="handleClick"></canvas>
    </div>
    <div class="card-content" v-if="!deleted">
      <div class="media">
        <div class="media-content">
          <span><strong>Name</strong> {{ document.name }}</span>
        </div>
      </div>

      <footer class="card-footer">
        <a class="card-footer-item is-danger delete-button" @click="showWarning = true">Delete</a>
      </footer>
    </div>

    <div class="delete-document-modal-wrapper" v-if="!deleted">
      <modal v-if="showWarning" v-on:close="showWarning = false">
        <div class="" slot="header">
          Delete Document
        </div>
        <div class="" slot="body">
          <div class="has-text-weight-bold">Are you sure you wish to delete {{ document.name }}?</div>
          <p class="is-5 is-italic">
            Files on disk will also be deleted.
          </p>
        </div>
        <div class="buttons has-addons is-centered" slot="footer">
          <button class="button is-outlined" @click="showWarning = false" style="width: 50%;">Cancel</button>
          <button class="button is-danger is-outlined" style="width: 50%;" @click="submitDelete">Delete</button>
        </div>
      </modal>
    </div>
  </div>

  </div>
</template>

<script>
import Modal from '../../Modal.vue';
export default {
  props: ['document'],
  components: { 'modal': Modal },
  data() {
    return {
      scale: 0.5,
      page: 1,
      loading: true,
      showWarning: false,
      deleted: false
    };
  },
  created() {
    let self = this;
    let url = window.location.origin + "/";
    url = url + this.document.file_location;
    url = url.replace("/public/", "/");
    let loader = window.pdfjsLib.getDocument(url);
    loader.promise.then((pdf) => {
      console.log("PDF Loaded");

      pdf.getPage(self.page).then((page) => {
        let viewport = page.getViewport(self.scale);
        let canvas = document.getElementById(self.documentPreviewId);
        let context = canvas.getContext("2d");
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        let renderContext = {
          canvasContext: context,
          viewport: viewport
        };
        let renderTask = page.render(renderContext);
        renderTask.then(() => {
          console.log('Page rendered');
          self.loading = false;
        });
      });
    })
  },
  computed: {
    documentPreviewId() {
      return "document-preview-" + this.document.id;
    }
  },
  methods: {
    handleClick() {
      let id = this.document.id;
      this.$router.push(`/admin/documents/${id}`);
    },
    submitDelete() {
      let id = this.document.id;
      window.axios.delete(`/api/document/${id}`).then((response) => {
        window.notification("success", "Document Deleted");
        this.showWarning = false;
        this.deleted = true;
        this.$emit("deleted");
      }).catch((error) => {
        console.error(error);
        if (error.response.status == 401) {
          // redirect to login page
          window.notification("warning", "You have been logged out due to inactivity.");
          document.cookie = "notification=danger|You have been logged out due to inactivity";
          window.location.href = "/login";
        }
        window.notification("danger", error.message);
        this.$router.push('/admin/documents');
      });
    },
  }
}
</script>

<style lang="css">
</style>
