(function webpackUniversalModuleDefinition(root, factory) {
	if(typeof exports === 'object' && typeof module === 'object')
		module.exports = factory();
	else if(typeof define === 'function' && define.amd)
		define([], factory);
	else if(typeof exports === 'object')
		exports["vxweb"] = factory();
	else
		root["vxweb"] = factory();
})((typeof self !== 'undefined' ? self : this), function() {
return /******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "fae3");
/******/ })
/************************************************************************/
/******/ ({

/***/ "09c3":
/***/ (function(module, exports, __webpack_require__) {

// Imports
var ___CSS_LOADER_API_IMPORT___ = __webpack_require__("24fb");
exports = ___CSS_LOADER_API_IMPORT___(false);
// Module
exports.push([module.i, "div[data-v-d96d5314]{position:relative}div a[data-v-d96d5314]:focus{box-shadow:none;text-decoration:none;font-family:icomoon}div a[data-v-d96d5314]:after{height:.8rem;margin:0 .25rem;position:absolute;top:50%;transform:translateY(-50%);width:.8rem;z-index:2;line-height:100%;right:.05rem;font-family:icomoon;content:\"\\e015\"}div a.show[data-v-d96d5314]:after{content:\"\\e016\"}", ""]);
// Exports
module.exports = exports;


/***/ }),

/***/ "11b0":
/***/ (function(module, exports, __webpack_require__) {

(function (global, factory) {
	 true ? factory(exports) :
	undefined;
}(this, (function (exports) { 'use strict';

// Export Sortable Element Component Mixin
var ElementMixin = {
  inject: ['manager'],
  props: {
    index: {
      type: Number,
      required: true
    },
    collection: {
      type: [String, Number],
      default: 'default'
    },
    disabled: {
      type: Boolean,
      default: false
    }
  },

  mounted: function mounted() {
    var _$props = this.$props,
        collection = _$props.collection,
        disabled = _$props.disabled,
        index = _$props.index;


    if (!disabled) {
      this.setDraggable(collection, index);
    }
  },


  watch: {
    index: function index(newIndex) {
      if (this.$el && this.$el.sortableInfo) {
        this.$el.sortableInfo.index = newIndex;
      }
    },
    disabled: function disabled(isDisabled) {
      if (isDisabled) {
        this.removeDraggable(this.collection);
      } else {
        this.setDraggable(this.collection, this.index);
      }
    },
    collection: function collection(newCollection, oldCollection) {
      this.removeDraggable(oldCollection);
      this.setDraggable(newCollection, this.index);
    }
  },

  beforeDestroy: function beforeDestroy() {
    var collection = this.collection,
        disabled = this.disabled;


    if (!disabled) this.removeDraggable(collection);
  },

  methods: {
    setDraggable: function setDraggable(collection, index) {
      var node = this.$el;

      node.sortableInfo = {
        index: index,
        collection: collection,
        manager: this.manager
      };

      this.ref = { node: node };
      this.manager.add(collection, this.ref);
    },
    removeDraggable: function removeDraggable(collection) {
      this.manager.remove(collection, this.ref);
    }
  }
};

var classCallCheck = function (instance, Constructor) {
  if (!(instance instanceof Constructor)) {
    throw new TypeError("Cannot call a class as a function");
  }
};

var createClass = function () {
  function defineProperties(target, props) {
    for (var i = 0; i < props.length; i++) {
      var descriptor = props[i];
      descriptor.enumerable = descriptor.enumerable || false;
      descriptor.configurable = true;
      if ("value" in descriptor) descriptor.writable = true;
      Object.defineProperty(target, descriptor.key, descriptor);
    }
  }

  return function (Constructor, protoProps, staticProps) {
    if (protoProps) defineProperties(Constructor.prototype, protoProps);
    if (staticProps) defineProperties(Constructor, staticProps);
    return Constructor;
  };
}();



























var slicedToArray = function () {
  function sliceIterator(arr, i) {
    var _arr = [];
    var _n = true;
    var _d = false;
    var _e = undefined;

    try {
      for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) {
        _arr.push(_s.value);

        if (i && _arr.length === i) break;
      }
    } catch (err) {
      _d = true;
      _e = err;
    } finally {
      try {
        if (!_n && _i["return"]) _i["return"]();
      } finally {
        if (_d) throw _e;
      }
    }

    return _arr;
  }

  return function (arr, i) {
    if (Array.isArray(arr)) {
      return arr;
    } else if (Symbol.iterator in Object(arr)) {
      return sliceIterator(arr, i);
    } else {
      throw new TypeError("Invalid attempt to destructure non-iterable instance");
    }
  };
}();













var toConsumableArray = function (arr) {
  if (Array.isArray(arr)) {
    for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) arr2[i] = arr[i];

    return arr2;
  } else {
    return Array.from(arr);
  }
};

var Manager = function () {
  function Manager() {
    classCallCheck(this, Manager);

    this.refs = {};
  }

  createClass(Manager, [{
    key: "add",
    value: function add(collection, ref) {
      if (!this.refs[collection]) {
        this.refs[collection] = [];
      }

      this.refs[collection].push(ref);
    }
  }, {
    key: "remove",
    value: function remove(collection, ref) {
      var index = this.getIndex(collection, ref);

      if (index !== -1) {
        this.refs[collection].splice(index, 1);
      }
    }
  }, {
    key: "isActive",
    value: function isActive() {
      return this.active;
    }
  }, {
    key: "getActive",
    value: function getActive() {
      var _this = this;

      return this.refs[this.active.collection].find(function (_ref) {
        var node = _ref.node;
        return node.sortableInfo.index == _this.active.index;
      });
    }
  }, {
    key: "getIndex",
    value: function getIndex(collection, ref) {
      return this.refs[collection].indexOf(ref);
    }
  }, {
    key: "getOrderedRefs",
    value: function getOrderedRefs() {
      var collection = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : this.active.collection;

      return this.refs[collection].sort(function (a, b) {
        return a.node.sortableInfo.index - b.node.sortableInfo.index;
      });
    }
  }]);
  return Manager;
}();

function arrayMove(arr, previousIndex, newIndex) {
  var array = arr.slice(0);
  if (newIndex >= array.length) {
    var k = newIndex - array.length;
    while (k-- + 1) {
      array.push(undefined);
    }
  }
  array.splice(newIndex, 0, array.splice(previousIndex, 1)[0]);
  return array;
}

var events = {
  start: ['touchstart', 'mousedown'],
  move: ['touchmove', 'mousemove'],
  end: ['touchend', 'touchcancel', 'mouseup']
};

var vendorPrefix = function () {
  if (typeof window === 'undefined' || typeof document === 'undefined') return ''; // server environment
  // fix for:
  //    https://bugzilla.mozilla.org/show_bug.cgi?id=548397
  //    window.getComputedStyle() returns null inside an iframe with display: none
  // in this case return an array with a fake mozilla style in it.
  var styles = window.getComputedStyle(document.documentElement, '') || ['-moz-hidden-iframe'];
  var pre = (Array.prototype.slice.call(styles).join('').match(/-(moz|webkit|ms)-/) || styles.OLink === '' && ['', 'o'])[1];

  switch (pre) {
    case 'ms':
      return 'ms';
    default:
      return pre && pre.length ? pre[0].toUpperCase() + pre.substr(1) : '';
  }
}();

function closest(el, fn) {
  while (el) {
    if (fn(el)) return el;
    el = el.parentNode;
  }
}

function limit(min, max, value) {
  if (value < min) {
    return min;
  }
  if (value > max) {
    return max;
  }
  return value;
}

function getCSSPixelValue(stringValue) {
  if (stringValue.substr(-2) === 'px') {
    return parseFloat(stringValue);
  }
  return 0;
}

function getElementMargin(element) {
  var style = window.getComputedStyle(element);

  return {
    top: getCSSPixelValue(style.marginTop),
    right: getCSSPixelValue(style.marginRight),
    bottom: getCSSPixelValue(style.marginBottom),
    left: getCSSPixelValue(style.marginLeft)
  };
}

// Export Sortable Container Component Mixin
var ContainerMixin = {
  data: function data() {
    return {
      sorting: false,
      sortingIndex: null,
      manager: new Manager(),
      events: {
        start: this.handleStart,
        move: this.handleMove,
        end: this.handleEnd
      }
    };
  },


  props: {
    value: { type: Array, required: true },
    axis: { type: String, default: 'y' }, // 'x', 'y', 'xy'
    distance: { type: Number, default: 0 },
    pressDelay: { type: Number, default: 0 },
    pressThreshold: { type: Number, default: 5 },
    useDragHandle: { type: Boolean, default: false },
    useWindowAsScrollContainer: { type: Boolean, default: false },
    hideSortableGhost: { type: Boolean, default: true },
    lockToContainerEdges: { type: Boolean, default: false },
    lockOffset: { type: [String, Number, Array], default: '50%' },
    transitionDuration: { type: Number, default: 300 },
    appendTo: { type: String, default: 'body' },
    draggedSettlingDuration: { type: Number, default: null },
    lockAxis: String,
    helperClass: String,
    contentWindow: Object,
    shouldCancelStart: {
      type: Function,
      default: function _default(e) {
        // Cancel sorting if the event target is an `input`, `textarea`, `select` or `option`
        var disabledElements = ['input', 'textarea', 'select', 'option', 'button'];
        return disabledElements.indexOf(e.target.tagName.toLowerCase()) !== -1;
      }
    },
    getHelperDimensions: {
      type: Function,
      default: function _default(_ref) {
        var node = _ref.node;
        return {
          width: node.offsetWidth,
          height: node.offsetHeight
        };
      }
    }
  },

  provide: function provide() {
    return {
      manager: this.manager
    };
  },
  mounted: function mounted() {
    var _this = this;

    this.container = this.$el;
    this.document = this.container.ownerDocument || document;
    this._window = this.contentWindow || window;
    this.scrollContainer = this.useWindowAsScrollContainer ? this.document.body : this.container;

    var _loop = function _loop(key) {
      if (_this.events.hasOwnProperty(key)) {
        events[key].forEach(function (eventName) {
          return _this.container.addEventListener(eventName, _this.events[key], { passive: true });
        });
      }
    };

    for (var key in this.events) {
      _loop(key);
    }
  },
  beforeDestroy: function beforeDestroy() {
    var _this2 = this;

    var _loop2 = function _loop2(key) {
      if (_this2.events.hasOwnProperty(key)) {
        events[key].forEach(function (eventName) {
          return _this2.container.removeEventListener(eventName, _this2.events[key]);
        });
      }
    };

    for (var key in this.events) {
      _loop2(key);
    }
  },


  methods: {
    handleStart: function handleStart(e) {
      var _this3 = this;

      var _$props = this.$props,
          distance = _$props.distance,
          shouldCancelStart = _$props.shouldCancelStart;


      if (e.button === 2 || shouldCancelStart(e)) {
        return false;
      }

      this._touched = true;
      this._pos = this.getOffset(e);

      var node = closest(e.target, function (el) {
        return el.sortableInfo != null;
      });

      if (node && node.sortableInfo && this.nodeIsChild(node) && !this.sorting) {
        var useDragHandle = this.$props.useDragHandle;
        var _node$sortableInfo = node.sortableInfo,
            index = _node$sortableInfo.index,
            collection = _node$sortableInfo.collection;


        if (useDragHandle && !closest(e.target, function (el) {
          return el.sortableHandle != null;
        })) return;

        this.manager.active = { index: index, collection: collection };

        /*
        * Fixes a bug in Firefox where the :active state of anchor tags
        * prevent subsequent 'mousemove' events from being fired
        * (see https://github.com/clauderic/react-sortable-hoc/issues/118)
        */
        if (e.target.tagName.toLowerCase() === 'a') {
          e.preventDefault();
        }

        if (!distance) {
          if (this.$props.pressDelay === 0) {
            this.handlePress(e);
          } else {
            this.pressTimer = setTimeout(function () {
              return _this3.handlePress(e);
            }, this.$props.pressDelay);
          }
        }
      }
    },
    nodeIsChild: function nodeIsChild(node) {
      return node.sortableInfo.manager === this.manager;
    },
    handleMove: function handleMove(e) {
      var _$props2 = this.$props,
          distance = _$props2.distance,
          pressThreshold = _$props2.pressThreshold;


      if (!this.sorting && this._touched) {
        var offset = this.getOffset(e);
        this._delta = {
          x: this._pos.x - offset.x,
          y: this._pos.y - offset.y
        };
        var delta = Math.abs(this._delta.x) + Math.abs(this._delta.y);

        if (!distance && (!pressThreshold || pressThreshold && delta >= pressThreshold)) {
          clearTimeout(this.cancelTimer);
          this.cancelTimer = setTimeout(this.cancel, 0);
        } else if (distance && delta >= distance && this.manager.isActive()) {
          this.handlePress(e);
        }
      }
    },
    handleEnd: function handleEnd() {
      var distance = this.$props.distance;


      this._touched = false;

      if (!distance) {
        this.cancel();
      }
    },
    cancel: function cancel() {
      if (!this.sorting) {
        clearTimeout(this.pressTimer);
        this.manager.active = null;
      }
    },
    handlePress: function handlePress(e) {
      var _this4 = this;

      e.stopPropagation();
      var active = this.manager.getActive();

      if (active) {
        var _$props3 = this.$props,
            axis = _$props3.axis,
            getHelperDimensions = _$props3.getHelperDimensions,
            helperClass = _$props3.helperClass,
            hideSortableGhost = _$props3.hideSortableGhost,
            useWindowAsScrollContainer = _$props3.useWindowAsScrollContainer,
            appendTo = _$props3.appendTo;
        var node = active.node,
            collection = active.collection;
        var index = node.sortableInfo.index;

        var margin = getElementMargin(node);

        var containerBoundingRect = this.container.getBoundingClientRect();
        var dimensions = getHelperDimensions({ index: index, node: node, collection: collection });

        this.node = node;
        this.margin = margin;
        this.width = dimensions.width;
        this.height = dimensions.height;
        this.marginOffset = {
          x: this.margin.left + this.margin.right,
          y: Math.max(this.margin.top, this.margin.bottom)
        };
        this.boundingClientRect = node.getBoundingClientRect();
        this.containerBoundingRect = containerBoundingRect;
        this.index = index;
        this.newIndex = index;

        this._axis = {
          x: axis.indexOf('x') >= 0,
          y: axis.indexOf('y') >= 0
        };
        this.offsetEdge = this.getEdgeOffset(node);
        this.initialOffset = this.getOffset(e);
        this.initialScroll = {
          top: this.scrollContainer.scrollTop,
          left: this.scrollContainer.scrollLeft
        };

        this.initialWindowScroll = {
          top: window.pageYOffset,
          left: window.pageXOffset
        };

        var fields = node.querySelectorAll('input, textarea, select');
        var clonedNode = node.cloneNode(true);
        var clonedFields = [].concat(toConsumableArray(clonedNode.querySelectorAll('input, textarea, select'))); // Convert NodeList to Array

        clonedFields.forEach(function (field, index) {
          if (field.type !== 'file' && fields[index]) {
            field.value = fields[index].value;
          }
        });

        this.helper = this.document.querySelector(appendTo).appendChild(clonedNode);

        this.helper.style.position = 'fixed';
        this.helper.style.top = this.boundingClientRect.top - margin.top + 'px';
        this.helper.style.left = this.boundingClientRect.left - margin.left + 'px';
        this.helper.style.width = this.width + 'px';
        this.helper.style.height = this.height + 'px';
        this.helper.style.boxSizing = 'border-box';
        this.helper.style.pointerEvents = 'none';

        if (hideSortableGhost) {
          this.sortableGhost = node;
          node.style.visibility = 'hidden';
          node.style.opacity = 0;
        }

        this.translate = {};
        this.minTranslate = {};
        this.maxTranslate = {};
        if (this._axis.x) {
          this.minTranslate.x = (useWindowAsScrollContainer ? 0 : containerBoundingRect.left) - this.boundingClientRect.left - this.width / 2;
          this.maxTranslate.x = (useWindowAsScrollContainer ? this._window.innerWidth : containerBoundingRect.left + containerBoundingRect.width) - this.boundingClientRect.left - this.width / 2;
        }
        if (this._axis.y) {
          this.minTranslate.y = (useWindowAsScrollContainer ? 0 : containerBoundingRect.top) - this.boundingClientRect.top - this.height / 2;
          this.maxTranslate.y = (useWindowAsScrollContainer ? this._window.innerHeight : containerBoundingRect.top + containerBoundingRect.height) - this.boundingClientRect.top - this.height / 2;
        }

        if (helperClass) {
          var _helper$classList;

          (_helper$classList = this.helper.classList).add.apply(_helper$classList, toConsumableArray(helperClass.split(' ')));
        }

        this.listenerNode = e.touches ? node : this._window;
        events.move.forEach(function (eventName) {
          return _this4.listenerNode.addEventListener(eventName, _this4.handleSortMove, false);
        });
        events.end.forEach(function (eventName) {
          return _this4.listenerNode.addEventListener(eventName, _this4.handleSortEnd, false);
        });

        this.sorting = true;
        this.sortingIndex = index;

        this.$emit('sort-start', { event: e, node: node, index: index, collection: collection });
      }
    },
    handleSortMove: function handleSortMove(e) {
      e.preventDefault(); // Prevent scrolling on mobile

      this.updatePosition(e);
      this.animateNodes();
      this.autoscroll();

      this.$emit('sort-move', { event: e });
    },
    handleSortEnd: function handleSortEnd(e) {
      var _this5 = this;

      var collection = this.manager.active.collection;

      // Remove the event listeners if the node is still in the DOM

      if (this.listenerNode) {
        events.move.forEach(function (eventName) {
          return _this5.listenerNode.removeEventListener(eventName, _this5.handleSortMove);
        });
        events.end.forEach(function (eventName) {
          return _this5.listenerNode.removeEventListener(eventName, _this5.handleSortEnd);
        });
      }

      var nodes = this.manager.refs[collection];

      var onEnd = function onEnd() {
        // Remove the helper from the DOM
        _this5.helper.parentNode.removeChild(_this5.helper);

        if (_this5.hideSortableGhost && _this5.sortableGhost) {
          _this5.sortableGhost.style.visibility = '';
          _this5.sortableGhost.style.opacity = '';
        }

        for (var i = 0, len = nodes.length; i < len; i++) {
          var node = nodes[i];
          var el = node.node;

          // Clear the cached offsetTop / offsetLeft value
          node.edgeOffset = null;

          // Remove the transforms / transitions
          el.style[vendorPrefix + 'Transform'] = '';
          el.style[vendorPrefix + 'TransitionDuration'] = '';
        }

        // Stop autoscroll
        clearInterval(_this5.autoscrollInterval);
        _this5.autoscrollInterval = null;

        // Update state
        _this5.manager.active = null;

        _this5.sorting = false;
        _this5.sortingIndex = null;

        _this5.$emit('sort-end', {
          event: e,
          oldIndex: _this5.index,
          newIndex: _this5.newIndex,
          collection: collection
        });
        _this5.$emit('input', arrayMove(_this5.value, _this5.index, _this5.newIndex));

        _this5._touched = false;
      };

      if (this.$props.transitionDuration || this.$props.draggedSettlingDuration) {
        this.transitionHelperIntoPlace(nodes).then(function () {
          return onEnd();
        });
      } else {
        onEnd();
      }
    },
    transitionHelperIntoPlace: function transitionHelperIntoPlace(nodes) {
      var _this6 = this;

      if (this.$props.draggedSettlingDuration === 0 || nodes.length === 0) {
        return Promise.resolve();
      }

      var deltaScroll = {
        left: this.scrollContainer.scrollLeft - this.initialScroll.left,
        top: this.scrollContainer.scrollTop - this.initialScroll.top
      };
      var indexNode = nodes[this.index].node;
      var newIndexNode = nodes[this.newIndex].node;

      var targetX = -deltaScroll.left;
      if (this.translate && this.translate.x > 0) {
        // Diff against right edge when moving to the right
        targetX += newIndexNode.offsetLeft + newIndexNode.offsetWidth - (indexNode.offsetLeft + indexNode.offsetWidth);
      } else {
        targetX += newIndexNode.offsetLeft - indexNode.offsetLeft;
      }

      var targetY = -deltaScroll.top;
      if (this.translate && this.translate.y > 0) {
        // Diff against the bottom edge when moving down
        targetY += newIndexNode.offsetTop + newIndexNode.offsetHeight - (indexNode.offsetTop + indexNode.offsetHeight);
      } else {
        targetY += newIndexNode.offsetTop - indexNode.offsetTop;
      }

      var duration = this.$props.draggedSettlingDuration !== null ? this.$props.draggedSettlingDuration : this.$props.transitionDuration;

      this.helper.style[vendorPrefix + 'Transform'] = 'translate3d(' + targetX + 'px,' + targetY + 'px, 0)';
      this.helper.style[vendorPrefix + 'TransitionDuration'] = duration + 'ms';

      return new Promise(function (resolve) {
        // Register an event handler to clean up styles when the transition
        // finishes.
        var cleanup = function cleanup(event) {
          if (!event || event.propertyName === 'transform') {
            clearTimeout(cleanupTimer);
            _this6.helper.style[vendorPrefix + 'Transform'] = '';
            _this6.helper.style[vendorPrefix + 'TransitionDuration'] = '';
            resolve();
          }
        };
        // Force cleanup in case 'transitionend' never fires
        var cleanupTimer = setTimeout(cleanup, duration + 10);
        _this6.helper.addEventListener('transitionend', cleanup, false);
      });
    },
    getEdgeOffset: function getEdgeOffset(node) {
      var offset = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : { top: 0, left: 0 };

      // Get the actual offsetTop / offsetLeft value, no matter how deep the node is nested
      if (node) {
        var nodeOffset = {
          top: offset.top + node.offsetTop,
          left: offset.left + node.offsetLeft
        };
        if (node.parentNode !== this.container) {
          return this.getEdgeOffset(node.parentNode, nodeOffset);
        } else {
          return nodeOffset;
        }
      }
    },
    getOffset: function getOffset(e) {
      var _ref2 = e.touches ? e.touches[0] : e,
          pageX = _ref2.pageX,
          pageY = _ref2.pageY;

      return {
        x: pageX,
        y: pageY
      };
    },
    getLockPixelOffsets: function getLockPixelOffsets() {
      var lockOffset = this.$props.lockOffset;


      if (!Array.isArray(this.lockOffset)) {
        lockOffset = [lockOffset, lockOffset];
      }

      if (lockOffset.length !== 2) {
        throw new Error('lockOffset prop of SortableContainer should be a single value or an array of exactly two values. Given ' + lockOffset);
      }

      var _lockOffset = lockOffset,
          _lockOffset2 = slicedToArray(_lockOffset, 2),
          minLockOffset = _lockOffset2[0],
          maxLockOffset = _lockOffset2[1];

      return [this.getLockPixelOffset(minLockOffset), this.getLockPixelOffset(maxLockOffset)];
    },
    getLockPixelOffset: function getLockPixelOffset(lockOffset) {
      var offsetX = lockOffset;
      var offsetY = lockOffset;
      var unit = 'px';

      if (typeof lockOffset === 'string') {
        var match = /^[+-]?\d*(?:\.\d*)?(px|%)$/.exec(lockOffset);

        if (match === null) {
          throw new Error('lockOffset value should be a number or a string of a number followed by "px" or "%". Given ' + lockOffset);
        }

        offsetX = offsetY = parseFloat(lockOffset);
        unit = match[1];
      }

      if (!isFinite(offsetX) || !isFinite(offsetY)) {
        throw new Error('lockOffset value should be a finite. Given ' + lockOffset);
      }

      if (unit === '%') {
        offsetX = offsetX * this.width / 100;
        offsetY = offsetY * this.height / 100;
      }

      return {
        x: offsetX,
        y: offsetY
      };
    },
    updatePosition: function updatePosition(e) {
      var _$props4 = this.$props,
          lockAxis = _$props4.lockAxis,
          lockToContainerEdges = _$props4.lockToContainerEdges;


      var offset = this.getOffset(e);
      var translate = {
        x: offset.x - this.initialOffset.x,
        y: offset.y - this.initialOffset.y
      };
      // Adjust for window scroll
      translate.y -= window.pageYOffset - this.initialWindowScroll.top;
      translate.x -= window.pageXOffset - this.initialWindowScroll.left;

      this.translate = translate;

      if (lockToContainerEdges) {
        var _getLockPixelOffsets = this.getLockPixelOffsets(),
            _getLockPixelOffsets2 = slicedToArray(_getLockPixelOffsets, 2),
            minLockOffset = _getLockPixelOffsets2[0],
            maxLockOffset = _getLockPixelOffsets2[1];

        var minOffset = {
          x: this.width / 2 - minLockOffset.x,
          y: this.height / 2 - minLockOffset.y
        };
        var maxOffset = {
          x: this.width / 2 - maxLockOffset.x,
          y: this.height / 2 - maxLockOffset.y
        };

        translate.x = limit(this.minTranslate.x + minOffset.x, this.maxTranslate.x - maxOffset.x, translate.x);
        translate.y = limit(this.minTranslate.y + minOffset.y, this.maxTranslate.y - maxOffset.y, translate.y);
      }

      if (lockAxis === 'x') {
        translate.y = 0;
      } else if (lockAxis === 'y') {
        translate.x = 0;
      }

      this.helper.style[vendorPrefix + 'Transform'] = 'translate3d(' + translate.x + 'px,' + translate.y + 'px, 0)';
    },
    animateNodes: function animateNodes() {
      var _$props5 = this.$props,
          transitionDuration = _$props5.transitionDuration,
          hideSortableGhost = _$props5.hideSortableGhost;

      var nodes = this.manager.getOrderedRefs();
      var deltaScroll = {
        left: this.scrollContainer.scrollLeft - this.initialScroll.left,
        top: this.scrollContainer.scrollTop - this.initialScroll.top
      };
      var sortingOffset = {
        left: this.offsetEdge.left + this.translate.x + deltaScroll.left,
        top: this.offsetEdge.top + this.translate.y + deltaScroll.top
      };
      var scrollDifference = {
        top: window.pageYOffset - this.initialWindowScroll.top,
        left: window.pageXOffset - this.initialWindowScroll.left
      };
      this.newIndex = null;

      for (var i = 0, len = nodes.length; i < len; i++) {
        var node = nodes[i].node;

        var index = node.sortableInfo.index;
        var width = node.offsetWidth;
        var height = node.offsetHeight;
        var offset = {
          width: this.width > width ? width / 2 : this.width / 2,
          height: this.height > height ? height / 2 : this.height / 2
        };

        var translate = {
          x: 0,
          y: 0
        };
        var edgeOffset = nodes[i].edgeOffset;

        // If we haven't cached the node's offsetTop / offsetLeft value

        if (!edgeOffset) {
          nodes[i].edgeOffset = edgeOffset = this.getEdgeOffset(node);
        }

        // Get a reference to the next and previous node
        var nextNode = i < nodes.length - 1 && nodes[i + 1];
        var prevNode = i > 0 && nodes[i - 1];

        // Also cache the next node's edge offset if needed.
        // We need this for calculating the animation in a grid setup
        if (nextNode && !nextNode.edgeOffset) {
          nextNode.edgeOffset = this.getEdgeOffset(nextNode.node);
        }

        // If the node is the one we're currently animating, skip it
        if (index === this.index) {
          if (hideSortableGhost) {
            /*
            * With windowing libraries such as `react-virtualized`, the sortableGhost
            * node may change while scrolling down and then back up (or vice-versa),
            * so we need to update the reference to the new node just to be safe.
            */
            this.sortableGhost = node;
            node.style.visibility = 'hidden';
            node.style.opacity = 0;
          }
          continue;
        }

        if (transitionDuration) {
          node.style[vendorPrefix + 'TransitionDuration'] = transitionDuration + 'ms';
        }

        if (this._axis.x) {
          if (this._axis.y) {
            // Calculations for a grid setup
            if (index < this.index && (sortingOffset.left + scrollDifference.left - offset.width <= edgeOffset.left && sortingOffset.top + scrollDifference.top <= edgeOffset.top + offset.height || sortingOffset.top + scrollDifference.top + offset.height <= edgeOffset.top)) {
              // If the current node is to the left on the same row, or above the node that's being dragged
              // then move it to the right
              translate.x = this.width + this.marginOffset.x;
              if (edgeOffset.left + translate.x > this.containerBoundingRect.width - offset.width) {
                // If it moves passed the right bounds, then animate it to the first position of the next row.
                // We just use the offset of the next node to calculate where to move, because that node's original position
                // is exactly where we want to go
                translate.x = nextNode.edgeOffset.left - edgeOffset.left;
                translate.y = nextNode.edgeOffset.top - edgeOffset.top;
              }
              if (this.newIndex === null) {
                this.newIndex = index;
              }
            } else if (index > this.index && (sortingOffset.left + scrollDifference.left + offset.width >= edgeOffset.left && sortingOffset.top + scrollDifference.top + offset.height >= edgeOffset.top || sortingOffset.top + scrollDifference.top + offset.height >= edgeOffset.top + height)) {
              // If the current node is to the right on the same row, or below the node that's being dragged
              // then move it to the left
              translate.x = -(this.width + this.marginOffset.x);
              if (edgeOffset.left + translate.x < this.containerBoundingRect.left + offset.width) {
                // If it moves passed the left bounds, then animate it to the last position of the previous row.
                // We just use the offset of the previous node to calculate where to move, because that node's original position
                // is exactly where we want to go
                translate.x = prevNode.edgeOffset.left - edgeOffset.left;
                translate.y = prevNode.edgeOffset.top - edgeOffset.top;
              }
              this.newIndex = index;
            }
          } else {
            if (index > this.index && sortingOffset.left + scrollDifference.left + offset.width >= edgeOffset.left) {
              translate.x = -(this.width + this.marginOffset.x);
              this.newIndex = index;
            } else if (index < this.index && sortingOffset.left + scrollDifference.left <= edgeOffset.left + offset.width) {
              translate.x = this.width + this.marginOffset.x;
              if (this.newIndex == null) {
                this.newIndex = index;
              }
            }
          }
        } else if (this._axis.y) {
          if (index > this.index && sortingOffset.top + scrollDifference.top + offset.height >= edgeOffset.top) {
            translate.y = -(this.height + this.marginOffset.y);
            this.newIndex = index;
          } else if (index < this.index && sortingOffset.top + scrollDifference.top <= edgeOffset.top + offset.height) {
            translate.y = this.height + this.marginOffset.y;
            if (this.newIndex == null) {
              this.newIndex = index;
            }
          }
        }
        node.style[vendorPrefix + 'Transform'] = 'translate3d(' + translate.x + 'px,' + translate.y + 'px,0)';
      }

      if (this.newIndex == null) {
        this.newIndex = this.index;
      }
    },
    autoscroll: function autoscroll() {
      var _this7 = this;

      var translate = this.translate;
      var direction = {
        x: 0,
        y: 0
      };
      var speed = {
        x: 1,
        y: 1
      };
      var acceleration = {
        x: 10,
        y: 10
      };

      if (translate.y >= this.maxTranslate.y - this.height / 2) {
        direction.y = 1; // Scroll Down
        speed.y = acceleration.y * Math.abs((this.maxTranslate.y - this.height / 2 - translate.y) / this.height);
      } else if (translate.x >= this.maxTranslate.x - this.width / 2) {
        direction.x = 1; // Scroll Right
        speed.x = acceleration.x * Math.abs((this.maxTranslate.x - this.width / 2 - translate.x) / this.width);
      } else if (translate.y <= this.minTranslate.y + this.height / 2) {
        direction.y = -1; // Scroll Up
        speed.y = acceleration.y * Math.abs((translate.y - this.height / 2 - this.minTranslate.y) / this.height);
      } else if (translate.x <= this.minTranslate.x + this.width / 2) {
        direction.x = -1; // Scroll Left
        speed.x = acceleration.x * Math.abs((translate.x - this.width / 2 - this.minTranslate.x) / this.width);
      }

      if (this.autoscrollInterval) {
        clearInterval(this.autoscrollInterval);
        this.autoscrollInterval = null;
        this.isAutoScrolling = false;
      }

      if (direction.x !== 0 || direction.y !== 0) {
        this.autoscrollInterval = setInterval(function () {
          _this7.isAutoScrolling = true;
          var offset = {
            left: 1 * speed.x * direction.x,
            top: 1 * speed.y * direction.y
          };
          _this7.scrollContainer.scrollTop += offset.top;
          _this7.scrollContainer.scrollLeft += offset.left;
          _this7.translate.x += offset.left;
          _this7.translate.y += offset.top;
          _this7.animateNodes();
        }, 5);
      }
    }
  }
};

// Export Sortable Element Handle Directive
var HandleDirective = {
  bind: function bind(el) {
    el.sortableHandle = true;
  }
};

function create(name, mixin) {
  return {
    name: name,
    mixins: [mixin],
    props: {
      tag: {
        type: String,
        default: 'div'
      }
    },
    render: function render(h) {
      return h(this.tag, this.$slots.default);
    }
  };
}

var SlickList = create('slick-list', ContainerMixin);
var SlickItem = create('slick-item', ElementMixin);

exports.ElementMixin = ElementMixin;
exports.ContainerMixin = ContainerMixin;
exports.HandleDirective = HandleDirective;
exports.SlickList = SlickList;
exports.SlickItem = SlickItem;
exports.arrayMove = arrayMove;

Object.defineProperty(exports, '__esModule', { value: true });

})));


/***/ }),

