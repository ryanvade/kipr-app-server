<template lang="html">
  <div class="datetime-wrapper">
    <input type="text" class="input" name="start_date" v-model="date" @focus="toggleDisplay">
    <!-- Date Time Picker -->
    <div class="box date-time" v-show="boxDisplayed">
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
    this.month = moment().format('MMMM');
    this.minute = moment().minute();
    this.hour = parseInt(moment().format('hh'));
    this.amPM = moment().format('A');
    this.year = moment().year();
    this.day = parseInt(moment().format('D'));
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
      if(day != '') {
        this.day = day;
      }
    },
    toggleDisplay() {
      console.log("Toggle Display");
      this.boxDisplayed = !this.boxDisplayed;
    },
    tableCellIsSelected(day) {
      if(this.day == day) {
        return 'calendar-cell-selected';
      }
      return 'calendar-cell';
    },
    rotateLeft() {
      if(this.month == this.months[0] && this.year == this.years[0]) {
        console.log("Cannot rotate left");
      }else if(this.month == this.months[0]) {
        // decrement year
        this.year = this.years[this.years.indexOf(this.year) - 1];
        this.month = this.months[11];
      } else {
        // decrement month in year
        this.month = this.months[this.months.indexOf(this.month) - 1];
      }
    },
    rotateRight() {
      if(this.month == this.months[11] && this.year == this.years[9]) {
        console.log("Cannot rotate right");
      }else if(this.month == this.months[11]) {
        // increment year
        this.year = this.years[this.years.indexOf(this.year) + 1];
        this.month = this.months[0];
      } else {
        // decrement month in year
        this.month = this.months[this.months.indexOf(this.month) + 1];
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
      if(this.month && this.year && this.hour && this.minute && this.amPM) {
        return moment(this.month + ' ' + this.day + ' ' + this.year, 'MMMM D YYYY').format('M/D/YYYY ') + this.hour + ':' + this.minute + this.amPM;
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
