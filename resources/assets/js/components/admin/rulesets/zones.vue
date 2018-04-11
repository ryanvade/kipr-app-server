<template lang="html">
  <div class="box">
    <nav class="level">
      <div class="level-left">
        <p class="subtitle has-text-centered">
          <strong>Define scoring zones</strong>
        </p>
      </div>
      <div class="level-left">
          <a>Clear</a>
      </div>
    </nav>
    <div class="columns" v-if="map_image.src != null">
        <div class="column" id="frame">
          <img ref="background" id="background" :src="map_image.src" :width="map_image.width" :height="map_image.height">
          <canvas ref="canvas" id="zone-canvas" :width="map_image.width" :height="map_image.height" @mouseup="mouseUp" @mousedown="mouseDown" @click="mouseClick" @mousemove="mouseMove"/>
        </div>
        <div class="column zone-list">
            <table class="table is-hoverable is-fullwidth">
              <thead>
                <tr>
                  <th>Zone Name</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="zone in zones" @mouseover="zoneHover(zone)">
                    <td class="level">
                        <div class="level-left">
                            <input type="text" v-model="zone.name"/>
                        </div>
                      <div class="level-right">
                        <!--<p class="level-item">-->
                            <!--<a @click="edit">Edit</a>  -->
                        <!--</p>-->
                        <p class="level-item">
                            <a @click="_delete(zone)">Delete</a>
                        </p>
                      </div>
                  </td>
                </tr>
              </tbody>
            </table>
        </div>
    </div>
    <div v-else>
        Please upload map image above
    </div>
  </div>
</template>

<script>

export default {
  components: {
  },
  props: ["map_image", "map_settings"],
  data() {
    return {
        zones: [],
        currentZone: [],
        mode: "idle",
    }
  },
  methods: {
    update() {
        this.$emit("update", this.zones);
    },
    onEnter() {
    },
    mousePosition(event) {
      var bounds = this.$refs.canvas.getBoundingClientRect();
      return {
        x: event.clientX - bounds.left,
        y: event.clientY - bounds.top
      };
    },
    finishPath() {
        var id = this.zones.length;
        this.zones.push({name: "Unnamed zone " + id, points: this.currentZone, id: id});
        this.mode = "idle";
        this.update();
    },
    distance(p1, p2) {
        return Math.sqrt((p1.x-p2.x)*(p1.x-p2.x)+(p1.y-p2.y)*(p1.y-p2.y));
    },
    mouseDown(event) {
        console.log("Mouse down");
        var pos = this.mousePosition(event);

        if(this.mode == "idle") {
            var selectedPoint = this.zones[0].points[0];
            for(var zone of this.zones) {
                for(var corner of zone.points) {
                    var d = this.distance(pos, corner);
                    console.log(d);
                    if(d < 10) {
                        console.log(zone);
                        this.mode = "dragging";
                        this.currentZone = zone.points;
                    }
                }
            }
        }
    },
    mouseUp(event) {
    },
    mouseClick(event) {
        var pos = this.mousePosition(event);
        if(this.mode == "editing") {
          var start = this.currentZone[0];
          var distanceFromStart = this.distance(start, pos); 

          if(distanceFromStart < 10) {
              this.finishPath();
          } else {
            this.currentZone.push(pos);
          }
        } else if(this.mode == "idle") {
          this.mode = "editing";
          this.currentZone = [pos, pos];
        }else if(this.mode == "dragging") {
            this.mode = "idle";
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

        for(var zone of this.zones) {
            this.drawRegion(context, zone.points, "rgba(150,150,150,0.3)", "rgb(150,150,150)");
            this.drawRegion(context, this.reflect(zone.points), "rgba(150,150,150,0.3)", "rgb(150,150,150)");
        }

        if(this.mode == "editing") {
            this.drawRegion(context, this.currentZone, "rgba(250,150,150,0.3)", "rgb(250,150,150)");
            this.drawRegion(context, this.reflect(this.currentZone), "rgba(250,150,150,0.3)", "rgb(250,150,150)");
        } else if(this.mode == "idle") {
            this.drawRegion(context, this.currentZone, "rgba(150,250,150,0.3)", "rgb(150,250,150)");
            this.drawRegion(context, this.reflect(this.currentZone), "rgba(150,250,150,0.3)", "rgb(150,250,150)");
        }
    },
    reflect(zone) {
        var canvas = this.$refs.canvas;

        var reflected = [];
        for(var point of zone) {
            reflected.push({
                x: canvas.width-point.x,
                y: canvas.height-point.y
            });
        }
        return reflected;

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
    zoneHover(zone) {
        console.log("hover");
        console.log(zone);
        if(this.mode == "idle") {
            this.currentZone = zone.points;
            this.updateCanvas();
        }
    },
    edit(event) {
        console.log(event.target.parentElement)
    },
    _delete(zone) {
        var zones = []
        for(var z of this.zones) {
            if(z.id != zone.id) {
                z.id = zones.length
                zones.push(z)
            }
        }
        this.currentZone = [];
        this.zones = zones;
        this.update();
        this.updateCanvas();
    },
    next() {

    },
    resize_canvas() {
    }

  },
  mounted(){
  },
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
    position:absolute;
    top: 10px;
    left: 10px;
    z-index: 20;
}
#frame img {
    position:relative;
    z-index: 1;
}

.button {
    margin: 10px 0;
}

</style>