/***/ "15c3":
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__("09c3");
if(content.__esModule) content = content.default;
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var add = __webpack_require__("499e").default
var update = add("5290e005", content, true, {"sourceMap":false,"shadowMode":false});

/***/ }),

/***/ "24fb":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/*
  MIT License http://www.opensource.org/licenses/mit-license.php
  Author Tobias Koppers @sokra
*/
// css base code, injected by the css-loader
// eslint-disable-next-line func-names
module.exports = function (useSourceMap) {
  var list = []; // return the list of modules as css string

  list.toString = function toString() {
    return this.map(function (item) {
      var content = cssWithMappingToString(item, useSourceMap);

      if (item[2]) {
        return "@media ".concat(item[2], " {").concat(content, "}");
      }

      return content;
    }).join('');
  }; // import a list of modules into the list
  // eslint-disable-next-line func-names


  list.i = function (modules, mediaQuery, dedupe) {
    if (typeof modules === 'string') {
      // eslint-disable-next-line no-param-reassign
      modules = [[null, modules, '']];
    }

    var alreadyImportedModules = {};

    if (dedupe) {
      for (var i = 0; i < this.length; i++) {
        // eslint-disable-next-line prefer-destructuring
        var id = this[i][0];

        if (id != null) {
          alreadyImportedModules[id] = true;
        }
      }
    }

    for (var _i = 0; _i < modules.length; _i++) {
      var item = [].concat(modules[_i]);

      if (dedupe && alreadyImportedModules[item[0]]) {
        // eslint-disable-next-line no-continue
        continue;
      }

      if (mediaQuery) {
        if (!item[2]) {
          item[2] = mediaQuery;
        } else {
          item[2] = "".concat(mediaQuery, " and ").concat(item[2]);
        }
      }

      list.push(item);
    }
  };

  return list;
};

function cssWithMappingToString(item, useSourceMap) {
  var content = item[1] || ''; // eslint-disable-next-line prefer-destructuring

  var cssMapping = item[3];

  if (!cssMapping) {
    return content;
  }

  if (useSourceMap && typeof btoa === 'function') {
    var sourceMapping = toComment(cssMapping);
    var sourceURLs = cssMapping.sources.map(function (source) {
      return "/*# sourceURL=".concat(cssMapping.sourceRoot || '').concat(source, " */");
    });
    return [content].concat(sourceURLs).concat([sourceMapping]).join('\n');
  }

  return [content].join('\n');
} // Adapted from convert-source-map (MIT)


function toComment(sourceMap) {
  // eslint-disable-next-line no-undef
  var base64 = btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap))));
  var data = "sourceMappingURL=data:application/json;charset=utf-8;base64,".concat(base64);
  return "/*# ".concat(data, " */");
}

/***/ }),

/***/ "388b":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_vue_style_loader_index_js_ref_8_oneOf_1_0_node_modules_css_loader_dist_cjs_js_ref_8_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_2_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_3_node_modules_sass_loader_dist_cjs_js_ref_8_oneOf_1_4_node_modules_cache_loader_dist_cjs_js_ref_0_0_node_modules_vue_loader_lib_index_js_vue_loader_options_password_input_vue_vue_type_style_index_0_id_d96d5314_scoped_true_lang_scss___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("15c3");
/* harmony import */ var _node_modules_vue_style_loader_index_js_ref_8_oneOf_1_0_node_modules_css_loader_dist_cjs_js_ref_8_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_2_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_3_node_modules_sass_loader_dist_cjs_js_ref_8_oneOf_1_4_node_modules_cache_loader_dist_cjs_js_ref_0_0_node_modules_vue_loader_lib_index_js_vue_loader_options_password_input_vue_vue_type_style_index_0_id_d96d5314_scoped_true_lang_scss___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_vue_style_loader_index_js_ref_8_oneOf_1_0_node_modules_css_loader_dist_cjs_js_ref_8_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_2_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_3_node_modules_sass_loader_dist_cjs_js_ref_8_oneOf_1_4_node_modules_cache_loader_dist_cjs_js_ref_0_0_node_modules_vue_loader_lib_index_js_vue_loader_options_password_input_vue_vue_type_style_index_0_id_d96d5314_scoped_true_lang_scss___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "499e":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

// EXPORTS
__webpack_require__.d(__webpack_exports__, "default", function() { return /* binding */ addStylesClient; });

// CONCATENATED MODULE: ./node_modules/vue-style-loader/lib/listToStyles.js
/**
 * Translates the list format produced by css-loader into something
 * easier to manipulate.
 */
function listToStyles (parentId, list) {
  var styles = []
  var newStyles = {}
  for (var i = 0; i < list.length; i++) {
    var item = list[i]
    var id = item[0]
    var css = item[1]
    var media = item[2]
    var sourceMap = item[3]
    var part = {
      id: parentId + ':' + i,
      css: css,
      media: media,
      sourceMap: sourceMap
    }
    if (!newStyles[id]) {
      styles.push(newStyles[id] = { id: id, parts: [part] })
    } else {
      newStyles[id].parts.push(part)
    }
  }
  return styles
}

// CONCATENATED MODULE: ./node_modules/vue-style-loader/lib/addStylesClient.js
/*
  MIT License http://www.opensource.org/licenses/mit-license.php
  Author Tobias Koppers @sokra
  Modified by Evan You @yyx990803
*/



var hasDocument = typeof document !== 'undefined'

if (typeof DEBUG !== 'undefined' && DEBUG) {
  if (!hasDocument) {
    throw new Error(
    'vue-style-loader cannot be used in a non-browser environment. ' +
    "Use { target: 'node' } in your Webpack config to indicate a server-rendering environment."
  ) }
}

/*
type StyleObject = {
  id: number;
  parts: Array<StyleObjectPart>
}

type StyleObjectPart = {
  css: string;
  media: string;
  sourceMap: ?string
}
*/

var stylesInDom = {/*
  [id: number]: {
    id: number,
    refs: number,
    parts: Array<(obj?: StyleObjectPart) => void>
  }
*/}

var head = hasDocument && (document.head || document.getElementsByTagName('head')[0])
var singletonElement = null
var singletonCounter = 0
var isProduction = false
var noop = function () {}
var options = null
var ssrIdKey = 'data-vue-ssr-id'

// Force single-tag solution on IE6-9, which has a hard limit on the # of <style>
// tags it will allow on a page
var isOldIE = typeof navigator !== 'undefined' && /msie [6-9]\b/.test(navigator.userAgent.toLowerCase())

function addStylesClient (parentId, list, _isProduction, _options) {
  isProduction = _isProduction

  options = _options || {}

  var styles = listToStyles(parentId, list)
  addStylesToDom(styles)

  return function update (newList) {
    var mayRemove = []
    for (var i = 0; i < styles.length; i++) {
      var item = styles[i]
      var domStyle = stylesInDom[item.id]
      domStyle.refs--
      mayRemove.push(domStyle)
    }
    if (newList) {
      styles = listToStyles(parentId, newList)
      addStylesToDom(styles)
    } else {
      styles = []
    }
    for (var i = 0; i < mayRemove.length; i++) {
      var domStyle = mayRemove[i]
      if (domStyle.refs === 0) {
        for (var j = 0; j < domStyle.parts.length; j++) {
          domStyle.parts[j]()
        }
        delete stylesInDom[domStyle.id]
      }
    }
  }
}

function addStylesToDom (styles /* Array<StyleObject> */) {
  for (var i = 0; i < styles.length; i++) {
    var item = styles[i]
    var domStyle = stylesInDom[item.id]
    if (domStyle) {
      domStyle.refs++
      for (var j = 0; j < domStyle.parts.length; j++) {
        domStyle.parts[j](item.parts[j])
      }
      for (; j < item.parts.length; j++) {
        domStyle.parts.push(addStyle(item.parts[j]))
      }
      if (domStyle.parts.length > item.parts.length) {
        domStyle.parts.length = item.parts.length
      }
    } else {
      var parts = []
      for (var j = 0; j < item.parts.length; j++) {
        parts.push(addStyle(item.parts[j]))
      }
      stylesInDom[item.id] = { id: item.id, refs: 1, parts: parts }
    }
  }
}

function createStyleElement () {
  var styleElement = document.createElement('style')
  styleElement.type = 'text/css'
  head.appendChild(styleElement)
  return styleElement
}

function addStyle (obj /* StyleObjectPart */) {
  var update, remove
  var styleElement = document.querySelector('style[' + ssrIdKey + '~="' + obj.id + '"]')

  if (styleElement) {
    if (isProduction) {
      // has SSR styles and in production mode.
      // simply do nothing.
      return noop
    } else {
      // has SSR styles but in dev mode.
      // for some reason Chrome can't handle source map in server-rendered
      // style tags - source maps in <style> only works if the style tag is
      // created and inserted dynamically. So we remove the server rendered
      // styles and inject new ones.
      styleElement.parentNode.removeChild(styleElement)
    }
  }

  if (isOldIE) {
    // use singleton mode for IE9.
    var styleIndex = singletonCounter++
    styleElement = singletonElement || (singletonElement = createStyleElement())
    update = applyToSingletonTag.bind(null, styleElement, styleIndex, false)
    remove = applyToSingletonTag.bind(null, styleElement, styleIndex, true)
  } else {
    // use multi-style-tag mode in all other cases
    styleElement = createStyleElement()
    update = applyToTag.bind(null, styleElement)
    remove = function () {
      styleElement.parentNode.removeChild(styleElement)
    }
  }

  update(obj)

  return function updateStyle (newObj /* StyleObjectPart */) {
    if (newObj) {
      if (newObj.css === obj.css &&
          newObj.media === obj.media &&
          newObj.sourceMap === obj.sourceMap) {
        return
      }
      update(obj = newObj)
    } else {
      remove()
    }
  }
}

var replaceText = (function () {
  var textStore = []

  return function (index, replacement) {
    textStore[index] = replacement
    return textStore.filter(Boolean).join('\n')
  }
})()

function applyToSingletonTag (styleElement, index, remove, obj) {
  var css = remove ? '' : obj.css

  if (styleElement.styleSheet) {
    styleElement.styleSheet.cssText = replaceText(index, css)
  } else {
    var cssNode = document.createTextNode(css)
    var childNodes = styleElement.childNodes
    if (childNodes[index]) styleElement.removeChild(childNodes[index])
    if (childNodes.length) {
      styleElement.insertBefore(cssNode, childNodes[index])
    } else {
      styleElement.appendChild(cssNode)
    }
  }
}

