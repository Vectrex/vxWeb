

    import pointerScroll from "./pointerScroll.js";

    export default {
		template: '<div><div v-if="!selectedOption" :class="classes.wrapper"><div style="position: relative; width: 100%; display: inline-block;"><input ref="search" :class="[&#39;form-input&#39;, isRequired]" :id="inputId" @click="seedSearchText" @focus="seedSearchText" @keyup.enter="setOption" @keyup.down="movePointerDown" @keyup.tab.stop="closeOut" @keyup.esc.stop="closeOut" @keyup.up="movePointerUp" :placeholder="placeholder" autocomplete="off" :required="required" v-model="searchText"><button type="button" @click="toggleDropdown" class="btn" style="position: absolute; top: 0; right: 0;"><i class="icon" :class="dropdownOpen ? &#39;icon-arrow-up&#39; : &#39;icon-arrow-down&#39;"></i></button><ul tabindex="-1" ref="options" v-if="matchingOptions" :style="{&#39;max-height&#39;: maxHeight}" style="z-index: 100; width: 100%; overflow: auto; position: absolute;"><li tabindex="-1" v-for="(option, ndx) in matchingOptions" :key="ndx" :class="ndx === pointer ? classes.activeClass : &#39;&#39;" class="cursor-pointer outline-none" @blur="handleClickOutside($event)" @mouseover="setPointerIdx(ndx)" @keyup.enter="setOption()" @click.prevent="setOption()" @keyup.up="movePointerUp()" @keyup.down="movePointerDown()"><slot name="option" v-bind="{option,ndx}">{{ getOptionDescription(option) }}</slot></li></ul></div></div><div :class="classes.wrapper" v-else=""><div style="position: relative; width: 100%; display: inline-block;"><input :id="inputId" :class="[classes.input]" ref="match" :required="required" @input="switchToSearch($event)" @click="switchToSearch($event)" :value="getOptionDescription(selectedOption)"><input type="hidden" :name="name" ref="selectedValue" :value="getOptionValue(selectedOption)"><div class="flex absolute items-center" :class="classes.icons"><button type="button" @click="closeOut" class="btn" style="position: absolute; top: 0; right: 0;"><i class="icon icon-arrow-left"></i></button></div></div></div></div>',
      props: {
        value: {
          required: true
        },
        name: {
          type: String,
          required: false,
          default: () => ""
        },
        options: {
          type: Array,
          required: false,
          default: () => []
        },
        optionLabel: {
          type: String,
          required: false,
          default: () => null
        },
        optionKey: {
          type: String,
          required: false,
          default: () => null
        },
        placeholder: {
          type: String,
          required: false,
          default: () => "Search Here"
        },
        maxHeight: {
          type: String,
          default: () => "220px",
          required: false
        },
        inputId: {
          type: String,
          default: () => "single-select",
          required: false
        },
        classes: {
          type: Object,
          required: false,
          default: () => {
            return {
              pointer: -1,
              wrapper: "single-select-wrapper",
              input: "search-input",
              icons: "icons",
              required: "required",
              activeClass: "active",
              dropdown: "dropdown"
            };
          }
        },
        initial: {
          type: String,
          required: false,
          default: () => null
        },
        required: {
          type: Boolean,
          required: false,
          default: () => false
        },
        maxResults: {
          type: Number,
          required: false,
          default: () => 30
        },
        tabindex: {
          type: String,
          required: false,
          default: () => {
            return "";
          }
        },
        getOptionDescription: {
          type: Function,
          default: function (option) {
            if (this.optionKey && this.optionLabel) {
              return option[this.optionKey] + " " + option[this.optionLabel];
            }
            if (this.optionLabel) {
              return option[this.optionLabel];
            }
            if (this.optionKey) {
              return option[this.optionKey];
            }
            return option;
          }
        },
        getOptionValue: {
          type: Function,
          default: function (option) {
            if (this.optionKey) {
              return option[this.optionKey];
            }

            if (this.optionLabel) {
              return option[this.optionLabel];
            }

            return option;
          }
        },
        filterBy: {
          type: Function,
          default: function (option) {
            if (this.optionLabel && this.optionKey) {
              return (
                option[this.optionLabel]
                  .toString()
                  .toLowerCase()
                  .includes(this.searchText.toString().toLowerCase()) ||
                  option[this.optionKey]
                  .toString()
                  .toLowerCase()
                  .includes(this.searchText.toString().toLowerCase())
              )
            }

            if (this.optionLabel) {
              return option[this.optionLabel]
                .toString()
                .toLowerCase()
                .includes(this.searchText.toString().toLowerCase())
            }

            if (this.optionKey) {
              option[this.optionKey]
                .toString()
                .toLowerCase()
                .includes(this.searchText.toString().toLowerCase())
            }

            return option
              .toString()
              .toLowerCase()
              .includes(this.searchText.toString().toLowerCase())
          }
        }
      },
      mixins: [pointerScroll],
      mounted() {
        document.addEventListener("click", this.handleClickOutside);
        document.addEventListener("keyup", this.handleClickOutside);
        if (this.value && this.options.includes(this.value)) {
          this.selectedOption = this.value;
          return;
        }
        this.searchText = this.initial;
      },
      destroyed() {
        document.removeEventListener("keyup", this.handleClickOutside);
        document.removeEventListener("click", this.handleClickOutside);
      },
      data() {
        return {
          searchText: null,
          selectedOption: null,
          dropdownOpen: false
        };
      },
      watch: {
        value(curr, prev) {
          this.selectedOption = curr;
        },
        searchText(curr, prev) {
          if (curr !== prev) {
            this.pointer = -1;
          }
        },
        selectedOption(curr, prev) {
          this.$emit("input", curr);
        },
        dropdownOpen(curr, prev) {
          if (curr === prev) {
            return;
          }

          if (!curr) {
            this.searchText = null;
            return;
          }

          if (!this.searchText) {
            this.searchText = "";
                 }

                 this.$nextTick().then(() => {
                     this.$refs.search.focus();
                 });
             }
         },
         computed: {
             isRequired() {
                 if (!this.required) {
                     return "";
                 }

                 if (this.selectedOption) {
                     return "";
                 }

                 return "required";
             },
             matchingOptions() {
                 if (this.searchText === null) {
                     return null;
                 }
                 if (!this.searchText.length) {
                     return [...this.options].slice(0, this.maxResults);
                 }

                 return this.options
                            .filter(option =>
                                this.filterBy(option)
                            )
                            .slice(0, this.maxResults);
             }
         },
         methods: {
             setPointerIdx(idx) {
                 this.pointer = idx;
             },
             seedSearchText() {
                 if (this.searchText !== null) {
                     return;
                 }

                 this.searchText = "";
             },
             switchToSearch(event) {
                 this.$refs.selectedValue.value = null;
                 this.searchText = event.target.value;
                 this.selectedOption = null;
                 this.dropdownOpen = true;
             },
             toggleDropdown() {
                 this.dropdownOpen = !this.dropdownOpen;
             },
             closeOut() {
                 this.selectedOption = null;
                 this.dropdownOpen = false;
                 this.searchText = null;
             },
             movePointerDown() {
                 if (!this.matchingOptions) {
                     return;
                 }
                 if (this.pointer >= this.matchingOptions.length - 1) {
                     return;
                 }

                 this.pointer++;
             },
             movePointerUp() {
                 if (this.pointer > 0) {
                     this.pointer--;
                 }
             },
             setOption() {
                 if (!this.matchingOptions || !this.matchingOptions.length) {
                     return;
                 }
                 if (this.pointer === -1) {
                     this.pointer++;
                 }

                 this.selectedOption = this.matchingOptions[this.pointer];
                 this.searchText = null;
                 this.dropdownOpen = false;
                 this.pointer = -1;
                 this.$nextTick().then(() => {
                     this.$refs.match.focus();
                 });
             },
             handleClickOutside(e) {
                 if (this.$el.contains(e.target)) {
                     return;
                 }

                 this.dropdownOpen = false;
                 this.searchText = null;
             }
         }
     };
