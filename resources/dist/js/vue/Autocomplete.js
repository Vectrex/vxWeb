

/* polyfills */

if (!Element.prototype.matches) {
  Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector;
}

if (!Element.prototype.closest) {
  Element.prototype.closest = function(s) {
    let el = this;

    do {
      if (el.matches(s)) return el;
      el = el.parentElement || el.parentNode;
    } while (el !== null && el.nodeType === 1);
    return null;
  };
}

let uniqueId = function() {
  let counter = 0;
  return function(prefix) {
    return (prefix || "") + ++counter;
  }
}();

export default {
		template: '<div ref="root"><slot :rootprops="rootProps" :inputprops="inputProps" :resultlistprops="resultListProps" :results="results" :resultprops="resultProps"><div v-bind="rootProps"><input ref="input" v-bind="inputProps" @input="handleInput" @keydown="handleKeyDown" @focus="handleFocus" @blur="hideResults" v-on="$listeners"><ul ref="resultList" v-bind="resultListProps" @click="handleResultClick" @mousedown.prevent=""><template v-for="(result, index) in results"><slot name="result" :result="result" :props="resultProps[index]"><li :key="resultProps[index].id" v-bind="resultProps[index]">{{ getResultValue(result) }}</li></slot></template></ul></div></slot></div>',
  name: 'Autocomplete',
  inheritAttrs: false,

  props: {
    search: {
      type: [Function, Promise],
      required: true
    },
    baseClass: {
      type: String,
      default: 'autocomplete'
    },
    autoSelect: {
      type: Boolean,
      default: false
    },
    getResultValue: {
      type: Function,
      default: result => result
    },
    defaultValue: {
      type: String,
      default: ""
    },
  },

  data() {
    return {
      value: this.defaultValue,
      resultListId: uniqueId(this.baseClass + "-result-list-"),
      results: [],
      selectedIndex: -1,
      searchCounter: 0,
      expanded: false,
      loading: false,
      position: 'below',
      resetPosition: true
    }
  },

  computed: {
    rootProps() {
      return {
        class: this.baseClass,
        style: { position: 'relative' },
        'data-expanded': this.expanded,
        'data-loading': this.loading,
        'data-position': this.position
      }
    },
    inputProps() {
      return {
        class: this.baseClass + "-input",
        value: this.value,
        role: 'combobox',
        autocomplete: 'off',
        autocapitalize: 'off',
        autocorrect: 'off',
        spellcheck: 'false',
        'aria-autocomplete': 'list',
        'aria-haspopup': 'listbox',
        'aria-owns': this.resultListId,
        'aria-expanded': this.expanded ? 'true' : 'false',
        'aria-activedescendant': this.selectedIndex > -1 ? this.resultProps[this.selectedIndex].id : '',
        ...this.$attrs
      }
    },
    resultListProps() {
      return {
        id: this.resultListId,
        class: this.baseClass + "-result-list",
        role: 'listbox',
        style: {
          position: 'absolute',
          zIndex: 1,
          width: '100%',
          visibility: this.expanded ? 'visible' : 'hidden',
          pointerEvents: this.expanded ? 'auto' : 'none',
          [this.position === 'below' ? 'top' : 'bottom']: '100%'
        }
      }
    },
    resultProps() {
      return this.results.map((result, index) => ({
        id: this.baseClass + "-result-" + index,
        class: this.baseClass + "-result",
        'data-result-index': index,
        role: 'option',
        ...(this.selectedIndex === index ? { 'aria-selected': 'true' } : {})
      }))
    },
  },

  mounted() {
    document.body.addEventListener('click', this.handleDocumentClick);
  },

  beforeDestroy() {
    document.body.removeEventListener('click', this.handleDocumentClick);
  },

  updated() {
    let inputPos, listPos;

    if (!this.$refs.input || !this.$refs.resultList) {
      return;
    }

    if (this.resetPosition && this.results.length > 0) {
      inputPos = this.$refs.input.getBoundingClientRect();
      listPos = this.$refs.resultList.getBoundingClientRect();

      this.position = (inputPos.bottom + listPos.height > window.innerHeight) && (window.innerHeight - inputPos.bottom < inputPos.top) && (window.pageYOffset + inputPos.top - listPos.height > 0) ? "above" : "below";
    }

    // Make sure selected result isn't scrolled out of view

    let selectedElem = this.$refs.resultList.querySelector('[data-result-index="' + this.selectedIndex + '"]');

    if (!selectedElem) {
      return;
    }

    let selectedPos = selectedElem.getBoundingClientRect();

    if (selectedPos.top < listPos.top) {
      // Element is above viewable area
      this.$refs.resultList.scrollTop -= listPos.top - selectedPos.top;
    }
    else if (selectedPos.bottom > listPos.bottom) {
      // Element is below viewable area
      this.$refs.resultList.scrollTop += selectedPos.bottom - listPos.bottom;
    }
  },

  methods: {
    setValue (result) {
      this.value = result ? this.getResultValue(result) : '';
    },

    handleInput (event) {
      this.value = event.target.value;
      this.updateResults(this.value);
    },

    handleFocus (event) {
      this.updateResults(event.target.value);
      this.value = event.target.value;
    },

    handleKeyDown (event) {
      const key = event.key;

      switch (key) {
        case 'Up': // IE/Edge
        case 'Down': // IE/Edge
        case 'ArrowUp':
        case 'ArrowDown': {
          event.preventDefault();
          const selectedIndex = this.selectedIndex + (key === 'ArrowUp' || key === 'Up' ? -1 : 1);
          const resultsCount = this.results.length;
          this.selectedIndex = ((selectedIndex % resultsCount) + resultsCount) % resultsCount;
          break;
        }
        case 'Tab': {
          this.selectResult();
          break;
        }
        case 'Enter': {
          this.selectResult();
          this.handleSubmit(this.results[this.selectedIndex]);
          break;
        }
        case 'Esc': // IE/Edge
        case 'Escape': {
          this.hideResults();
          this.setValue();
          break;
        }
      }
    },

    hideResults () {
      this.selectedIndex = -1;
      this.results = [];
      this.expanded = false;
      this.resetPosition = true;
    },

    selectResult () {
      const selectedResult = this.results[this.selectedIndex];
      if (selectedResult) {
        this.setValue(selectedResult);
      }
      this.hideResults();
    },

    handleSubmit (selectedResult) {
      this.$emit('submit', selectedResult);
    },

    handleResultClick (event) {
      const result = event.target.closest('[data-result-index]');
      if (result) {
        this.selectedIndex = parseInt(result.dataset.resultIndex, 10);
        this.selectResult();
        this.handleSubmit(this.results[this.selectedIndex]);
      }
    },

    handleDocumentClick (event) {
      if (this.$refs.root.contains(event.target)) {
        return;
      }
      this.hideResults();
    },

    updateResults: function (value) {

      if (this.search instanceof Promise) {

        const currentSearch = ++this.searchCounter;
        this.loading = true;

        this.search(value).then(results => {
          if (currentSearch !== this.searchCounter) {
            return;
          }
          this.results = results;
          this.loading = false;

          if (this.results.length === 0) {
            this.hideResults();
          } else {
            this.selectedIndex = this.autoSelect ? 0 : -1;
            this.expanded = true;
          }
        });

      } else {

        this.results = this.search(value);

        if (this.results.length === 0) {
          this.hideResults();
        } else {
          this.selectedIndex = this.autoSelect ? 0 : -1;
          this.expanded = true;
        }
      }
    }
  }
}