function applyToTag (styleElement, obj) {
  var css = obj.css
  var media = obj.media
  var sourceMap = obj.sourceMap

  if (media) {
    styleElement.setAttribute('media', media)
  }
  if (options.ssrId) {
    styleElement.setAttribute(ssrIdKey, obj.id)
  }

  if (sourceMap) {
    // https://developer.chrome.com/devtools/docs/javascript-debugging
    // this makes source maps inside style tags work properly in Chrome
    css += '\n/*# sourceURL=' + sourceMap.sources[0] + ' */'
    // http://stackoverflow.com/a/26603875
    css += '\n/*# sourceMappingURL=data:application/json;base64,' + btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap)))) + ' */'
  }

  if (styleElement.styleSheet) {
    styleElement.styleSheet.cssText = css
  } else {
    while (styleElement.firstChild) {
      styleElement.removeChild(styleElement.firstChild)
    }
    styleElement.appendChild(document.createTextNode(css))
  }
}


/***/ }),

/***/ "fae3":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

// EXPORTS
__webpack_require__.d(__webpack_exports__, "Components", function() { return /* reexport */ Components; });
__webpack_require__.d(__webpack_exports__, "Filters", function() { return /* reexport */ Filters; });
__webpack_require__.d(__webpack_exports__, "Directives", function() { return /* reexport */ Directives; });
__webpack_require__.d(__webpack_exports__, "Mixins", function() { return /* reexport */ Mixins; });
__webpack_require__.d(__webpack_exports__, "Util", function() { return /* reexport */ Util; });

// CONCATENATED MODULE: ./node_modules/@vue/cli-service/lib/commands/build/setPublicPath.js
// This file is imported into lib/wc client bundles.

if (typeof window !== 'undefined') {
  var currentScript = window.document.currentScript
  if (false) { var getCurrentScript; }

  var src = currentScript && currentScript.src.match(/(.+\/)[^/]+\.js(\?.*)?$/)
  if (src) {
    __webpack_require__.p = src[1] // eslint-disable-line
  }
}

// Indicate to webpack that this file can be concatenated
/* harmony default export */ var setPublicPath = (null);

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vx-vue/message-toast.vue?vue&type=template&id=0c7264fa&
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{class:[{ 'display': _vm.isActive }, _vm.classname, 'toast'],attrs:{"id":"messageBox"}},[_c('button',{staticClass:"btn btn-clear float-right",on:{"click":function($event){_vm.isActive = false}}}),_vm._l((_vm.lines),function(line){return _c('div',[_vm._v(_vm._s(line))])})],2)}
var staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/vx-vue/message-toast.vue?vue&type=template&id=0c7264fa&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vx-vue/message-toast.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//

/* harmony default export */ var message_toastvue_type_script_lang_js_ = ({

    data: () => ({
        activeTimeout: null,
        isActive: false
    }),

    props: {
        message: [String, Array],
        classname: String,
        timeout: {
            type: Number,
            default: 5000
        },
        active: {
            type: Boolean,
            default: false
        }
    },

    computed: {
        lines () {
            return typeof this.message === 'string' ? [this.message] : this.message;
        }
    },

    watch: {
        active (state) {
            this.isActive = state;
        },
        isActive () {
            this.setTimeout();
        }
    },

    mounted () {
        this.setTimeout();
    },

    methods: {
        setTimeout() {
            window.clearTimeout(this.activeTimeout);

            // timeout 0 disables fadeout

            if (this.isActive && this.timeout) {
                this.activeTimeout = window.setTimeout( () => {
                    this.isActive = false;
                }, this.timeout);
            }
        }
    }
});

// CONCATENATED MODULE: ./vue/components/vx-vue/message-toast.vue?vue&type=script&lang=js&
 /* harmony default export */ var vx_vue_message_toastvue_type_script_lang_js_ = (message_toastvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./node_modules/vue-loader/lib/runtime/componentNormalizer.js
/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file (except for modules).
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

function normalizeComponent (
  scriptExports,
  render,
  staticRenderFns,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier, /* server only */
  shadowMode /* vue-cli only */
) {
  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (render) {
    options.render = render
    options.staticRenderFns = staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = 'data-v-' + scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = shadowMode
      ? function () {
        injectStyles.call(
          this,
          (options.functional ? this.parent : this).$root.$options.shadowRoot
        )
      }
      : injectStyles
  }

  if (hook) {
    if (options.functional) {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functional component in vue file
      var originalRender = options.render
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return originalRender(h, context)
      }
    } else {
      // inject component registration as beforeCreate hook
      var existing = options.beforeCreate
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    }
  }

  return {
    exports: scriptExports,
    options: options
  }
}

// CONCATENATED MODULE: ./vue/components/vx-vue/message-toast.vue





/* normalize component */

var component = normalizeComponent(
  vx_vue_message_toastvue_type_script_lang_js_,
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var message_toast = (component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/circular-progress.vue?vue&type=template&id=126fe8d2&
var circular_progressvue_type_template_id_126fe8d2_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('svg',{staticClass:"circular-progress",attrs:{"height":_vm.size,"width":_vm.size}},[_c('circle',{attrs:{"stroke":"white","fill":"transparent","stroke-width":_vm.strokeWidth,"r":_vm.normalizedRadius,"cx":_vm.radius,"cy":_vm.radius}}),_c('circle',{style:({ strokeDashoffset: _vm.strokeDashoffset }),attrs:{"stroke":_vm.color,"fill":"transparent","stroke-dasharray":_vm.circumference + ' ' + _vm.circumference,"stroke-width":_vm.strokeWidth,"r":_vm.normalizedRadius,"cx":_vm.radius,"cy":_vm.radius}})])}
var circular_progressvue_type_template_id_126fe8d2_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/circular-progress.vue?vue&type=template&id=126fe8d2&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/circular-progress.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ var circular_progressvue_type_script_lang_js_ = ({
    name: "circular-progress",

    props: {
        progress: Number,
        radius: { type: Number, default: 24 },
        strokeWidth: { type: Number, default: 8 },
        color: { type: String, default: 'white' }
    },

    computed: {
        size () {
            return 2 * this.radius;
        },
        normalizedRadius () {
            return this.radius - this.strokeWidth / 2;
        },
        circumference () {
            return this.normalizedRadius * 2 * Math.PI;
        },
        strokeDashoffset () {
            return this.circumference - this.progress / 100 * this.circumference;
        }
    }
});

// CONCATENATED MODULE: ./vue/components/circular-progress.vue?vue&type=script&lang=js&
 /* harmony default export */ var components_circular_progressvue_type_script_lang_js_ = (circular_progressvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/circular-progress.vue





/* normalize component */

var circular_progress_component = normalizeComponent(
  components_circular_progressvue_type_script_lang_js_,
  circular_progressvue_type_template_id_126fe8d2_render,
  circular_progressvue_type_template_id_126fe8d2_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var circular_progress = (circular_progress_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vx-vue/autocomplete.vue?vue&type=template&id=d11d0aa8&
var autocompletevue_type_template_id_d11d0aa8_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',_vm._b({ref:"container"},'div',_vm.containerProps,false),[_c('autocomplete-input',_vm._g(_vm._b({ref:"input",attrs:{"value":_vm.value},on:{"input":_vm.handleInput,"keydown":[function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"enter",13,$event.key,"Enter")){ return null; }return _vm.handleEnter($event)},function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"esc",27,$event.key,["Esc","Escape"])){ return null; }return _vm.handleEsc($event)},function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"tab",9,$event.key,"Tab")){ return null; }return _vm.handleTab($event)},function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"up",38,$event.key,["Up","ArrowUp"])){ return null; }$event.preventDefault();return _vm.handleUp($event)},function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"down",40,$event.key,["Down","ArrowDown"])){ return null; }$event.preventDefault();return _vm.handleDown($event)}],"focus":_vm.handleFocus,"blur":_vm.handleBlur}},'autocomplete-input',_vm.inputProps,false),_vm.$listeners)),(_vm.results.length)?_c('ul',_vm._b({ref:"resultList",on:{"click":_vm.handleResultClick,"mousedown":function($event){$event.preventDefault();}}},'ul',_vm.resultListProps,false),[_vm._l((_vm.results),function(result,index){return [_vm._t("result",[_c('li',_vm._b({key:_vm.resultProps[index].id},'li',_vm.resultProps[index],false),[_vm._v(" "+_vm._s(_vm.getResultValue(result))+" ")])],{"result":result,"props":_vm.resultProps[index]})]})],2):_vm._e()],1)}
var autocompletevue_type_template_id_d11d0aa8_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/vx-vue/autocomplete.vue?vue&type=template&id=d11d0aa8&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vx-vue/formelements/form-input.vue?vue&type=template&id=1f07e820&
var form_inputvue_type_template_id_1f07e820_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('input',_vm._g(_vm._b({staticClass:"form-input"},'input',_vm.$attrs,false),Object.assign({}, _vm.$listeners,
      {input: function (event) { return _vm.$emit('input', event.target.value); }})))}
var form_inputvue_type_template_id_1f07e820_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-input.vue?vue&type=template&id=1f07e820&

// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-input.vue

var script = {}


/* normalize component */

var form_input_component = normalizeComponent(
  script,
  form_inputvue_type_template_id_1f07e820_render,
  form_inputvue_type_template_id_1f07e820_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var form_input = (form_input_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vx-vue/autocomplete.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//



let uniqueId = function() {
  let counter = 0;
  return function(prefix) {
    return (prefix || "") + ++counter;
  }
}();

/* harmony default export */ var autocompletevue_type_script_lang_js_ = ({
  name: 'Autocomplete',
  inheritAttrs: false,

  components: {
    'autocomplete-input': form_input,
  },
  props: {
    value: {
      type: String,
      default: ""
    },
    search: {
      type: Function,
      required: true
    },
    baseClass: {
      type: String,
      default: 'form-autocomplete'
    },
    resultListClass: {
      type: String,
      default: 'menu'
    },
    resultClass: {
      type: String,
      default: 'menu-item'
    },
    inputClass: {
      type: String,
      default: 'autocomplete-input'
    },
    autoSelect: {
      type: Boolean,
      default: false
    },
    getResultValue: {
      type: Function,
      default: result => result
    }
  },

  data() {
    return {
      resultListId: uniqueId(this.resultListClass + "-"),
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
    containerProps() {
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
        class: this.inputClass,
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
        class: this.resultListClass,
        role: 'listbox'
      }
    },
    resultProps() {
      return this.results.map((result, index) => ({
        id: this.resultClass + "-" + index,
        class: this.resultClass,
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
    if (!this.$refs.resultList) {
      return;
    }

    let inputPos = this.$refs.input.$el.getBoundingClientRect();
    let listPos = this.$refs.resultList.getBoundingClientRect();

    if (this.resetPosition && this.results.length) {

      this.resetPosition = false;

      // show list above or below

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
    handleInput (value) {
      this.$emit('input', value);
      this.updateResults(value);
    },

    handleFocus (event) {
      this.updateResults(event.target.value);
    },

    handleBlur () {
      this.hideResults();
      this.$emit ('blur');
    },

    handleUp () {
      const listLen = this.results.length;
      this.selectedIndex = (((this.selectedIndex - 1) % listLen) + listLen) % listLen;
    },

    handleDown (event) {
      if(!this.expanded) {
        this.handleFocus(event);
      }
      const listLen = this.results.length;
      this.selectedIndex = (((this.selectedIndex + 1) % listLen) + listLen) % listLen;
    },

    handleEsc () {
      this.hideResults();
      this.$emit('input', '');
    },

    handleEnter () {
      this.$emit('submit', this.selectResult());
    },

    handleTab () {
      this.selectResult();
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
        this.$emit('input', this.getResultValue(selectedResult));
      }
      this.hideResults();
      return selectedResult;
    },

    handleResultClick (event) {
      const result = event.target.closest('[data-result-index]');
      if (result) {
        this.selectedIndex = parseInt(result.dataset.resultIndex, 10);
        this.$emit('submit', this.selectResult());
      }
    },

    handleDocumentClick (event) {
      if (this.$refs.container.contains(event.target)) {
        return;
      }
      this.hideResults();
    },

    updateResults (value) {

      const search = this.search(value);

      if (search instanceof Promise) {

        const currentSearch = ++this.searchCounter;
        this.loading = true;

        search.then(results => {
          if (currentSearch !== this.searchCounter) {
            return;
          }
          this.results = results;
          this.loading = false;

          if (!this.results.length) {
            this.hideResults();
          } else {
            this.selectedIndex = this.autoSelect ? 0 : -1;
            this.expanded = true;
          }
        });

      } else {

        this.results = search;

        if (this.results.length === 0) {
          this.hideResults();
        } else {
          this.selectedIndex = this.autoSelect ? 0 : -1;
          this.expanded = true;
        }
      }
    }
  }
});

// CONCATENATED MODULE: ./vue/components/vx-vue/autocomplete.vue?vue&type=script&lang=js&
 /* harmony default export */ var vx_vue_autocompletevue_type_script_lang_js_ = (autocompletevue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/vx-vue/autocomplete.vue





/* normalize component */

var autocomplete_component = normalizeComponent(
  vx_vue_autocompletevue_type_script_lang_js_,
  autocompletevue_type_template_id_d11d0aa8_render,
  autocompletevue_type_template_id_d11d0aa8_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var autocomplete = (autocomplete_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vx-vue/formelements/datepicker.vue?vue&type=template&id=637cf27e&
var datepickervue_type_template_id_637cf27e_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',_vm._b({},'div',_vm.rootProps,false),[(_vm.hasInput)?_c('date-input',_vm._b({ref:"input",attrs:{"value":_vm.selectedDate},on:{"input":_vm.handleInput,"toggle-datepicker":_vm.toggleDatepicker}},'date-input',_vm.inputProps,false)):_vm._e(),_c('div',_vm._b({ref:"calendar",staticClass:"calendar",class:_vm.align === 'left' ? 'align-left' : 'align-right'},'div',_vm.calendarProps,false),[_c('div',{staticClass:"calendar-nav navbar"},[_c('button',{staticClass:"btn btn-action btn-link btn-large prvMon",on:{"click":function($event){$event.stopPropagation();return _vm.previousMonth($event)}}}),_c('div',{staticClass:"month navbar-primary"},[_vm._v(_vm._s(_vm.monthLabel)+" "+_vm._s(_vm.year))]),_c('button',{staticClass:"btn btn-action btn-link btn-large nxtMon",on:{"click":function($event){$event.stopPropagation();return _vm.nextMonth($event)}}})]),_c('div',{staticClass:"calendar-container"},[_c('div',{staticClass:"calendar-header"},_vm._l((_vm.weekdays),function(weekday){return _c('div',{staticClass:"calendar-date"},[_vm._v(_vm._s(weekday))])}),0),_c('div',{staticClass:"calendar-body"},_vm._l((_vm.days),function(day){return _c('div',{key:day.getTime(),staticClass:"calendar-date text-center",class:['prev-month', '', 'next-month'][day.getMonth() - _vm.month + 1]},[_c('button',{staticClass:"date-item",class:{
                            'active': _vm.selectedDate && day.getTime() === _vm.selectedDate.getTime(),
                            'date-today': day.getTime() === _vm.today.getTime()
                        },attrs:{"type":"button","disabled":(_vm.validFrom && _vm.validFrom) > day || (_vm.validUntil && _vm.validUntil < day)},on:{"click":function($event){$event.stopPropagation();(_vm.validFrom && _vm.validFrom) > day || (_vm.validUntil && _vm.validUntil < day) ? null : _vm.selectDate(day)}}},[_vm._v(_vm._s(day.getDate()))])])}),0)])])],1)}
var datepickervue_type_template_id_637cf27e_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/datepicker.vue?vue&type=template&id=637cf27e&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vx-vue/formelements/date-input.vue?vue&type=template&id=3dfc53c8&
var date_inputvue_type_template_id_3dfc53c8_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"date-input"},[_c('div',{staticClass:"input-group",style:(_vm.computedStyles)},[(_vm.dateString)?_c('div',{staticClass:"form-input"},[_c('span',{staticClass:"chip"},[_vm._v(" "+_vm._s(_vm.dateString)+" "),_c('a',{staticClass:"btn btn-clear",attrs:{"href":"#","aria-label":"Close","role":"button"},on:{"click":function($event){$event.preventDefault();return _vm.handleClear($event)}}})])]):_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.inputString),expression:"inputString"}],staticClass:"form-input",attrs:{"type":"text","autocomplete":"off"},domProps:{"value":(_vm.inputString)},on:{"blur":_vm.handleBlur,"input":function($event){if($event.target.composing){ return; }_vm.inputString=$event.target.value}}}),(_vm.showButton)?_c('button',{staticClass:"btn webfont-icon-only btn-primary input-group-btn",attrs:{"type":"button"},on:{"click":function($event){$event.stopPropagation();return _vm.$emit('toggle-datepicker')}}},[_vm._v("")]):_vm._e()])])}
var date_inputvue_type_template_id_3dfc53c8_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/date-input.vue?vue&type=template&id=3dfc53c8&

// CONCATENATED MODULE: ./vue/util/date-functions.js
/* harmony default export */ var date_functions = ({

    formatDate(date, format, options) {

        if (!date instanceof Date) {
            return "";
        }

        const dayNames = options && options.dayNames ? options.dayNames : "Sun Mon Tue Wed Thu Fri Sat".split(" ");
        const monthNames = options && options.monthNames ? options.monthNames : "Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec".split(" ");

        return format
            .replace(/\bd\b/, date.getDate())
            .replace(/\bdd\b/, ("0" + date.getDate()).slice(-2))
            .replace(/\bm\b/, date.getMonth() + 1)
            .replace(/\bmm\b/, ("0" + (date.getMonth() + 1)).slice(-2))
            .replace(/\bmmm\b/, monthNames[date.getMonth()].trim())
            .replace(/\by\b/, date.getFullYear())
            .replace(/\bw\b/, dayNames[date.getDay()].trim())
            .replace(/\bh\b/, ("0" + date.getHours()).slice(-2))
            .replace(/\bi\b/, ("0" + date.getMinutes()).slice(-2))
            .replace(/\bs\b/, ("0" + date.getSeconds()).slice(-2));
    },

    parseDate(dateString, format) {

        let matches, escapedFormat = format.toLowerCase().replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), posMap = [];

        // check for single day, month and year expression

        if((matches = format.match(/\bd\b/gi)) && 1 === matches.length) {
            escapedFormat = escapedFormat.replace('d', '(\\d{1,2})');
        }
        else if((matches = format.match(/\bdd\b/gi)) && 1 === matches.length) {
            escapedFormat = escapedFormat.replace('dd', '(\\d{2})');
        }
        else {
            return false;
        }
        posMap.push( { srcPos: format.toLowerCase().indexOf('d'), destPos: 2 });

        if((matches = format.match(/\bm\b/gi)) && 1 === matches.length) {
            escapedFormat = escapedFormat.replace('m', '(\\d{1,2})');
        }
        else if((matches = format.match(/\bmm\b/gi)) && 1 === matches.length) {
            escapedFormat = escapedFormat.replace('mm', '(\\d{2})');
        }
        else {
            return false;
        }
        posMap.push( { srcPos: format.toLowerCase().indexOf('m'), destPos: 1 });

        if((matches = format.match(/\by\b/gi)) && 1 === matches.length) {
            escapedFormat = escapedFormat.replace('y', '(\\d{4})');
        }
        else {
            return false;
        }
        posMap.push( { srcPos: format.toLowerCase().indexOf('y'), destPos: 0 });

        if(!(matches = dateString.match(escapedFormat))) {
            return false;
        }

        // remove first match

        matches.shift();

        // bring day, month, year in correct order to allow ISO notation

        posMap.sort( (a, b) => a.srcPos < b.srcPos ? -1 : 1);

        let result = [], part, pos;

        while((part = matches.shift())) {
            pos = posMap.shift();
            result[pos.destPos] = part;
        }

        result = Date.parse(result.join('-'));

        if(!result) {
            return false;
        }
        result = new Date(result);
        return new Date(result.getFullYear(), result.getMonth(), result.getDate(), 0, 0, 0);

    },

    isValidTime: function (timeString) {
        let matches = timeString.match(/^\s*([0-9]{1,2}):([0-9]{1,2})(?::([0-9]{1,2}))?\s*$/);
        return (
            matches
            &&
            matches[1] < 24
            &&
            matches[2] < 60
            &&
            (!matches[3] || matches[3] < 60)
        );
    }
});
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vx-vue/formelements/date-input.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//



/* harmony default export */ var date_inputvue_type_script_lang_js_ = ({

    data() {
        return {
            inputString: null
        }
    },

    props: {
        monthNames: {
            type: Array,
            default: () => "Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec".split(" ")
        },
        dayNames: {
            type: Array,
            default: () => "Mon Tue Wed Thu Fri Sat Sun".split(" ")
        },
        outputFormat: {
            type: String,
            default: "y-mm-dd"
        },
        inputFormat: {
            type: String,
            default: 'y-m-d'
        },
        showButton: {
            type: Boolean,
            default: true
        },
        value: Date
    },

    watch: {
        value (value) {
            this.inputString = value ? date_functions.formatDate(value, this.outputFormat) : '';
        }
    },

    computed: {
        dateString () {
            return this.value ? date_functions.formatDate(this.value, this.outputFormat) : '';
        },
        computedStyles() {
            return {
                width: '100%'
            }
        }
    },

    methods: {
        handleBlur () {
            let date = date_functions.parseDate(this.inputString, this.inputFormat);
            this.$emit('input', date || null);
        },
        handleClear () {
            this.$emit('input', null);
        }
    }
});

// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/date-input.vue?vue&type=script&lang=js&
 /* harmony default export */ var formelements_date_inputvue_type_script_lang_js_ = (date_inputvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/date-input.vue





/* normalize component */

var date_input_component = normalizeComponent(
  formelements_date_inputvue_type_script_lang_js_,
  date_inputvue_type_template_id_3dfc53c8_render,
  date_inputvue_type_template_id_3dfc53c8_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var date_input = (date_input_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vx-vue/formelements/datepicker.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//



/* harmony default export */ var datepickervue_type_script_lang_js_ = ({
    components: {
        DateInput: date_input
    },

    data() {
        return {
            year: null,
            month: null,
            selectedDate: null,
            expanded: !this.hasInput,
            align: 'left'
        };
    },

    watch: {
        value (newValue) {
            this.year = (newValue || this.today).getFullYear();
            this.month = (newValue || this.today).getMonth();
            this.selectedDate = newValue ? new Date(newValue.getFullYear(), newValue.getMonth(), newValue.getDate()) : null;
        },
        expanded (newValue) {
            if(newValue && this.hasInput) {
                this.$nextTick(() =>
                     this.align = this.$refs.input.$el.getBoundingClientRect().left + this.$refs.calendar.getBoundingClientRect().width > window.innerWidth ? 'right' : 'left'
                );
            }
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
                style: { position: 'relative' },
                inputFormat: this.$attrs['input-format'],
                outputFormat: this.$attrs['output-format'],
                dayNames: this.$attrs['day-names'],
                monthNames: this.$attrs['month-names'],
                showButton: this.$attrs['show-button']
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
        value: {
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
            default: (() => "M T W T F S S".split(" "))
        },
        monthNames: {
            type: Array,
            default: (() => "Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec".split(" "))
        },
        startOfWeekIndex: {
            type: Number,
            default: 1,
            validator: value => !value || value === 1
        },
        hasInput: {
            type: Boolean,
            default: true
        }
    },

    mounted () {
        if(this.hasInput) {
            document.body.addEventListener('click', this.handleBodyClick);
        }
        this.year = (this.value || this.today).getFullYear();
        this.month = (this.value || this.today).getMonth();
        this.selectedDate = this.value ? new Date(this.value.getFullYear(), this.value.getMonth(), this.value.getDate()) : null;
    },
    beforeDestroy() {
        if(this.hasInput) {
            document.body.removeEventListener('click', this.handleBodyClick);
        }
    },

    methods: {
        previousMonth() {
            const d = new Date(this.year, this.month - 1, 1);
            this.month = d.getMonth();
            this.year = d.getFullYear();
            this.$emit("month-change");
        },
        nextMonth() {
            const d = new Date(this.year, this.month + 1, 1);
            this.month = d.getMonth();
            this.year = d.getFullYear();
            this.$emit("month-change");
        },
        selectDate(day) {
            this.selectedDate = day;
            this.$emit('input', day);
            this.expanded = !this.hasInput;
        },
        toggleDatepicker() {
            this.expanded = !this.expanded;
        },
        handleBodyClick() {
            this.expanded = false;
        },
        handleInput (date) {
            this.selectedDate = date;
            this.$emit('input', date);
        }
    }
});

// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/datepicker.vue?vue&type=script&lang=js&
 /* harmony default export */ var formelements_datepickervue_type_script_lang_js_ = (datepickervue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/datepicker.vue





/* normalize component */

var datepicker_component = normalizeComponent(
  formelements_datepickervue_type_script_lang_js_,
  datepickervue_type_template_id_637cf27e_render,
  datepickervue_type_template_id_637cf27e_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var datepicker = (datepicker_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vx-vue/sortable.vue?vue&type=template&id=333bb357&
var sortablevue_type_template_id_333bb357_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('table',{staticClass:"table table-striped"},[_c('thead',[_c('tr',_vm._l((_vm.columns),function(column){return _c('th',{class:[
                'vx-sortable-header',
                column.cssClass,
                _vm.sortColumn === column ? _vm.sortDir : null,
                column.width
            ],on:{"click":function($event){column.sortable ? _vm.clickSort(column) : null}}},[_vm._t(column.prop + '-header',[_vm._v(" "+_vm._s(column.label)+" ")],{"column":column})],2)}),0)]),_c('tbody',_vm._l((_vm.sortedRows),function(row){return _c('tr',{key:row[_vm.keyProperty],class:row.cssClass},_vm._l((_vm.columns),function(column){return _c('td',{class:{ 'active': _vm.sortColumn === column }},[_vm._t(column.prop,[_vm._v(_vm._s(row[column.prop]))],{"row":row})],2)}),0)}),0)])}
var sortablevue_type_template_id_333bb357_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/vx-vue/sortable.vue?vue&type=template&id=333bb357&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vx-vue/sortable.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ var sortablevue_type_script_lang_js_ = ({
    name: 'sortable',

    props: {
        columns: {
            type: Array,
            required: true,
            validator (val) {
                for(let l = val.length; l--;) {
                    if(val[l].label === undefined || val[l].prop === undefined) {
                        return false;
                    }
                }
                return true;
            }
        },
        rows: {
            type: Array,
            required: true
        },
        offset: {
            type: Number,
            default: null
        },
        count: {
            type: Number,
            default: null
        },
        sortProp: {
            type: String
        },
        sortDirection: {
            type: String,
            validator (val) {
                return !val || ['asc', 'desc'].indexOf(val) !== -1;
            }
        },
        keyProperty: {
          type: String,
          default: 'key'
        }
    },

    data() {
        return {
            sortColumn: this.columns.find(item => item.prop === this.sortProp),
            sortDir: this.sortDirection
        };
    },

    computed: {
        sortedRows () {
            let rows = this.rows.slice();

            if(this.sortColumn) {
                if (this.sortDir === 'asc' && this.sortColumn.sortAscFunction) {
                    rows.sort (this.sortColumn.sortAscFunction);
                }
                else if (this.sortDir === 'desc' && this.sortColumn.sortDescFunction) {
                    rows.sort (this.sortColumn.sortDescFunction);
                }
                else {
                    let prop = this.sortColumn.prop;

                    rows.sort((a, b) => {
                        if (a[prop] < b[prop]) {
                            return this.sortDir === "asc" ? -1 : 1;
                        }
                        if (a[prop] > b[prop]) {
                            return this.sortDir === "asc" ? 1 : -1;
                        }
                        return 0;
                    });
                }
            }

            let offset = this.offset || 0, count = this.count || rows.length;

            return rows.slice(offset, offset + count);
        }
    },

    methods: {
        clickSort (column) {
            this.$emit('before-sort');
            if(this.sortColumn === column) {
                this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
            }
            else {
                this.sortColumn = column;
            }
            this.$nextTick( () => this.$emit('after-sort') );
        }
    }
});

// CONCATENATED MODULE: ./vue/components/vx-vue/sortable.vue?vue&type=script&lang=js&
 /* harmony default export */ var vx_vue_sortablevue_type_script_lang_js_ = (sortablevue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/vx-vue/sortable.vue





/* normalize component */

var sortable_component = normalizeComponent(
  vx_vue_sortablevue_type_script_lang_js_,
  sortablevue_type_template_id_333bb357_render,
  sortablevue_type_template_id_333bb357_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var sortable = (sortable_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vx-vue/simple-tree/simple-tree.vue?vue&type=template&id=77ec9f4f&
var simple_treevue_type_template_id_77ec9f4f_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('ul',{staticClass:"vx-tree"},[_c('simple-tree-branch',{directives:[{name:"bubble",rawName:"v-bubble.branch-selected",modifiers:{"branch-selected":true}}],attrs:{"branch":_vm.branch}})],1)}
var simple_treevue_type_template_id_77ec9f4f_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/vx-vue/simple-tree/simple-tree.vue?vue&type=template&id=77ec9f4f&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vx-vue/simple-tree/simple-tree-branch.vue?vue&type=template&id=9b63f26a&
var simple_tree_branchvue_type_template_id_9b63f26a_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('li',{class:{ 'terminates': !_vm.branch.branches || !_vm.branch.branches.length }},[(_vm.branch.branches && _vm.branch.branches.length)?[_c('input',{attrs:{"type":"checkbox","id":'branch-' + _vm.branch.id},domProps:{"checked":_vm.expanded},on:{"click":function($event){_vm.expanded = !_vm.expanded}}}),_c('label',{attrs:{"for":'branch-' + _vm.branch.id}})]:_vm._e(),(_vm.branch.current)?_c('strong',[_vm._v(_vm._s(_vm.branch.label))]):_c('a',{attrs:{"href":_vm.branch.path},on:{"click":function($event){$event.preventDefault();return _vm.$emit('branch-selected', _vm.branch)}}},[_vm._v(_vm._s(_vm.branch.label))]),(_vm.branch.branches && _vm.branch.branches.length)?_c('ul',{directives:[{name:"show",rawName:"v-show",value:(_vm.expanded),expression:"expanded"}]},_vm._l((_vm.branch.branches),function(child){return _c('simple-tree-branch',{directives:[{name:"bubble",rawName:"v-bubble.branch-selected",modifiers:{"branch-selected":true}}],key:child.id,attrs:{"branch":child}})}),1):_vm._e()],2)}
var simple_tree_branchvue_type_template_id_9b63f26a_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/vx-vue/simple-tree/simple-tree-branch.vue?vue&type=template&id=9b63f26a&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vx-vue/simple-tree/simple-tree-branch.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ var simple_tree_branchvue_type_script_lang_js_ = ({
  name: 'simple-tree-branch',

  data () { return {
    expanded: false
  }},

  props: {
    branch: { type: Object, default: () => { return {} } }
  },

  mounted () {
    if(this.branch.current) {
      let parent = this.$parent;
      while(parent && parent.branch && parent.expanded !== undefined) {
        parent.expanded = true;
        parent = parent.$parent;
      }
    }
  }
});

// CONCATENATED MODULE: ./vue/components/vx-vue/simple-tree/simple-tree-branch.vue?vue&type=script&lang=js&
 /* harmony default export */ var simple_tree_simple_tree_branchvue_type_script_lang_js_ = (simple_tree_branchvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/vx-vue/simple-tree/simple-tree-branch.vue





/* normalize component */

var simple_tree_branch_component = normalizeComponent(
  simple_tree_simple_tree_branchvue_type_script_lang_js_,
  simple_tree_branchvue_type_template_id_9b63f26a_render,
  simple_tree_branchvue_type_template_id_9b63f26a_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var simple_tree_branch = (simple_tree_branch_component.exports);
// CONCATENATED MODULE: ./vue/directives.js
// simple directive to enable event bubbling

const Bubble = {
    bind (el, binding, vnode) {
        Object.keys(binding.modifiers).forEach(event => {
            // Bubble events of Vue components
            if (vnode.componentInstance) {
                vnode.componentInstance.$off(event);
                vnode.componentInstance.$on(event, (...args) => {
                    vnode.context.$emit(event, ...args);
                });
                // Bubble events of native DOM elements
            } else {
                el.addEventListener(event, payload => {
                    vnode.context.$emit(event, payload);
                });
            }
        })
    }
};

// focus an element upon insertion

const Focus = { inserted: (el) => el.focus() };



// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vx-vue/simple-tree/simple-tree.vue?vue&type=script&lang=js&
//
//
//
//
//
//




Vue.directive('bubble', Bubble);

/* harmony default export */ var simple_treevue_type_script_lang_js_ = ({
    name: 'simple-tree',
    props: {
        branch: Object
    },
    components: {
      SimpleTreeBranch: simple_tree_branch
    }
});

// CONCATENATED MODULE: ./vue/components/vx-vue/simple-tree/simple-tree.vue?vue&type=script&lang=js&
 /* harmony default export */ var simple_tree_simple_treevue_type_script_lang_js_ = (simple_treevue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/vx-vue/simple-tree/simple-tree.vue





/* normalize component */

var simple_tree_component = normalizeComponent(
  simple_tree_simple_treevue_type_script_lang_js_,
  simple_treevue_type_template_id_77ec9f4f_render,
  simple_treevue_type_template_id_77ec9f4f_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var simple_tree = (simple_tree_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/filemanager/filemanager.vue?vue&type=template&id=629145da&
var filemanagervue_type_template_id_629145da_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{class:{'dragged-over': _vm.indicateDrag},on:{"drop":function($event){$event.preventDefault();return _vm.uploadDraggedFiles($event)},"dragover":function($event){$event.preventDefault();_vm.indicateDrag = true},"dragleave":function($event){$event.preventDefault();_vm.indicateDrag = false}}},[_c('div',{staticClass:"vx-button-bar navbar"},[_c('section',{staticClass:"navbar-section"},[_c('filemanager-breadcrumbs',{attrs:{"breadcrumbs":_vm.breadcrumbs,"current-folder":_vm.currentFolder,"folders":_vm.folders},on:{"breadcrumb-clicked":_vm.readFolder}}),_c('div',{staticClass:"popup popup-bottom ml-1",class:{ active: _vm.showAddActivities }},[_c('button',{staticClass:"btn webfont-icon-only",attrs:{"type":"button"},on:{"click":function($event){$event.stopPropagation();_vm.showAddActivities = !_vm.showAddActivities}}},[_vm._v("")]),_c('div',{staticClass:"popup-container"},[_c('div',{staticClass:"card"},[_c('div',{staticClass:"card-body"},[_c('filemanager-add',{on:{"upload":_vm.uploadInputFiles,"create-folder":_vm.createFolder}})],1)])])]),_c('filemanager-actions',{attrs:{"files":_vm.checkedFiles,"folders":_vm.checkedFolders},on:{"delete-selection":_vm.delSelection,"move-selection":_vm.moveSelection}})],1),_c('section',{staticClass:"navbar-section"},[(_vm.upload.progressing)?[_c('button',{staticClass:"btn btn-link webfont-icon-only tooltip",attrs:{"data-tooltip":"Abbrechen","type":"button"},on:{"click":_vm.cancelUpload}},[_vm._v("")]),_c('label',{staticClass:"d-inline-block mr-2"},[_vm._v(_vm._s(_vm.progress.file))]),_c('circular-progress',{attrs:{"progress":100 * _vm.progress.loaded / (_vm.progress.total || 1),"radius":16}})]:_c('strong',{staticClass:"text-primary d-block col-12 text-center"},[_vm._v("Dateien zum Upload hierher ziehen")])],2),_c('section',{staticClass:"navbar-section"},[_c('filemanager-search',{attrs:{"search":_vm.doSearch},scopedSlots:_vm._u([{key:"folder",fn:function(slotProps){return [_c('span',{staticClass:"with-webfont-icon-left",attrs:{"data-icon":""}},[_c('a',{attrs:{"href":'#' + slotProps.folder.id},on:{"click":function($event){$event.preventDefault();return _vm.readFolder(slotProps.folder.id)}}},[_vm._v(_vm._s(slotProps.folder.name))])])]}},{key:"file",fn:function(slotProps){return [_c('span',{staticClass:"with-webfont-icon-left",attrs:{"data-icon":""}},[_vm._v(_vm._s(slotProps.file.name)+" ("+_vm._s(slotProps.file.type)+")")]),_c('br'),_c('a',{attrs:{"href":'#' + slotProps.file.folder},on:{"click":function($event){$event.preventDefault();return _vm.readFolder(slotProps.file.folder)}}},[_vm._v(_vm._s(slotProps.file.path))])]}}])})],1)]),_c('sortable',{ref:"sortable",attrs:{"rows":_vm.directoryEntries,"columns":_vm.columns,"sort-prop":_vm.initSort.column,"sort-direction":_vm.initSort.dir,"id":"files-list"},on:{"after-sort":function($event){return _vm.$emit('after-sort', { sortColumn: _vm.$refs.sortable.sortColumn, sortDir: _vm.$refs.sortable.sortDir })}},scopedSlots:_vm._u([{key:"checked-header",fn:function(){return [_c('label',{staticClass:"form-checkbox"},[_c('input',{directives:[{name:"check-indeterminate",rawName:"v-check-indeterminate"}],attrs:{"type":"checkbox"},on:{"click":_vm.toggleAll}}),_c('i',{staticClass:"form-icon"})])]},proxy:true},{key:"checked",fn:function(slotProps){return [_c('label',{staticClass:"form-checkbox"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(slotProps.row.checked),expression:"slotProps.row.checked"}],attrs:{"type":"checkbox"},domProps:{"checked":Array.isArray(slotProps.row.checked)?_vm._i(slotProps.row.checked,null)>-1:(slotProps.row.checked)},on:{"change":function($event){var $$a=slotProps.row.checked,$$el=$event.target,$$c=$$el.checked?(true):(false);if(Array.isArray($$a)){var $$v=null,$$i=_vm._i($$a,$$v);if($$el.checked){$$i<0&&(_vm.$set(slotProps.row, "checked", $$a.concat([$$v])))}else{$$i>-1&&(_vm.$set(slotProps.row, "checked", $$a.slice(0,$$i).concat($$a.slice($$i+1))))}}else{_vm.$set(slotProps.row, "checked", $$c)}}}}),_c('i',{staticClass:"form-icon"})])]}},{key:"name",fn:function(slotProps){return [(slotProps.row.isFolder)?[(slotProps.row === _vm.toRename)?_c('input',{directives:[{name:"focus",rawName:"v-focus"}],staticClass:"form-input",domProps:{"value":slotProps.row.name},on:{"keydown":[function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"enter",13,$event.key,"Enter")){ return null; }return _vm.renameFolder($event)},function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"esc",27,$event.key,["Esc","Escape"])){ return null; }_vm.toRename = null}],"blur":function($event){_vm.toRename = null}}}):[_c('a',{attrs:{"href":'#' + slotProps.row.id},on:{"click":function($event){$event.preventDefault();return _vm.readFolder(slotProps.row.id)}}},[_vm._v(_vm._s(slotProps.row.name))]),_c('button',{staticClass:"btn webfont-icon-only tooltip mr-1 rename display-only-on-hover ml-2",attrs:{"data-tooltip":"Umbenennen"},on:{"click":function($event){_vm.toRename = slotProps.row}}},[_vm._v("")])]]:[(slotProps.row === _vm.toRename)?_c('input',{directives:[{name:"focus",rawName:"v-focus"}],staticClass:"form-input",domProps:{"value":slotProps.row.name},on:{"keydown":[function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"enter",13,$event.key,"Enter")){ return null; }return _vm.renameFile($event)},function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"esc",27,$event.key,["Esc","Escape"])){ return null; }_vm.toRename = null}],"blur":function($event){_vm.toRename = null}}}):[_c('span',[_vm._v(_vm._s(slotProps.row.name))]),_c('button',{staticClass:"btn webfont-icon-only tooltip mr-1 rename display-only-on-hover ml-2",attrs:{"data-tooltip":"Umbenennen"},on:{"click":function($event){_vm.toRename = slotProps.row}}},[_vm._v("")])]]]}},{key:"size",fn:function(slotProps){return [(!slotProps.row.isFolder)?[_vm._v(_vm._s(_vm._f("formatFilesize")(slotProps.row.size,',')))]:_vm._e()]}},{key:"type",fn:function(slotProps){return [(slotProps.row.image)?_c('img',{attrs:{"src":slotProps.row.src,"alt":""}}):_c('span',[_vm._v(_vm._s(slotProps.row.type))])]}},_vm._l((_vm.$scopedSlots),function(_,name){return {key:name,fn:function(slotData){return [_vm._t(name,null,null,slotData)]}}})],null,true)}),(_vm.showEditForm)?_c('div',{staticClass:"modal active"},[_c('div',{staticClass:"modal-overlay"}),_c('div',{staticClass:"modal-container"},[_c('div',{staticClass:"modal-header"},[_c('a',{staticClass:"btn btn-clear float-right",attrs:{"href":"#close","aria-label":"Close"},on:{"click":function($event){$event.preventDefault();_vm.showEditForm = false}}})]),_c('div',{staticClass:"modal-body"},[_c('file-edit-form',{ref:"editForm",attrs:{"initial-data":_vm.editFormData,"file-info":_vm.editFileInfo,"url":_vm.routes.updateFile},on:{"response-received":function (response) { return _vm.$emit('response-received', response); }}})],1)])]):_vm._e(),_c('confirm',{ref:"confirm",attrs:{"config":{ cancelLabel: 'Abbrechen', okLabel: 'Löschen', okClass: 'btn-error' }}}),_c('alert',{ref:"alert",attrs:{"config":{ label: 'Ok', buttonClass: 'btn-error' }}}),_c('folder-tree',{ref:"folder-tree"})],1)}
var filemanagervue_type_template_id_629145da_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/filemanager/filemanager.vue?vue&type=template&id=629145da&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/filemanager/filemanager-add.vue?vue&type=template&id=00de0c76&
var filemanager_addvue_type_template_id_00de0c76_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[(_vm.showAddFolderInput)?_c('input',{directives:[{name:"focus",rawName:"v-focus"}],staticClass:"form-input",on:{"keydown":[function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"enter",13,$event.key,"Enter")){ return null; }return _vm.addFolder($event)},function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"esc",27,$event.key,["Esc","Escape"])){ return null; }_vm.showAddFolderInput = false}],"blur":function($event){_vm.showAddFolderInput = false}}}):_vm._e(),(!_vm.showAddFolderInput)?_c('button',{staticClass:"btn with-webfont-icon-left btn-link",attrs:{"type":"button","data-icon":""},on:{"click":function($event){$event.stopPropagation();_vm.showAddFolderInput = true}}},[_vm._v("Verzeichnis erstellen")]):_vm._e(),_c('label',{staticClass:"btn with-webfont-icon-left btn-link",attrs:{"data-icon":"","for":"file_upload"}},[_vm._v("Datei hochladen")]),_c('input',{staticClass:"d-none",attrs:{"type":"file","id":"file_upload","multiple":_vm.multiple},on:{"change":_vm.fileChanged}})])}
var filemanager_addvue_type_template_id_00de0c76_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/filemanager/filemanager-add.vue?vue&type=template&id=00de0c76&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/filemanager/filemanager-add.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//



