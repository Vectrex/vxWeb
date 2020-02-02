
    import DateInput from './date-input';
    import DateFunctions from '../util/date-functions.js';

    export default {
		template: '<div v-bind="rootProps"><date-input v-if="hasInput" :date="selectedDate" :output-format="$attrs[&#39;output-format&#39;]" :day-names="$attrs[&#39;day-names&#39;]" :show-button="$attrs[&#39;show-button&#39;]" :month-name="$attrs[&#39;month-names&#39;]" @toggle-datepicker="toggleDatepicker" @dateinput-blur="updateDate" @date-clear="clearDate" v-bind="inputProps" ref="input"></date-input><div class="calendar" v-bind="calendarProps"><div class="calendar-nav navbar"><button class="btn btn-action btn-link btn-large prvMon" @click.stop="previousMonth"></button><div class="month navbar-primary">{{ monthLabel }} {{ year }}</div><button class="btn btn-action btn-link btn-large nxtMon" @click.stop="nextMonth"></button></div><div class="calendar-container"><div class="calendar-header"><div v-for="weekday in weekdays" class="calendar-date">{{ weekday }}</div></div><div class="calendar-body"><div v-for="day in days" class="calendar-date text-center" :class="getCellClass(day)"><button type="button" class="date-item" :class="getButtonClass(day)" :disabled="isDisabled(day)" @click.stop="isDisabled(day) ? null : selectDate(day)">{{ day.getDate() }}</button></div></div></div></div></div>',
        components: {
            DateInput
        },

        data() {
            return {
                year: null,
                month: null,
                dateDay: null,
                selectedDate: null,
                expanded: !this.hasInput
            };
        },

        watch: {
            initDate (newValue) {
                this.year = newValue.getFullYear();
                this.month = newValue.getMonth();
                this.dateDay = newValue.getDate();
            },
            pickedDate (newValue) {
                this.selectedDate = new Date(newValue.getFullYear(), newValue.getMonth(), newValue.getDate(), 0, 0, 0);
            }
        },

        computed: {
            rootProps() {
                return {
                    class: ['datepicker', this.$attrs['class']],
                    style: { position: 'relative' }
                }
            },
            inputProps() {
                return {
                    style: { position: 'relative' }
                }
            },
            calendarProps() {
                return {
                    style: this.hasInput ? {
                        display: this.expanded ? 'block': 'none',
                        position: 'absolute',
                        top: '100%',
                        transform: 'translateY(.2rem)',
                        'z-index': 300
                    } : {}
                }
            },
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
            pickedDate: {
                type: Date
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
            },
            hasInput: {
                type: Boolean,
                default: true
            },
            inputFormat: {
                type: String,
                default: 'y-m-d'
            }
        },

        mounted () {
            if(this.hasInput) {
                document.body.addEventListener('click', this.handleDocumentClick);
            }
            this.year = this.initDate.getFullYear();
            this.month = this.initDate.getMonth();
            this.dateDay = this.initDate.getDate();
            if(this.pickedDate) {
                this.selectedDate = new Date(this.pickedDate.getFullYear(), this.pickedDate.getMonth(), this.pickedDate.getDate(), 0, 0, 0);
            }
        },
        beforeDestroy() {
            if(this.hasInput) {
                document.body.removeEventListener('click', this.handleDocumentClick);
            }
        },

        methods: {
            isDisabled(day) {
                return (this.validFrom && this.validFrom > day) || (this.validUntil && this.validUntil < day())
            },
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
            getButtonClass(day) {
                const classes = [];
                if(this.today.getTime() === day.getTime()) {
                    classes.push('date-today');
                }
                if(this.selectedDate && this.selectedDate.getTime() === day.getTime()) {
                    classes.push('active');
                }
                return classes;
            },
            previousMonth() {
                const d = new Date(this.year, this.month - 1, this.dateDay);
                this.month = d.getMonth();
                this.year = d.getFullYear();
                this.$emit("month-change");
            },
            nextMonth() {
                const d = new Date(this.year, this.month + 1, this.dateDay);
                this.month = d.getMonth();
                this.year = d.getFullYear();
                this.$emit("month-change");
            },
            selectDate(day) {
                this.selectedDate = day;
                this.$emit('select', day);
                this.expanded = !this.hasInput;
            },
            toggleDatepicker() {
                this.expanded = !this.expanded;
            },
            handleDocumentClick() {
                this.expanded = false;
            },
            updateDate(dateString) {
                let day = DateFunctions.parseDate(dateString, this.inputFormat);
                if(day) {
                    this.selectedDate = day;
                    this.$emit("select", day);
                }
            },
            clearDate() {
                this.selectedDate = null;
                this.$emit('clear');
            }
        }
    }
