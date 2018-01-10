<template lang="html">
  <div class="">
    <section class="hero welcome is-small is-primary is-bold">
      <div class="hero-body">
        <div class="container">
          <h1 class="title">{{ welcomeText }}</h1>
        </div>
      </div>
    </section>
    <div class="index-content">
      <nav class="level">
        <div class="level-item has-text-centered">
          <div class="box">
            <p class="heading">Competitions</p>
            <p class="title">{{ Math.floor(Math.random() * (6000 - 0 + 1)) }}</p>
          </div>
        </div>
        <div class="level-item has-text-centered">
          <div class="box">
            <p class="heading">Matches</p>
            <p class="title">{{ Math.floor(Math.random() * (6000 - 0 + 1)) }}</p>
          </div>
        </div>
        <div class="level-item has-text-centered">
          <div class="box">
            <p class="heading">Teams</p>
            <p class="title">{{ Math.floor(Math.random() * (6000 - 0 + 1)) }}</p>
          </div>
        </div>
      </nav>
      <div class="columns">
        <div class="column is-6">
          <div class="card events-card">
            <header class="card-header">
              <p class="card-header-title">
                Events
              </p>
              <a href="#" class="card-header-icon" aria-label="more options">
                <span class="icon">
                  <i class="fa fa-angle-down" aria-hidden="true"></i>
                </span>
              </a>
            </header>
            <div class="card-table">
              <div class="content">
                <table class="table is-fullwidth is-striped">
                  <tbody>
                    <tr>
                      <td width="5%"><i class="fa fa-sign-in"></i></td>
                      <td>Team <a href="#">Robots</a> signed in</td>
                      <td><a class="button is-small is-danger" href="#">Undo</a></td>
                    </tr>
                    <tr>
                      <td width="5%"><i class="fa fa-trophy"></i></td>
                      <td>Match <a href="#">1</a> scored</td>
                      <td><a class="button is-small is-info" href="#">View</a></td>
                    </tr>
                    <tr>
                      <td width="5%"><i class="fa fa-trophy"></i></td>
                      <td>Match <a href="#">2</a> scored</td>
                      <td><a class="button is-small is-info" href="#">View</a></td>
                    </tr>
                    <tr>
                      <td width="5%"><i class="fa fa-trophy"></i></td>
                      <td>Match <a href="#">3</a> scored</td>
                      <td><a class="button is-small is-info" href="#">View</a></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <footer class="card-footer">
              <a href="#" class="card-footer-item">View All</a>
            </footer>
          </div>
        </div>
        <div class="column is-6">
          <div class="card">
            <header class="card-header">
              <p class="card-header-title">Teams</p>
            </header>
            <div class="card-content">
              <div class="content">
                <div class="control has-icons-left field has-addons">
                  <div class="control">
                    <input class="input is-medium" placeholder="team name" type="text">
                    <span class="icon is-medium is-left">
                      <i class="fa fa-search"></i>
                    </span>
                  </div>
                  <div class="control">
                  <button class="button is-primary is-medium" style="margin:0px;">Search</button>
                </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <header class="card-header">
              <p class="card-header-title">Competitions</p>
            </header>
            <div class="card-content">
              <div class="content">
                <div class="control has-icons-left field has-addons">
                  <div class="control">
                    <input class="input is-medium" placeholder="competition name" type="text">
                    <span class="icon is-medium is-left">
                      <i class="fa fa-search"></i>
                    </span>
                  </div>
                  <div class="control">
                  <button class="button is-primary is-medium" style="margin:0px;">Search</button>
                </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
</template>

<script>
export default {
  data() {
    return {
      welcomeText: "Hello"
    };
  },
  mounted() {
    // Can access window global at this point
    this.welcomeText = "Hello, " + window.user.name;
  }
}
</script>

<style lang="css">
</style>
