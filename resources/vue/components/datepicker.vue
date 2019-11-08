<template>
    <div v-bind="rootProps">
        <date-input
            v-if="hasInput"
            :date="selectedDate"
            :output-format="$attrs['output-format']"
            :day-names="$attrs['day-names']"
            :show-button="$attrs['show-button']"
            :month-name="$attrs['month-names']"
            @toggle-datepicker="toggleDatepicker"
            @dateinput-blur="updateDate"
            @date-clear="clearDate"
            v-bind="inputProps"
            ref="input"
        ></date-input>
        <div class="calendar" v-bind="calendarProps">
            <div class="calendar-nav navbar">
                <button class="btn btn-action btn-link btn-large prvMon" @click.stop="previousMonth"></button>
                <div class="month navbar-primary">{{ monthLabel }} {{ year }}</div>
                <button class="btn btn-action btn-link btn-large nxtMon" @click.stop="nextMonth"></button>
            </div>
            <div class="calendar-container">
                <div class="calendar-header">
                    <div v-for="weekday in weekdays" class="calendar-date">{{ weekday }}</div>
                </div>
                <div class="calendar-body">
                    <div v-for="day in days" class="calendar-date" :class="getCellClass(day)">
                        <button
                            class="date-item"
                            :class="[
                                today.toString() === day.toString() ? 'date-today' : '',
                                selectedDate && selectedDate.toString() === day.toString() ? 'active' : ''
                            ]"
                            @click.stop="selectDate(day)"
                        >{{ day.getDate() }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import DateInput from './date-input.js';
    import DateFunctions from '../util/date-functions.js';

    export default {
        components: {
            DateInput
        },

        data() {
            return {
                year: this.initDate.getFullYear(),
                month: this.initDate.getMonth(),
                dateDay: this.initDate.getDate(),
                selectedDate: null,
                expanded: !this.hasInput
            };
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

        mounted() {
            if(this.hasInput) {
                document.body.addEventListener('click', this.handleDocumentClick);
            }
        },

        beforeDestroy() {
            if(this.hasInput) {
                document.body.removeEventListener('click', this.handleDocumentClick);
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
                this.$emit("month-change");
            },
            nextMonth() {
                const d = new Date(this.year, ++this.month, this.dateDay);
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
</script>