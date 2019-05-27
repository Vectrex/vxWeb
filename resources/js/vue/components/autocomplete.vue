<template>
    <div class="autocomplete">
        <input
            type="text"
            @input="onChange"
            v-model="search"
            @keydown.down="onArrowDown"
            @keydown.up="onArrowUp"
            @keydown.enter="onEnter"
            @keydown.esc="onEsc"
            ref="autocompleteInput"
        />
        <ul
            id="autocomplete-results"
            v-show="isOpen"
            class="autocomplete-results"
            ref="autocompleteList"
        >
            <li
                class="loading"
                v-if="isLoading"
            >
                Loading results...
            </li>
            <li
                v-else
                v-for="(result, i) in results"
                :key="i"
                @click="setResult(result)"
                class="autocomplete-result"
                :class="{ 'is-active': i === arrowNdx }"
            >
                {{ result }}
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        name: 'autocomplete',

        props: {
            items: {
                type: Array,
                required: false,
                default: () => []
            },
            isAsync: {
                type: Boolean,
                required: false,
                default: false
            },
        },

        data() {
            return {
                isOpen: false,
                results: [],
                search: '',
                isLoading: false,
                arrowNdx: 0
            };
        },

        methods: {
            onChange() {
                this.$emit('input', this.search);

                if (this.isAsync) {
                    this.isLoading = true;
                }
                else {
                    this.filterResults();
                    this.isOpen = true;
                }
            },

            filterResults() {
                this.results = this.items.filter((item) => {
                    return item.toLowerCase().indexOf(this.search.toLowerCase()) > -1;
                });
            },
            setResult(result) {
                this.search = result;
                this.isOpen = false;
            },
            onArrowDown(evt) {
                if (this.arrowNdx < this.results.length) {
                    ++this.arrowNdx;
                }
            },
            onArrowUp() {
                if (this.arrowNdx > 0) {
                    --this.arrowNdx;
                }
            },
            onEnter() {
                this.search = this.results[this.arrowNdx];
                this.isOpen = false;
                this.arrowNdx = -1;
            },
            onEsc() {
                this.isOpen = false;
                this.arrowNdx = -1;
            },
            handleClickOutside(evt) {
                if (!this.$el.contains(evt.target)) {
                    this.isOpen = false;
                    this.arrowNdx = -1;
                }
            }
        },

        watch: {
            items: function (val, oldValue) {
                if (val.length !== oldValue.length) {
                    this.results = val;
                    this.isLoading = false;
                }
            },

            isOpen(newValue) {
                if(newValue) {
                    const elem = this.$refs.autocompleteInput;
                    console.log("reposition", elem);
                    console.log(elem.getBoundingClientRect().top + document.documentElement.scrollTop);
                }
            }
        },

        mounted() {
            document.addEventListener('click', this.handleClickOutside);
        },
        destroyed() {
            document.removeEventListener('click', this.handleClickOutside);
        }
    };
</script>