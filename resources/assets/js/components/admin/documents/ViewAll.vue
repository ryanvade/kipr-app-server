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
