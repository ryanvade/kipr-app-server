<template lang="html">
  <div class="card document-preview">
    <div class="loader-wrapper" v-if="loading">
      <div class="loader"></div>
    </div>
    <div class="card-image" v-show="!loading">
      <canvas class="document-canvas" :id="documentPreviewId" @click="handleClick"></canvas>
    </div>
    <div class="card-content">
      <div class="media">
        <div class="media-content">
          <span><strong>Name</strong> {{ document.name }}</span>
        </div>
      </div>

      <footer class="card-footer">
        <a class="card-footer-item is-danger delete-button">Delete</a>
      </footer>
    </div>
  </div>
</template>

<script>
export default {
  props: ['document'],
  components: { },
  data() {
    return {
      scale: 0.5,
      page: 1,
      loading: true
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
    }
  }
}
</script>

<style lang="css">
</style>
