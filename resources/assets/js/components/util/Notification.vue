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
