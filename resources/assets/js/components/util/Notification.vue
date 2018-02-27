<template lang="html">
  <transition name="slide">
    <div class="notification-wrapper" v-if="show">
      <div :class="className">
        <button class="delete" @click="close"></button>
        {{ body }}
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  data() {
    return {
      show: false,
      type: "",
      body: ""
    };
  },
  created() {
    if (this.message && this.type) {
        this.flash(this.message, this.type);
    }
    window.events.$on(
        'is-success', message => this.flash('is-success', message)
    );
    window.events.$on(
        'is-danger', message => this.flash('is-danger', message)
    );
    window.events.$on(
        'is-warning', message => this.flash('is-warning', message)
    );
    window.events.$on(
        'is-info', message => this.flash('is-info', message)
    );
  },
  methods: {
    flash(type, message) {
      this.type = type;
      this.body = message;
      this.show = true;
    },
    close() {
      this.show = false;
      this.body = "";
      this.type = "";
    }
  },
  computed: {
    className() {
      return "notification " + this.type;
    }
  }
}
</script>

<style lang="css">
.slide-enter-active {
 transition: all 0.25s ease-in-out;
}
.slide-enter {
 transform: translateX(100%);
}
.slide-leave-active, .slide-leave-to, .slide-leave {
 transition: all 0.5s ease-in-out;
}
.slide-leave-to {
 /*transition: transform 0.25s;*/
 transform: translateX(150%);
}
</style>
