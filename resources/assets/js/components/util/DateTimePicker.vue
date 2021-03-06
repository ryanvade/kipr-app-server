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
  <div class="datetime-wrapper">
    <input type="text" class="input" :name="compName" v-model="date" @focus="toggleDisplay">
    <!-- Date Time Picker -->
    <div class="box date-time" v-if="boxDisplayed">
      <div class="date-wrapper">
        <span class="month-year">
          <button @click="rotateLeft">
            <i class="fa fa-chevron-left" aria-hidden="true"></i>
          </button>
          <select class="month-selector" v-model="month">
            <option v-for="month in months" :value="month">{{ month }}</option>
          </select>
          <select class="year-selector" v-model="year">
            <option v-for="year in years" :value="year">{{ year }}</option>
          </select>
          <button @click="rotateRight">
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
          </button>
          <button @click="toggleDisplay">
            <i class="fa fa-times-circle" aria-hidden="true"></i>
          </button>
        </span>
        <div class="date-table-wrapper">
          <table class="table is-narrow date-table">
            <thead>
              <tr>
                <th v-for="day in days" class="">{{ day }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="week in weeks">
                <td v-for="day in week" :class="tableCellIsSelected(day)" @click="setDay(day)">{{ day }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="time-wrapper">
        <input type="number" min="1" max="12" v-model="hour">
        <input type="number" min="0" max="59" v-model="minute">
        <select name="" v-model="amPM">
          <option value="AM">AM</option>
          <option value="PM">PM</option>
        </select>
      </div>
    </div>
  </div>
</template>

<script>
import moment from 'moment-timezone';
export default {
  props: ['initial', 'name'],
  data() {
    return {
      boxDisplayed: false,
      month: null,
      year: null,
      hour: 0,
      day: 0,
      minute: 0,
      amPM: 'PM',
      months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
      days: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
      weeks: [
        [],
        [],
        [],
        [],
        [],
      ]
    };
  },
  mounted() {
    console.log(this.initial);
    if(this.initial != null)
    {
      let init = moment(this.initial);
      this.month = init.format('MMMM');
      this.minute = init.format('mm');
      this.hour = parseInt(init.format('hh'));
      this.amPM = init.format('A');
      this.year = init.year();
      this.day = parseInt(init.format('D'));
    }else {
      this.month = moment().format('MMMM');
      this.minute = moment().format('mm');
      this.hour = parseInt(moment().format('hh'));
      this.amPM = moment().format('A');
      this.year = moment().year();
      this.day = parseInt(moment().format('D'));
    }
    this.generateCalendar();
    document.body.addEventListener('click', (event) => {
      console.log(event);
      this.boxDisplayed = false;
    });
    this.$el.addEventListener('click', (event) => {
      event.stopPropagation();
    });
  },
  methods: {
    generateCalendar() {
      // get the first day of the week
      // NOTE: Sunday is index 0
      let firstDayOfMonth = moment(this.month + ' ' + this.year, 'MMMM YYYY').startOf('month').day();
      // get the number of days in the month
      let daysInMonth = moment(this.month + ' ' + this.year, 'MMMM YYYY').daysInMonth();
      // generate arrays
      this.weeks = [
        [],
        [],
        [],
        [],
        [],
      ];
      let counter = 1;
      for (var i = 0; i < 7; i++) {
        if (i < firstDayOfMonth) {
          this.weeks[0][i] = '';
        } else {
          this.weeks[0][i] = counter;
          counter++;
        }
      }
      for (var week = 1; week < 4; week++) {
        for (var day = 0; day < 7; day++) {
          this.weeks[week][day] = counter;
          counter++;
        }
      }
      for (var day = 0; day < 7; day++) {
        if (counter <= daysInMonth) {
          this.weeks[4][day] = counter;
          counter++;
        } else {
          this.weeks[4][day] = '';
        }
      }
    },
    setDay(day) {
      if (day != '') {
        this.day = day;
      }
    },
    toggleDisplay() {
      console.log("Toggle Display");
      this.boxDisplayed = !this.boxDisplayed;
    },
    tableCellIsSelected(day) {
      if (this.day == day) {
        return 'calendar-cell-selected';
      }
      return 'calendar-cell';
    },
    rotateLeft() {
      if (this.month == this.months[0] && this.year == this.years[0]) {
        console.log("Cannot rotate left");
      } else if (this.month == this.months[0]) {
        // decrement year
        this.year = this.years[this.years.indexOf(this.year) - 1];
        this.month = this.months[11];
      } else {
        // decrement month in year
        this.month = this.months[this.months.indexOf(this.month) - 1];
      }
    },
    rotateRight() {
      if (this.month == this.months[11] && this.year == this.years[9]) {
        console.log("Cannot rotate right");
      } else if (this.month == this.months[11]) {
        // increment year
        this.year = this.years[this.years.indexOf(this.year) + 1];
        this.month = this.months[0];
      } else {
        // increment month in year
        this.month = this.months[this.months.indexOf(this.month) + 1];
      }
      let str = '' + this.month + ' ' + this.day + ' ' + this.year + ' ' + this.hour + ':' + this.minute + this.amPM;
      if(!moment(str, 'MMMM D YYYY h:mA').isValid()) {
        // Month probably does not have the current 'day'
        this.day = moment(this.month + ' ' + this.year, "MMMM YYYY").daysInMonth();
      }
    }
  },
  computed: {
    years() {
      if (this.year == null) {
        return [];
      } else {
        let start = this.year - 5;
        let arr = [];
        for (var i = start; i < this.year + 5; i++) {
          arr.push(i);
        }
        return arr;
      }
    },
    date() {
      let tz = moment.tz.zone(moment.tz.guess()).abbr(moment());
      if (this.month != null && this.year != null && this.hour != null && this.minute != null && this.amPM != null) {
        let str = '' + this.month + ' ' + this.day + ' ' + this.year + ' ' + this.hour + ':' + this.minute + this.amPM;
        return moment(str, 'MMMM D YYYY h:mA').format('M/D/YYYY h:mmA');
      }
      return '';
    },
    compName() {
      if(this.name) {
        return this.name;
      }
      return '';
    }
  },
  watch: {
    month() {
      this.generateCalendar();
      this.$emit('change', this.date);
    },
    year() {
      this.generateCalendar();
      this.$emit('change', this.date);
    },
    day() {
      this.generateCalendar();
      this.$emit('change', this.date);
    },
    year() {
      this.$emit('change', this.date);
    },
    hour() {
      this.$emit('change', this.date);
    },
    minute() {
      this.$emit('change', this.date);
    },
    amPM() {
      this.$emit('change', this.date);
    }
  }
}
</script>

<style lang="css">
.datetime-wrapper {
  position: relative;
}

.date-time {
  position: absolute;
  top: 45px;
  z-index: 800;
}

.calendar-cell {
  cursor: pointer;
}

.calendar-cell:hover {
  background-color: hsl(0, 0%, 98%);
}

.calendar-cell-selected {
  cursor: pointer;
  background-color: hsl(0, 0%, 96%);
}
</style>
