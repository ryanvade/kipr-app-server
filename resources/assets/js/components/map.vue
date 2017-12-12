<template lang="html">
  <section class="hero is-fullheight is-medium">
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
                      <i class="fa fa-user"></i>
                      Next
                    </button>
                  </p>
                </div>
              </article>
            </div>
          </div>
    </div>
  </section>
</template>

<script>
export default {
  methods: {
    submit()
    {
      this.$router.push('/ruleset/zones');
    },
    update_image(name, file) {
        var url = window.URL.createObjectURL(file[0]);
        document.getElementById('preview').src = url;
        this.$store.commit('update_map', url);
        console.log(this.$store);
        console.log(url);
    }
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