/* harmony default export */ var filemanager_addvue_type_script_lang_js_ = ({
    data() {
        return {
            showAddFolderInput: false
        }
    },

    props: {
        multiple: { type: Boolean, default: true }
    },

    methods: {
        fileChanged (event) {
            const files = event.target.files || event.dataTransfer.files;
            if (files) {
                this.$emit('upload', files);
            }
        },
        addFolder (event) {
            const name = event.target.value.trim();
            if(name) {
                this.$emit('create-folder', name);
            }
            this.showAddFolderInput = false;
        }
    },

    directives: {
        focus: Focus
    }
});

// CONCATENATED MODULE: ./vue/components/filemanager/filemanager-add.vue?vue&type=script&lang=js&
 /* harmony default export */ var filemanager_filemanager_addvue_type_script_lang_js_ = (filemanager_addvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/filemanager/filemanager-add.vue





/* normalize component */

var filemanager_add_component = normalizeComponent(
  filemanager_filemanager_addvue_type_script_lang_js_,
  filemanager_addvue_type_template_id_00de0c76_render,
  filemanager_addvue_type_template_id_00de0c76_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var filemanager_add = (filemanager_add_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/filemanager/filemanager-actions.vue?vue&type=template&id=45b0484a&
var filemanager_actionsvue_type_template_id_45b0484a_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return (_vm.files.length || _vm.folders.length)?_c('div',{staticClass:"mx-2"},[_c('button',{staticClass:"btn btn-link webfont-icon-only tooltip",attrs:{"data-tooltip":_vm.files.length + _vm.folders.length + ' gewählte Dateien/Ordner löschen',"type":"button"},on:{"click":_vm.confirmDelete}},[_vm._v("")]),_c('button',{staticClass:"btn btn-link webfont-icon-only tooltip",attrs:{"data-tooltip":_vm.files.length + _vm.folders.length + ' gewählte Dateien/Ordner verschieben',"type":"button"},on:{"click":_vm.pickFolder}},[_vm._v("")])]):_vm._e()}
var filemanager_actionsvue_type_template_id_45b0484a_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/filemanager/filemanager-actions.vue?vue&type=template&id=45b0484a&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/filemanager/filemanager-actions.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//

/* harmony default export */ var filemanager_actionsvue_type_script_lang_js_ = ({
    props: {
        files: { type: Array, default: [] },
        folders:  { type: Array, default: [] }
    },
    methods: {
        async confirmDelete () {
            if(await this.$parent.$refs.confirm.open('Auswahl löschen', "Selektierte Dateien/Ordner wirklich löschen?")) {
                this.$emit('delete-selection');
            }
        },
        async pickFolder () {
          this.$emit('move-selection');
        }
    }
});

// CONCATENATED MODULE: ./vue/components/filemanager/filemanager-actions.vue?vue&type=script&lang=js&
 /* harmony default export */ var filemanager_filemanager_actionsvue_type_script_lang_js_ = (filemanager_actionsvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/filemanager/filemanager-actions.vue





/* normalize component */

var filemanager_actions_component = normalizeComponent(
  filemanager_filemanager_actionsvue_type_script_lang_js_,
  filemanager_actionsvue_type_template_id_45b0484a_render,
  filemanager_actionsvue_type_template_id_45b0484a_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var filemanager_actions = (filemanager_actions_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/filemanager/filemanager-search.vue?vue&type=template&id=e8a8e562&
var filemanager_searchvue_type_template_id_e8a8e562_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('div',{staticClass:"has-icon-right"},[_c('input',_vm._b({staticClass:"text-input",on:{"keydown":function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"esc",27,$event.key,["Esc","Escape"])){ return null; }return _vm.handleEsc($event)},"input":_vm.handleInput,"focus":_vm.handleInput}},'input',_vm.inputProps,false)),(_vm.loading)?_c('i',{staticClass:"form-icon loading"}):_vm._e()]),(_vm.showResults)?_c('div',{staticClass:"modal-container",staticStyle:{"position":"fixed","left":"50%","top":"50%","transform":"translate(-50%, -50%)"}},[_c('div',{staticClass:"modal-header"},[_c('a',{staticClass:"btn btn-clear float-right",attrs:{"href":"#close","aria-label":"Close"},on:{"click":function($event){$event.preventDefault();_vm.showResults = false}}}),_c('div',{staticClass:"modal-title h5"},[_vm._v("Gefundene Dateien und Ordner…")])]),_c('div',{staticClass:"modal-body"},[_vm._l(((_vm.results.folders || [])),function(result){return _c('div',{key:result.id},[_vm._t("folder",[_vm._v(" "+_vm._s(result.name)+" ")],{"folder":result})],2)}),(_vm.results.folders.length && _vm.results.files.length)?_c('div',{staticClass:"divider"}):_vm._e(),_vm._l(((_vm.results.files || [])),function(result){return _c('div',{key:result.id},[_vm._t("file",[_vm._v(" "+_vm._s(result.name)+" ("+_vm._s(result.type)+")"),_c('br'),_c('span',{staticClass:"text-gray"},[_vm._v(_vm._s(result.path))])],{"file":result})],2)})],2)]):_vm._e()])}
var filemanager_searchvue_type_template_id_e8a8e562_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/filemanager/filemanager-search.vue?vue&type=template&id=e8a8e562&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/filemanager/filemanager-search.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ var filemanager_searchvue_type_script_lang_js_ = ({
    data () {
        return {
            value: "",
            results: {
                files: [],
                folders: []
            },
            loading: false,
            hideDialog: false
        }
    },

    props: {
        placeholder: { type: String, default: 'Datei/Verzeichnis suchen...' },
        search: { type: Function, required: true }
    },

    computed: {
        inputProps () {
            return {
                value: this.value,
                placeholder: this.placeholder
            }
        },
        showResults: {
            get () {
                return this.results.folders.length || this.results.files.length;
            },
            set (newValue) {
                if(!newValue) {
                    this.results.folders = [];
                    this.results.files = [];
                }
            }
        }
    },

    methods: {
        handleInput (event) {
            this.value = event.target.value;
            const search = this.search(this.value);

            if (search instanceof Promise) {
                this.loading = true;
                search.then(results => {
                    this.results.files = results.files || [];
                    this.results.folders = results.folders || [];
                    this.loading = false;
                });
            }
            else {
                this.results = Object.assign({}, this.results, search);
            }
        },
        handleEsc () {
            this.value = "";
            this.showResults = false;
        }
    }
});

// CONCATENATED MODULE: ./vue/components/filemanager/filemanager-search.vue?vue&type=script&lang=js&
 /* harmony default export */ var filemanager_filemanager_searchvue_type_script_lang_js_ = (filemanager_searchvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/filemanager/filemanager-search.vue





/* normalize component */

var filemanager_search_component = normalizeComponent(
  filemanager_filemanager_searchvue_type_script_lang_js_,
  filemanager_searchvue_type_template_id_e8a8e562_render,
  filemanager_searchvue_type_template_id_e8a8e562_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var filemanager_search = (filemanager_search_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/filemanager/filemanager-breadcrumbs.vue?vue&type=template&id=17b6dd5d&
var filemanager_breadcrumbsvue_type_template_id_17b6dd5d_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('span',{staticClass:"btn-group"},_vm._l((_vm.items),function(breadcrumb,ndx){return _c('button',{key:ndx,staticClass:"btn",class:{ 'active': breadcrumb.folder === _vm.currentFolder },on:{"click":function($event){return _vm.$emit('breadcrumb-clicked', breadcrumb.folder)}}},[_vm._v(_vm._s(breadcrumb.name)+" ")])}),0)}
var filemanager_breadcrumbsvue_type_template_id_17b6dd5d_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/filemanager/filemanager-breadcrumbs.vue?vue&type=template&id=17b6dd5d&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/filemanager/filemanager-breadcrumbs.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ var filemanager_breadcrumbsvue_type_script_lang_js_ = ({
    data () {
        return { items: [] }
    },
    props: {
        breadcrumbs: Array,
        folders: Array,
        currentFolder: Number
    },
    watch: {
        breadcrumbs (newValue) {
            if (
                newValue.length >= this.items.length ||
                this.items.map(item => item.folder).join().indexOf(newValue.map(item => item.folder).join()) !== 0
            ) {
                this.items = newValue;
            }
        },
        folders: {
            deep: true,
            handler (newValue) {

                // find current folder

                let current = this.items.findIndex(item => item.folder === this.currentFolder);

                if(this.items[current + 1]) {
                    let ndx = newValue.findIndex(item => item.id === this.items[current + 1].folder);

                    // check for deletion

                    if (ndx === -1) {
                        this.items = this.items.slice(0, current + 1);
                    }

                    // handle possible rename

                    else {
                        this.items[current + 1].name = newValue[ndx].name;
                    }
                }
            }
        }
    }
});

// CONCATENATED MODULE: ./vue/components/filemanager/filemanager-breadcrumbs.vue?vue&type=script&lang=js&
 /* harmony default export */ var filemanager_filemanager_breadcrumbsvue_type_script_lang_js_ = (filemanager_breadcrumbsvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/filemanager/filemanager-breadcrumbs.vue





/* normalize component */

var filemanager_breadcrumbs_component = normalizeComponent(
  filemanager_filemanager_breadcrumbsvue_type_script_lang_js_,
  filemanager_breadcrumbsvue_type_template_id_17b6dd5d_render,
  filemanager_breadcrumbsvue_type_template_id_17b6dd5d_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var filemanager_breadcrumbs = (filemanager_breadcrumbs_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/filemanager/filemanager-folder-tree.vue?vue&type=template&id=6834d85d&
var filemanager_folder_treevue_type_template_id_6834d85d_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{ref:"container",staticClass:"modal",class:{ active: _vm.show }},[_c('div',{staticClass:"modal-container"},[_c('div',{staticClass:"modal-header"},[_c('a',{staticClass:"btn btn-clear float-right",attrs:{"href":"#close","aria-label":"Close"},on:{"click":function($event){$event.preventDefault();return _vm.cancel($event)}}}),_c('div',{staticClass:"modal-title h5"},[_vm._v(_vm._s(_vm.title))])]),_c('div',{staticClass:"modal-body"},[_c('simple-tree',{attrs:{"branch":_vm.root},on:{"branch-selected":_vm.folderSelected}})],1)])])}
var filemanager_folder_treevue_type_template_id_6834d85d_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/filemanager/filemanager-folder-tree.vue?vue&type=template&id=6834d85d&

// CONCATENATED MODULE: ./vue/util/simple-fetch.js
async function SimpleFetch(url, method = 'GET', headers = {}, payload = null) {

    if(!headers['X-CSRF-Token'] && document.querySelector('meta[name="csrf-token"]')) {
        headers['X-CSRF-Token'] = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
    }
    headers['X-Requested-With'] = 'XMLHttpRequest';
    const response = await fetch(
        url,
        {
            method: method.toUpperCase(),
            body: payload,
            headers: headers
        }
    );

    return await response.json();
}

// CONCATENATED MODULE: ./vue/util/url-query.js
/* harmony default export */ var url_query = ({
    /**
     * @return {string}
     */
    create (url, query = {}) {
        return url + (url.indexOf('?') !== -1 ? '&' : '?') + this.objectToSearch(query);
    },

    /* @see https://stackoverflow.com/questions/8648892/how-to-convert-url-parameters-to-a-javascript-object */

    searchToObject (query) {
        return Object.fromEntries(new URLSearchParams(query));
    },

    /* @see https://stackoverflow.com/questions/1714786/query-string-encoding-of-a-javascript-object */

    objectToSearch (obj) {
        return new URLSearchParams(obj).toString();
    }
});

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/filemanager/filemanager-folder-tree.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//





/* harmony default export */ var filemanager_folder_treevue_type_script_lang_js_ = ({
  name: 'folder-tree',
  components: {
    'simple-tree': simple_tree
  },
  data () {
      return {
        show: false,
        resolve: null,
        reject: null,
        root: {}
      }
  },
  props: {
    title: { type: String, default: 'Zielordner wählen' }
  },
  methods: {
    async open (route, currentFolder) {

      this.root = await SimpleFetch(url_query.create(route, { folder: currentFolder }));
      this.show = true;

      return new Promise((resolve, reject) => {
        this.resolve = resolve;
        this.reject = reject;
      });
    },
    cancel () {
      this.show = false;
      this.resolve(false);
    },
    folderSelected (folder) {
      this.show = false;
      this.resolve(folder);
    }
  }
});

// CONCATENATED MODULE: ./vue/components/filemanager/filemanager-folder-tree.vue?vue&type=script&lang=js&
 /* harmony default export */ var filemanager_filemanager_folder_treevue_type_script_lang_js_ = (filemanager_folder_treevue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/filemanager/filemanager-folder-tree.vue





/* normalize component */

var filemanager_folder_tree_component = normalizeComponent(
  filemanager_filemanager_folder_treevue_type_script_lang_js_,
  filemanager_folder_treevue_type_template_id_6834d85d_render,
  filemanager_folder_treevue_type_template_id_6834d85d_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var filemanager_folder_tree = (filemanager_folder_tree_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vx-vue/confirm.vue?vue&type=template&id=217d9a93&
var confirmvue_type_template_id_217d9a93_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{ref:"container",staticClass:"modal modal-sm",class:{ active: _vm.show },on:{"keydown":function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"esc",27,$event.key,["Esc","Escape"])){ return null; }$event.stopPropagation();return _vm.cancel($event)}}},[_c('a',{staticClass:"modal-overlay",attrs:{"href":"#close"},on:{"click":function($event){$event.preventDefault();return _vm.cancel($event)}}}),_c('div',{staticClass:"modal-container"},[(_vm.title)?_c('div',{staticClass:"modal-header bg-error text-light"},[_c('div',{staticClass:"modal-title h5"},[_vm._v(_vm._s(_vm.title))])]):_vm._e(),_c('div',{staticClass:"modal-body"},[_c('div',{staticClass:"content"},[_vm._v(" "+_vm._s(_vm.message)+" ")])]),_c('div',{staticClass:"modal-footer"},[_c('button',{ref:"okButton",staticClass:"btn mr-2",class:_vm.options.okClass,attrs:{"type":"button"},on:{"click":function($event){$event.preventDefault();return _vm.ok($event)}}},[_vm._v(_vm._s(_vm.options.okLabel))]),_c('button',{staticClass:"btn btn-link",attrs:{"type":"button"},on:{"click":function($event){$event.preventDefault();return _vm.cancel($event)}}},[_vm._v(_vm._s(_vm.options.cancelLabel))])])])])}
var confirmvue_type_template_id_217d9a93_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/vx-vue/confirm.vue?vue&type=template&id=217d9a93&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vx-vue/confirm.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* heavily inspired by https://gist.github.com/eolant/ba0f8a5c9135d1a146e1db575276177d */

/* harmony default export */ var confirmvue_type_script_lang_js_ = ({
    name: 'confirm',

    props: {
        config: {
            type: Object
        }
    },

    data () { return {
        title: "",
        message: "",
        show: false,
        resolve: null,
        reject: null,
        options: {
            okLabel: "Ok",
            cancelLabel: "Cancel",
            okClass: "btn-success"
        }
    }},

    watch: {
        config() {
            this.options = Object.assign({}, this.options, this.config);
        }
    },

    created() {
        this.options = Object.assign({}, this.options, this.config);
    },

    methods: {
        open (title, message, options) {
            this.title = title;
            this.message = message;
            this.show = true;
            this.$nextTick(() => this.$refs.okButton.focus());
            this.options = Object.assign(this.options, options || {});
            return new Promise((resolve, reject) => {
                this.resolve = resolve;
                this.reject = reject;
            });
        },
        ok () {
            this.show = false;
            this.resolve(true);
        },
        cancel () {
            this.show = false;
            this.resolve(false);
        }
    }
});

// CONCATENATED MODULE: ./vue/components/vx-vue/confirm.vue?vue&type=script&lang=js&
 /* harmony default export */ var vx_vue_confirmvue_type_script_lang_js_ = (confirmvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/vx-vue/confirm.vue





/* normalize component */

var confirm_component = normalizeComponent(
  vx_vue_confirmvue_type_script_lang_js_,
  confirmvue_type_template_id_217d9a93_render,
  confirmvue_type_template_id_217d9a93_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var vx_vue_confirm = (confirm_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vx-vue/alert.vue?vue&type=template&id=9bfcbb82&
var alertvue_type_template_id_9bfcbb82_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{ref:"container",staticClass:"modal modal-sm",class:{ active: _vm.show }},[_c('div',{staticClass:"modal-overlay"}),_c('div',{staticClass:"modal-container"},[(_vm.title)?_c('div',{staticClass:"modal-header bg-error text-light"},[_c('div',{staticClass:"modal-title h5"},[_vm._v(_vm._s(_vm.title))])]):_vm._e(),_c('div',{staticClass:"modal-body"},[_c('div',{staticClass:"content"},[_vm._v(" "+_vm._s(_vm.message)+" ")])]),_c('div',{staticClass:"modal-footer"},[_c('button',{ref:"button",staticClass:"btn",class:_vm.options.buttonClass,attrs:{"type":"button"},on:{"click":function($event){$event.preventDefault();return _vm.ok($event)}}},[_vm._v(_vm._s(_vm.options.label))])])])])}
var alertvue_type_template_id_9bfcbb82_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/vx-vue/alert.vue?vue&type=template&id=9bfcbb82&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vx-vue/alert.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ var alertvue_type_script_lang_js_ = ({
    name: 'alert',

    props: {
        config: {
            type: Object
        }
    },

    data () { return {
        title: "",
        message: "",
        show: false,
        resolve: null,
        reject: null,
        options: {
            label: "Ok",
            buttonClass: "btn-success"
        }
    }},

    watch: {
        config() {
            this.options = Object.assign({}, this.options, this.config);
        }
    },

    created() {
        this.options = Object.assign({}, this.options, this.config);
    },

    methods: {
        open (title, message, options) {
            this.title = title;
            this.message = message;
            this.show = true;
            this.$nextTick(() => this.$refs.button.focus());
            this.options = Object.assign(this.options, options || {});
            return new Promise((resolve, reject) => {
                this.resolve = resolve;
                this.reject = reject;
            });
        },
        ok () {
            this.show = false;
            this.resolve(true);
        }
    }
});

// CONCATENATED MODULE: ./vue/components/vx-vue/alert.vue?vue&type=script&lang=js&
 /* harmony default export */ var vx_vue_alertvue_type_script_lang_js_ = (alertvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/vx-vue/alert.vue





/* normalize component */

var alert_component = normalizeComponent(
  vx_vue_alertvue_type_script_lang_js_,
  alertvue_type_template_id_9bfcbb82_render,
  alertvue_type_template_id_9bfcbb82_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var vx_vue_alert = (alert_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/forms/file-edit-form.vue?vue&type=template&id=707c41ea&
var file_edit_formvue_type_template_id_707c41ea_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('form',{attrs:{"action":"/","id":"events-edit-form"},on:{"submit":function($event){$event.preventDefault();}}},[_c('div',{staticClass:"columns"},[_c('div',{staticClass:"column"},[(_vm.fileInfo.thumb)?_c('img',{staticClass:"img-responsive",attrs:{"src":_vm.fileInfo.thumb}}):_vm._e()]),_c('div',{staticClass:"column"},[_c('table',{staticClass:"table"},[_c('tr',[_c('td',[_vm._v("Typ")]),_c('td',[_vm._v(_vm._s(_vm.fileInfo.mimetype))])]),(_vm.fileInfo.cache)?_c('tr',[_c('td',[_vm._v("Cache")]),_c('td',[_vm._v(_vm._s(_vm.fileInfo.cache.count)+" Files, "+_vm._s(_vm._f("formatFilesize")(_vm.fileInfo.cache.totalSize,',')))])]):_vm._e(),_c('tr',[_c('td',[_vm._v("Link")]),_c('td',[_c('a',{attrs:{"href":'/' + _vm.fileInfo.path,"target":"_blank"}},[_vm._v(_vm._s(_vm.fileInfo.name))])])])])])]),_c('div',{staticClass:"divider",attrs:{"data-content":"Metadaten der Datei"}}),_c('div',{staticClass:"form-group"},[_c('label',{attrs:{"for":"title_input"}},[_vm._v("Titel")]),_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.title),expression:"form.title"}],staticClass:"form-input",class:{'is-error': _vm.errors.title},attrs:{"id":"title_input","autocomplete":"off"},domProps:{"value":(_vm.form.title)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "title", $event.target.value)}}})]),_c('div',{staticClass:"form-group"},[_c('label',{attrs:{"for":"subtitle_input"}},[_vm._v("Untertitel")]),_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.subtitle),expression:"form.subtitle"}],staticClass:"form-input",class:{'is-error': _vm.errors.subtitle},attrs:{"id":"subtitle_input","autocomplete":"off"},domProps:{"value":(_vm.form.subtitle)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "subtitle", $event.target.value)}}})]),_c('div',{staticClass:"form-group"},[_c('label',{attrs:{"for":"description_input"}},[_vm._v("Beschreibung")]),_c('textarea',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.description),expression:"form.description"}],staticClass:"form-input",class:{'is-error': _vm.errors.description},attrs:{"rows":"2","id":"description_input"},domProps:{"value":(_vm.form.description)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "description", $event.target.value)}}})]),_c('div',{staticClass:"divider",attrs:{"data-content":"Erweiterte Einstellungen"}}),_c('div',{staticClass:"form-group"},[_c('label',{attrs:{"for":"customsort_input"}},[_vm._v("Sortierziffer")]),_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.customsort),expression:"form.customsort"}],staticClass:"form-input col-4",class:{'is-error': _vm.errors.customsort},attrs:{"id":"customsort_input","autocomplete":"off"},domProps:{"value":(_vm.form.customsort)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "customsort", $event.target.value)}}})]),_c('div',{staticClass:"form-group"},[_c('button',{staticClass:"btn btn-success col-12",class:{'loading': _vm.loading},attrs:{"type":"button","disabled":_vm.loading},on:{"click":_vm.submit}},[_vm._v("Änderungen speichern")])])])}
var file_edit_formvue_type_template_id_707c41ea_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/forms/file-edit-form.vue?vue&type=template&id=707c41ea&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/forms/file-edit-form.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//



