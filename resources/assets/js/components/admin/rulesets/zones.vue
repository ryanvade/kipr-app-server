<template lang="html">
  <section class="hero is-medium">
        <div class="hero-body">
          <div class="">
            <div class="columns is-centered">
              <article class="card is-rounded">
                <div class="card-content">
                  <h1 class="title is-flex">
                    <span>Define scoring zones</span>
                  </h1>
                  <div>
                      <div class="zone-list">
                          <zonebar v-for="zone in zones" @preview="zoneHover" v-bind:region="zone"></zonebar>
                      </div>
                      <div id="frame">
                          <canvas ref="canvas" id="zone-canvas" @click="mouseClick" @mousemove="mouseMove"/>
                          <img ref="preview" id="preview"/>
                      </div>
                  </div>
                  <p class="control">
                    <button class="button is-primary is-medium is-fullwidth" @click="next">
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
import zonebar from "./zonebar.vue"

export default {
  data() {
    return {
      zones: [],
      currentZone: [],
      mode: "idle"
    }
  },
  methods: {
    onEnter() {},
    mousePosition(event) {
      var bounds = this.$refs.canvas.getBoundingClientRect();
      return {
        x: event.clientX - bounds.left,
        y: event.clientY - bounds.top
      };
    },
    finishPath() {
      this.zones.push(this.currentZone);
      this.mode = "idle";
    },
    mouseClick(event) {
      var pos = this.mousePosition(event);
      if (this.mode == "editing") {
        var start = this.currentZone[0];
        var distanceFromStart = Math.sqrt((start.x - pos.x) * (start.x - pos.x) + (start.y - pos.y) * (start.y - pos.y));

        if (distanceFromStart < 10) {
          this.finishPath();
        } else {
          this.currentZone.push(pos);
        }
      } else if (this.mode == "idle") {
        this.mode = "editing";
        this.currentZone = [pos, pos];
      }
      this.updateCanvas();
    },
    mouseMove(event) {
      var pos = this.mousePosition(event)
      if (this.mode == "editing") {
        this.currentZone[this.currentZone.length - 1] = pos;
        this.updateCanvas(pos);
      }
    },
    updateCanvas() {
      var canvas = this.$refs.canvas;
      var context = canvas.getContext('2d');
      context.clearRect(0, 0, canvas.width, canvas.height);

      this.zones.forEach(function(region) {
        this.drawRegion(context, region, "rgba(150,150,150,0.5)", "rgb(150,150,150)");
      }, this);

      if (this.mode == "editing") {
        this.drawRegion(context, this.currentZone, "rgba(250,150,150,0.5)", "rgb(250,150,150)");
      } else if (this.mode == "idle") {
        this.drawRegion(context, this.currentZone, "rgba(150,250,150,0.5)", "rgb(150,250,150)");
      }
    },
    drawRegion(canvas, region, fillStyle, strokeStyle) {
      if (region.length > 0) {
        canvas.strokeStyle = strokeStyle;
        canvas.fillStyle = fillStyle;
        canvas.lineWidth = 2;
        canvas.beginPath()
        canvas.moveTo(region[0].x, region[0].y);
        region.slice(1).forEach(function(point) {
          canvas.lineTo(point.x, point.y);
        });
        canvas.closePath();
        canvas.fill();
        canvas.stroke();
      }
    },
    zoneHover(event) {
      if (this.mode == "idle") {
        this.currentZone = event.region;
        this.updateCanvas();
      }
    },
    next() {

    }
  },
  mounted() {
    var background = this.$store.state.map_image;
    var backgroundImg = this.$refs.preview;
    backgroundImg.src = background;
    var canvas = this.$refs.canvas;
    canvas.width = backgroundImg.width;
    canvas.height = backgroundImg.height;
  },
  components: {
    zonebar
  }

}
</script>

<style lang="css">
#frame {
    position:relative;
    display: inline-block;
    margin: 0 auto;
    border: 1px solid black;
}
#frame canvas {
    position:relative;
    z-index: 20;
}
#frame img {
    position:absolute;
    top: 0px;
    left: 0px;
    z-index: 1;
}

.button {
    margin: 10px 0;
}

</style>
