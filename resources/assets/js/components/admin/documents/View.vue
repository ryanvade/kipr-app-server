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
<div class="document-page">
  <div class="loader-wrapper" v-if="loading">
    <div class="loader"></div>
  </div>
  <div class="" v-if="!loading">
    <nav class="level">
      <div class="level-left">
        <p class="subtitle has-text-centered">
          <strong>{{ document.name }}</strong>
        </p>
      </div>
      <div class="level-right">
        <!-- <p class="level-item">
          <a class="card-footer-item" id="edit" @click="$router.push('/admin/documents/' + document.id + '/edit')">Edit</a>
        </p> -->
        <p class="level-item">
          <a class="card-footer-item is-danger" id="delete" @click="showWarning = true">Delete</a>
        </p>
      </div>
    </nav>

    <div class="box">
      <canvas :id="documentPreviewId" class="document-canvas"></canvas>
    </div>

    <div class="delete-document-modal-wrapper">
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
  <!-- Missing Document Modal -->
  <div class="missing-document-modal">
    <modal v-if="showMissingDocument">
      <div class="" slot="header">
        Unknown Document
      </div>
      <div class="" slot="body">
        <div class="has-text-weight-bold">Cannot find document with id {{ $route.params.id }}.</div>
      </div>
      <div class="buttons has-addons is-centered" slot="footer">
        <button class="button is-outlined" @click="$router.push('/admin/')" style="width: 50%;">Admin Home</button>
        <button class="button is-outlined" style="width: 50%;" @click="$router.push('/admin/documents')">View All Documents</button>
      </div>
    </modal>
  </div>
</div>
</template>

<script>
import Modal from '../../Modal.vue';
export default {
  components: {
    'modal': Modal,
  },
  data() {
    return {
      document: null,
      teams: [],
      loading: true,
      showWarning: false,
      showMissingDocument: false,
      scale: 0.8,
      page: 1
    };
  },
  computed: {
    documentPreviewId() {
      return "document-preview-" + this.document.id;
    }
  },
  mounted() {
    let id = this.$route.params.id;
    this.getDocument(id);
  },
  methods: {
    getDocument(id) {
      window.axios.get(`/api/document/${id}`).then((response) => {
        console.log(response);
        this.document = response.data;
        this.loadPreview();
      }).catch((error) => {
        console.error(error);
        if (error.response.status == 401) {
          // redirect to login page
          window.notification("warning", "You have been logged out due to inactivity.");
          document.cookie = "notification=danger|You have been logged out due to inactivity";
          window.location.href = "/login";
        }
        window.notification("danger", error.message);
        if (error.response.status == 404) {
          this.showMissingDocument = true;
        }
      });
    },
    prettyDate(date) {
      return moment(date).format('M/D/YYYY h:mmA');;
    },
    submitDelete() {
      let id = this.document.id;
      window.axios.delete(`/api/document/${id}`).then((response) => {
        window.notification("success", "Document Deleted");
        this.$router.push('/admin/documents');
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
    loadPreview() {
      let self = this;
      let url = window.location.origin + "/";
      url = url + this.document.file_location;
      url = url.replace("/public/", "/");
      let loader = window.pdfjsLib.getDocument(url);
      loader.promise.then((pdf) => {
        console.log("PDF Loaded");
        self.loading = false;
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
          });
        });
      }).catch((error) => {
        console.error(error);
        self.loading = false;
      });
    }
  }
}
</script>

<style lang="css">
</style>