/* harmony default export */ var file_edit_formvue_type_script_lang_js_ = ({
    props: {
        initialData: { type: Object, default: () => { return {} } },
        fileInfo: { type: Object, default: () => { return {} } },
        url: { type: String, default: "" }
    },
    data() {
        return {
            form: {},
            response: {},
            loading: false
        }
    },
    computed: {
        errors () {
            return this.response ? (this.response.errors || {}) : {};
        },
        message () {
            return this.response ? this.response.message : "";
        }
    },
    watch: {
        initialData (newValue) {
            this.form = Object.assign({}, this.form, newValue);
        }
    },
    methods: {
        async submit() {
            this.loading = true;

            /* avoid strings "null" with null values */

            let formData = {};

            Object.keys(this.form).forEach(key => { if(this.form[key] !== null) { formData[key] = this.form[key]; }});

            this.response = await SimpleFetch(this.url, 'POST', {}, JSON.stringify(formData));
            this.$emit('response-received', this.response);
            this.loading = false;
        }
    },
    filters: {
        formatFilesize(size, sep) {
            let suffixes = ['B', 'kB', 'MB', 'GB'], ndx = Math.floor(Math.floor(Math.log(size) / Math.log(1000)));
            return (size / Math.pow(1000, ndx)).toFixed(ndx ? 2: 0).toString().replace('.', sep || '.') + suffixes[ndx];
        }
    }
});

// CONCATENATED MODULE: ./vue/components/forms/file-edit-form.vue?vue&type=script&lang=js&
 /* harmony default export */ var forms_file_edit_formvue_type_script_lang_js_ = (file_edit_formvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/forms/file-edit-form.vue





/* normalize component */

var file_edit_form_component = normalizeComponent(
  forms_file_edit_formvue_type_script_lang_js_,
  file_edit_formvue_type_template_id_707c41ea_render,
  file_edit_formvue_type_template_id_707c41ea_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var file_edit_form = (file_edit_form_component.exports);
// CONCATENATED MODULE: ./vue/util/promised-xhr.js
function PromisedXhr(url, method = 'GET', headers = {}, payload = null, timeout = null, progressCallback = null, cancelToken = null) {

    if(!headers['X-CSRF-Token'] && document.querySelector('meta[name="csrf-token"]')) {
        headers['X-CSRF-Token'] = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
    }
    headers['Content-type'] = headers['Content-type'] || 'application/x-www-form-urlencoded';
    headers['X-Requested-With'] = 'XMLHttpRequest';

    let xhr = new XMLHttpRequest();

    let xhrPromise = new Promise(function(resolve, reject) {
        xhr.onreadystatechange = () => {
            if (xhr.readyState === 4) {
                if (xhr.status >= 200 && xhr.status < 300) {
                    resolve(JSON.parse(xhr.responseText));
                } else {
                    reject({
                        status: xhr.status,
                        statusText: xhr.statusText,
                        responseText: xhr.responseText
                    });
                }
            }
        };

        xhr.upload.onprogress = progressCallback || null;

        if(cancelToken) {
            cancelToken.cancel = () =>  { xhr.abort(); reject({ status: 499, statusText: 'Request cancelled.' }); };
        }

        xhr.open(method, url, true);
        xhr.responseType = 'text'; // currently no JSON support in IE/Edge
        Object.keys(headers).forEach(key => xhr.setRequestHeader(key, headers[key]));

        if (timeout) {
            xhr.timeout = timeout;
            xhr.ontimeout = (e) => {reject({ status: 408, statusText: 'Request timeout.' });};
        }

        xhr.send(payload);
    });

    xhrPromise.cancel = () => xhr.abort();

    return xhrPromise;
}

// CONCATENATED MODULE: ./vue/filters.js

function formatFilesize (size, sep) {
    let i = Math.floor(Math.floor(Math.log(size) / Math.log(1000)));
    return (size / Math.pow(1000, i)).toFixed(i ? 2 : 0).toString().replace('.', sep || '.') + ['B', 'kB', 'MB', 'GB'][i];
}


// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/filemanager/filemanager.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//



















/* harmony default export */ var filemanagervue_type_script_lang_js_ = ({
  components: {
    FolderTree: filemanager_folder_tree,
    'sortable': sortable,
    'simple-tree': simple_tree,
    'circular-progress': circular_progress,
    'confirm': vx_vue_confirm,
    'alert': vx_vue_alert,
    'file-edit-form': file_edit_form,
    'filemanager-add': filemanager_add,
    'filemanager-search': filemanager_search,
    'filemanager-actions': filemanager_actions,
    'filemanager-breadcrumbs': filemanager_breadcrumbs,
    'filemanager-folder-tree': filemanager_folder_tree
  },

  data () {
    return {
      root: {},
      currentFolder: null,
      files: [],
      folders: [],
      breadcrumbs: [],
      toRename: null,
      showEditForm: false,
      showAddActivities: false,
      indicateDrag: false,
      upload: {
        files: [],
        progressing: false,
        cancelToken: {}
      },
      cancelUploadToken: {},
      progress: { total: null, loaded: null, file: null },
      editFormData: {},
      editFileInfo: {}
    }
  },

  computed: {
    directoryEntries () {
      let folders = this.folders;
      let files = this.files;
      folders.forEach(item => {
        item.isFolder = true;
        item.key = 'd' + item.id
      });
      files.forEach(item => item.key = item.id);
      return [...folders, ...files];
    },
    checkedFiles () {
      return this.files.filter(item => item.checked);
    },
    checkedFolders () {
      return this.folders.filter(item => item.checked);
    }
  },

  props: {
    routes: { type: Object, required: true },
    limits: { type: Object, default: () => {} },
    columns: { type: Array, required: true },
    folder: { type: String, default: '' },
    initSort: { type: Object }
  },

  watch: {
    folder (newValue) {
      this.currentFolder = newValue;
    }
  },

  async created () {
    let response = await SimpleFetch(url_query.create(this.routes.init, { folder: this.folder }));

    this.breadcrumbs = response.breadcrumbs || [];
    this.files = response.files || [];
    this.folders = response.folders || [];
    this.currentFolder = response.currentFolder || null;
  },
  mounted () {
    document.body.addEventListener('click', this.handleBodyClick);
  },
  beforeDestroy () {
    document.body.removeEventListener('click', this.handleBodyClick);
  },

  methods: {
    handleBodyClick () {
      this.showAddActivities = false;
    },
    toggleAll (event) {
      [...this.folders, ...this.files].forEach(item => this.$set(item, 'checked', event.target.checked));
    },
    async readFolder (id) {
      let response = await SimpleFetch(url_query.create(this.routes.readFolder, { folder: id }));

      if(response.success) {
        this.files = response.files || [];
        this.folders = response.folders || [];
        this.currentFolder = id;
        if(response.breadcrumbs) {
          this.breadcrumbs = response.breadcrumbs;
        }
      }
    },
    async delSelection () {
      let response = await SimpleFetch(url_query.create(this.routes.delSelection, { files: this.checkedFiles.map(item => item.id).join(","), folders: this.checkedFolders.map(item => item.id).join(",") }), 'DELETE');
      if(response.success) {
        this.files = response.files || [];
        this.folders = response.folders || [];
      }
      else if(response.error) {
        this.$emit('response-received', response);
        this.files = response.files || this.files;
        this.folders = response.folders || this.folders;
      }
    },
    async moveSelection () {
      let folder = await this.$refs['folder-tree'].open(this.routes.getFoldersTree, this.currentFolder);

      if (folder !== false) {
        let response = await SimpleFetch(url_query.create(this.routes.moveSelection, { destination: folder.id }), 'POST', {}, JSON.stringify({
          files: this.checkedFiles.map(item => item.id),
          folders: this.checkedFolders.map(item => item.id)
        }));

        if(response.success) {
          this.files = response.files || [];
          this.folders = response.folders || [];
        }
        else if(response.error) {
          this.$emit('response-received', response);
          this.files = response.files || this.files;
          this.folders = response.folders || this.folders;
        }
      }
    },

    async editFile (row) {
      this.showEditForm = true;
      let response = await SimpleFetch(url_query.create(this.routes.getFile, { id: row.id }));
      this.editFormData = response.form || {};
      this.editFileInfo = response.fileInfo || {};
      this.editFormData.id = row.id;
    },
    async delFile (row) {
      if(await this.$refs.confirm.open('Datei löschen', "'" + row.name + "' wirklich löschen?")) {
        let response = await SimpleFetch(url_query.create(this.routes.delFile, { id: row.id }), 'DELETE');
        if(response.success) {
          this.files.splice(this.files.findIndex(item => row === item), 1);
        }
      }
    },
    async renameFile (event) {
      let name = event.target.value.trim();
      if(name && this.toRename) {
        let response = await SimpleFetch(this.routes.renameFile, 'POST', {}, JSON.stringify({name: name, id: this.toRename.id }));
        if(response.success) {
          this.toRename.name = response.name || name;
          this.toRename = null;
        }
      }
    },
    async renameFolder (event) {
      let name = event.target.value.trim();
      if(name && this.toRename) {
        let response = await SimpleFetch(this.routes.renameFolder, 'POST', {}, JSON.stringify({name: name, folder: this.toRename.id }));
        if(response.success) {
          this.toRename.name = response.name || name;
          this.toRename = null;
        }
      }
    },
    async delFolder (row) {
      if(await this.$refs.confirm.open('Verzeichnis löschen', "'" + row.name + "' und enthaltene Dateien wirklich löschen?", { cancelLabel: "Abbrechen" })) {
        let response = await SimpleFetch(url_query.create(this.routes.delFolder, { folder: row.id }), 'DELETE');
        if(response.success) {
          this.folders.splice(this.folders.findIndex(item => row === item), 1);
        }
      }
    },
    async createFolder (name) {
      this.showAddActivities = false;

      let response = await SimpleFetch(this.routes.addFolder, 'POST', {}, JSON.stringify({ name: name, parent: this.currentFolder }));
      if(response.folder) {
        this.folders.push(response.folder);
      }
    },
    async moveFile (row) {
      let folder = await this.$refs['folder-tree'].open(this.routes.getFoldersTree, this.currentFolder);

      if (folder !== false) {
        let response = await SimpleFetch(this.routes.moveFile, 'POST', {}, JSON.stringify({
          id: row.id,
          folderId: folder.id
        }));
        if (response.success) {
          this.files.splice(this.files.findIndex(item => row === item), 1);
        }
        else {
          this.$emit('response-received', response);
        }
      }
    },
    uploadDraggedFiles (event) {
      this.indicateDrag = false;
      let files = event.dataTransfer.files;

      if (!files) {
        return;
      }

      this.uploadInputFiles(files);
    },
    uploadInputFiles (files) {
      this.showAddActivities = false;

      [...files].forEach(f => this.upload.files.push(f));
      if(!this.upload.progressing) {
        this.upload.progressing = true;
        this.progress.loaded = 0;
        this.handleUploads();
      }
    },
    async handleUploads () {
      let file = null, response = null;
      while((file = this.upload.files.shift()) !== undefined) {

        if(this.limits.maxUploadFilesize && this.limits.maxUploadFilesize < file.size) {
          await this.$refs.alert.open('Datei zu groß', "'" + file.name + "' übersteigt die maximale Uploadgröße.");
          continue;
        }
        this.progress.file = file.name;
        try {
          response = await PromisedXhr(
              url_query.create(this.routes.uploadFile, { folder: this.currentFolder }),
              'POST',
              {
                'Content-type': file.type || 'application/octet-stream',
                'X-File-Name': file.name.replace(/[^\x00-\x7F]/g, c => encodeURIComponent(c)),
                'X-File-Size': file.size,
                'X-File-Type': file.type
              },
              file,
              null,
              e => {
                this.progress.total = e.total;
                this.progress.loaded = e.loaded;
              },
              this.upload.cancelToken
          );
          this.files = response.files || this.files;
        } catch(err) {
          this.upload.files = [];
          this.upload.progressing = false;
          return;
        }

        if(!response.success) {
          this.$emit('response-received', response);
          this.upload.files = [];
          this.upload.progressing = false;
          return;
        }
      }
      this.upload.progressing = false;
      if(response) {
        this.$emit('response-received', { success: true, message: response.message || 'File upload successful' });
      }
    },
    cancelUpload () {
      if(this.upload.cancelToken.cancel) {
        this.upload.cancelToken.cancel();
        this.upload.cancelToken = {};
      }
    },
    doSearch (term) {
      if(term.trim().length > 2) {
        return SimpleFetch(url_query.create(this.routes.search, { search: term }));
      }
      return { files: [], folders: [] };
    }
  },

  directives: {
    focus: Focus,
    checkIndeterminate: {
      update (el, binding, vnode) {
        let filteredLength = vnode.context.checkedFolders.length + vnode.context.checkedFiles.length;
        if (!filteredLength) {
          el.checked = false;
        }
        el.indeterminate = filteredLength && filteredLength !== vnode.context.folders.length + vnode.context.files.length;
      }
    }
  },

  filters: {
    formatFilesize: formatFilesize
  }
});

// CONCATENATED MODULE: ./vue/components/filemanager/filemanager.vue?vue&type=script&lang=js&
 /* harmony default export */ var filemanager_filemanagervue_type_script_lang_js_ = (filemanagervue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/filemanager/filemanager.vue





/* normalize component */

var filemanager_component = normalizeComponent(
  filemanager_filemanagervue_type_script_lang_js_,
  filemanagervue_type_template_id_629145da_render,
  filemanagervue_type_template_id_629145da_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var filemanager = (filemanager_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/slicksort/slicksort-list.vue?vue&type=template&id=8c5e72ba&
var slicksort_listvue_type_template_id_8c5e72ba_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{attrs:{"use-drag-handle":true}},_vm._l((_vm.value),function(item,ndx){return _c('slicksort-item',{key:ndx,attrs:{"index":ndx,"item":item},scopedSlots:_vm._u([_vm._l((_vm.$scopedSlots),function(_,name){return {key:name,fn:function(slotData){return [_vm._t(name,null,null,slotData)]}}})],null,true)})}),1)}
var slicksort_listvue_type_template_id_8c5e72ba_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/slicksort/slicksort-list.vue?vue&type=template&id=8c5e72ba&

// EXTERNAL MODULE: ./node_modules/vue-slicksort/dist/vue-slicksort.umd.js
var vue_slicksort_umd = __webpack_require__("11b0");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/slicksort/slicksort-item.vue?vue&type=template&id=bc0038d2&
var slicksort_itemvue_type_template_id_bc0038d2_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"slick-sort-item"},[_c('div',{directives:[{name:"handle",rawName:"v-handle"}],staticClass:"handle"}),_vm._t("row",[_vm._v(_vm._s(_vm.item))],{"item":_vm.item})],2)}
var slicksort_itemvue_type_template_id_bc0038d2_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/slicksort/slicksort-item.vue?vue&type=template&id=bc0038d2&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/slicksort/slicksort-item.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//



/* harmony default export */ var slicksort_itemvue_type_script_lang_js_ = ({
    mixins: [vue_slicksort_umd["ElementMixin"]],
    props: ['item']
});

