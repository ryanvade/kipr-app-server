<template lang="html">
  <div>
  <section class="hero is-fullheight" v-show="!showNoCompetitions">
        <div class="hero-body">
          <div class="container">
            <div class="columns is-centered">
              <article class="card is-rounded">
                <div class="card-content">
                  <h1 class="title is-flex">
                    <span>Upload board image</span>
                  </h1>

				 <form enctype="multipart/form-data">
					<img id="preview"/>
					<div class="dropbox">
						<!--<input name="myFile" type="file" accept="image/*"/>-->
						<input type="file" @change="update_image($event.target.name, $event.target.files)">
					</div>
				  </form>
                  <p class="control">
                    <button class="button is-primary is-medium is-fullwidth" @click="submit">
                      Next
                    </button>
                  </p>
                </div>
              </article>
            </div>
          </div>
    </div>
  </section>
  <!-- No Competition Modal -->
  <div class="no-competition-modal-wrapper">
    <modal v-if="showNoCompetitions">
      <div class="" slot="header">
        <strong>Missing Competition</strong>
      </div>
      <div class="" slot="body">
        <div class="">
          Please create a competition or <a class="" @click="reloadPage">reload the page</a>.
        </div>
        <div class="">
          You may also <router-link class="" :to="{ name: 'index', params: {} }" exact>go back to the home page</router-link>.
        </div>
      </div>
      <div class="missing-competition-modal" slot="footer">
        <router-link class="button is-primary" :to="{ name: 'create_competition', params: {} }">Create Competition</router-link>
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
      showNoCompetitions: false,
      competition: null,
    };
  },
  mounted() {
    this.getCurrentCompetition();
  },
  methods: {
    submit() {
      this.$router.push('/admin/ruleset/zones');
    },
    update_image(name, file) {
      var url = window.URL.createObjectURL(file[0]);
      document.getElementById('preview').src = url;
      this.$store.commit('update_map', url);
      console.log(this.$store);
      console.log(url);
    },
    getCurrentCompetition() {
      let self = this;
      window.axios.get('/api/competition/current').then((result) => {
        console.log(result);
        let comps = result.data.competitions;
        if (comps.length == 1) {
          self.competition = comps[0];
          self.$store.commit('set_competition', self.competition);
        } else if (comps.length > 1) {
          // Display 'Choose Current Competition' modal
        } else {
          // Display 'No Competitions, please make one' modal
          self.showNoCompetitions = true;
        }
      }).catch((error) => {
        console.error(error);
      });
    },
    reloadPage() {
      window.location.href = window.location.href;
    },
  }
}
</script>

<style lang="css">

form #preview {
    display: inline-block;
    width: 300px;
    height: 300px;
    border: 1px solid black;
    margin: auto 0;
}

.button {
    margin: 10px auto;
}

</style>
