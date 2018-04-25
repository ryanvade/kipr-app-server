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
  <div class="documents-page">
    <div class="loader-wrapper" v-if="loading">
      <div class="loader"></div>
    </div>

    <nav class="level" v-if="!loading">
      <div class="level-left">
        <div class="level-item">
          <p class="subtitle is-5">
            <strong>{{ documents.length }}</strong> Documents
          </p>
        </div>
        <div class="level-item">
          <p class="has-text-grey" v-if="documents.length > 0">
            Click on a document for more options.
          </p>
        </div>
      </div>
      <div class="level-right">
        <p class="level-item">
          <router-link class="button is-primary" :to="{ name: 'create_document', params: {} }">Create</router-link>
        </p>
      </div>
    </nav>

    <div class="document-preview-wrappers" v-if="!loading">
      <DocumentPreview v-for="doc in documents" :document="doc"></DocumentPreview>
    </div>
  </div>
</template>

<script>
import DocumentPreview from './DocumentPreview.vue';
export default {
  components: {DocumentPreview},
  data() {
    return {
      loading: true,
      documents: []
    };
  },
  mounted() {
    this.getDocuments();
  },
  methods: {
    getDocuments() {
      window.axios.get('/api/document').then((response) => {
        console.log(response);
        this.documents = response.data;
        this.documents.forEach((document) => {
        });
        this.loading = false;
      }).catch((error) => {
        console.error(error);
      });
    }
  }
}
</script>

<style lang="css">
</style>
