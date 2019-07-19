
    import DateInput from './date-input.js';
    export default {
		template: '<div class="datepicker"><date-input :date="selectedDate" :date-format="$attrs[&#39;date-format&#39;]" :day-names="$attrs[&#39;day-names&#39;]" :show-button="$attrs[&#39;show-button&#39;]" :month-name="$attrs[&#39;month-names&#39;]"></date-input><div class="calendar"><div class="calendar-nav navbar"><button class="btn btn-action btn-link btn-large prvMon" @click="previousMonth"></button><div class="month navbar-primary">{{ monthLabel }} {{ year }}</div><button class="btn btn-action btn-link btn-large nxtMon" @click="nextMonth"></button></div><div class="calendar-container"><div class="calendar-header"><div v-for="weekday in weekdays" class="calendar-date">{{ weekday }}</div></div><div class="calendar-body"><div v-for="day in days" class="calendar-date" :class="getCellClass(day)"><button class="date-item" :class="[today.toString() === day.toString() ? &#39;date-today&#39; : &#39;&#39;,selectedDate &amp;&amp; selectedDate.toString() === day.toString() ? &#39;active&#39; : &#39;&#39;]" @click="selectDate(day)">{{ day.getDate() }}</button></div></div></div></div></div>',

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
            days() {
                const dates = [];
                const nextMonth = new Date(this.year, this.month + 1, 0);
                const preceedingDays = (new Date(this.year, this.month, 0)).getDay() + 1 - this.startOfWeekIndex;
                const trailingDays = (7 - nextMonth.getDay()) % 7 - 1 + this.startOfWeekIndex;

                for(let i = -preceedingDays, j = nextMonth.getDate() + trailingDays; i < j; ++i) {
                    dates.push(new Date(this.year, this.month, i + 1));
                }

                return (dates);
            },

            preceedingDays() {
                const days = [];
                for(let i = (new Date(this.year, this.month, 0)).getDay(); i--;) {
                    days.push((new Date(this.year, this.month, -i)).getDate());
                }
                return days;
            },
            monthLabel() {
                return this.monthNames[this.month];
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
            },
            weekdays: {
                type: Array,
                default: (() => "M D M D F S S".split(" "))
            },
            monthNames: {
                type: Array,
                default: (() => "Jan Feb Mar Apr Mai Jun Jul Aug Sep Okt Nov Dez".split(" "))
            },
            startOfWeekIndex: {
                type: Number,
                default: 1,
                validator: value => !value || value === 1
            }
        },

        methods: {
            getCellClass(day) {
                switch(day.getMonth() - this.month) {
                    case -1:
                        return 'prev-month';
                    case 1:
                        return 'next-month';
                    default:
                        return '';
                }
            },

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
            selectDate(day) {
                this.selectedDate = day;
                this.$emit("selected", day);
            }
        }
    }