// CONCATENATED MODULE: ./vue/components/slicksort/slicksort-item.vue?vue&type=script&lang=js&
 /* harmony default export */ var slicksort_slicksort_itemvue_type_script_lang_js_ = (slicksort_itemvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/slicksort/slicksort-item.vue





/* normalize component */

var slicksort_item_component = normalizeComponent(
  slicksort_slicksort_itemvue_type_script_lang_js_,
  slicksort_itemvue_type_template_id_bc0038d2_render,
  slicksort_itemvue_type_template_id_bc0038d2_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var slicksort_item = (slicksort_item_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/slicksort/slicksort-list.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//




/* harmony default export */ var slicksort_listvue_type_script_lang_js_ = ({
    mixins: [vue_slicksort_umd["ContainerMixin"]],
    components: { SlicksortItem: slicksort_item },
    props: {
        value: {
            type: Array,
            default: []
        }
    }
});

// CONCATENATED MODULE: ./vue/components/slicksort/slicksort-list.vue?vue&type=script&lang=js&
 /* harmony default export */ var slicksort_slicksort_listvue_type_script_lang_js_ = (slicksort_listvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/slicksort/slicksort-list.vue





/* normalize component */

var slicksort_list_component = normalizeComponent(
  slicksort_slicksort_listvue_type_script_lang_js_,
  slicksort_listvue_type_template_id_8c5e72ba_render,
  slicksort_listvue_type_template_id_8c5e72ba_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var slicksort_list = (slicksort_list_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vx-vue/tab.vue?vue&type=template&id=18edb786&
var tabvue_type_template_id_18edb786_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[(_vm.items.length)?_c('ul',{staticClass:"tab",class:{ 'tab-block': _vm.block }},[_vm._l((_vm.items),function(item,ndx){return _c('li',{key:ndx,staticClass:"tab-item",class:{ active: _vm.activeIndex === ndx }},[_c('a',{class:{ disabled: item.disabled },attrs:{"data-badge":item.badge},on:{"click":function($event){return _vm.itemOnClick(item)}}},[_vm._v(_vm._s(item.name))])])}),(_vm.hasActionSlot)?_c('li',{staticClass:"tab-item tab-action"},[_vm._t("action")],2):_vm._e()],2):_vm._e()])}
var tabvue_type_template_id_18edb786_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/vx-vue/tab.vue?vue&type=template&id=18edb786&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vx-vue/tab.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ var tabvue_type_script_lang_js_ = ({
  name: 'Tab',

  props: {
    items: {
      type: Array,
      default: []
    },
    activeIndex: {
      type: Number,
      default: 0
    },
    block: {
      type: Boolean,
      default: false
    }
  },

  data() {
    return {
      activeTab: {}
    };
  },

  computed: {
    hasActionSlot() {
      return !!this.$slots.action;
    }
  },

  created() {
    this.activeTab = this.items[this.activeIndex] || {};
  },
  watch: {
    activeIndex(newVal) {
      this.activeTab = this.items[newVal] || {};
    },
  },
  methods: {
    itemOnClick(item) {
      if(!item.disabled) {
        this.activeTab = item;
        this.$emit('update:active-index', this.items.indexOf(item));
      }
    }
  }
});

// CONCATENATED MODULE: ./vue/components/vx-vue/tab.vue?vue&type=script&lang=js&
 /* harmony default export */ var vx_vue_tabvue_type_script_lang_js_ = (tabvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/vx-vue/tab.vue





/* normalize component */

var tab_component = normalizeComponent(
  vx_vue_tabvue_type_script_lang_js_,
  tabvue_type_template_id_18edb786_render,
  tabvue_type_template_id_18edb786_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var tab = (tab_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vx-vue/formelements/password-input.vue?vue&type=template&id=d96d5314&scoped=true&
var password_inputvue_type_template_id_d96d5314_scoped_true_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"form-group is-password"},[_c('input',_vm._g(_vm._b({staticClass:"form-input",attrs:{"type":_vm.show ? 'text': 'password'}},'input',_vm.$attrs,false),Object.assign({}, _vm.$listeners,
      {input: function (event) { return _vm.$emit('input', event.target.value); }}))),_c('a',{class:{ 'show': _vm.show },attrs:{"href":"#"},on:{"click":function($event){$event.preventDefault();_vm.show = !_vm.show}}})])}
var password_inputvue_type_template_id_d96d5314_scoped_true_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/password-input.vue?vue&type=template&id=d96d5314&scoped=true&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vx-vue/formelements/password-input.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ var password_inputvue_type_script_lang_js_ = ({
    inheritAttrs: false,
    data () { return {
        show: false
    }}
});

// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/password-input.vue?vue&type=script&lang=js&
 /* harmony default export */ var formelements_password_inputvue_type_script_lang_js_ = (password_inputvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./vue/components/vx-vue/formelements/password-input.vue?vue&type=style&index=0&id=d96d5314&scoped=true&lang=scss&
var password_inputvue_type_style_index_0_id_d96d5314_scoped_true_lang_scss_ = __webpack_require__("388b");

// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/password-input.vue






/* normalize component */

var password_input_component = normalizeComponent(
  formelements_password_inputvue_type_script_lang_js_,
  password_inputvue_type_template_id_d96d5314_scoped_true_render,
  password_inputvue_type_template_id_d96d5314_scoped_true_staticRenderFns,
  false,
  null,
  "d96d5314",
  null
  
)

/* harmony default export */ var password_input = (password_input_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/cookie-consent.vue?vue&type=template&id=7b9bf9ae&
var cookie_consentvue_type_template_id_7b9bf9ae_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('transition',{attrs:{"appear":"","name":_vm.transitionName}},[(_vm.isOpen)?_c('div',{staticClass:"cookie-consent",class:_vm.containerPosition},[_vm._t("default",[_c('div',{staticClass:"content"},[_vm._t("message",[_vm._v(_vm._s(_vm.message))])],2),_c('div',{staticClass:"buttons"},[(_vm.buttonLink)?_c('a',{staticClass:"btn-link",attrs:{"target":_vm.target,"href":_vm.buttonLink}},[_vm._v(_vm._s(_vm.buttonLinkText))]):_vm._e(),_c('button',{staticClass:"btn-accept",on:{"click":_vm.accept}},[_vm._v(_vm._s(_vm.buttonText))]),(_vm.buttonDecline)?_c('button',{staticClass:"btn-decline",on:{"click":_vm.decline}},[_vm._v(_vm._s(_vm.buttonDeclineText))]):_vm._e()])],{"accept":_vm.accept,"close":_vm.close,"decline":_vm.decline,"open":_vm.open})],2):_vm._e()])}
var cookie_consentvue_type_template_id_7b9bf9ae_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/cookie-consent.vue?vue&type=template&id=7b9bf9ae&

// CONCATENATED MODULE: ./vue/util/cookie.js
function _computeExpires(str) {
    const value = parseInt(str, 10);
    let expires = new Date();

    switch (str.charAt(str.length - 1)) {
        case 'Y': expires.setFullYear(expires.getFullYear() + value); break;
        case 'M': expires.setMonth(expires.getMonth() + value); break;
        case 'D': expires.setDate(expires.getDate() + value); break;
        case 'h': expires.setHours(expires.getHours() + value); break;
        case 'm': expires.setMinutes(expires.getMinutes() + value); break;
        case 's': expires.setSeconds(expires.getSeconds() + value); break;
        default: expires = new Date(str);
    }

    return expires;
}

function _conv (opts) {
    let res = '';

    Object.getOwnPropertyNames(opts).forEach(
        key => {
            if (/^expires$/i.test(key)) {
                let expires = opts[key];

                if (typeof expires !== 'object') {
                    expires += typeof expires === 'number' ? 'D' : '';
                    expires = _computeExpires(expires);
                }
                res += ';' + key + '=' + expires.toUTCString();
            } else if (/^secure$/.test(key)) {
                if (opts[key]) {
                    res += ';' + key;
                }
            } else {
                res += ';' + key + '=' + opts[key];
            }
        }
    );
    if(!opts.hasOwnProperty('path')) {
        res += ';path=/';
    }
    return res;
}

function enabled () {
    const key = '__vxweb-key__', val = 1, regex = new RegExp('(?:^|; )' + key + '=' + val + '(?:;|$)');
    document.cookie = key + '=' + val + ';path=/';
    if(regex.test(document.cookie)) {
        remove(key);
        return true;
    }
    return false;
}

function get (key) {
    const raw = getRaw(key);
    return  raw ? decodeURIComponent(raw) : null;
}

function set (key, val, options = {}) {
    document.cookie = key + '=' + encodeURIComponent(val) + _conv(options);
}

function getAll () {
    const reKey = /(?:^|; )([^=]+?)(?:=([^;]*))?(?:;|$)/g, cookies = {};
    let match;

    while ((match = reKey.exec(document.cookie))) {
        reKey.lastIndex = (match.index + match.length) - 1;
        cookies[match[1]] = decodeURIComponent(match[2]);
    }

    return cookies;
}

function remove (key, options = {}) {
    return set (key, 'x',{ ...options, ...{ expires: -1 }});
}

function setRaw (key, val, options) {
    document.cookie = key + '=' + val + _conv(options);
}

function getRaw (key) {
    if (!key || typeof key !== 'string') {
        return null;
    }
    const escKey = key.replace(/[.*+?^$|[\](){}\\-]/g, '\\$&');
    const match = (new RegExp('(?:^|; )' + escKey + '(?:=([^;]*))?(?:;|$)').exec(document.cookie));
    if(match === null) {
        return null;
    }
    return match[1];
}


// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/cookie-consent.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//



/* harmony default export */ var cookie_consentvue_type_script_lang_js_ = ({
    name: 'cookie-consent',
    props: {
        buttonText: {
            type: String,
            default: 'Ok'
        },
        buttonDecline: {
            type: Boolean,
            default: false
        },
        buttonDeclineText: {
            type: String,
            default: 'Decline'
        },
        buttonLink: {
            type: String,
            required: false
        },
        buttonLinkText: {
            type: String,
            default: 'More info'
        },
        buttonLinkNewTab: {
            type: Boolean,
            default: true
        },
        message: {
            type: String,
            default: 'This website uses cookies to ensure you get the best experience on our website.'
        },
        position: {
            type: String,
            default: 'bottom'
        },
        /**
         * options are: slideFromBottom, slideFromTop, fade
         */
        transitionName: {
            type: String,
            default: 'fade'
        },
        storageName: {
            type: String,
            default: 'cookie:accepted'
        },
        cookieOptions: {
            type: Object,
            default: () => {},
            required: false
        }
    },

    data () {
        return {
            isOpen: false
        }
    },

    computed: {
        containerPosition () {
            return this.position;
        },
        target () {
            return this.buttonLinkNewTab ? '_blank' : '_self';
        }
    },

    created () {
        if (!this.getVisited()) {
            this.isOpen = true;
        }
    },
    mounted () {
        if (this.isAccepted()) {
            this.$emit('accept');
        }
    },
    methods: {
        setVisited () {
            set(this.storageName, true, { ...this.cookieOptions, expires: '1Y' })
        },
        setAccepted () {
            set(this.storageName, true, { ...this.cookieOptions, expires: '1Y' })
        },
        setDeclined () {
            set(this.storageName, false, { ...this.cookieOptions, expires: '1Y' })
        },
        getVisited () {
            let visited = false;
            visited = get(this.storageName);
            if (typeof visited === 'string') {
                visited = JSON.parse(visited);
            }
            return !(visited === null || visited === undefined);
        },
        isAccepted () {
            let accepted = false;
            accepted = get(this.storageName);
            if (typeof accepted === 'string') {
                accepted = JSON.parse(accepted);
            }
            return accepted;
        },
        accept () {
            this.setVisited();
            this.setAccepted();
            this.isOpen = false;
            this.$emit('accept');
        },
        close () {
            this.isOpen = false;
            this.$emit('close');
        },
        decline () {
            this.setVisited();
            this.setDeclined();
            this.isOpen = false;
            this.$emit('decline');
        },
        revoke () {
            remove(this.storageName);
            this.isOpen = true;
            this.$emit('revoke');
        },
        open () {
            if (!this.getVisited()) {
                this.isOpen = true;
            }
        }
    }
});

// CONCATENATED MODULE: ./vue/components/cookie-consent.vue?vue&type=script&lang=js&
 /* harmony default export */ var components_cookie_consentvue_type_script_lang_js_ = (cookie_consentvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/cookie-consent.vue





/* normalize component */

var cookie_consent_component = normalizeComponent(
  components_cookie_consentvue_type_script_lang_js_,
  cookie_consentvue_type_template_id_7b9bf9ae_render,
  cookie_consentvue_type_template_id_7b9bf9ae_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var cookie_consent = (cookie_consent_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vx-vue/pagination.vue?vue&type=template&id=e8c7c19c&
var paginationvue_type_template_id_e8c7c19c_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('ul',{staticClass:"pagination"},[(_vm.showNavButtons)?_c('li',{staticClass:"page-item",class:{ disabled: _vm.currentPage <= 1 }},[_c('a',{staticClass:"menu-item",attrs:{"tabindex":"-1"},on:{"click":_vm.prevPage}},[_vm._v(_vm._s(_vm.prevText))])]):_vm._e(),_vm._l((_vm.pagesToShow),function(page,idx){return _c('li',{key:idx,staticClass:"page-item",class:{active: _vm.currentPage === page}},[(page !== 'dots')?_c('a',{on:{"click":function($event){return _vm.pageClick(page)}}},[_vm._v(_vm._s(page))]):_c('span',[_vm._v("…")])])}),(_vm.showNavButtons)?_c('li',{staticClass:"page-item",class:{ disabled: _vm.currentPage >= _vm.maxPage }},[_c('a',{attrs:{"tabindex":"-1"},on:{"click":_vm.nextPage}},[_vm._v(_vm._s(_vm.nextText))])]):_vm._e()],2)])}
var paginationvue_type_template_id_e8c7c19c_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/vx-vue/pagination.vue?vue&type=template&id=e8c7c19c&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vx-vue/pagination.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


/* harmony default export */ var paginationvue_type_script_lang_js_ = ({
  name: 'Pagination',
  props: {
    items: Array,
    total: { type: Number, default: 1 },
    page: { type: Number, default: 1 },
    perPage: { type: Number, default: 20 },
    showNavButtons: { type: Boolean, default: true },
    prevText: { type: String, default: 'Previous' },
    nextText: { type: String, default: 'Next' },
    showAllPages: { type: Boolean, default: false },
    onPageChange: Function
  },
  data () {
    return {
      currentPage: 1,
      maxPage: 0,
      showPerPage: 20,
      dataItems: undefined,
    };
  },
  created () {
    this.currentPage = this.page;
    this.totalResults = this.total;
    this.showPerPage = this.perPage;

    if (typeof this.items !== 'undefined') {
      this.dataItems = this.items;
      this.totalResults = this.items.length;
    }

    this.countMaxPage();

    if (typeof this.onPageChange === 'function') {
      this.onPageChange.apply(null, [this.currentPage, this.dataResults, this.maxPage]);
    }
  },
  methods: {
    pageClick(page) {
      this.currentPage = page;
      this.$emit('update:page', page);

      if (typeof this.onPageChange === 'function') {
        this.onPageChange.apply(null, [page, this.dataResults, this.maxPage]);
      }
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.pageClick(this.currentPage - 1);
      }
    },
    nextPage() {
      if (this.currentPage < this.maxPage) {
        this.pageClick(this.currentPage + 1);
      }
    },
    countMaxPage() {
      this.maxPage = Math.ceil(this.totalResults / this.showPerPage);
    }
  },
  watch: {
    page(val) {
      if (val < 1) {
        val = 1;
      } else if (val > this.maxPage) {
        val = this.maxPage;
      }
      
      this.currentPage = val;

      if (typeof this.onPageChange === 'function') {
        this.onPageChange.apply(null, [this.currentPage, this.dataResults, this.maxPage]);
      }
    },
    total(val) {
      if (val < 0) {
        val = 0;
      }

      this.totalResults = val;

      this.countMaxPage();
      // if total number of items has changed, go to page 1
      this.pageClick(1);
    },
    perPage(val) {
      if (val < 1) {
        val = 20;
      }
      this.showPerPage = val;
      
      this.countMaxPage();
      this.pageClick(1);
    },
    items(val) {
      this.dataItems = val;

      this.totalResults = this.dataItems.length;
      this.$emit('update:total', this.totalResults);

      this.countMaxPage();
      this.pageClick(1);
    }
  },
  computed: {
    dataResults() {
      if (typeof this.dataItems !== 'undefined' && this.dataItems.length > 0) {
        let start = (this.currentPage - 1) * this.showPerPage;
        let end = this.currentPage * this.showPerPage;

        return this.dataItems.slice(start, end);
      }

      return null;
    },
    pagesToShow() {
      let pages = [1];

      if (this.showAllPages === true || this.maxPage <= 7) {
        let i = 2;
        for (; i <= this.maxPage; ++i) {
          pages.push(i);
        }
        return pages;
      }

      if (this.currentPage >= this.maxPage) {
        pages.push('dots');
        pages.push(this.currentPage - 2);
        pages.push(this.currentPage - 1);
      } else if (this.currentPage - 1 && this.currentPage - 1 > 1) {
        if (this.currentPage - 1 > 2) {
          pages.push('dots');
        }

        pages.push(this.currentPage - 1);
      }

      if (this.currentPage > 1) {
        pages.push(this.currentPage);
      }

      if (this.currentPage + 1 < this.maxPage) {
        pages.push(this.currentPage + 1);

        if (this.currentPage <= 1) {
          pages.push(this.currentPage + 2);
        }

        if (this.currentPage + 2 < this.maxPage) {
          pages.push('dots');
        }
      }

      if (this.currentPage < this.maxPage) {
        pages.push(this.maxPage);
      } 

      return pages;
    }
  }
});

// CONCATENATED MODULE: ./vue/components/vx-vue/pagination.vue?vue&type=script&lang=js&
 /* harmony default export */ var vx_vue_paginationvue_type_script_lang_js_ = (paginationvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/vx-vue/pagination.vue





/* normalize component */

var pagination_component = normalizeComponent(
  vx_vue_paginationvue_type_script_lang_js_,
  paginationvue_type_template_id_e8c7c19c_render,
  paginationvue_type_template_id_e8c7c19c_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var pagination = (pagination_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vue-ckeditor.vue?vue&type=template&id=0fb3331a&
var vue_ckeditorvue_type_template_id_0fb3331a_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"ckeditor"},[_c('textarea',{attrs:{"name":_vm.name,"id":_vm.id,"types":_vm.types,"config":_vm.config,"disabled":_vm.readOnlyMode},domProps:{"value":_vm.value}})])}
var vue_ckeditorvue_type_template_id_0fb3331a_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/vue-ckeditor.vue?vue&type=template&id=0fb3331a&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vue-ckeditor.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

let inc = new Date().getTime();

/* harmony default export */ var vue_ckeditorvue_type_script_lang_js_ = ({
  name: 'VueCkeditor',
  props: {
    name: { type: String, default: `editor-${++inc}` },
    value: String,
    id: { type: String, default:`editor-${inc}` },
    types: { type: String, default: `classic` },
    config: { type: Object, default: {} },
    instanceReadyCallback: Function,
    readOnlyMode: { type: Boolean, default: false }
  },
  data() {
    return {
      instanceValue: ''
    };
  },
  computed: {
    instance() {
      return CKEDITOR.instances[this.id];
    }
  },
  watch: {
    value(val) {
      try {
        if (this.instance) {
          this.update(val);
        }
      } catch (e) {}
    },
    readOnlyMode(val) {
      this.instance.setReadOnly(val);
    }
  },
  mounted() {
    if (typeof CKEDITOR === 'undefined') {
      console.log('CKEDITOR is missing (http://ckeditor.com/)');
    } else {
      if (this.types === 'inline') {
        CKEDITOR.inline(this.id, this.config);
      } else {
        CKEDITOR.replace(this.id, this.config);
      }

      this.instance.setData(this.value);

      this.instance.on('instanceReady', () => {
        this.instance.setData(this.value);
      });

      // Ckeditor change event
      this.instance.on('change', this.onChange);

      // Ckeditor mode html or source
      this.instance.on('mode', this.onMode);

      // Ckeditor blur event
      this.instance.on('blur', evt => {
        this.$emit('blur', evt);
      });

      // Ckeditor focus event
      this.instance.on('focus', evt => {
        this.$emit('focus', evt);
      });

      // Ckeditor contentDom event
      this.instance.on('contentDom', evt => {
        this.$emit('contentDom', evt);
      });

      // Ckeditor dialog definition event
      CKEDITOR.on('dialogDefinition', evt => {
        this.$emit('dialogDefinition', evt);
      });

      // Ckeditor file upload request event
      this.instance.on('fileUploadRequest', evt => {
        this.$emit('fileUploadRequest', evt);
      });

      // Ckditor file upload response event
      this.instance.on('fileUploadResponse', evt => {
        setTimeout(() => {
          this.onChange();
        }, 0);
        this.$emit('fileUploadResponse', evt);
      });

      // Listen for instanceReady event
      if (typeof this.instanceReadyCallback !== 'undefined') {
        this.instance.on('instanceReady', this.instanceReadyCallback);
      }

      // Registering the beforeDestroyed hook right after creating the instance
      this.$once('hook:beforeDestroy', () => {
        this.destroy();
      });
    }
  },
  methods: {
    update(val) {
      if (this.instanceValue !== val) {
        this.instance.setData(val, { internal: false });
        this.instanceValue = val;
      }
    },
    destroy() {
      try {
        let editor = window['CKEDITOR'];
        if (editor.instances && editor.instances[this.id]) {
          editor.instances[this.id].destroy();
        }
      } catch (e) {}
    },
    onMode() {
      if (this.instance.mode === 'source') {
        let editable = this.instance.editable();
        editable.attachListener(editable, 'input', () => {
          this.onChange();
        });
      }
    },
    onChange() {
      let html = this.instance.getData();
      if (html !== this.value) {
        this.$emit('input', html);
        this.instanceValue = html;
      }
    }
  }
});

// CONCATENATED MODULE: ./vue/components/vue-ckeditor.vue?vue&type=script&lang=js&
 /* harmony default export */ var components_vue_ckeditorvue_type_script_lang_js_ = (vue_ckeditorvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/vue-ckeditor.vue





/* normalize component */

var vue_ckeditor_component = normalizeComponent(
  components_vue_ckeditorvue_type_script_lang_js_,
  vue_ckeditorvue_type_template_id_0fb3331a_render,
  vue_ckeditorvue_type_template_id_0fb3331a_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var vue_ckeditor = (vue_ckeditor_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/forms/profile-form.vue?vue&type=template&id=4fa519d5&
var profile_formvue_type_template_id_4fa519d5_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('form',{staticClass:"form-horizontal",attrs:{"action":"/"},on:{"submit":function($event){$event.preventDefault();}}},[_c('div',{staticClass:"form-sect"},[_c('div',{staticClass:"form-sect"},_vm._l((_vm.elements),function(element){return _c('div',{staticClass:"form-group"},[_c('label',{staticClass:"form-label col-3",class:{ required: element.required, 'text-error': _vm.errors[element.model] },attrs:{"for":element.model + '_' + element.type}},[_vm._v(_vm._s(element.label))]),_c('div',{staticClass:"col-9"},[_c(element.type || 'form-input',{tag:"component",attrs:{"id":element.model + '_' + element.type,"name":element.model},model:{value:(_vm.form[element.model]),callback:function ($$v) {_vm.$set(_vm.form, element.model, $$v)},expression:"form[element.model]"}}),(_vm.errors[element.model])?_c('p',{staticClass:"form-input-hint vx-error-box error"},[_vm._v(_vm._s(_vm.errors[element.model]))]):_vm._e()],1)])}),0)]),(_vm.notifications.length)?[_c('div',{staticClass:"divider text-center",attrs:{"data-content":"Benachrichtigungen"}}),_c('div',{staticClass:"form-sect off-3"},_vm._l((_vm.notifications),function(notification){return _c('div',{staticClass:"form-group"},[_c('label',{staticClass:"form-switch"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.notifications),expression:"form.notifications"}],attrs:{"name":"notification[]","type":"checkbox"},domProps:{"value":notification.alias,"checked":Array.isArray(_vm.form.notifications)?_vm._i(_vm.form.notifications,notification.alias)>-1:(_vm.form.notifications)},on:{"change":function($event){var $$a=_vm.form.notifications,$$el=$event.target,$$c=$$el.checked?(true):(false);if(Array.isArray($$a)){var $$v=notification.alias,$$i=_vm._i($$a,$$v);if($$el.checked){$$i<0&&(_vm.$set(_vm.form, "notifications", $$a.concat([$$v])))}else{$$i>-1&&(_vm.$set(_vm.form, "notifications", $$a.slice(0,$$i).concat($$a.slice($$i+1))))}}else{_vm.$set(_vm.form, "notifications", $$c)}}}}),_c('i',{staticClass:"form-icon"}),_vm._v(_vm._s(notification.label))])])}),0)]:_vm._e(),_c('div',{staticClass:"divider"}),_c('div',{staticClass:"form-base"},[_c('div',{staticClass:"form-group off-3"},[_c('button',{staticClass:"btn btn-success",class:{'loading': _vm.loading},attrs:{"name":"submit_profile","type":"button","disabled":_vm.loading},on:{"click":_vm.submit}},[_vm._v("Änderungen speichern")])])])],2)}
var profile_formvue_type_template_id_4fa519d5_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/forms/profile-form.vue?vue&type=template&id=4fa519d5&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/forms/profile-form.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//






/* harmony default export */ var profile_formvue_type_script_lang_js_ = ({
  components: {
    'password-input': password_input,
    'form-input': form_input
  },
  props: {
    url: { type: String, required: true },
    initialData: { type: Object, default: () => { return {} } },
    notifications: { type: Array }
  },

  data() {
    return {
      form: {},
      response: {},
      loading: false,
      elements: [
        { model: 'username', label: 'Username', attrs: { maxlength: 128, autocomplete: "off" }, required: true },
        { model: 'email', label: 'E-Mail', attrs: { maxlength: 128, autocomplete: "off" }, required: true },
        { model: 'name', label: 'Name', attrs: { maxlength: 128, autocomplete: "off" }, required: true },
        { type: 'password-input', model: 'new_PWD', label: 'Neues Passwort', attrs: { maxlength: 128, autocomplete: "off" } },
        { type: 'password-input', model: 'new_PWD_verify', label: 'Passwort wiederholen', attrs: { maxlength: 128, autocomplete: "off" } }
      ]
    }
  },

  computed: {
    errors () {
      return this.response ? (this.response.errors || {}) : {};
    },
    message () {
      return this.response ? this.response.message : "";
    }
  },

  watch: {
    initialData (newValue) {
      this.form = newValue;
    }
  },

  methods: {
    async submit() {
      this.loading = true;
      this.$emit("request-sent");
      this.response = await SimpleFetch(this.url, 'post', {}, JSON.stringify(this.form));
      this.loading = false;
      this.$emit("response-received");
    }
  }
});

// CONCATENATED MODULE: ./vue/components/forms/profile-form.vue?vue&type=script&lang=js&
 /* harmony default export */ var forms_profile_formvue_type_script_lang_js_ = (profile_formvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/forms/profile-form.vue





/* normalize component */

var profile_form_component = normalizeComponent(
  forms_profile_formvue_type_script_lang_js_,
  profile_formvue_type_template_id_4fa519d5_render,
  profile_formvue_type_template_id_4fa519d5_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var profile_form = (profile_form_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/forms/user-form.vue?vue&type=template&id=afb970ac&
var user_formvue_type_template_id_afb970ac_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('form',{staticClass:"form-horizontal",attrs:{"action":"/"},on:{"submit":function($event){$event.preventDefault();}}},[_c('div',{staticClass:"form-sect"},_vm._l((_vm.elements),function(element){return _c('div',{staticClass:"form-group"},[_c('label',{staticClass:"form-label col-3",class:{ required: element.required, 'text-error': _vm.errors[element.model] },attrs:{"for":element.model + '_' + element.type}},[_vm._v(_vm._s(element.label))]),_c('div',{staticClass:"col-9"},[_c(element.type || 'form-input',{tag:"component",attrs:{"id":element.model + '_' + element.type,"name":element.model,"options":element.options},model:{value:(_vm.form[element.model]),callback:function ($$v) {_vm.$set(_vm.form, element.model, $$v)},expression:"form[element.model]"}}),(_vm.errors[element.model])?_c('p',{staticClass:"form-input-hint vx-error-box error"},[_vm._v(_vm._s(_vm.errors[element.model]))]):_vm._e()],1)])}),0),_c('div',{staticClass:"divider"}),_c('div',{staticClass:"form-base"},[_c('div',{staticClass:"form-group off-3"},[_c('button',{staticClass:"btn btn-success",class:{'loading': _vm.loading},attrs:{"name":"submit_user","type":"button","disabled":_vm.loading},on:{"click":_vm.submit}},[_vm._v(_vm._s(_vm.form.id ? 'Daten übernehmen' : 'User anlegen'))])])])])}
var user_formvue_type_template_id_afb970ac_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/forms/user-form.vue?vue&type=template&id=afb970ac&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vx-vue/formelements/form-select.vue?vue&type=template&id=d2ede324&
var form_selectvue_type_template_id_d2ede324_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('select',_vm._g(_vm._b({staticClass:"form-select"},'select',_vm.$attrs,false),Object.assign({}, _vm.$listeners,
    {change: function (event) { return _vm.$emit('input', event.target.value); }})),_vm._l((_vm.options),function(option){return _c('option',{domProps:{"value":option.key || option.label || option,"selected":(option.key || option.label || option) == _vm.value}},[_vm._v(_vm._s(option.label || option)+" ")])}),0)}
var form_selectvue_type_template_id_d2ede324_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-select.vue?vue&type=template&id=d2ede324&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vx-vue/formelements/form-select.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ var form_selectvue_type_script_lang_js_ = ({
  props: { options: Array }
});

// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-select.vue?vue&type=script&lang=js&
 /* harmony default export */ var formelements_form_selectvue_type_script_lang_js_ = (form_selectvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-select.vue





/* normalize component */

var form_select_component = normalizeComponent(
  formelements_form_selectvue_type_script_lang_js_,
  form_selectvue_type_template_id_d2ede324_render,
  form_selectvue_type_template_id_d2ede324_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var form_select = (form_select_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/forms/user-form.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//







/* harmony default export */ var user_formvue_type_script_lang_js_ = ({
    components: {
        'password-input': password_input,
        'form-input': form_input,
        'form-select': form_select
    },

    props: {
        url: { type: String, required: true },
        initialData: { type: Object, default: () => { return {} } },
        options: { type: Object }
    },

    data: function() {
        return {
            form: {},
            response: {},
            loading: false,
            elements: [
                { model: 'username', label: 'Username', attrs: { maxlength: 128, autocomplete: "off" }, required: true },
                { model: 'email', label: 'E-Mail', attrs: { maxlength: 128, autocomplete: "off" }, required: true },
                { model: 'name', label: 'Name', attrs: { maxlength: 128, autocomplete: "off" }, required: true },
                { type: 'form-select', model: 'admingroupsid', label: 'Gruppe', required: true, options: [] },
                { type: 'password-input', model: 'new_PWD', label: 'Neues Passwort', attrs: { maxlength: 128, autocomplete: "off" } },
                { type: 'password-input', model: 'new_PWD_verify', label: 'Passwort wiederholen', attrs: { maxlength: 128, autocomplete: "off" } }
            ]
        }
    },

    computed: {
        errors () {
            return this.response ? (this.response.errors || {}) : {};
        },
        message () {
            return this.response ? this.response.message : "";
        }
    },

    watch: {
        initialData (newValue) {
            this.form = newValue;
        },
        options (newValue) {
          this.elements[this.elements.findIndex(item => item.model === 'admingroupsid')].options = newValue.admingroups;
        }
    },

    methods: {
        async submit() {
            this.loading = true;
            this.$emit("request-sent");
            this.response = await SimpleFetch(this.url, 'post', {}, JSON.stringify(this.form));
            this.loading = false;
            this.$emit("response-received");
        }
    }
});

// CONCATENATED MODULE: ./vue/components/forms/user-form.vue?vue&type=script&lang=js&
 /* harmony default export */ var forms_user_formvue_type_script_lang_js_ = (user_formvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/forms/user-form.vue





/* normalize component */

var user_form_component = normalizeComponent(
  forms_user_formvue_type_script_lang_js_,
  user_formvue_type_template_id_afb970ac_render,
  user_formvue_type_template_id_afb970ac_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var user_form = (user_form_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/forms/article-form.vue?vue&type=template&id=2c2dcea6&
var article_formvue_type_template_id_2c2dcea6_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('form',{staticClass:"form-horizontal",attrs:{"action":"/"},on:{"submit":function($event){$event.preventDefault();}}},[_vm._l((_vm.elements),function(element){return _c('div',{staticClass:"form-group"},[_c('label',{staticClass:"form-label col-3",class:{ required: element.required, 'text-error': _vm.errors[element.model] },attrs:{"for":element.model + '_' + element.type}},[_vm._v(_vm._s(element.label))]),_c('div',{staticClass:"col-9"},[_c(element.type || 'form-input',_vm._b({tag:"component",attrs:{"id":element.model + '_' + element.type,"name":element.model,"options":element.options},model:{value:(_vm.form[element.model]),callback:function ($$v) {_vm.$set(_vm.form, element.model, $$v)},expression:"form[element.model]"}},'component',element.attrs,false))],1)])}),_c('div',{staticClass:"divider"}),_c('div',{staticClass:"form-group"},[_c('button',{staticClass:"btn btn-success off-3 col-3",class:{'loading': _vm.loading},attrs:{"type":"button","disabled":_vm.loading},on:{"click":_vm.submit}},[_vm._v(_vm._s(_vm.form.id ? 'Daten übernehmen' : 'Artikel anlegen'))])])],2)}
var article_formvue_type_template_id_2c2dcea6_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/forms/article-form.vue?vue&type=template&id=2c2dcea6&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vx-vue/formelements/form-textarea.vue?vue&type=template&id=4d47ed12&
var form_textareavue_type_template_id_4d47ed12_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('textarea',_vm._g(_vm._b({staticClass:"form-input"},'textarea',_vm.$attrs,false),Object.assign({}, _vm.$listeners,
      {input: function (event) { return _vm.$emit('input', event.target.value); }})))}
var form_textareavue_type_template_id_4d47ed12_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-textarea.vue?vue&type=template&id=4d47ed12&

// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-textarea.vue

var form_textarea_script = {}


/* normalize component */

var form_textarea_component = normalizeComponent(
  form_textarea_script,
  form_textareavue_type_template_id_4d47ed12_render,
  form_textareavue_type_template_id_4d47ed12_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var form_textarea = (form_textarea_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vx-vue/formelements/form-checkbox.vue?vue&type=template&id=a9e2ff60&
var form_checkboxvue_type_template_id_a9e2ff60_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('label',{staticClass:"form-checkbox"},[_c('input',_vm._b({attrs:{"value":"1","type":"checkbox"},domProps:{"checked":_vm.value},on:{"change":function($event){return _vm.$emit('input', $event.target.checked)}}},'input',_vm.$attrs,false)),_c('i',{staticClass:"form-icon"})])}
var form_checkboxvue_type_template_id_a9e2ff60_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-checkbox.vue?vue&type=template&id=a9e2ff60&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/vx-vue/formelements/form-checkbox.vue?vue&type=script&lang=js&
//
//
//
//
//
//

/* harmony default export */ var form_checkboxvue_type_script_lang_js_ = ({
  inheritAttrs: false,
  props: ['value']
});

// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-checkbox.vue?vue&type=script&lang=js&
 /* harmony default export */ var formelements_form_checkboxvue_type_script_lang_js_ = (form_checkboxvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-checkbox.vue





/* normalize component */

var form_checkbox_component = normalizeComponent(
  formelements_form_checkboxvue_type_script_lang_js_,
  form_checkboxvue_type_template_id_a9e2ff60_render,
  form_checkboxvue_type_template_id_a9e2ff60_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var form_checkbox = (form_checkbox_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/forms/article-form.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//










/* harmony default export */ var article_formvue_type_script_lang_js_ = ({
  components: {
    'vue-ckeditor': vue_ckeditor,
    'datepicker': datepicker,
    'form-input': form_input,
    'form-textarea': form_textarea,
    'form-select': form_select,
    'form-checkbox': form_checkbox
  },

  props: {
    url: { type: String, required: true },
    initialData: { type: Object, default: () => { return {} } },
    options: { type: Object },
    editorConfig: { type: Object }
  },

  data: function() {
    return {
      elements: [
        { type: 'datepicker', model: 'article_date', label: 'Artikeldatum', attrs: {
            'input-format': "d.m.y",
            'output-format': "d.m.y",
            'day-names': 'Mo Di Mi Do Fr Sa So'.split(' '),
            'month-names': 'Jan Feb Mär Apr Mai Jun Jul Aug Sep Okt Nov Dez'.split(' ') }},
        { type: 'datepicker', model: 'display_from', label: 'Anzeige von', attrs: {
            'input-format': "d.m.y",
            'output-format': "d.m.y",
            'day-names': 'Mo Di Mi Do Fr Sa So'.split(' '),
            'month-names': 'Jan Feb Mär Apr Mai Jun Jul Aug Sep Okt Nov Dez'.split(' '),
            'valid-from': new Date() }},
        { type: 'datepicker', model: 'display_until', label: 'Anzeige bis', attrs: {
            'input-format': "d.m.y",
            'output-format': "d.m.y",
            'day-names': 'Mo Di Mi Do Fr Sa So'.split(' '),
            'month-names': 'Jan Feb Mär Apr Mai Jun Jul Aug Sep Okt Nov Dez'.split(' '),
            'valid-from': new Date() }},
        { model: 'customsort', label: 'generische Sortierung', attrs: { 'class': 'col-2', maxlength: 4 } },
        { type: 'form-checkbox', model: 'customflags', label: 'Markiert' },
        { type: 'form-select', model: 'articlecategoriesid', label: 'Kategorie', required: true, options: [] },
        { model: 'headline', label: 'Überschrift/Titel', required: true },
        { model: 'subline', label: 'Unterüberschrift' },
        { type: 'form-textarea', model: 'teaser', label: 'Anrisstext' },
        { type: 'vue-ckeditor', model: 'content', label: 'Inhalt', required: true, attrs: { config: this.editorConfig } }
      ],
      form: {},
      response: {},
      loading: false
    }
  },

  computed: {
    errors () {
      return this.response ? (this.response.errors || {}) : {};
    },
    message () {
      return this.response ? this.response.message : "";
    }
  },

  watch: {
    initialData (newValue) {
      this.form = Object.assign({}, this.form, newValue);
      this.setDates();
    },
    options (newValue) {
      this.elements[this.elements.findIndex(item => item.model === 'articlecategoriesid')].options = newValue.categories;
    }
  },

  mounted () {
    this.form = Object.assign({}, this.form, this.initialData);
    this.setDates();
  },

  methods: {
    async submit () {
      this.loading = true;
      let payload = Object.assign({}, this.form);
      Object.keys(payload).forEach(prop => {
        if(payload[prop] instanceof Date) {
          payload[prop] = payload[prop].getFullYear() + '-' + (payload[prop].getMonth() + 1) + '-' + payload[prop].getDate();
        }
      });
      this.response = await SimpleFetch(this.url, 'post', {}, JSON.stringify(payload));
      if(this.response.id) {
        this.$set(this.form, 'id', this.response.id);
      }
      this.$emit("response-received", this.response);
      this.loading = false;
    },
    setDates () {
      ['article_date', 'display_from', 'display_until'].forEach(item => {
        if (this.form[item] && !(this.form[item] instanceof Date)) {
          this.form[item] = new Date(this.form[item]);
        }
      });
    }
  }
});

// CONCATENATED MODULE: ./vue/components/forms/article-form.vue?vue&type=script&lang=js&
 /* harmony default export */ var forms_article_formvue_type_script_lang_js_ = (article_formvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/forms/article-form.vue





/* normalize component */

var article_form_component = normalizeComponent(
  forms_article_formvue_type_script_lang_js_,
  article_formvue_type_template_id_2c2dcea6_render,
  article_formvue_type_template_id_2c2dcea6_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var article_form = (article_form_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/forms/page-form.vue?vue&type=template&id=d79d7422&
var page_formvue_type_template_id_d79d7422_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('form',{attrs:{"action":"/"},on:{"submit":function($event){$event.preventDefault();}}},[_c('div',{staticClass:"columns"},[_c('div',{staticClass:"column col-8"},[_c('div',{staticClass:"form-group"},[_c('label',{staticClass:"form-label",attrs:{"for":"alias_input"}},[_vm._v("Eindeutiger Name (automatisch generiert)")]),_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.alias),expression:"form.alias"}],staticClass:"form-input",attrs:{"id":"alias_input","disabled":"disabled","maxlength":"64"},domProps:{"value":(_vm.form.alias)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "alias", $event.target.value)}}})]),_c('div',{staticClass:"form-group"},[_c('label',{staticClass:"form-label",attrs:{"for":"title_input"}},[_vm._v("Titel")]),_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.title),expression:"form.title"}],staticClass:"form-input",attrs:{"id":"title_input","maxlength":"128"},domProps:{"value":(_vm.form.title)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "title", $event.target.value)}}}),(_vm.errors.title)?_c('p',{staticClass:"form-input-hint vx-error-box error"},[_vm._v(_vm._s(_vm.errors.title))]):_vm._e()]),_c('div',{staticClass:"form-group"},[_c('label',{staticClass:"form-label"},[_vm._v("Inhalt")]),_c('vue-ckeditor',{attrs:{"config":_vm.editorConfig},model:{value:(_vm.form.markup),callback:function ($$v) {_vm.$set(_vm.form, "markup", $$v)},expression:"form.markup"}})],1)]),_c('div',{staticClass:"column col-4"},[_c('div',{staticClass:"form-group"},[_c('label',{staticClass:"form-label",attrs:{"for":"keywords_input"}},[_vm._v("Schlüsselwörter")]),_c('textarea',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.keywords),expression:"form.keywords"}],staticClass:"form-input",attrs:{"id":"keywords_input","rows":"4"},domProps:{"value":(_vm.form.keywords)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "keywords", $event.target.value)}}})]),_c('div',{staticClass:"form-group"},[_c('label',{staticClass:"form-label",attrs:{"for":"description_input"}},[_vm._v("Beschreibung")]),_c('textarea',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.description),expression:"form.description"}],staticClass:"form-input",attrs:{"id":"description_input","rows":"4"},domProps:{"value":(_vm.form.description)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "description", $event.target.value)}}})]),_c('div',{staticClass:"divider",attrs:{"data-content":"Revisionen"}}),_c('div',{attrs:{"id":"revisionsContainer"}},[_c('revision-table',{attrs:{"revisions":_vm.revisions},on:{"activate-revision":function($event){return _vm.$emit('activate-revision', $event)},"load-revision":function($event){return _vm.$emit('load-revision', $event)},"delete-revision":function($event){return _vm.$emit('delete-revision', $event)}}})],1)])]),_c('div',{staticClass:"divider"}),_c('div',{staticClass:"form-base"},[_c('div',{staticClass:"form-group"},[_c('button',{staticClass:"btn btn-success",class:{'loading': _vm.loading},attrs:{"name":"submit_page","type":"button","disabled":_vm.loading},on:{"click":_vm.submit}},[_vm._v("Änderungen übernehmen und neue Revision erzeugen")])])])])}
var page_formvue_type_template_id_d79d7422_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/forms/page-form.vue?vue&type=template&id=d79d7422&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"090a5385-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/forms/revision-table.vue?vue&type=template&id=5daf591e&
var revision_tablevue_type_template_id_5daf591e_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('table',{staticClass:"table table-striped",attrs:{"id":"revisions"}},[_vm._m(0),_c('tbody',_vm._l((_vm.sortedRevisions),function(revision){return _c('tr',{key:revision.id},[_c('td',[_vm._v(_vm._s(_vm._f("formatDateTime")(revision.firstCreated)))]),_c('td',[_c('button',{staticClass:"btn btn-link webfont-icon-only tooltip",attrs:{"type":"button","data-tooltip":"Ansicht"},on:{"click":function($event){return _vm.$emit('load-revision', revision)}}},[_vm._v("")])]),_c('td',[_c('label',{staticClass:"form-switch"},[_c('input',{attrs:{"type":"checkbox","disabled":revision.active},domProps:{"checked":revision.active},on:{"click":function($event){return _vm.$emit('activate-revision', revision)}}}),_c('i',{staticClass:"form-icon"})])]),_c('td',{staticClass:"text-right"},[(!revision.active)?_c('button',{staticClass:"btn btn-primary webfont-icon-only tooltip tooltip-left",attrs:{"type":"button","data-tooltip":"Löschen"},on:{"click":function($event){return _vm.$emit('delete-revision', revision)}}},[_vm._v("")]):_vm._e()])])}),0)])}
var revision_tablevue_type_template_id_5daf591e_staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('thead',[_c('tr',[_c('th',[_vm._v("Angelegt um")]),_c('th',{staticClass:"col-2"}),_c('th',{staticClass:"col-2"},[_vm._v("aktiv")]),_c('th',{staticClass:"col-2"})])])}]


