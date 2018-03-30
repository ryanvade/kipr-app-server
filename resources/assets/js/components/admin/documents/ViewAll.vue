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
          <router-link class="button is-primary" :to="{ name: 'create_competition', params: {} }">Create</router-link>
        </p>
      </div>
    </nav>

    <div class="" v-if="!loading">
      <div class="card" v-for="doc in documents">
        <div class="card-image">
          <figure class="image">
            DOCUMENT PREVIEW
          </figure>
        </div>
        <div class="card-content">
          <div class="media">
            <div class="media-content">
            </div>
          </div>

          <footer class="card-footer">
            <a class="card-footer-item is-danger delete-button">Delete</a>
          </footer>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
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
