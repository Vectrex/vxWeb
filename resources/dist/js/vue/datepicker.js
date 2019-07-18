
    import DateInput from './date-input.js';
    export default {
		template: '<div class="datepicker"><date-input :selected-date="selectedDate"></date-input><div class="calendar"><div class="calendar-nav navbar"><button class="btn btn-action btn-link btn-large prvMon" @click="previousMonth"></button><div class="month navbar-primary">{{ monthLabel }} {{ year }}</div><button class="btn btn-action btn-link btn-large nxtMon" @click="nextMonth"></button></div><div class="calendar-container"><div class="calendar-header"><div v-for="weekday in weekdays" class="calendar-date">{{ weekday }}</div></div><div class="calendar-body"><div v-for="day in preceedingDays" class="calendar-date prev-month"><button class="date-item" @click="selectDate(day, month - 1)">{{ day }}</button></div><div v-for="day in currentDays" class="calendar-date"><button class="date-item" :class="[today.toString() === (new Date(year, month, day)).toString() ? &#39;date-today&#39; : &#39;&#39;,selectedDate &amp;&amp; selectedDate.toString() === (new Date(year, month, day)).toString() ? &#39;active&#39; : &#39;&#39;]" @click="selectDate(day, month)">{{ day }}</button></div><div v-for="day in trailingDays" class="calendar-date next-month"><button class="date-item" @click="selectDate(day, month + 1)">{{ day }}</button></div></div></div></div></div>',

        components: {
            DateInput
        },

        data() {
            return {
                year: this.initDate.getFullYear(),
                month: this.initDate.getMonth(),
                dateDay: this.initDate.getDate(),
                selectedDate: null
            };
        },

        computed: {
            preceedingDays() {
                const days = [];
                for(let i = (new Date(this.year, this.month, 0)).getDay(); i--;) {
                    days.push((new Date(this.year, this.month, -i)).getDate());
                }
                return days;
            },
            currentDays() {
                return (new Date(this.year, this.month + 1, 0)).getDate();
            },
            trailingDays() {
                return (7 - (new Date(this.year, this.month + 1, 0)).getDay()) % 7;
            },
            monthLabel() {
                return "Jan Feb Mar Apr Mai Jun Jul Aug Sep Okt Nov Dez".split(" ")[this.month];
            },
            weekdays() {
                return "M D M D F S S".split(" ");
            },
            today() {
                const now = new Date();
                return new Date(now.getFullYear(), now.getMonth(), now.getDate());
            }
        },

        props: {
            initDate: {
                type: Date,
                default: () => (new Date())
            },
            validFrom: {
                type: Date
            },
            validUntil: {
                type: Date
            }

        },

        mounted () {
        },

        methods: {
            previousMonth() {
                const d = new Date(this.year, --this.month, this.dateDay);
                this.month = d.getMonth();
                this.year = d.getFullYear();
                this.$emit("changedMonth");
            },
            nextMonth() {
                const d = new Date(this.year, ++this.month, this.dateDay);
                this.month = d.getMonth();
                this.year = d.getFullYear();
                this.$emit("changedMonth");
            },
            selectDate(day, month) {
                this.dateDay = day;
                this.selectedDate = new Date(this.year, month, day);
                this.$emit("selected");
            }
        }
    }