// CONCATENATED MODULE: ./vue/components/forms/revision-table.vue?vue&type=template&id=5daf591e&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/forms/revision-table.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


/* harmony default export */ var revision_tablevue_type_script_lang_js_ = ({
    props: {
        revisions: { type: Array, default: [] }
    },
    computed: {
        sortedRevisions() {
            return this.revisions.slice().sort((a, b) => a.firstCreated < b.firstCreated ? 1 : -1);
        }
    },
    filters: {
        formatDateTime (date) {
            return date_functions.formatDate(date, 'y-mm-dd h:i:s');
        }
    }

});

// CONCATENATED MODULE: ./vue/components/forms/revision-table.vue?vue&type=script&lang=js&
 /* harmony default export */ var forms_revision_tablevue_type_script_lang_js_ = (revision_tablevue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/forms/revision-table.vue





/* normalize component */

var revision_table_component = normalizeComponent(
  forms_revision_tablevue_type_script_lang_js_,
  revision_tablevue_type_template_id_5daf591e_render,
  revision_tablevue_type_template_id_5daf591e_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var revision_table = (revision_table_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/forms/page-form.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//





/* harmony default export */ var page_formvue_type_script_lang_js_ = ({
    components: {
        'vue-ckeditor': vue_ckeditor,
        'revision-table': revision_table
    },
    props: {
        url: { type: String, required: true },
        initialData: { type: Object, default: () => { return {} } }
    },

    data() {
        return {
            form: {},
            revisions: [],
            response: {},
            loading: false,
            editorConfig: {
                allowedContent: true,
                autoParagraph: false,
                customConfig: "",
                toolbar:
                    [
                        ['Maximize', '-', 'Source'],
                        ['Undo', 'Redo', '-', 'Cut','Copy','Paste','PasteText','PasteFromWord'],
                        [ 'Find', 'Replace'],
                        [ 'Bold', 'Italic', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat', '-', 'TextColor', 'BGColor'],
                        ['NumberedList','BulletedList','-','Blockquote'],
                        ['Link','Unlink'],
                        ['Image','Table','SpecialChar'],
                        ['Styles', 'Format'],
                        ['ShowBlocks']
                    ],
                height: "24rem", contentsCss: ['/css/site.css', '/css/site_edit.css'],
                filebrowserBrowseUrl: null,
                filebrowserImageBrowseUrl: null
            }
        }
    },

    computed: {
        errors () {
            return this.response ? (this.response.errors || {}) : {};
        },
        message () {
            return this.response ? this.response.message : "";
        }
    },

    watch: {
        initialData (newValue) {
            this.form = newValue.form || this.form;
            this.revisions = newValue.revisions || this.revisions;
        }
    },

    created () {
        this.editorConfig.filebrowserBrowseUrl = this.$parent.$options.routes.fileBrowse;
        this.editorConfig.filebrowserImageBrowseUrl = this.$parent.$options.routes.fileBrowse + "?filter=image";
    },

    methods: {
        async submit() {
            this.loading = true;
            this.$emit("request-sent");
            this.response = await SimpleFetch(this.url, 'post', {}, JSON.stringify(this.form));
            this.loading = false;
            this.$emit("response-received");
        }
    }
});

// CONCATENATED MODULE: ./vue/components/forms/page-form.vue?vue&type=script&lang=js&
 /* harmony default export */ var forms_page_formvue_type_script_lang_js_ = (page_formvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/forms/page-form.vue





/* normalize component */

var page_form_component = normalizeComponent(
  forms_page_formvue_type_script_lang_js_,
  page_formvue_type_template_id_d79d7422_render,
  page_formvue_type_template_id_d79d7422_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var page_form = (page_form_component.exports);
// CONCATENATED MODULE: ./vue/build/vxweb.js
































const Components = {
    MessageToast: message_toast,
    CircularProgress: circular_progress,
    Autocomplete: autocomplete,
    DatePicker: datepicker,
    Sortable: sortable,
    SimpleTree: simple_tree,
    Filemanager: filemanager,
    SlicksortList: slicksort_list,
    Tab: tab,
    Confirm: vx_vue_confirm,
    Alert: vx_vue_alert,
    PasswordInput: password_input,
    CookieConsent: cookie_consent,
    Pagination: pagination,
    VueCkeditor: vue_ckeditor,
    ProfileForm: profile_form,
    UserForm: user_form,
    ArticleForm: article_form,
    PageForm: page_form
};

const Filters = {
    formatFilesize: formatFilesize
};

const Directives = {
    Focus: Focus,
    Bubble: Bubble,
    HandleDirective: vue_slicksort_umd["HandleDirective"]
};

const Mixins = {
    ContainerMixin: vue_slicksort_umd["ContainerMixin"],
    ElementMixin: vue_slicksort_umd["ElementMixin"]
};

const Util = {
    SimpleFetch: SimpleFetch,
    PromisedXhr: PromisedXhr,
    UrlQuery: url_query,
    DateFunctions: date_functions
};


// CONCATENATED MODULE: ./node_modules/@vue/cli-service/lib/commands/build/entry-lib-no-default.js




/***/ })

/******/ });
});
//# sourceMappingURL=vxweb.umd.js.map