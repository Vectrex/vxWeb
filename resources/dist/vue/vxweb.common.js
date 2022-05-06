/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ 607:
/***/ ((module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_css_loader_dist_runtime_noSourceMaps_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(81);
/* harmony import */ var _node_modules_css_loader_dist_runtime_noSourceMaps_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_noSourceMaps_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(645);
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_1__);
// Imports


var ___CSS_LOADER_EXPORT___ = _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_1___default()((_node_modules_css_loader_dist_runtime_noSourceMaps_js__WEBPACK_IMPORTED_MODULE_0___default()));
// Module
___CSS_LOADER_EXPORT___.push([module.id, "div[data-v-0aec82d5]{position:relative}div a[data-v-0aec82d5]:focus{box-shadow:none;text-decoration:none;font-family:icomoon}div a[data-v-0aec82d5]:after{height:.8rem;margin:0 .25rem;position:absolute;top:50%;transform:translateY(-50%);width:.8rem;z-index:2;line-height:100%;right:.05rem;font-family:icomoon;content:\"\"}div a.show[data-v-0aec82d5]:after{content:\"\"}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ 685:
/***/ ((module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_css_loader_dist_runtime_noSourceMaps_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(81);
/* harmony import */ var _node_modules_css_loader_dist_runtime_noSourceMaps_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_noSourceMaps_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(645);
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_1__);
// Imports


var ___CSS_LOADER_EXPORT___ = _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_1___default()((_node_modules_css_loader_dist_runtime_noSourceMaps_js__WEBPACK_IMPORTED_MODULE_0___default()));
// Module
___CSS_LOADER_EXPORT___.push([module.id, ".sortable-header[data-v-d2ff1a72]:after{font-family:icomoon;font-weight:400;vertical-align:middle;display:inline-block}.sortable-header.asc[data-v-d2ff1a72]:after{content:\"\"}.sortable-header.desc[data-v-d2ff1a72]:after{content:\"\"}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ 645:
/***/ ((module) => {

"use strict";


/*
  MIT License http://www.opensource.org/licenses/mit-license.php
  Author Tobias Koppers @sokra
*/
module.exports = function (cssWithMappingToString) {
  var list = []; // return the list of modules as css string

  list.toString = function toString() {
    return this.map(function (item) {
      var content = "";
      var needLayer = typeof item[5] !== "undefined";

      if (item[4]) {
        content += "@supports (".concat(item[4], ") {");
      }

      if (item[2]) {
        content += "@media ".concat(item[2], " {");
      }

      if (needLayer) {
        content += "@layer".concat(item[5].length > 0 ? " ".concat(item[5]) : "", " {");
      }

      content += cssWithMappingToString(item);

      if (needLayer) {
        content += "}";
      }

      if (item[2]) {
        content += "}";
      }

      if (item[4]) {
        content += "}";
      }

      return content;
    }).join("");
  }; // import a list of modules into the list


  list.i = function i(modules, media, dedupe, supports, layer) {
    if (typeof modules === "string") {
      modules = [[null, modules, undefined]];
    }

    var alreadyImportedModules = {};

    if (dedupe) {
      for (var k = 0; k < this.length; k++) {
        var id = this[k][0];

        if (id != null) {
          alreadyImportedModules[id] = true;
        }
      }
    }

    for (var _k = 0; _k < modules.length; _k++) {
      var item = [].concat(modules[_k]);

      if (dedupe && alreadyImportedModules[item[0]]) {
        continue;
      }

      if (typeof layer !== "undefined") {
        if (typeof item[5] === "undefined") {
          item[5] = layer;
        } else {
          item[1] = "@layer".concat(item[5].length > 0 ? " ".concat(item[5]) : "", " {").concat(item[1], "}");
          item[5] = layer;
        }
      }

      if (media) {
        if (!item[2]) {
          item[2] = media;
        } else {
          item[1] = "@media ".concat(item[2], " {").concat(item[1], "}");
          item[2] = media;
        }
      }

      if (supports) {
        if (!item[4]) {
          item[4] = "".concat(supports);
        } else {
          item[1] = "@supports (".concat(item[4], ") {").concat(item[1], "}");
          item[4] = supports;
        }
      }

      list.push(item);
    }
  };

  return list;
};

/***/ }),

/***/ 81:
/***/ ((module) => {

"use strict";


module.exports = function (i) {
  return i[1];
};

/***/ }),

/***/ 744:
/***/ ((__unused_webpack_module, exports) => {

"use strict";
var __webpack_unused_export__;

__webpack_unused_export__ = ({ value: true });
// runtime helper for setting properties on components
// in a tree-shakable way
exports.Z = (sfc, props) => {
    const target = sfc.__vccOpts || sfc;
    for (const [key, val] of props) {
        target[key] = val;
    }
    return target;
};


/***/ }),

/***/ 247:
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {

(function (global, factory) {
     true ? factory(exports, __webpack_require__(797)) :
    0;
}(this, (function (exports, vue) { 'use strict';

    // Export Sortable Element Component Mixin
    const ElementMixin = vue.defineComponent({
        inject: ['manager'],
        props: {
            index: {
                type: Number,
                required: true,
            },
            disabled: {
                type: Boolean,
                default: false,
            },
        },
        data() {
            return {};
        },
        watch: {
            index(newIndex) {
                if (this.$el && this.$el.sortableInfo) {
                    this.$el.sortableInfo.index = newIndex;
                }
            },
            disabled(isDisabled) {
                if (isDisabled) {
                    this.removeDraggable();
                }
                else {
                    this.setDraggable(this.index);
                }
            },
        },
        mounted() {
            const { disabled, index } = this.$props;
            if (!disabled) {
                this.setDraggable(index);
            }
        },
        beforeUnmount() {
            if (!this.disabled)
                this.removeDraggable();
        },
        methods: {
            setDraggable(index) {
                const node = this.$el;
                node.sortableInfo = {
                    index,
                    manager: this.manager,
                };
                this.ref = { node };
                this.manager.add(this.ref);
            },
            removeDraggable() {
                this.manager.remove(this.ref);
            },
        },
    });

    class Manager {
        constructor() {
            this.refs = [];
            this.active = null;
        }
        add(ref) {
            if (!this.refs) {
                this.refs = [];
            }
            this.refs.push(ref);
        }
        remove(ref) {
            const index = this.getIndex(ref);
            if (index !== -1) {
                this.refs.splice(index, 1);
            }
        }
        isActive() {
            return !!this.active;
        }
        getActive() {
            return this.refs.find(({ node }) => { var _a, _b; return ((_a = node === null || node === void 0 ? void 0 : node.sortableInfo) === null || _a === void 0 ? void 0 : _a.index) == ((_b = this === null || this === void 0 ? void 0 : this.active) === null || _b === void 0 ? void 0 : _b.index); }) || null;
        }
        getIndex(ref) {
            return this.refs.indexOf(ref);
        }
        getRefs() {
            return this.refs;
        }
        getOrderedRefs() {
            return this.refs.sort((a, b) => {
                return a.node.sortableInfo.index - b.node.sortableInfo.index;
            });
        }
    }

    const isTouch = (e) => {
        return e.touches != null;
    };
    // eslint-disable-next-line @typescript-eslint/ban-types
    function hasOwnProperty(obj, prop) {
        return !!obj && Object.prototype.hasOwnProperty.call(obj, prop);
    }
    function arrayMove(arr, previousIndex, newIndex) {
        const array = arr.slice(0);
        if (newIndex >= array.length) {
            let k = newIndex - array.length;
            while (k-- + 1) {
                array.push(undefined);
            }
        }
        array.splice(newIndex, 0, array.splice(previousIndex, 1)[0]);
        return array;
    }
    function arrayRemove(arr, previousIndex) {
        const array = arr.slice(0);
        if (previousIndex >= array.length)
            return array;
        array.splice(previousIndex, 1);
        return array;
    }
    function arrayInsert(arr, newIndex, value) {
        const array = arr.slice(0);
        if (newIndex === array.length) {
            array.push(value);
        }
        else {
            array.splice(newIndex, 0, value);
        }
        return array;
    }
    const events = {
        start: ['touchstart', 'mousedown'],
        move: ['touchmove', 'mousemove'],
        end: ['touchend', 'mouseup'],
        cancel: ['touchcancel', 'keyup'],
    };
    function closest(el, fn) {
        while (el) {
            if (fn(el))
                return el;
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
        const style = window.getComputedStyle(element);
        return {
            top: getCSSPixelValue(style.marginTop),
            right: getCSSPixelValue(style.marginRight),
            bottom: getCSSPixelValue(style.marginBottom),
            left: getCSSPixelValue(style.marginLeft),
        };
    }
    function getPointerOffset(e, reference = 'page') {
        const x = `${reference}X`;
        const y = `${reference}Y`;
        return {
            x: isTouch(e) ? e.touches[0][x] : e[x],
            y: isTouch(e) ? e.touches[0][y] : e[y],
        };
    }
    function offsetParents(node) {
        const nodes = [node];
        for (; node; node = node.offsetParent) {
            nodes.unshift(node);
        }
        return nodes;
    }
    function commonOffsetParent(node1, node2) {
        const parents1 = offsetParents(node1);
        const parents2 = offsetParents(node2);
        if (parents1[0] != parents2[0])
            throw 'No common ancestor!';
        for (let i = 0; i < parents1.length; i++) {
            if (parents1[i] != parents2[i])
                return parents1[i - 1];
        }
    }
    function getEdgeOffset(node, container, offset = { top: 0, left: 0 }) {
        // Get the actual offsetTop / offsetLeft value, no matter how deep the node is nested
        if (node) {
            const nodeOffset = {
                top: offset.top + node.offsetTop,
                left: offset.left + node.offsetLeft,
            };
            if (node.offsetParent !== container.offsetParent) {
                return getEdgeOffset(node.offsetParent, container, nodeOffset);
            }
            else {
                return nodeOffset;
            }
        }
        return { top: 0, left: 0 };
    }
    function cloneNode(node) {
        const fields = node.querySelectorAll('input, textarea, select');
        const clonedNode = node.cloneNode(true);
        const clonedFields = [...clonedNode.querySelectorAll('input, textarea, select')]; // Convert NodeList to Array
        clonedFields.forEach((field, index) => {
            if (field.type !== 'file' && fields[index]) {
                field.value = fields[index].value;
            }
        });
        return clonedNode;
    }
    function getLockPixelOffsets(lockOffset, width, height) {
        if (typeof lockOffset == 'string') {
            lockOffset = +lockOffset;
        }
        if (!Array.isArray(lockOffset)) {
            lockOffset = [lockOffset, lockOffset];
        }
        if (lockOffset.length !== 2) {
            throw new Error(`lockOffset prop of SortableContainer should be a single value or an array of exactly two values. Given ${lockOffset}`);
        }
        const [minLockOffset, maxLockOffset] = lockOffset;
        return [getLockPixelOffset(minLockOffset, width, height), getLockPixelOffset(maxLockOffset, width, height)];
    }
    function getLockPixelOffset(lockOffset, width, height) {
        let offsetX = lockOffset;
        let offsetY = lockOffset;
        let unit = 'px';
        if (typeof lockOffset === 'string') {
            const match = /^[+-]?\d*(?:\.\d*)?(px|%)$/.exec(lockOffset);
            if (match === null) {
                throw new Error(`lockOffset value should be a number or a string of a number followed by "px" or "%". Given ${lockOffset}`);
            }
            offsetX = offsetY = parseFloat(lockOffset);
            unit = match[1];
        }
        if (!isFinite(offsetX) || !isFinite(offsetY)) {
            throw new Error(`lockOffset value should be a finite. Given ${lockOffset}`);
        }
        if (unit === '%') {
            offsetX = (offsetX * width) / 100;
            offsetY = (offsetY * height) / 100;
        }
        return {
            x: offsetX,
            y: offsetY,
        };
    }
    function getDistance(x1, y1, x2, y2) {
        const x = x1 - x2;
        const y = y1 - y2;
        return Math.sqrt(x * x + y * y);
    }
    function getRectCenter(clientRect) {
        return {
            x: clientRect.left + clientRect.width / 2,
            y: clientRect.top + clientRect.height / 2,
        };
    }
    function resetTransform(nodes = []) {
        for (let i = 0, len = nodes.length; i < len; i++) {
            const node = nodes[i];
            const el = node.node;
            if (!el)
                return;
            // Clear the cached offsetTop / offsetLeft value
            node.edgeOffset = null;
            // Remove the transforms / transitions
            setTransform(el);
        }
    }
    function setTransform(el, transform = '', duration = '') {
        if (!el)
            return;
        el.style['transform'] = transform;
        el.style['transitionDuration'] = duration;
    }
    function withinBounds(pos, top, bottom) {
        const upper = Math.max(top, bottom);
        const lower = Math.min(top, bottom);
        return lower <= pos && pos <= upper;
    }
    function isPointWithinRect({ x, y }, { top, left, width, height }) {
        const withinX = withinBounds(x, left, left + width);
        const withinY = withinBounds(y, top, top + height);
        return withinX && withinY;
    }

    /* eslint-disable @typescript-eslint/ban-ts-comment */
    // eslint-disable-next-line @typescript-eslint/ban-types
    const timeout = setTimeout;
    // Export Sortable Container Component Mixin
    const ContainerMixin = vue.defineComponent({
        inject: {
            SlicksortHub: {
                from: 'SlicksortHub',
                default: null,
            },
        },
        provide() {
            return {
                manager: this.manager,
            };
        },
        props: {
            list: { type: Array, required: true },
            axis: { type: String, default: 'y' },
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
            group: { type: String, default: '' },
            accept: { type: [Boolean, Array, Function], default: null },
            cancelKey: { type: String, default: 'Escape' },
            block: { type: Array, default: () => [] },
            lockAxis: { type: String, default: '' },
            helperClass: { type: String, default: '' },
            contentWindow: { type: Object, default: null },
            shouldCancelStart: {
                type: Function,
                default: (e) => {
                    // Cancel sorting if the event target is an `input`, `textarea`, `select` or `option`
                    const disabledElements = ['input', 'textarea', 'select', 'option', 'button'];
                    return disabledElements.indexOf(e.target.tagName.toLowerCase()) !== -1;
                },
            },
            getHelperDimensions: {
                type: Function,
                default: ({ node }) => ({
                    width: node.offsetWidth,
                    height: node.offsetHeight,
                }),
            },
        },
        emits: ['sort-start', 'sort-move', 'sort-end', 'sort-cancel', 'sort-insert', 'sort-remove', 'update:list'],
        data() {
            let useHub = false;
            if (this.group) {
                // If the group option is set, it is assumed the user intends
                // to drag between containers and the required plugin has been installed
                if (this.SlicksortHub) {
                    useHub = true;
                }
                else if (false) {}
            }
            return {
                sorting: false,
                hub: useHub ? this.SlicksortHub : null,
                manager: new Manager(),
            };
        },
        mounted() {
            if (this.hub) {
                this.id = this.hub.getId();
            }
            this.container = this.$el;
            this.document = this.container.ownerDocument || document;
            this._window = this.contentWindow || window;
            this.scrollContainer = this.useWindowAsScrollContainer ? { scrollLeft: 0, scrollTop: 0 } : this.container;
            this.events = {
                start: this.handleStart,
                move: this.handleMove,
                end: this.handleEnd,
            };
            for (const key in this.events) {
                if (hasOwnProperty(this.events, key)) {
                    // @ts-ignore
                    events[key].forEach((eventName) => this.container.addEventListener(eventName, this.events[key]));
                }
            }
            if (this.hub) {
                this.hub.addContainer(this);
            }
        },
        beforeUnmount() {
            for (const key in this.events) {
                if (hasOwnProperty(this.events, key)) {
                    // @ts-ignore
                    events[key].forEach((eventName) => this.container.removeEventListener(eventName, this.events[key]));
                }
            }
            if (this.hub) {
                this.hub.removeContainer(this);
            }
            if (this.dragendTimer)
                clearTimeout(this.dragendTimer);
            if (this.cancelTimer)
                clearTimeout(this.cancelTimer);
            if (this.pressTimer)
                clearTimeout(this.pressTimer);
            if (this.autoscrollInterval)
                clearInterval(this.autoscrollInterval);
        },
        methods: {
            handleStart(e) {
                const { distance, shouldCancelStart } = this.$props;
                if ((!isTouch(e) && e.button === 2) || shouldCancelStart(e)) {
                    return false;
                }
                this._touched = true;
                this._pos = getPointerOffset(e);
                const target = e.target;
                const node = closest(target, (el) => el.sortableInfo != null);
                if (node && node.sortableInfo && this.nodeIsChild(node) && !this.sorting) {
                    const { useDragHandle } = this.$props;
                    const { index } = node.sortableInfo;
                    if (useDragHandle && !closest(target, (el) => el.sortableHandle != null))
                        return;
                    this.manager.active = { index };
                    /*
                     * Fixes a bug in Firefox where the :active state of anchor tags
                     * prevent subsequent 'mousemove' events from being fired
                     * (see https://github.com/clauderic/react-sortable-hoc/issues/118)
                     */
                    if (target.tagName.toLowerCase() === 'a') {
                        e.preventDefault();
                    }
                    if (!distance) {
                        if (this.pressDelay === 0) {
                            this.handlePress(e);
                        }
                        else {
                            this.pressTimer = timeout(() => this.handlePress(e), this.pressDelay);
                        }
                    }
                }
            },
            nodeIsChild(node) {
                return node.sortableInfo.manager === this.manager;
            },
            handleMove(e) {
                const { distance, pressThreshold } = this.$props;
                if (!this.sorting && this._touched) {
                    const offset = getPointerOffset(e);
                    this._delta = {
                        x: this._pos.x - offset.x,
                        y: this._pos.y - offset.y,
                    };
                    const delta = Math.abs(this._delta.x) + Math.abs(this._delta.y);
                    if (!distance && (!pressThreshold || (pressThreshold && delta >= pressThreshold))) {
                        if (this.cancelTimer)
                            clearTimeout(this.cancelTimer);
                        this.cancelTimer = timeout(this.cancel, 0);
                    }
                    else if (distance && delta >= distance && this.manager.isActive()) {
                        this.handlePress(e);
                    }
                }
            },
            handleEnd() {
                if (!this._touched)
                    return;
                const { distance } = this.$props;
                this._touched = false;
                if (!distance) {
                    this.cancel();
                }
            },
            cancel() {
                if (!this.sorting) {
                    if (this.pressTimer)
                        clearTimeout(this.pressTimer);
                    this.manager.active = null;
                    if (this.hub)
                        this.hub.cancel();
                }
            },
            handleSortCancel(e) {
                if (isTouch(e) || e.key === this.cancelKey) {
                    this.newIndex = this.index;
                    this.canceling = true;
                    this.translate = { x: 0, y: 0 };
                    this.animateNodes();
                    this.handleSortEnd(e);
                }
            },
            handlePress(e) {
                e.stopPropagation();
                const active = this.manager.getActive();
                if (active) {
                    const { getHelperDimensions, helperClass, hideSortableGhost, appendTo } = this.$props;
                    const { node } = active;
                    const { index } = node.sortableInfo;
                    const margin = getElementMargin(node);
                    const containerBoundingRect = this.container.getBoundingClientRect();
                    const dimensions = getHelperDimensions({ index, node });
                    this.node = node;
                    this.margin = margin;
                    this.width = dimensions.width;
                    this.height = dimensions.height;
                    this.marginOffset = {
                        x: this.margin.left + this.margin.right,
                        y: Math.max(this.margin.top, this.margin.bottom),
                    };
                    this.boundingClientRect = node.getBoundingClientRect();
                    this.containerBoundingRect = containerBoundingRect;
                    this.index = index;
                    this.newIndex = index;
                    const clonedNode = cloneNode(node);
                    this.helper = this.document.querySelector(appendTo).appendChild(clonedNode);
                    this.helper.style.position = 'fixed';
                    this.helper.style.top = `${this.boundingClientRect.top - margin.top}px`;
                    this.helper.style.left = `${this.boundingClientRect.left - margin.left}px`;
                    this.helper.style.width = `${this.width}px`;
                    this.helper.style.height = `${this.height}px`;
                    this.helper.style.boxSizing = 'border-box';
                    this.helper.style.pointerEvents = 'none';
                    if (hideSortableGhost) {
                        this.sortableGhost = node;
                        node.style.visibility = 'hidden';
                        node.style.opacity = '0';
                    }
                    if (this.hub) {
                        this.hub.sortStart(this);
                        this.hub.helper = this.helper;
                        this.hub.ghost = this.sortableGhost;
                    }
                    this.intializeOffsets(e, this.boundingClientRect);
                    this.offsetEdge = getEdgeOffset(node, this.container);
                    if (helperClass) {
                        this.helper.classList.add(...helperClass.split(' '));
                    }
                    this.listenerNode = isTouch(e) ? node : this._window;
                    // @ts-ignore
                    events.move.forEach((eventName) => this.listenerNode.addEventListener(eventName, this.handleSortMove));
                    // @ts-ignore
                    events.end.forEach((eventName) => this.listenerNode.addEventListener(eventName, this.handleSortEnd));
                    // @ts-ignore
                    events.cancel.forEach((eventName) => this.listenerNode.addEventListener(eventName, this.handleSortCancel));
                    this.sorting = true;
                    this.$emit('sort-start', { event: e, node, index });
                }
            },
            handleSortMove(e) {
                e.preventDefault(); // Prevent scrolling on mobile
                this.updatePosition(e);
                if (this.hub) {
                    const payload = this.list[this.index];
                    this.hub.handleSortMove(e, payload);
                }
                if (!this.hub || this.hub.isDest(this)) {
                    this.animateNodes();
                    this.autoscroll();
                }
                this.$emit('sort-move', { event: e });
            },
            handleDropOut() {
                const removed = this.list[this.index];
                const newValue = arrayRemove(this.list, this.index);
                this.$emit('sort-remove', {
                    oldIndex: this.index,
                });
                this.$emit('update:list', newValue);
                return removed;
            },
            handleDropIn(payload) {
                const newValue = arrayInsert(this.list, this.newIndex, payload);
                this.$emit('sort-insert', {
                    newIndex: this.newIndex,
                    value: payload,
                });
                this.$emit('update:list', newValue);
                this.handleDragEnd();
            },
            handleDragOut() {
                if (this.autoscrollInterval) {
                    clearInterval(this.autoscrollInterval);
                    this.autoscrollInterval = null;
                }
                if (this.hub.isSource(this)) {
                    // Trick to animate all nodes up
                    this.translate = {
                        x: 10000,
                        y: 10000,
                    };
                    this.animateNodes();
                }
                else {
                    this.manager.getRefs().forEach((ref) => {
                        ref.node.style['transform'] = '';
                    });
                    this.dragendTimer = timeout(this.handleDragEnd, this.transitionDuration || 0);
                }
            },
            handleDragEnd() {
                if (this.autoscrollInterval) {
                    clearInterval(this.autoscrollInterval);
                    this.autoscrollInterval = null;
                }
                resetTransform(this.manager.getRefs());
                if (this.sortableGhost) {
                    this.sortableGhost.remove();
                    this.sortableGhost = null;
                }
                if (this.dragendTimer) {
                    clearTimeout(this.dragendTimer);
                    this.dragendTimer = null;
                }
                this.manager.active = null;
                this._touched = false;
                this.sorting = false;
            },
            intializeOffsets(e, clientRect) {
                const { useWindowAsScrollContainer, containerBoundingRect, _window } = this;
                this.marginOffset = {
                    x: this.margin.left + this.margin.right,
                    y: Math.max(this.margin.top, this.margin.bottom),
                };
                this._axis = {
                    x: this.axis.indexOf('x') >= 0,
                    y: this.axis.indexOf('y') >= 0,
                };
                this.initialOffset = getPointerOffset(e);
                // initialScroll;
                this.initialScroll = {
                    top: this.scrollContainer.scrollTop,
                    left: this.scrollContainer.scrollLeft,
                };
                // initialWindowScroll;
                this.initialWindowScroll = {
                    top: window.pageYOffset,
                    left: window.pageXOffset,
                };
                this.translate = { x: 0, y: 0 };
                this.minTranslate = {};
                this.maxTranslate = {};
                if (this._axis.x) {
                    this.minTranslate.x =
                        (useWindowAsScrollContainer ? 0 : containerBoundingRect.left) - clientRect.left - this.width / 2;
                    this.maxTranslate.x =
                        (useWindowAsScrollContainer ? _window.innerWidth : containerBoundingRect.left + containerBoundingRect.width) -
                            clientRect.left -
                            this.width / 2;
                }
                if (this._axis.y) {
                    this.minTranslate.y =
                        (useWindowAsScrollContainer ? 0 : containerBoundingRect.top) - clientRect.top - this.height / 2;
                    this.maxTranslate.y =
                        (useWindowAsScrollContainer
                            ? _window.innerHeight
                            : containerBoundingRect.top + containerBoundingRect.height) -
                            clientRect.top -
                            this.height / 2;
                }
            },
            handleDragIn(e, sortableGhost, helper) {
                if (this.hub.isSource(this)) {
                    return;
                }
                if (this.dragendTimer) {
                    this.handleDragEnd();
                    clearTimeout(this.dragendTimer);
                    this.dragendTimer = null;
                }
                const nodes = this.manager.getRefs();
                this.index = nodes.length;
                this.manager.active = { index: this.index };
                const containerBoundingRect = this.container.getBoundingClientRect();
                const helperBoundingRect = helper.getBoundingClientRect();
                this.containerBoundingRect = containerBoundingRect;
                this.sortableGhost = cloneNode(sortableGhost);
                this.container.appendChild(this.sortableGhost);
                const ghostRect = this.sortableGhost.getBoundingClientRect();
                this.boundingClientRect = ghostRect;
                this.margin = getElementMargin(this.sortableGhost);
                this.width = ghostRect.width;
                this.height = ghostRect.height;
                // XY coords of the inserted node, relative to the top-left corner of the container
                this.offsetEdge = getEdgeOffset(this.sortableGhost, this.container);
                this.intializeOffsets(e, ghostRect);
                // Move the initialOffset back to the insertion point of the
                // sortableGhost (end of the list), as if we had started the drag there.
                this.initialOffset.x += ghostRect.x - helperBoundingRect.x;
                this.initialOffset.y += ghostRect.y - helperBoundingRect.y;
                // Turn on dragging
                this.sorting = true;
            },
            handleSortEnd(e) {
                // Remove the event listeners if the node is still in the DOM
                if (this.listenerNode) {
                    events.move.forEach((eventName) => 
                    // @ts-ignore
                    this.listenerNode.removeEventListener(eventName, this.handleSortMove));
                    events.end.forEach((eventName) => 
                    // @ts-ignore
                    this.listenerNode.removeEventListener(eventName, this.handleSortEnd));
                    events.cancel.forEach((eventName) => 
                    // @ts-ignore
                    this.listenerNode.removeEventListener(eventName, this.handleSortCancel));
                }
                const nodes = this.manager.getRefs();
                // Remove the helper class(es) early to give it a chance to transition back
                if (this.helper && this.helperClass) {
                    this.helper.classList.remove(...this.helperClass.split(' '));
                }
                // Stop autoscroll
                if (this.autoscrollInterval)
                    clearInterval(this.autoscrollInterval);
                this.autoscrollInterval = null;
                const onEnd = () => {
                    // Remove the helper from the DOM
                    if (this.helper) {
                        this.helper.remove();
                        this.helper = null;
                    }
                    if (this.hideSortableGhost && this.sortableGhost) {
                        this.sortableGhost.style.visibility = '';
                        this.sortableGhost.style.opacity = '';
                    }
                    resetTransform(nodes);
                    // Update state
                    if (this.hub && !this.hub.isDest(this)) {
                        this.canceling ? this.hub.cancel() : this.hub.handleSortEnd();
                    }
                    else if (this.canceling) {
                        this.$emit('sort-cancel', { event: e });
                    }
                    else {
                        this.$emit('sort-end', {
                            event: e,
                            oldIndex: this.index,
                            newIndex: this.newIndex,
                        });
                        this.$emit('update:list', arrayMove(this.list, this.index, this.newIndex));
                    }
                    this.manager.active = null;
                    this._touched = false;
                    this.canceling = false;
                    this.sorting = false;
                };
                if (this.transitionDuration || this.draggedSettlingDuration) {
                    this.transitionHelperIntoPlace(nodes, onEnd);
                }
                else {
                    onEnd();
                }
            },
            transitionHelperIntoPlace(nodes, cb) {
                if (this.draggedSettlingDuration === 0 || nodes.length === 0 || !this.helper) {
                    return Promise.resolve();
                }
                const indexNode = nodes[this.index].node;
                let targetX = 0;
                let targetY = 0;
                const scrollDifference = {
                    top: window.pageYOffset - this.initialWindowScroll.top,
                    left: window.pageXOffset - this.initialWindowScroll.left,
                };
                if (this.hub && !this.hub.isDest(this) && !this.canceling) {
                    const dest = this.hub.getDest();
                    if (!dest)
                        return;
                    const destIndex = dest.newIndex;
                    const destRefs = dest.manager.getOrderedRefs();
                    const destNode = destIndex < destRefs.length ? destRefs[destIndex].node : dest.sortableGhost;
                    const ancestor = commonOffsetParent(indexNode, destNode);
                    const sourceOffset = getEdgeOffset(indexNode, ancestor);
                    const targetOffset = getEdgeOffset(destNode, ancestor);
                    targetX = targetOffset.left - sourceOffset.left - scrollDifference.left;
                    targetY = targetOffset.top - sourceOffset.top - scrollDifference.top;
                }
                else {
                    const newIndexNode = nodes[this.newIndex].node;
                    const deltaScroll = {
                        left: this.scrollContainer.scrollLeft - this.initialScroll.left + scrollDifference.left,
                        top: this.scrollContainer.scrollTop - this.initialScroll.top + scrollDifference.top,
                    };
                    targetX = -deltaScroll.left;
                    if (this.translate && this.translate.x > 0) {
                        // Diff against right edge when moving to the right
                        targetX +=
                            newIndexNode.offsetLeft + newIndexNode.offsetWidth - (indexNode.offsetLeft + indexNode.offsetWidth);
                    }
                    else {
                        targetX += newIndexNode.offsetLeft - indexNode.offsetLeft;
                    }
                    targetY = -deltaScroll.top;
                    if (this.translate && this.translate.y > 0) {
                        // Diff against the bottom edge when moving down
                        targetY +=
                            newIndexNode.offsetTop + newIndexNode.offsetHeight - (indexNode.offsetTop + indexNode.offsetHeight);
                    }
                    else {
                        targetY += newIndexNode.offsetTop - indexNode.offsetTop;
                    }
                }
                const duration = this.draggedSettlingDuration !== null ? this.draggedSettlingDuration : this.transitionDuration;
                setTransform(this.helper, `translate3d(${targetX}px,${targetY}px, 0)`, `${duration}ms`);
                // Register an event handler to clean up styles when the transition
                // finishes.
                const cleanup = (event) => {
                    if (!event || event.propertyName === 'transform') {
                        clearTimeout(cleanupTimer);
                        setTransform(this.helper);
                        cb();
                    }
                };
                // Force cleanup in case 'transitionend' never fires
                const cleanupTimer = setTimeout(cleanup, duration + 10);
                this.helper.addEventListener('transitionend', cleanup);
            },
            updatePosition(e) {
                const { lockAxis, lockToContainerEdges } = this.$props;
                const offset = getPointerOffset(e);
                const translate = {
                    x: offset.x - this.initialOffset.x,
                    y: offset.y - this.initialOffset.y,
                };
                // Adjust for window scroll
                translate.y -= window.pageYOffset - this.initialWindowScroll.top;
                translate.x -= window.pageXOffset - this.initialWindowScroll.left;
                this.translate = translate;
                if (lockToContainerEdges) {
                    const [minLockOffset, maxLockOffset] = getLockPixelOffsets(this.lockOffset, this.height, this.width);
                    const minOffset = {
                        x: this.width / 2 - minLockOffset.x,
                        y: this.height / 2 - minLockOffset.y,
                    };
                    const maxOffset = {
                        x: this.width / 2 - maxLockOffset.x,
                        y: this.height / 2 - maxLockOffset.y,
                    };
                    if (this.minTranslate.x && this.maxTranslate.x)
                        translate.x = limit(this.minTranslate.x + minOffset.x, this.maxTranslate.x - maxOffset.x, translate.x);
                    if (this.minTranslate.y && this.maxTranslate.y)
                        translate.y = limit(this.minTranslate.y + minOffset.y, this.maxTranslate.y - maxOffset.y, translate.y);
                }
                if (lockAxis === 'x') {
                    translate.y = 0;
                }
                else if (lockAxis === 'y') {
                    translate.x = 0;
                }
                if (this.helper) {
                    this.helper.style['transform'] = `translate3d(${translate.x}px,${translate.y}px, 0)`;
                }
            },
            animateNodes() {
                const { transitionDuration, hideSortableGhost } = this.$props;
                const nodes = this.manager.getOrderedRefs();
                const deltaScroll = {
                    left: this.scrollContainer.scrollLeft - this.initialScroll.left,
                    top: this.scrollContainer.scrollTop - this.initialScroll.top,
                };
                const sortingOffset = {
                    left: this.offsetEdge.left + this.translate.x + deltaScroll.left,
                    top: this.offsetEdge.top + this.translate.y + deltaScroll.top,
                };
                const scrollDifference = {
                    top: window.pageYOffset - this.initialWindowScroll.top,
                    left: window.pageXOffset - this.initialWindowScroll.left,
                };
                this.newIndex = null;
                for (let i = 0, len = nodes.length; i < len; i++) {
                    const { node } = nodes[i];
                    const index = node.sortableInfo.index;
                    const width = node.offsetWidth;
                    const height = node.offsetHeight;
                    const offset = {
                        width: this.width > width ? width / 2 : this.width / 2,
                        height: this.height > height ? height / 2 : this.height / 2,
                    };
                    const translate = {
                        x: 0,
                        y: 0,
                    };
                    let { edgeOffset } = nodes[i];
                    // If we haven't cached the node's offsetTop / offsetLeft value
                    if (!edgeOffset) {
                        nodes[i].edgeOffset = edgeOffset = getEdgeOffset(node, this.container);
                    }
                    // Get a reference to the next and previous node
                    const nextNode = i < nodes.length - 1 && nodes[i + 1];
                    const prevNode = i > 0 && nodes[i - 1];
                    // Also cache the next node's edge offset if needed.
                    // We need this for calculating the animation in a grid setup
                    if (nextNode && !nextNode.edgeOffset) {
                        nextNode.edgeOffset = getEdgeOffset(nextNode.node, this.container);
                    }
                    // If the node is the one we're currently animating, skip it
                    if (index === this.index) {
                        /*
                         * With windowing libraries such as `react-virtualized`, the sortableGhost
                         * node may change while scrolling down and then back up (or vice-versa),
                         * so we need to update the reference to the new node just to be safe.
                         */
                        if (hideSortableGhost) {
                            this.sortableGhost = node;
                            node.style.visibility = 'hidden';
                            node.style.opacity = '0';
                        }
                        continue;
                    }
                    if (transitionDuration) {
                        node.style['transitionDuration'] = `${transitionDuration}ms`;
                    }
                    if (this._axis.x) {
                        if (this._axis.y) {
                            // Calculations for a grid setup
                            if (index < this.index &&
                                ((sortingOffset.left + scrollDifference.left - offset.width <= edgeOffset.left &&
                                    sortingOffset.top + scrollDifference.top <= edgeOffset.top + offset.height) ||
                                    sortingOffset.top + scrollDifference.top + offset.height <= edgeOffset.top)) {
                                // If the current node is to the left on the same row, or above the node that's being dragged
                                // then move it to the right
                                translate.x = this.width + this.marginOffset.x;
                                if (edgeOffset.left + translate.x > this.containerBoundingRect.width - offset.width && nextNode) {
                                    // If it moves passed the right bounds, then animate it to the first position of the next row.
                                    // We just use the offset of the next node to calculate where to move, because that node's original position
                                    // is exactly where we want to go
                                    translate.x = nextNode.edgeOffset.left - edgeOffset.left;
                                    translate.y = nextNode.edgeOffset.top - edgeOffset.top;
                                }
                                if (this.newIndex === null) {
                                    this.newIndex = index;
                                }
                            }
                            else if (index > this.index &&
                                ((sortingOffset.left + scrollDifference.left + offset.width >= edgeOffset.left &&
                                    sortingOffset.top + scrollDifference.top + offset.height >= edgeOffset.top) ||
                                    sortingOffset.top + scrollDifference.top + offset.height >= edgeOffset.top + height)) {
                                // If the current node is to the right on the same row, or below the node that's being dragged
                                // then move it to the left
                                translate.x = -(this.width + this.marginOffset.x);
                                if (edgeOffset.left + translate.x < this.containerBoundingRect.left + offset.width && prevNode) {
                                    // If it moves passed the left bounds, then animate it to the last position of the previous row.
                                    // We just use the offset of the previous node to calculate where to move, because that node's original position
                                    // is exactly where we want to go
                                    translate.x = prevNode.edgeOffset.left - edgeOffset.left;
                                    translate.y = prevNode.edgeOffset.top - edgeOffset.top;
                                }
                                this.newIndex = index;
                            }
                        }
                        else {
                            if (index > this.index && sortingOffset.left + scrollDifference.left + offset.width >= edgeOffset.left) {
                                translate.x = -(this.width + this.marginOffset.x);
                                this.newIndex = index;
                            }
                            else if (index < this.index &&
                                sortingOffset.left + scrollDifference.left <= edgeOffset.left + offset.width) {
                                translate.x = this.width + this.marginOffset.x;
                                if (this.newIndex == null) {
                                    this.newIndex = index;
                                }
                            }
                        }
                    }
                    else if (this._axis.y) {
                        if (index > this.index && sortingOffset.top + scrollDifference.top + offset.height >= edgeOffset.top) {
                            translate.y = -(this.height + this.marginOffset.y);
                            this.newIndex = index;
                        }
                        else if (index < this.index &&
                            sortingOffset.top + scrollDifference.top <= edgeOffset.top + offset.height) {
                            translate.y = this.height + this.marginOffset.y;
                            if (this.newIndex == null) {
                                this.newIndex = index;
                            }
                        }
                    }
                    node.style['transform'] = `translate3d(${translate.x}px,${translate.y}px,0)`;
                }
                if (this.newIndex == null) {
                    this.newIndex = this.index;
                }
            },
            autoscroll() {
                const translate = this.translate;
                const direction = {
                    x: 0,
                    y: 0,
                };
                const speed = {
                    x: 1,
                    y: 1,
                };
                const acceleration = {
                    x: 10,
                    y: 10,
                };
                if (translate.y >= this.maxTranslate.y - this.height / 2) {
                    direction.y = 1; // Scroll Down
                    speed.y = acceleration.y * Math.abs((this.maxTranslate.y - this.height / 2 - translate.y) / this.height);
                }
                else if (translate.x >= this.maxTranslate.x - this.width / 2) {
                    direction.x = 1; // Scroll Right
                    speed.x = acceleration.x * Math.abs((this.maxTranslate.x - this.width / 2 - translate.x) / this.width);
                }
                else if (translate.y <= this.minTranslate.y + this.height / 2) {
                    direction.y = -1; // Scroll Up
                    speed.y = acceleration.y * Math.abs((translate.y - this.height / 2 - this.minTranslate.y) / this.height);
                }
                else if (translate.x <= this.minTranslate.x + this.width / 2) {
                    direction.x = -1; // Scroll Left
                    speed.x = acceleration.x * Math.abs((translate.x - this.width / 2 - this.minTranslate.x) / this.width);
                }
                if (this.autoscrollInterval) {
                    clearInterval(this.autoscrollInterval);
                    this.autoscrollInterval = null;
                }
                if (direction.x !== 0 || direction.y !== 0) {
                    this.autoscrollInterval = window.setInterval(() => {
                        const offset = {
                            left: 1 * speed.x * direction.x,
                            top: 1 * speed.y * direction.y,
                        };
                        if (this.useWindowAsScrollContainer) {
                            this._window.scrollBy(offset.left, offset.top);
                        }
                        else {
                            this.scrollContainer.scrollTop += offset.top;
                            this.scrollContainer.scrollLeft += offset.left;
                        }
                        this.translate.x += offset.left;
                        this.translate.y += offset.top;
                        this.animateNodes();
                    }, 5);
                }
            },
        },
    });

    // Export Sortable Element Handle Directive
    const HandleDirective = {
        beforeMount(el) {
            el.sortableHandle = true;
        },
    };

    const SlickItem = vue.defineComponent({
        name: 'SlickItem',
        mixins: [ElementMixin],
        props: {
            tag: {
                type: String,
                default: 'div',
            },
        },
        render() {
            var _a, _b;
            return vue.h(this.tag, (_b = (_a = this.$slots).default) === null || _b === void 0 ? void 0 : _b.call(_a));
        },
    });

    const SlickList = vue.defineComponent({
        name: 'SlickList',
        mixins: [ContainerMixin],
        props: {
            tag: {
                type: String,
                default: 'div',
            },
            itemKey: {
                type: [String, Function],
                default: 'id',
            },
        },
        render() {
            var _a, _b;
            if (this.$slots.item) {
                return vue.h(this.tag, this.list.map((item, index) => {
                    let key;
                    if (item == null) {
                        return;
                    }
                    else if (typeof this.itemKey === 'function') {
                        key = this.itemKey(item);
                    }
                    else if (typeof item === 'object' &&
                        hasOwnProperty(item, this.itemKey) &&
                        typeof item[this.itemKey] == 'string') {
                        key = item[this.itemKey];
                    }
                    else if (typeof item === 'string') {
                        key = item;
                    }
                    else {
                        throw new Error('Cannot find key for item, use the item-key prop and pass a function or string');
                    }
                    return vue.h(SlickItem, {
                        key,
                        index,
                    }, {
                        default: () => { var _a, _b; return (_b = (_a = this.$slots).item) === null || _b === void 0 ? void 0 : _b.call(_a, { item, index }); },
                    });
                }));
            }
            return vue.h(this.tag, (_b = (_a = this.$slots).default) === null || _b === void 0 ? void 0 : _b.call(_a));
        },
    });

    const DragHandle = vue.defineComponent({
        props: {
            tag: {
                type: String,
                default: 'span',
            },
        },
        mounted() {
            this.$el.sortableHandle = true;
        },
        render() {
            var _a, _b;
            return vue.h(this.tag, (_b = (_a = this.$slots).default) === null || _b === void 0 ? void 0 : _b.call(_a));
        },
    });

    let containerIDCounter = 1;
    /**
     * Always allow when dest === source
     * Defer to 'dest.accept()' if it is a function
     * Allow any group in the accept lists
     * Deny any group in the block list
     * Allow the same group by default, this can be overridden with the block prop
     */
    function canAcceptElement(dest, source, payload) {
        if (source.id === dest.id)
            return true;
        if (dest.block && dest.block.includes(source.group))
            return false;
        if (typeof dest.accept === 'function') {
            return dest.accept({ dest, source, payload });
        }
        if (typeof dest.accept === 'boolean') {
            return dest.accept;
        }
        if (dest.accept && dest.accept.includes(source.group))
            return true;
        if (dest.group === source.group)
            return true;
        return false;
    }
    function findClosestDest({ x, y }, refs, currentDest) {
        // Quickly check if we are within the bounds of the current destination
        if (isPointWithinRect({ x, y }, currentDest.container.getBoundingClientRect())) {
            return currentDest;
        }
        let closest = null;
        let minDistance = Infinity;
        for (let i = 0; i < refs.length; i++) {
            const ref = refs[i];
            const rect = ref.container.getBoundingClientRect();
            const isWithin = isPointWithinRect({ x, y }, rect);
            if (isWithin) {
                // If we are within another destination, stop here
                return ref;
            }
            const center = getRectCenter(rect);
            const distance = getDistance(x, y, center.x, center.y);
            if (distance < minDistance) {
                closest = ref;
                minDistance = distance;
            }
        }
        // Try to guess the closest destination
        return closest;
    }
    class SlicksortHub {
        constructor() {
            this.helper = null;
            this.ghost = null;
            this.refs = [];
            this.source = null;
            this.dest = null;
        }
        getId() {
            return '' + containerIDCounter++;
        }
        isSource({ id }) {
            var _a;
            return ((_a = this.source) === null || _a === void 0 ? void 0 : _a.id) === id;
        }
        getSource() {
            return this.source;
        }
        isDest({ id }) {
            var _a;
            return ((_a = this.dest) === null || _a === void 0 ? void 0 : _a.id) === id;
        }
        getDest() {
            return this.dest;
        }
        addContainer(ref) {
            this.refs.push(ref);
        }
        removeContainer(ref) {
            this.refs = this.refs.filter((c) => c.id !== ref.id);
        }
        sortStart(ref) {
            this.source = ref;
            this.dest = ref;
        }
        handleSortMove(e, payload) {
            var _a, _b, _c, _d;
            const dest = this.dest;
            const source = this.source;
            if (!dest || !source)
                return;
            const refs = this.refs;
            const pointer = getPointerOffset(e, 'client');
            const newDest = findClosestDest(pointer, refs, dest) || dest;
            if (dest.id !== newDest.id && canAcceptElement(newDest, source, payload)) {
                this.dest = newDest;
                dest.handleDragOut();
                newDest.handleDragIn(e, this.ghost, this.helper);
            }
            if (dest.id !== ((_a = this.source) === null || _a === void 0 ? void 0 : _a.id)) {
                (_b = this.dest) === null || _b === void 0 ? void 0 : _b.updatePosition(e);
                (_c = this.dest) === null || _c === void 0 ? void 0 : _c.animateNodes();
                (_d = this.dest) === null || _d === void 0 ? void 0 : _d.autoscroll();
            }
        }
        handleSortEnd() {
            var _a, _b, _c, _d;
            if (((_a = this.source) === null || _a === void 0 ? void 0 : _a.id) === ((_b = this.dest) === null || _b === void 0 ? void 0 : _b.id))
                return;
            const payload = (_c = this.source) === null || _c === void 0 ? void 0 : _c.handleDropOut();
            (_d = this.dest) === null || _d === void 0 ? void 0 : _d.handleDropIn(payload);
            this.reset();
        }
        reset() {
            this.source = null;
            this.dest = null;
            this.helper = null;
            this.ghost = null;
        }
        cancel() {
            var _a;
            (_a = this.dest) === null || _a === void 0 ? void 0 : _a.handleDragEnd();
            this.reset();
        }
    }

    const plugin = {
        install(app) {
            app.directive('drag-handle', HandleDirective);
            app.provide('SlicksortHub', new SlicksortHub());
        },
    };

    exports.ContainerMixin = ContainerMixin;
    exports.DragHandle = DragHandle;
    exports.ElementMixin = ElementMixin;
    exports.HandleDirective = HandleDirective;
    exports.SlickItem = SlickItem;
    exports.SlickList = SlickList;
    exports.arrayMove = arrayMove;
    exports.plugin = plugin;

    Object.defineProperty(exports, '__esModule', { value: true });

})));


/***/ }),

/***/ 26:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(607);
if(content.__esModule) content = content.default;
if(typeof content === 'string') content = [[module.id, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var add = (__webpack_require__(402)/* ["default"] */ .Z)
var update = add("4d3e487a", content, true, {"sourceMap":false,"shadowMode":false});

/***/ }),

/***/ 628:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(685);
if(content.__esModule) content = content.default;
if(typeof content === 'string') content = [[module.id, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var add = (__webpack_require__(402)/* ["default"] */ .Z)
var update = add("0b3e8540", content, true, {"sourceMap":false,"shadowMode":false});

/***/ }),

/***/ 402:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";

// EXPORTS
__webpack_require__.d(__webpack_exports__, {
  "Z": () => (/* binding */ addStylesClient)
});

;// CONCATENATED MODULE: ./node_modules/vue-style-loader/lib/listToStyles.js
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

;// CONCATENATED MODULE: ./node_modules/vue-style-loader/lib/addStylesClient.js
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

/***/ 797:
/***/ ((module) => {

"use strict";
module.exports = require("vue");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			id: moduleId,
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/publicPath */
/******/ 	(() => {
/******/ 		__webpack_require__.p = "";
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

// EXPORTS
__webpack_require__.d(__webpack_exports__, {
  "Components": () => (/* reexport */ Components),
  "Directives": () => (/* reexport */ Directives),
  "Filters": () => (/* reexport */ Filters),
  "Plugins": () => (/* reexport */ Plugins),
  "Util": () => (/* reexport */ Util)
});

;// CONCATENATED MODULE: ./node_modules/@vue/cli-service/lib/commands/build/setPublicPath.js
/* eslint-disable no-var */
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
/* harmony default export */ const setPublicPath = (null);

// EXTERNAL MODULE: external {"commonjs":"vue","commonjs2":"vue","root":"Vue"}
var external_commonjs_vue_commonjs2_vue_root_Vue_ = __webpack_require__(797);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/message-toast.vue?vue&type=template&id=fd1e9b62


function render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", {
    id: "messageBox",
    class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)([{ 'display': $props.active }, $props.classname, 'toast'])
  }, [
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("button", {
      class: "btn btn-clear float-right",
      onClick: _cache[0] || (_cache[0] = $event => (_ctx.$emit('cancel')))
    }),
    ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(true), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_.Fragment, null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.renderList)($options.lines, (line) => {
      return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)(line), 1))
    }), 256))
  ], 2))
}
;// CONCATENATED MODULE: ./vue/components/vx-vue/message-toast.vue?vue&type=template&id=fd1e9b62

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/message-toast.vue?vue&type=script&lang=js

/* harmony default export */ const message_toastvue_type_script_lang_js = ({
  name: 'message-toast',
  emits: ['timeout', 'cancel'],
  props: {
    message: [String, Array],
    classname: String,
    timeout: {type: Number, default: 5000},
    active: {type: Boolean, default: false}
  },
  data() {
    return {
      activeTimeout: null
    }
  },
  computed: {
    lines() {
      return typeof this.message === 'string' ? [this.message] : this.message;
    }
  },
  watch: {
    active() {
      this.setTimeout();
    }
  },

  mounted() {
    this.setTimeout();
  },

  methods: {
    setTimeout() {
      window.clearTimeout(this.activeTimeout);

      // timeout 0 disables fadeout

      if (this.active && this.timeout) {
        this.activeTimeout = window.setTimeout(() => {
          this.$emit('timeout');
        }, this.timeout);
      }
    }
  }
});

;// CONCATENATED MODULE: ./vue/components/vx-vue/message-toast.vue?vue&type=script&lang=js
 
// EXTERNAL MODULE: ./node_modules/vue-loader/dist/exportHelper.js
var exportHelper = __webpack_require__(744);
;// CONCATENATED MODULE: ./vue/components/vx-vue/message-toast.vue




;
const __exports__ = /*#__PURE__*/(0,exportHelper/* default */.Z)(message_toastvue_type_script_lang_js, [['render',render]])

/* harmony default export */ const message_toast = (__exports__);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/circular-progress.vue?vue&type=template&id=2300c3e9


const _hoisted_1 = ["height", "width"]
const _hoisted_2 = ["stroke-width", "r", "cx", "cy"]
const _hoisted_3 = ["stroke", "stroke-dasharray", "stroke-width", "r", "cx", "cy"]

function circular_progressvue_type_template_id_2300c3e9_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("svg", {
    height: $options.size,
    width: $options.size,
    class: "circular-progress"
  }, [
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("circle", {
      stroke: "white",
      fill: "transparent",
      "stroke-width": $props.strokeWidth,
      r: $options.normalizedRadius,
      cx: $props.radius,
      cy: $props.radius
    }, null, 8, _hoisted_2),
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("circle", {
      stroke: $props.color,
      fill: "transparent",
      "stroke-dasharray": $options.circumference + ' ' + $options.circumference,
      style: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeStyle)({ strokeDashoffset: $options.strokeDashoffset }),
      "stroke-width": $props.strokeWidth,
      r: $options.normalizedRadius,
      cx: $props.radius,
      cy: $props.radius
    }, null, 12, _hoisted_3)
  ], 8, _hoisted_1))
}
;// CONCATENATED MODULE: ./vue/components/circular-progress.vue?vue&type=template&id=2300c3e9

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/circular-progress.vue?vue&type=script&lang=js

/* harmony default export */ const circular_progressvue_type_script_lang_js = ({
  name: "circular-progress",

  props: {
    progress: Number,
    radius: {type: Number, default: 24},
    strokeWidth: {type: Number, default: 8},
    color: {type: String, default: 'white'}
  },

  computed: {
    size() {
      return 2 * this.radius;
    },
    normalizedRadius() {
      return this.radius - this.strokeWidth / 2;
    },
    circumference() {
      return this.normalizedRadius * 2 * Math.PI;
    },
    strokeDashoffset() {
      return this.circumference - this.progress / 100 * this.circumference;
    }
  }
});

;// CONCATENATED MODULE: ./vue/components/circular-progress.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/circular-progress.vue




;
const circular_progress_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(circular_progressvue_type_script_lang_js, [['render',circular_progressvue_type_template_id_2300c3e9_render]])

/* harmony default export */ const circular_progress = (circular_progress_exports_);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/autocomplete.vue?vue&type=template&id=1e90eeda


function autocompletevue_type_template_id_1e90eeda_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_autocomplete_input = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.resolveComponent)("autocomplete-input")

  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", (0,external_commonjs_vue_commonjs2_vue_root_Vue_.mergeProps)($options.containerProps, { ref: "container" }), [
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createVNode)(_component_autocomplete_input, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.mergeProps)({
      ref: "input",
      value: $props.modelValue
    }, $options.inputProps, {
      onInput: _cache[0] || (_cache[0] = $event => ($options.handleInput($event.target.value))),
      onKeydown: [
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withKeys)($options.handleEnter, ["enter"]),
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withKeys)($options.handleEsc, ["esc"]),
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withKeys)($options.handleTab, ["tab"]),
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withKeys)((0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)($options.handleUp, ["prevent"]), ["up"]),
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withKeys)((0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)($options.handleDown, ["prevent"]), ["down"])
      ],
      onFocus: $options.handleFocus,
      onBlur: $options.handleBlur
    }), null, 16, ["value", "onKeydown", "onFocus", "onBlur"]),
    ($data.results.length)
      ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("ul", (0,external_commonjs_vue_commonjs2_vue_root_Vue_.mergeProps)({
          key: 0,
          ref: "resultList"
        }, $options.resultListProps, {
          onClick: _cache[1] || (_cache[1] = (...args) => ($options.handleResultClick && $options.handleResultClick(...args))),
          onMousedown: _cache[2] || (_cache[2] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)(() => {}, ["prevent"]))
        }), [
          ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(true), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_.Fragment, null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.renderList)($data.results, (result, index) => {
            return (0,external_commonjs_vue_commonjs2_vue_root_Vue_.renderSlot)(_ctx.$slots, "result", {
              result: result,
              props: $options.resultProps[index]
            }, () => [
              ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("li", (0,external_commonjs_vue_commonjs2_vue_root_Vue_.mergeProps)({
                key: $options.resultProps[index].id
              }, $options.resultProps[index]), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($props.getResultValue(result)), 17))
            ])
          }), 256))
        ], 16))
      : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true)
  ], 16))
}
;// CONCATENATED MODULE: ./vue/components/vx-vue/autocomplete.vue?vue&type=template&id=1e90eeda

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/formelements/form-input.vue?vue&type=template&id=05532945


const form_inputvue_type_template_id_05532945_hoisted_1 = ["value"]

function form_inputvue_type_template_id_05532945_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("input", {
    value: $props.modelValue,
    class: "form-input",
    onInput: _cache[0] || (_cache[0] = $event => (_ctx.$emit('update:modelValue', $event.target.value)))
  }, null, 40, form_inputvue_type_template_id_05532945_hoisted_1))
}
;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-input.vue?vue&type=template&id=05532945

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/formelements/form-input.vue?vue&type=script&lang=js

  /* harmony default export */ const form_inputvue_type_script_lang_js = ({
    name: 'form-input',
    props: ['modelValue'],
    emits: ['update:modelValue']
  });

;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-input.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-input.vue




;
const form_input_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(form_inputvue_type_script_lang_js, [['render',form_inputvue_type_template_id_05532945_render]])

/* harmony default export */ const form_input = (form_input_exports_);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/autocomplete.vue?vue&type=script&lang=js

  

  let uniqueId = function() {
    let counter = 0;
    return function(prefix) {
      return (prefix || "") + ++counter;
    }
  }();

  /* harmony default export */ const autocompletevue_type_script_lang_js = ({
    name: 'Autocomplete',
    inheritAttrs: false,

    components: {
      'autocomplete-input': form_input,
    },
    emits: ['update:modelValue', 'blur', 'submit'],
    props: {
      modelValue: {
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

    beforeUnmount() {
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
        this.$emit('update:modelValue', value);
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
        this.$emit('update:modelValue', '');
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
          this.$emit('update:modelValue', this.getResultValue(selectedResult));
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

;// CONCATENATED MODULE: ./vue/components/vx-vue/autocomplete.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/vx-vue/autocomplete.vue




;
const autocomplete_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(autocompletevue_type_script_lang_js, [['render',autocompletevue_type_template_id_1e90eeda_render]])

/* harmony default export */ const autocomplete = (autocomplete_exports_);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/formelements/datepicker.vue?vue&type=template&id=10a22640


const datepickervue_type_template_id_10a22640_hoisted_1 = { class: "calendar-nav navbar" }
const datepickervue_type_template_id_10a22640_hoisted_2 = { class: "month navbar-primary" }
const datepickervue_type_template_id_10a22640_hoisted_3 = { class: "calendar-container" }
const _hoisted_4 = { class: "calendar-header" }
const _hoisted_5 = { class: "calendar-date" }
const _hoisted_6 = { class: "calendar-body" }
const _hoisted_7 = ["disabled", "onClick"]

function datepickervue_type_template_id_10a22640_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_date_input = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.resolveComponent)("date-input")

  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeProps)((0,external_commonjs_vue_commonjs2_vue_root_Vue_.guardReactiveProps)($options.rootProps)), [
    ($props.hasInput)
      ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createBlock)(_component_date_input, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.mergeProps)({
          key: 0,
          modelValue: $data.selectedDate,
          "onUpdate:modelValue": _cache[0] || (_cache[0] = $event => (($data.selectedDate) = $event)),
          onToggleDatepicker: $options.toggleDatepicker
        }, $options.inputProps, { ref: "input" }), null, 16, ["modelValue", "onToggleDatepicker"]))
      : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true),
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", (0,external_commonjs_vue_commonjs2_vue_root_Vue_.mergeProps)({ class: "calendar" }, $options.calendarProps, {
      ref: "calendar",
      class: $data.align === 'left' ? 'align-left' : 'align-right'
    }), [
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", datepickervue_type_template_id_10a22640_hoisted_1, [
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("button", {
          class: "btn btn-action btn-link btn-large prvMon",
          onClick: _cache[1] || (_cache[1] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)((...args) => ($options.previousMonth && $options.previousMonth(...args)), ["stop"]))
        }),
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", datepickervue_type_template_id_10a22640_hoisted_2, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($options.monthLabel) + " " + (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($data.sheetDate.getFullYear()), 1),
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("button", {
          class: "btn btn-action btn-link btn-large nxtMon",
          onClick: _cache[2] || (_cache[2] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)((...args) => ($options.nextMonth && $options.nextMonth(...args)), ["stop"]))
        })
      ]),
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", datepickervue_type_template_id_10a22640_hoisted_3, [
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", _hoisted_4, [
          ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(true), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_.Fragment, null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.renderList)($props.weekdays, (weekday) => {
            return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", _hoisted_5, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)(weekday), 1))
          }), 256))
        ]),
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", _hoisted_6, [
          ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(true), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_.Fragment, null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.renderList)($options.days, (day) => {
            return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", {
              class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(["calendar-date text-center", ['prev-month', '', 'next-month'][day.getMonth() - $data.sheetDate.getMonth() + 1]])
            }, [
              (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("button", {
                type: "button",
                class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(["date-item", {
                  'active': $data.selectedDate && day.getTime() === $data.selectedDate.getTime(),
                  'date-today': day.getTime() === $options.today.getTime()
                }]),
                disabled: ($props.validFrom && $props.validFrom) > day || ($props.validUntil && $props.validUntil < day),
                onClick: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)($event => (($props.validFrom && $props.validFrom) > day || ($props.validUntil && $props.validUntil < day) ? null : $options.selectDate(day)), ["stop"])
              }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)(day.getDate()), 11, _hoisted_7)
            ], 2))
          }), 256))
        ])
      ])
    ], 16)
  ], 16))
}
;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/datepicker.vue?vue&type=template&id=10a22640

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/formelements/date-input.vue?vue&type=template&id=009accae


const date_inputvue_type_template_id_009accae_hoisted_1 = { class: "date-input" }
const date_inputvue_type_template_id_009accae_hoisted_2 = {
  key: 0,
  class: "form-input"
}
const date_inputvue_type_template_id_009accae_hoisted_3 = { class: "chip" }

function date_inputvue_type_template_id_009accae_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", date_inputvue_type_template_id_009accae_hoisted_1, [
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", {
      class: "input-group",
      style: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeStyle)($options.computedStyles)
    }, [
      ($options.dateString)
        ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", date_inputvue_type_template_id_009accae_hoisted_2, [
            (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("span", date_inputvue_type_template_id_009accae_hoisted_3, [
              (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createTextVNode)((0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($options.dateString) + " ", 1),
              (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("a", {
                href: "#",
                class: "btn btn-clear",
                "aria-label": "Close",
                role: "button",
                onClick: _cache[0] || (_cache[0] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)((...args) => ($options.handleClear && $options.handleClear(...args)), ["prevent"]))
              })
            ])
          ]))
        : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withDirectives)(((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("input", {
            key: 1,
            type: "text",
            class: "form-input",
            autocomplete: "off",
            "onUpdate:modelValue": _cache[1] || (_cache[1] = $event => (($data.inputString) = $event)),
            onBlur: _cache[2] || (_cache[2] = (...args) => ($options.handleBlur && $options.handleBlur(...args))),
            onInput: _cache[3] || (_cache[3] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)(() => {}, ["stop"]))
          }, null, 544)), [
            [external_commonjs_vue_commonjs2_vue_root_Vue_.vModelText, $data.inputString]
          ]),
      ($props.showButton)
        ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("button", {
            key: 2,
            type: "button",
            class: "btn webfont-icon-only btn-primary input-group-btn",
            onClick: _cache[4] || (_cache[4] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)($event => (_ctx.$emit('toggle-datepicker')), ["stop"]))
          }, " "))
        : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true)
    ], 4)
  ]))
}
;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/date-input.vue?vue&type=template&id=009accae

;// CONCATENATED MODULE: ./vue/util/date-functions.js
/* harmony default export */ const date_functions = ({

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
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/formelements/date-input.vue?vue&type=script&lang=js



/* harmony default export */ const date_inputvue_type_script_lang_js = ({
  name: 'date-input',
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
    modelValue: Date
  },

  watch: {
    modelValue(value) {
      this.inputString = value ? date_functions.formatDate(value, this.outputFormat) : '';
    }
  },

  computed: {
    dateString() {
      return this.modelValue ? date_functions.formatDate(this.modelValue, this.outputFormat) : '';
    },
    computedStyles() {
      return {
        width: '100%'
      }
    }
  },

  methods: {
    handleBlur() {
      let date = date_functions.parseDate(this.inputString, this.inputFormat);
      this.$emit('update:modelValue', date || null);
    },
    handleClear() {
      this.$emit('update:modelValue', null);
    }
  }
});

;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/date-input.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/date-input.vue




;
const date_input_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(date_inputvue_type_script_lang_js, [['render',date_inputvue_type_template_id_009accae_render]])

/* harmony default export */ const date_input = (date_input_exports_);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/formelements/datepicker.vue?vue&type=script&lang=js



/* harmony default export */ const datepickervue_type_script_lang_js = ({
  name: 'date-picker',
  components: {
    DateInput: date_input
  },
  emits: ['update:modelValue', 'month-change'],

  data() {
    return {
      sheetDate: null,
      selectedDate: null,
      expanded: !this.hasInput,
      align: 'left'
    };
  },

  watch: {
    modelValue(newValue) {
      if (newValue) {
        this.selectedDate = new Date(newValue.getFullYear(), newValue.getMonth(), newValue.getDate()); // crop any timedata
        this.sheetDate = new Date(this.selectedDate.getTime());
      }
      else {
        this.selectedDate = null;
        this.sheetDate = this.today;
      }
      this.sheetDate.setDate(1);
    },
    expanded(newValue) {
      if (newValue && this.hasInput) {
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
        style: {position: 'relative'}
      }
    },
    inputProps() {
      return {
        style: {position: 'relative'},
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
          display: this.expanded ? 'block' : 'none',
          position: 'absolute',
          top: '100%',
          transform: 'translateY(.2rem)',
          'z-index': 300
        } : {}
      }
    },
    days() {
      const dates = [], yr = this.sheetDate.getFullYear(), mon = this.sheetDate.getMonth();
      const nextMonth = new Date(yr, mon + 1, 0);
      const preceedingDays = (new Date(yr, mon, 0)).getDay() + 1 - this.startOfWeekIndex;
      const trailingDays = (7 - nextMonth.getDay()) % 7 - 1 + this.startOfWeekIndex;

      for (let i = -preceedingDays, j = nextMonth.getDate() + trailingDays; i < j; ++i) {
        dates.push(new Date(yr, mon, i + 1));
      }

      return (dates);
    },
    monthLabel() {
      return this.monthNames[this.sheetDate.getMonth()];
    },
    today() {
      const now = new Date();
      return new Date(now.getFullYear(), now.getMonth(), now.getDate());
    }
  },

  props: {
    modelValue: Date,
    validFrom: Date,
    validUntil: Date,
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
  created () {
    this.sheetDate = new Date(this.today.getTime());
    this.sheetDate.setDate(1);
  },
  mounted() {
    if (this.hasInput) {
      document.body.addEventListener('click', this.handleBodyClick);
    }
    if (this.modelValue) {
      this.sheetDate = new Date(this.modelValue.getTime());
      this.sheetDate.setDate(1);
      this.selectedDate = new Date(this.modelValue.getFullYear(), this.modelValue.getMonth(), this.modelValue.getDate());
    }
  },
  beforeUnmount() {
    document.body.removeEventListener('click', this.handleBodyClick);
  },

  methods: {
    previousMonth() {
      this.sheetDate = new Date(this.sheetDate.getFullYear(), this.sheetDate.getMonth() - 1, 1);
      this.$emit("month-change", this.sheetDate);
    },
    nextMonth() {
      this.sheetDate = new Date(this.sheetDate.getFullYear(), this.sheetDate.getMonth() + 1, 1);
      this.$emit("month-change", this.sheetDate);
    },
    selectDate(day) {
      this.selectedDate = day;
      this.$emit('update:modelValue', day);
      this.expanded = !this.hasInput;
    },
    toggleDatepicker() {
      this.expanded = !this.expanded;
    },
    handleBodyClick() {
      this.expanded = false;
    },
    handleInput(date) {
      this.selectedDate = date;
      this.$emit('update:modelValue', date);
    }
  }
});

;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/datepicker.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/datepicker.vue




;
const datepicker_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(datepickervue_type_script_lang_js, [['render',datepickervue_type_template_id_10a22640_render]])

/* harmony default export */ const datepicker = (datepicker_exports_);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/sortable.vue?vue&type=template&id=d2ff1a72&scoped=true


const _withScopeId = n => (_pushScopeId("data-v-d2ff1a72"),n=n(),_popScopeId(),n)
const sortablevue_type_template_id_d2ff1a72_scoped_true_hoisted_1 = { class: "table table-striped" }
const sortablevue_type_template_id_d2ff1a72_scoped_true_hoisted_2 = ["onClick"]

function sortablevue_type_template_id_d2ff1a72_scoped_true_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("table", sortablevue_type_template_id_d2ff1a72_scoped_true_hoisted_1, [
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("thead", null, [
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("tr", null, [
        ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(true), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_.Fragment, null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.renderList)($props.columns, (column) => {
          return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("th", {
            class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)([
                    { 'sortable-header': column.sortable },
                    column.cssClass,
                    $data.sortColumn === column ? $data.sortDir : null,
                    column.width
                ]),
            onClick: $event => (column.sortable ? $options.clickSort(column) : null)
          }, [
            (0,external_commonjs_vue_commonjs2_vue_root_Vue_.renderSlot)(_ctx.$slots, column.prop + '-header', { column: column }, () => [
              (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createTextVNode)((0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)(column.label), 1)
            ], true)
          ], 10, sortablevue_type_template_id_d2ff1a72_scoped_true_hoisted_2))
        }), 256))
      ])
    ]),
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("tbody", null, [
      ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(true), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_.Fragment, null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.renderList)($options.sortedRows, (row) => {
        return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("tr", {
          key: row[$props.keyProperty],
          class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(row.cssClass)
        }, [
          ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(true), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_.Fragment, null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.renderList)($props.columns, (column) => {
            return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("td", {
              class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)({ 'active': $data.sortColumn === column })
            }, [
              (0,external_commonjs_vue_commonjs2_vue_root_Vue_.renderSlot)(_ctx.$slots, column.prop, { row: row }, () => [
                (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createTextVNode)((0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)(row[column.prop]), 1)
              ], true)
            ], 2))
          }), 256))
        ], 2))
      }), 128))
    ])
  ]))
}
;// CONCATENATED MODULE: ./vue/components/vx-vue/sortable.vue?vue&type=template&id=d2ff1a72&scoped=true

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/sortable.vue?vue&type=script&lang=js

    /* harmony default export */ const sortablevue_type_script_lang_js = ({
        name: 'sortable',
        emits: ['before-sort', 'after-sort'],
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
                sortColumn: this.columns.find(({prop}) => prop === this.sortProp),
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
                    this.sortDir = this.sortDir || 'asc';
                }
                this.$nextTick( () => this.$emit('after-sort') );
            }
        }
    });

;// CONCATENATED MODULE: ./vue/components/vx-vue/sortable.vue?vue&type=script&lang=js
 
// EXTERNAL MODULE: ./node_modules/vue-style-loader/index.js??clonedRuleSet-22.use[0]!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-22.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-22.use[2]!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-22.use[3]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-22.use[4]!./node_modules/style-resources-loader/lib/index.js??clonedRuleSet-22.use[5]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/sortable.vue?vue&type=style&index=0&id=d2ff1a72&scoped=true&lang=scss
var sortablevue_type_style_index_0_id_d2ff1a72_scoped_true_lang_scss = __webpack_require__(628);
;// CONCATENATED MODULE: ./vue/components/vx-vue/sortable.vue?vue&type=style&index=0&id=d2ff1a72&scoped=true&lang=scss

;// CONCATENATED MODULE: ./vue/components/vx-vue/sortable.vue




;


const sortable_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(sortablevue_type_script_lang_js, [['render',sortablevue_type_template_id_d2ff1a72_scoped_true_render],['__scopeId',"data-v-d2ff1a72"]])

/* harmony default export */ const sortable = (sortable_exports_);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/simple-tree/simple-tree.vue?vue&type=template&id=d2c9e362


const simple_treevue_type_template_id_d2c9e362_hoisted_1 = { class: "vx-tree" }

function simple_treevue_type_template_id_d2c9e362_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_simple_tree_branch = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.resolveComponent)("simple-tree-branch")

  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("ul", simple_treevue_type_template_id_d2c9e362_hoisted_1, [
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createVNode)(_component_simple_tree_branch, {
      branch: $props.branch,
      onBranchSelected: _cache[0] || (_cache[0] = $event => (_ctx.$emit('branch-selected', $event)))
    }, null, 8, ["branch"])
  ]))
}
;// CONCATENATED MODULE: ./vue/components/vx-vue/simple-tree/simple-tree.vue?vue&type=template&id=d2c9e362

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/simple-tree/simple-tree-branch.vue?vue&type=template&id=348e85e6


const simple_tree_branchvue_type_template_id_348e85e6_hoisted_1 = ["id", "checked"]
const simple_tree_branchvue_type_template_id_348e85e6_hoisted_2 = ["for"]
const simple_tree_branchvue_type_template_id_348e85e6_hoisted_3 = { key: 1 }
const simple_tree_branchvue_type_template_id_348e85e6_hoisted_4 = ["href"]
const simple_tree_branchvue_type_template_id_348e85e6_hoisted_5 = { key: 3 }

function simple_tree_branchvue_type_template_id_348e85e6_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_simple_tree_branch = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.resolveComponent)("simple-tree-branch", true)

  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("li", {
    class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)({ 'terminates': !$props.branch.branches || !$props.branch.branches.length })
  }, [
    ($props.branch.branches && $props.branch.branches.length)
      ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_.Fragment, { key: 0 }, [
          (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("input", {
            type: "checkbox",
            id: 'branch-' + $props.branch.id,
            onClick: _cache[0] || (_cache[0] = $event => ($data.expanded = !$data.expanded)),
            checked: $data.expanded
          }, null, 8, simple_tree_branchvue_type_template_id_348e85e6_hoisted_1),
          (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("label", {
            for: 'branch-' + $props.branch.id
          }, null, 8, simple_tree_branchvue_type_template_id_348e85e6_hoisted_2)
        ], 64))
      : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true),
    ($props.branch.current)
      ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("strong", simple_tree_branchvue_type_template_id_348e85e6_hoisted_3, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($props.branch.label), 1))
      : ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("a", {
          key: 2,
          href: $props.branch.path,
          onClick: _cache[1] || (_cache[1] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)($event => (_ctx.$emit('branch-selected', $props.branch)), ["prevent"]))
        }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($props.branch.label), 9, simple_tree_branchvue_type_template_id_348e85e6_hoisted_4)),
    ($props.branch.branches && $props.branch.branches.length)
      ? (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withDirectives)(((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("ul", simple_tree_branchvue_type_template_id_348e85e6_hoisted_5, [
          ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(true), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_.Fragment, null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.renderList)($props.branch.branches, (child) => {
            return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createBlock)(_component_simple_tree_branch, {
              branch: child,
              key: child.id,
              onBranchSelected: _cache[2] || (_cache[2] = $event => (_ctx.$emit('branch-selected', $event)))
            }, null, 8, ["branch"]))
          }), 128))
        ], 512)), [
          [external_commonjs_vue_commonjs2_vue_root_Vue_.vShow, $data.expanded]
        ])
      : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true)
  ], 2))
}
;// CONCATENATED MODULE: ./vue/components/vx-vue/simple-tree/simple-tree-branch.vue?vue&type=template&id=348e85e6

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/simple-tree/simple-tree-branch.vue?vue&type=script&lang=js

/* harmony default export */ const simple_tree_branchvue_type_script_lang_js = ({
  name: 'simple-tree-branch',
  emits: ['branch-selected'],
  data() {
    return {
      expanded: false
    }
  },

  props: {
    branch: { type: Object, default: {} }
  },

  mounted() {
    if (this.branch.current) {
      let parent = this.$parent;
      while (parent && parent.branch && parent.expanded !== undefined) {
        parent.expanded = true;
        parent = parent.$parent;
      }
    }
  }
});

;// CONCATENATED MODULE: ./vue/components/vx-vue/simple-tree/simple-tree-branch.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/vx-vue/simple-tree/simple-tree-branch.vue




;
const simple_tree_branch_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(simple_tree_branchvue_type_script_lang_js, [['render',simple_tree_branchvue_type_template_id_348e85e6_render]])

/* harmony default export */ const simple_tree_branch = (simple_tree_branch_exports_);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/simple-tree/simple-tree.vue?vue&type=script&lang=js

    

    /* harmony default export */ const simple_treevue_type_script_lang_js = ({
        name: 'simple-tree',
        props: {
            branch: Object
        },
        components: {
          SimpleTreeBranch: simple_tree_branch
        }
    });

;// CONCATENATED MODULE: ./vue/components/vx-vue/simple-tree/simple-tree.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/vx-vue/simple-tree/simple-tree.vue




;
const simple_tree_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(simple_treevue_type_script_lang_js, [['render',simple_treevue_type_template_id_d2c9e362_render]])

/* harmony default export */ const simple_tree = (simple_tree_exports_);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/filemanager/filemanager.vue?vue&type=template&id=42f6cf2c


const filemanagervue_type_template_id_42f6cf2c_hoisted_1 = { class: "vx-button-bar navbar" }
const filemanagervue_type_template_id_42f6cf2c_hoisted_2 = { class: "navbar-section" }
const filemanagervue_type_template_id_42f6cf2c_hoisted_3 = { class: "popup-container" }
const filemanagervue_type_template_id_42f6cf2c_hoisted_4 = { class: "card" }
const filemanagervue_type_template_id_42f6cf2c_hoisted_5 = { class: "card-body" }
const filemanagervue_type_template_id_42f6cf2c_hoisted_6 = { class: "navbar-section" }
const filemanagervue_type_template_id_42f6cf2c_hoisted_7 = { class: "d-inline-block mr-2" }
const _hoisted_8 = {
  key: 1,
  class: "text-primary d-block col-12 text-center"
}
const _hoisted_9 = { class: "navbar-section" }
const _hoisted_10 = {
  class: "with-webfont-icon-left",
  "data-icon": ""
}
const _hoisted_11 = ["href", "onClick"]
const _hoisted_12 = {
  class: "with-webfont-icon-left",
  "data-icon": ""
}
const _hoisted_13 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("br", null, null, -1)
const _hoisted_14 = ["href", "onClick"]
const _hoisted_15 = { class: "form-checkbox" }
const _hoisted_16 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("i", { class: "form-icon" }, null, -1)
const _hoisted_17 = { class: "form-checkbox" }
const _hoisted_18 = ["onUpdate:modelValue"]
const _hoisted_19 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("i", { class: "form-icon" }, null, -1)
const _hoisted_20 = ["value"]
const _hoisted_21 = ["href", "onClick"]
const _hoisted_22 = ["onClick"]
const _hoisted_23 = ["value"]
const _hoisted_24 = ["onClick"]
const _hoisted_25 = ["src"]
const _hoisted_26 = { key: 1 }
const _hoisted_27 = {
  key: 0,
  class: "modal active"
}
const _hoisted_28 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", { class: "modal-overlay" }, null, -1)
const _hoisted_29 = { class: "modal-container" }
const _hoisted_30 = { class: "modal-header" }
const _hoisted_31 = { class: "modal-body" }

function filemanagervue_type_template_id_42f6cf2c_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_filemanager_breadcrumbs = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.resolveComponent)("filemanager-breadcrumbs")
  const _component_filemanager_add = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.resolveComponent)("filemanager-add")
  const _component_filemanager_actions = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.resolveComponent)("filemanager-actions")
  const _component_circular_progress = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.resolveComponent)("circular-progress")
  const _component_filemanager_search = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.resolveComponent)("filemanager-search")
  const _component_sortable = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.resolveComponent)("sortable")
  const _component_file_edit_form = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.resolveComponent)("file-edit-form")
  const _component_folder_edit_form = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.resolveComponent)("folder-edit-form")
  const _component_confirm = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.resolveComponent)("confirm")
  const _component_alert = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.resolveComponent)("alert")
  const _component_folder_tree = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.resolveComponent)("folder-tree")
  const _directive_check_indeterminate = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.resolveDirective)("check-indeterminate")
  const _directive_focus = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.resolveDirective)("focus")

  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", {
    onDrop: _cache[13] || (_cache[13] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)((...args) => ($options.uploadDraggedFiles && $options.uploadDraggedFiles(...args)), ["prevent"])),
    onDragover: _cache[14] || (_cache[14] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)($event => ($data.indicateDrag = true), ["prevent"])),
    onDragleave: _cache[15] || (_cache[15] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)($event => ($data.indicateDrag = false), ["prevent"])),
    class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)({'dragged-over': $data.indicateDrag})
  }, [
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", filemanagervue_type_template_id_42f6cf2c_hoisted_1, [
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("section", filemanagervue_type_template_id_42f6cf2c_hoisted_2, [
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createVNode)(_component_filemanager_breadcrumbs, {
          breadcrumbs: $data.breadcrumbs,
          "current-folder": $data.currentFolder,
          folders: $data.folders,
          onBreadcrumbClicked: $options.readFolder
        }, null, 8, ["breadcrumbs", "current-folder", "folders", "onBreadcrumbClicked"]),
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", {
          class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(["popup popup-bottom ml-1", { active: $data.showAddActivities }])
        }, [
          (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("button", {
            class: "btn webfont-icon-only",
            type: "button",
            onClick: _cache[0] || (_cache[0] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)($event => ($data.showAddActivities = !$data.showAddActivities), ["stop"]))
          }, "  "),
          (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", filemanagervue_type_template_id_42f6cf2c_hoisted_3, [
            (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", filemanagervue_type_template_id_42f6cf2c_hoisted_4, [
              (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", filemanagervue_type_template_id_42f6cf2c_hoisted_5, [
                (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createVNode)(_component_filemanager_add, {
                  onUpload: $options.uploadInputFiles,
                  onCreateFolder: $options.createFolder
                }, null, 8, ["onUpload", "onCreateFolder"])
              ])
            ])
          ])
        ], 2),
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createVNode)(_component_filemanager_actions, {
          onDeleteSelection: $options.delSelection,
          onMoveSelection: $options.moveSelection,
          files: $options.checkedFiles,
          folders: $options.checkedFolders
        }, null, 8, ["onDeleteSelection", "onMoveSelection", "files", "folders"])
      ]),
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("section", filemanagervue_type_template_id_42f6cf2c_hoisted_6, [
        ($data.upload.progressing)
          ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_.Fragment, { key: 0 }, [
              (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("button", {
                class: "btn btn-link webfont-icon-only tooltip",
                "data-tooltip": "Abbrechen",
                type: "button",
                onClick: _cache[1] || (_cache[1] = (...args) => ($options.cancelUpload && $options.cancelUpload(...args)))
              }, " "),
              (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("label", filemanagervue_type_template_id_42f6cf2c_hoisted_7, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($data.progress.file), 1),
              (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createVNode)(_component_circular_progress, {
                progress: 100 * $data.progress.loaded / ($data.progress.total || 1),
                radius: 16
              }, null, 8, ["progress"])
            ], 64))
          : ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("strong", _hoisted_8, "Dateien zum Upload hierher ziehen"))
      ]),
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("section", _hoisted_9, [
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createVNode)(_component_filemanager_search, { search: $options.doSearch }, {
          folder: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withCtx)((slotProps) => [
            (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("span", _hoisted_10, [
              (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("a", {
                href: '#' + slotProps.folder.id,
                onClick: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)($event => ($options.readFolder(slotProps.folder.id)), ["prevent"])
              }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)(slotProps.folder.name), 9, _hoisted_11)
            ])
          ]),
          file: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withCtx)((slotProps) => [
            (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("span", _hoisted_12, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)(slotProps.file.name) + " (" + (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)(slotProps.file.type) + ")", 1),
            _hoisted_13,
            (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("a", {
              href: '#' + slotProps.file.folder,
              onClick: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)($event => ($options.readFolder(slotProps.file.folder)), ["prevent"])
            }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)(slotProps.file.path), 9, _hoisted_14)
          ]),
          _: 1
        }, 8, ["search"])
      ])
    ]),
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createVNode)(_component_sortable, {
      rows: $options.directoryEntries,
      columns: $props.columns,
      "sort-prop": $props.initSort.column,
      "sort-direction": $props.initSort.dir,
      onAfterSort: _cache[9] || (_cache[9] = $event => (_ctx.$emit('after-sort', { sortColumn: _ctx.$refs.sortable.sortColumn, sortDir: _ctx.$refs.sortable.sortDir }))),
      ref: "sortable",
      id: "files-list"
    }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createSlots)({
      "checked-header": (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withCtx)(() => [
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("label", _hoisted_15, [
          (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withDirectives)((0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("input", {
            type: "checkbox",
            onClick: _cache[2] || (_cache[2] = $event => {[...$data.folders, ...$data.files].forEach(item => item.checked = $event.target.checked);})
          }, null, 512), [
            [_directive_check_indeterminate]
          ]),
          _hoisted_16
        ])
      ]),
      checked: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withCtx)((slotProps) => [
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("label", _hoisted_17, [
          (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withDirectives)((0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("input", {
            type: "checkbox",
            "onUpdate:modelValue": $event => ((slotProps.row.checked) = $event)
          }, null, 8, _hoisted_18), [
            [external_commonjs_vue_commonjs2_vue_root_Vue_.vModelCheckbox, slotProps.row.checked]
          ]),
          _hoisted_19
        ])
      ]),
      name: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withCtx)((slotProps) => [
        (slotProps.row.isFolder)
          ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_.Fragment, { key: 0 }, [
              (slotProps.row === $data.toRename)
                ? (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withDirectives)(((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("input", {
                    key: 0,
                    class: "form-input",
                    value: slotProps.row.name,
                    onKeydown: [
                      _cache[3] || (_cache[3] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withKeys)((...args) => ($options.renameFolder && $options.renameFolder(...args)), ["enter"])),
                      _cache[4] || (_cache[4] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withKeys)($event => ($data.toRename = null), ["esc"]))
                    ],
                    onBlur: _cache[5] || (_cache[5] = $event => ($data.toRename = null))
                  }, null, 40, _hoisted_20)), [
                    [_directive_focus]
                  ])
                : ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_.Fragment, { key: 1 }, [
                    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("a", {
                      href: '#' + slotProps.row.id,
                      onClick: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)($event => ($options.readFolder(slotProps.row.id)), ["prevent"])
                    }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)(slotProps.row.name), 9, _hoisted_21),
                    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("button", {
                      class: "btn webfont-icon-only tooltip mr-1 rename display-only-on-hover ml-2",
                      "data-tooltip": "Umbenennen",
                      onClick: $event => ($data.toRename = slotProps.row)
                    }, " ", 8, _hoisted_22)
                  ], 64))
            ], 64))
          : ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_.Fragment, { key: 1 }, [
              (slotProps.row === $data.toRename)
                ? (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withDirectives)(((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("input", {
                    key: 0,
                    class: "form-input",
                    value: slotProps.row.name,
                    onKeydown: [
                      _cache[6] || (_cache[6] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withKeys)((...args) => ($options.renameFile && $options.renameFile(...args)), ["enter"])),
                      _cache[7] || (_cache[7] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withKeys)($event => ($data.toRename = null), ["esc"]))
                    ],
                    onBlur: _cache[8] || (_cache[8] = $event => ($data.toRename = null))
                  }, null, 40, _hoisted_23)), [
                    [_directive_focus]
                  ])
                : ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_.Fragment, { key: 1 }, [
                    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("span", null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)(slotProps.row.name), 1),
                    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("button", {
                      class: "btn webfont-icon-only tooltip mr-1 rename display-only-on-hover ml-2",
                      "data-tooltip": "Umbenennen",
                      onClick: $event => ($data.toRename = slotProps.row)
                    }, " ", 8, _hoisted_24)
                  ], 64))
            ], 64))
      ]),
      size: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withCtx)((slotProps) => [
        (!slotProps.row.isFolder)
          ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_.Fragment, { key: 0 }, [
              (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createTextVNode)((0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($options.formatFilesize(slotProps.row.size, ',')), 1)
            ], 64))
          : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true)
      ]),
      type: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withCtx)((slotProps) => [
        (slotProps.row.image)
          ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("img", {
              key: 0,
              src: slotProps.row.src,
              alt: ""
            }, null, 8, _hoisted_25))
          : ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("span", _hoisted_26, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)(slotProps.row.type), 1))
      ]),
      _: 2
    }, [
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.renderList)(_ctx.$slots, (_, name) => {
        return {
          name: name,
          fn: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withCtx)((slotData) => [
            (0,external_commonjs_vue_commonjs2_vue_root_Vue_.renderSlot)(_ctx.$slots, name, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeProps)((0,external_commonjs_vue_commonjs2_vue_root_Vue_.guardReactiveProps)(slotData)))
          ])
        }
      })
    ]), 1032, ["rows", "columns", "sort-prop", "sort-direction"]),
    ($data.showFileForm || $data.showFolderForm)
      ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", _hoisted_27, [
          _hoisted_28,
          (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", _hoisted_29, [
            (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", _hoisted_30, [
              (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("a", {
                href: "#close",
                class: "btn btn-clear float-right",
                "aria-label": "Close",
                onClick: _cache[10] || (_cache[10] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)($event => ($data.showFileForm = $data.showFolderForm = false), ["prevent"]))
              })
            ]),
            (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", _hoisted_31, [
              ($data.showFileForm)
                ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createBlock)(_component_file_edit_form, {
                    key: 0,
                    "initial-data": $data.editFormData,
                    "file-info": $data.editMetaData,
                    url: $props.routes.updateFile,
                    onResponseReceived: _cache[11] || (_cache[11] = response => _ctx.$emit('response-received', response))
                  }, null, 8, ["initial-data", "file-info", "url"]))
                : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true),
              ($data.showFolderForm)
                ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createBlock)(_component_folder_edit_form, {
                    key: 1,
                    "initial-data": $data.editFormData,
                    "folder-info": $data.editMetaData,
                    url: $props.routes.updateFolder,
                    onResponseReceived: _cache[12] || (_cache[12] = response => _ctx.$emit('response-received', response))
                  }, null, 8, ["initial-data", "folder-info", "url"]))
                : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true)
            ])
          ])
        ]))
      : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true),
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createVNode)(_component_confirm, {
      ref: "confirm",
      config: { cancelLabel: 'Abbrechen', okLabel: 'Löschen', okClass: 'btn-error' }
    }, null, 512),
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createVNode)(_component_alert, {
      ref: "alert",
      config: { label: 'Ok', buttonClass: 'btn-error' }
    }, null, 512),
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createVNode)(_component_folder_tree, { ref: "folder-tree" }, null, 512)
  ], 34))
}
;// CONCATENATED MODULE: ./vue/components/filemanager/filemanager.vue?vue&type=template&id=42f6cf2c

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/filemanager/filemanager-add.vue?vue&type=template&id=67362ebb


const filemanager_addvue_type_template_id_67362ebb_hoisted_1 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("label", {
  class: "btn with-webfont-icon-left btn-link",
  "data-icon": "",
  for: "file_upload"
}, "Datei hochladen", -1)
const filemanager_addvue_type_template_id_67362ebb_hoisted_2 = ["multiple"]

function filemanager_addvue_type_template_id_67362ebb_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _directive_focus = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.resolveDirective)("focus")

  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", null, [
    ($data.showAddFolderInput)
      ? (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withDirectives)(((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("input", {
          key: 0,
          class: "form-input",
          onKeydown: [
            _cache[0] || (_cache[0] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withKeys)((...args) => ($options.addFolder && $options.addFolder(...args)), ["enter"])),
            _cache[1] || (_cache[1] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withKeys)($event => ($data.showAddFolderInput = false), ["esc"]))
          ],
          onBlur: _cache[2] || (_cache[2] = $event => ($data.showAddFolderInput = false))
        }, null, 544)), [
          [_directive_focus]
        ])
      : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true),
    (!$data.showAddFolderInput)
      ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("button", {
          key: 1,
          class: "btn with-webfont-icon-left btn-link",
          type: "button",
          "data-icon": "",
          onClick: _cache[3] || (_cache[3] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)($event => ($data.showAddFolderInput = true), ["stop"]))
        }, "Verzeichnis erstellen "))
      : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true),
    filemanager_addvue_type_template_id_67362ebb_hoisted_1,
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("input", {
      type: "file",
      id: "file_upload",
      class: "d-none",
      multiple: $props.multiple,
      onChange: _cache[4] || (_cache[4] = (...args) => ($options.fileChanged && $options.fileChanged(...args)))
    }, null, 40, filemanager_addvue_type_template_id_67362ebb_hoisted_2)
  ]))
}
;// CONCATENATED MODULE: ./vue/components/filemanager/filemanager-add.vue?vue&type=template&id=67362ebb

;// CONCATENATED MODULE: ./vue/directives.js
// focus an element upon insertion

const Focus = { mounted: (el) => el.focus() };



;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/filemanager/filemanager-add.vue?vue&type=script&lang=js



/* harmony default export */ const filemanager_addvue_type_script_lang_js = ({
  name: 'filemanager-add',
  emits: ['upload', 'create-folder'],
  props: {
    multiple: {type: Boolean, default: true}
  },
  data() {
    return {
      showAddFolderInput: false
    }
  },

  methods: {
    fileChanged(event) {
      const files = event.target.files || event.dataTransfer.files;
      if (files) {
        this.$emit('upload', files);
      }
    },
    addFolder(event) {
      const name = event.target.value.trim();
      if (name) {
        this.$emit('create-folder', name);
      }
      this.showAddFolderInput = false;
    }
  },

  directives: {
    focus: Focus
  }
});

;// CONCATENATED MODULE: ./vue/components/filemanager/filemanager-add.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/filemanager/filemanager-add.vue




;
const filemanager_add_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(filemanager_addvue_type_script_lang_js, [['render',filemanager_addvue_type_template_id_67362ebb_render]])

/* harmony default export */ const filemanager_add = (filemanager_add_exports_);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/filemanager/filemanager-actions.vue?vue&type=template&id=27c523be


const filemanager_actionsvue_type_template_id_27c523be_hoisted_1 = {
  key: 0,
  class: "mx-2"
}
const filemanager_actionsvue_type_template_id_27c523be_hoisted_2 = ["data-tooltip"]
const filemanager_actionsvue_type_template_id_27c523be_hoisted_3 = ["data-tooltip"]

function filemanager_actionsvue_type_template_id_27c523be_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ($props.files.length || $props.folders.length)
    ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", filemanager_actionsvue_type_template_id_27c523be_hoisted_1, [
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("button", {
          class: "btn btn-link webfont-icon-only tooltip",
          "data-tooltip": $props.files.length + $props.folders.length + ' gewählte Dateien/Ordner löschen',
          type: "button",
          onClick: _cache[0] || (_cache[0] = (...args) => ($options.confirmDelete && $options.confirmDelete(...args)))
        }, "", 8, filemanager_actionsvue_type_template_id_27c523be_hoisted_2),
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("button", {
          class: "btn btn-link webfont-icon-only tooltip",
          "data-tooltip": $props.files.length + $props.folders.length + ' gewählte Dateien/Ordner verschieben',
          type: "button",
          onClick: _cache[1] || (_cache[1] = (...args) => ($options.pickFolder && $options.pickFolder(...args)))
        }, "", 8, filemanager_actionsvue_type_template_id_27c523be_hoisted_3)
      ]))
    : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true)
}
;// CONCATENATED MODULE: ./vue/components/filemanager/filemanager-actions.vue?vue&type=template&id=27c523be

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/filemanager/filemanager-actions.vue?vue&type=script&lang=js

    /* harmony default export */ const filemanager_actionsvue_type_script_lang_js = ({
        name: 'filemanager-actions',
        emits: ['delete-selection', 'move-selection'],
        props: {
            files: { type: Array, default: () => ([]) },
            folders:  { type: Array, default: () => ([]) }
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

;// CONCATENATED MODULE: ./vue/components/filemanager/filemanager-actions.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/filemanager/filemanager-actions.vue




;
const filemanager_actions_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(filemanager_actionsvue_type_script_lang_js, [['render',filemanager_actionsvue_type_template_id_27c523be_render]])

/* harmony default export */ const filemanager_actions = (filemanager_actions_exports_);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/filemanager/filemanager-search.vue?vue&type=template&id=90743fce


const filemanager_searchvue_type_template_id_90743fce_hoisted_1 = { class: "has-icon-right" }
const filemanager_searchvue_type_template_id_90743fce_hoisted_2 = {
  key: 0,
  class: "form-icon loading"
}
const filemanager_searchvue_type_template_id_90743fce_hoisted_3 = {
  key: 0,
  class: "modal-container",
  style: {"position":"fixed","left":"50%","top":"50%","transform":"translate(-50%, -50%)"}
}
const filemanager_searchvue_type_template_id_90743fce_hoisted_4 = { class: "modal-header" }
const filemanager_searchvue_type_template_id_90743fce_hoisted_5 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", { class: "modal-title h5" }, "Gefundene Dateien und Ordner…", -1)
const filemanager_searchvue_type_template_id_90743fce_hoisted_6 = { class: "modal-body" }
const filemanager_searchvue_type_template_id_90743fce_hoisted_7 = {
  key: 0,
  class: "divider"
}
const filemanager_searchvue_type_template_id_90743fce_hoisted_8 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("br", null, null, -1)
const filemanager_searchvue_type_template_id_90743fce_hoisted_9 = { class: "text-gray" }

function filemanager_searchvue_type_template_id_90743fce_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", null, [
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", filemanager_searchvue_type_template_id_90743fce_hoisted_1, [
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("input", (0,external_commonjs_vue_commonjs2_vue_root_Vue_.mergeProps)({
        class: "text-input",
        onKeydown: _cache[0] || (_cache[0] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withKeys)((...args) => ($options.handleEsc && $options.handleEsc(...args)), ["esc"])),
        onInput: _cache[1] || (_cache[1] = (...args) => ($options.handleInput && $options.handleInput(...args))),
        onFocus: _cache[2] || (_cache[2] = (...args) => ($options.handleInput && $options.handleInput(...args)))
      }, $options.inputProps), null, 16),
      ($data.loading)
        ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("i", filemanager_searchvue_type_template_id_90743fce_hoisted_2))
        : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true)
    ]),
    ($options.showResults)
      ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", filemanager_searchvue_type_template_id_90743fce_hoisted_3, [
          (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", filemanager_searchvue_type_template_id_90743fce_hoisted_4, [
            (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("a", {
              href: "#close",
              class: "btn btn-clear float-right",
              "aria-label": "Close",
              onClick: _cache[3] || (_cache[3] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)($event => ($options.showResults = false), ["prevent"]))
            }),
            filemanager_searchvue_type_template_id_90743fce_hoisted_5
          ]),
          (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", filemanager_searchvue_type_template_id_90743fce_hoisted_6, [
            ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(true), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_.Fragment, null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.renderList)(($data.results.folders || []), (result) => {
              return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", {
                key: result.id
              }, [
                (0,external_commonjs_vue_commonjs2_vue_root_Vue_.renderSlot)(_ctx.$slots, "folder", { folder: result }, () => [
                  (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createTextVNode)((0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)(result.name), 1)
                ])
              ]))
            }), 128)),
            ($data.results.folders.length && $data.results.files.length)
              ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", filemanager_searchvue_type_template_id_90743fce_hoisted_7))
              : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true),
            ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(true), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_.Fragment, null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.renderList)(($data.results.files || []), (result) => {
              return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", {
                key: result.id
              }, [
                (0,external_commonjs_vue_commonjs2_vue_root_Vue_.renderSlot)(_ctx.$slots, "file", { file: result }, () => [
                  (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createTextVNode)((0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)(result.name) + " (" + (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)(result.type) + ")", 1),
                  filemanager_searchvue_type_template_id_90743fce_hoisted_8,
                  (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("span", filemanager_searchvue_type_template_id_90743fce_hoisted_9, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)(result.path), 1)
                ])
              ]))
            }), 128))
          ])
        ]))
      : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true)
  ]))
}
;// CONCATENATED MODULE: ./vue/components/filemanager/filemanager-search.vue?vue&type=template&id=90743fce

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/filemanager/filemanager-search.vue?vue&type=script&lang=js

/* harmony default export */ const filemanager_searchvue_type_script_lang_js = ({
  name: 'filemanager-search',
  data() {
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
    placeholder: {type: String, default: 'Datei/Verzeichnis suchen...'},
    search: {type: Function, required: true}
  },

  computed: {
    inputProps() {
      return {
        value: this.value,
        placeholder: this.placeholder
      }
    },
    showResults: {
      get() {
        return this.results.folders.length || this.results.files.length;
      },
      set(newValue) {
        if (!newValue) {
          this.results.folders = [];
          this.results.files = [];
        }
      }
    }
  },

  methods: {
    handleInput(event) {
      this.value = event.target.value;
      const search = this.search(this.value);

      if (search instanceof Promise) {
        this.loading = true;
        search.then(({files, folders}) => {
          this.results.files = files || [];
          this.results.folders = folders || [];
          this.loading = false;
        });
      } else {
        this.results = Object.assign({}, this.results, search);
      }
    },
    handleEsc() {
      this.value = "";
      this.showResults = false;
    }
  }
});

;// CONCATENATED MODULE: ./vue/components/filemanager/filemanager-search.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/filemanager/filemanager-search.vue




;
const filemanager_search_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(filemanager_searchvue_type_script_lang_js, [['render',filemanager_searchvue_type_template_id_90743fce_render]])

/* harmony default export */ const filemanager_search = (filemanager_search_exports_);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/filemanager/filemanager-breadcrumbs.vue?vue&type=template&id=587f2913


const filemanager_breadcrumbsvue_type_template_id_587f2913_hoisted_1 = { class: "btn-group" }
const filemanager_breadcrumbsvue_type_template_id_587f2913_hoisted_2 = ["onClick"]

function filemanager_breadcrumbsvue_type_template_id_587f2913_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("span", filemanager_breadcrumbsvue_type_template_id_587f2913_hoisted_1, [
    ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(true), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_.Fragment, null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.renderList)($data.items, (breadcrumb, ndx) => {
      return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("button", {
        class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(["btn", { 'active': breadcrumb.folder === $props.currentFolder }]),
        key: ndx,
        onClick: $event => (_ctx.$emit('breadcrumb-clicked', breadcrumb.folder))
      }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)(breadcrumb.name), 11, filemanager_breadcrumbsvue_type_template_id_587f2913_hoisted_2))
    }), 128))
  ]))
}
;// CONCATENATED MODULE: ./vue/components/filemanager/filemanager-breadcrumbs.vue?vue&type=template&id=587f2913

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/filemanager/filemanager-breadcrumbs.vue?vue&type=script&lang=js

/* harmony default export */ const filemanager_breadcrumbsvue_type_script_lang_js = ({
  name: 'filemanager-breadcrumbs',
  emits: ['breadcrumb-clicked'],
  data() {
    return {items: []}
  },
  props: {
    breadcrumbs: Array,
    folders: Array,
    currentFolder: Number
  },
  watch: {
    breadcrumbs(newValue) {
      if (
          newValue.length >= this.items.length ||
          this.items.map(item => item.folder).join().indexOf(newValue.map(item => item.folder).join()) !== 0
      ) {
        this.items = newValue;
      }
    },
    folders: {
      deep: true,
      handler(newValue) {

        // find current folder

        let current = this.items.findIndex(item => item.folder === this.currentFolder);

        if (this.items[current + 1]) {
          let ndx = newValue.findIndex(({id}) => id === this.items[current + 1].folder);

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

;// CONCATENATED MODULE: ./vue/components/filemanager/filemanager-breadcrumbs.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/filemanager/filemanager-breadcrumbs.vue




;
const filemanager_breadcrumbs_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(filemanager_breadcrumbsvue_type_script_lang_js, [['render',filemanager_breadcrumbsvue_type_template_id_587f2913_render]])

/* harmony default export */ const filemanager_breadcrumbs = (filemanager_breadcrumbs_exports_);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/filemanager/filemanager-folder-tree.vue?vue&type=template&id=6834d85d


const filemanager_folder_treevue_type_template_id_6834d85d_hoisted_1 = { class: "modal-container" }
const filemanager_folder_treevue_type_template_id_6834d85d_hoisted_2 = { class: "modal-header" }
const filemanager_folder_treevue_type_template_id_6834d85d_hoisted_3 = { class: "modal-title h5" }
const filemanager_folder_treevue_type_template_id_6834d85d_hoisted_4 = { class: "modal-body" }

function filemanager_folder_treevue_type_template_id_6834d85d_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_simple_tree = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.resolveComponent)("simple-tree")

  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", {
    ref: "container",
    class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(["modal", { active: $data.show }])
  }, [
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", filemanager_folder_treevue_type_template_id_6834d85d_hoisted_1, [
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", filemanager_folder_treevue_type_template_id_6834d85d_hoisted_2, [
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("a", {
          href: "#close",
          class: "btn btn-clear float-right",
          "aria-label": "Close",
          onClick: _cache[0] || (_cache[0] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)((...args) => ($options.cancel && $options.cancel(...args)), ["prevent"]))
        }),
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", filemanager_folder_treevue_type_template_id_6834d85d_hoisted_3, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($props.title), 1)
      ]),
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", filemanager_folder_treevue_type_template_id_6834d85d_hoisted_4, [
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createVNode)(_component_simple_tree, {
          branch: $data.root,
          onBranchSelected: $options.folderSelected
        }, null, 8, ["branch", "onBranchSelected"])
      ])
    ])
  ], 2))
}
;// CONCATENATED MODULE: ./vue/components/filemanager/filemanager-folder-tree.vue?vue&type=template&id=6834d85d

;// CONCATENATED MODULE: ./vue/util/simple-fetch.js
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

;// CONCATENATED MODULE: ./vue/util/url-query.js
/* harmony default export */ const url_query = ({
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

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/filemanager/filemanager-folder-tree.vue?vue&type=script&lang=js

  
  
  

  /* harmony default export */ const filemanager_folder_treevue_type_script_lang_js = ({
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

;// CONCATENATED MODULE: ./vue/components/filemanager/filemanager-folder-tree.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/filemanager/filemanager-folder-tree.vue




;
const filemanager_folder_tree_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(filemanager_folder_treevue_type_script_lang_js, [['render',filemanager_folder_treevue_type_template_id_6834d85d_render]])

/* harmony default export */ const filemanager_folder_tree = (filemanager_folder_tree_exports_);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/confirm.vue?vue&type=template&id=09de9d7a


const confirmvue_type_template_id_09de9d7a_hoisted_1 = { class: "modal-container" }
const confirmvue_type_template_id_09de9d7a_hoisted_2 = {
  key: 0,
  class: "modal-header bg-error text-light"
}
const confirmvue_type_template_id_09de9d7a_hoisted_3 = { class: "modal-title h5" }
const confirmvue_type_template_id_09de9d7a_hoisted_4 = { class: "modal-body" }
const confirmvue_type_template_id_09de9d7a_hoisted_5 = { class: "content" }
const confirmvue_type_template_id_09de9d7a_hoisted_6 = { class: "modal-footer" }

function confirmvue_type_template_id_09de9d7a_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", {
    ref: "container",
    class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(["modal modal-sm", { active: $data.show }]),
    onKeydown: _cache[3] || (_cache[3] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withKeys)((0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)((...args) => ($options.cancel && $options.cancel(...args)), ["stop"]), ["esc"]))
  }, [
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("a", {
      href: "#close",
      class: "modal-overlay",
      onClick: _cache[0] || (_cache[0] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)((...args) => ($options.cancel && $options.cancel(...args)), ["prevent"]))
    }),
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", confirmvue_type_template_id_09de9d7a_hoisted_1, [
      ($data.title)
        ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", confirmvue_type_template_id_09de9d7a_hoisted_2, [
            (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", confirmvue_type_template_id_09de9d7a_hoisted_3, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($data.title), 1)
          ]))
        : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true),
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", confirmvue_type_template_id_09de9d7a_hoisted_4, [
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", confirmvue_type_template_id_09de9d7a_hoisted_5, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($data.message), 1)
      ]),
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", confirmvue_type_template_id_09de9d7a_hoisted_6, [
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("button", {
          type: "button",
          class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(["btn mr-2", $data.options.okClass]),
          onClick: _cache[1] || (_cache[1] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)((...args) => ($options.ok && $options.ok(...args)), ["prevent"])),
          ref: "okButton"
        }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($data.options.okLabel), 3),
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("button", {
          type: "button",
          class: "btn btn-link",
          onClick: _cache[2] || (_cache[2] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)((...args) => ($options.cancel && $options.cancel(...args)), ["prevent"]))
        }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($data.options.cancelLabel), 1)
      ])
    ])
  ], 34))
}
;// CONCATENATED MODULE: ./vue/components/vx-vue/confirm.vue?vue&type=template&id=09de9d7a

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/confirm.vue?vue&type=script&lang=js

/* heavily inspired by https://gist.github.com/eolant/ba0f8a5c9135d1a146e1db575276177d */

/* harmony default export */ const confirmvue_type_script_lang_js = ({
  name: 'confirm',

  props: {
    config: {
      type: Object
    }
  },

  data() {
    return {
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
    }
  },

  watch: {
    config() {
      this.options = Object.assign({}, this.options, this.config);
    }
  },

  created() {
    this.options = Object.assign({}, this.options, this.config);
  },

  methods: {
    open(title, message, options) {
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
    ok() {
      this.show = false;
      this.resolve(true);
    },
    cancel() {
      this.show = false;
      this.resolve(false);
    }
  }
});

;// CONCATENATED MODULE: ./vue/components/vx-vue/confirm.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/vx-vue/confirm.vue




;
const confirm_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(confirmvue_type_script_lang_js, [['render',confirmvue_type_template_id_09de9d7a_render]])

/* harmony default export */ const vx_vue_confirm = (confirm_exports_);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/alert.vue?vue&type=template&id=9bfcbb82


const alertvue_type_template_id_9bfcbb82_hoisted_1 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", { class: "modal-overlay" }, null, -1)
const alertvue_type_template_id_9bfcbb82_hoisted_2 = { class: "modal-container" }
const alertvue_type_template_id_9bfcbb82_hoisted_3 = {
  key: 0,
  class: "modal-header bg-error text-light"
}
const alertvue_type_template_id_9bfcbb82_hoisted_4 = { class: "modal-title h5" }
const alertvue_type_template_id_9bfcbb82_hoisted_5 = { class: "modal-body" }
const alertvue_type_template_id_9bfcbb82_hoisted_6 = { class: "content" }
const alertvue_type_template_id_9bfcbb82_hoisted_7 = { class: "modal-footer" }

function alertvue_type_template_id_9bfcbb82_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", {
    ref: "container",
    class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(["modal modal-sm", { active: $data.show }])
  }, [
    alertvue_type_template_id_9bfcbb82_hoisted_1,
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", alertvue_type_template_id_9bfcbb82_hoisted_2, [
      ($data.title)
        ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", alertvue_type_template_id_9bfcbb82_hoisted_3, [
            (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", alertvue_type_template_id_9bfcbb82_hoisted_4, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($data.title), 1)
          ]))
        : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true),
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", alertvue_type_template_id_9bfcbb82_hoisted_5, [
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", alertvue_type_template_id_9bfcbb82_hoisted_6, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($data.message), 1)
      ]),
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", alertvue_type_template_id_9bfcbb82_hoisted_7, [
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("button", {
          type: "button",
          class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(["btn", $data.options.buttonClass]),
          onClick: _cache[0] || (_cache[0] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)((...args) => ($options.ok && $options.ok(...args)), ["prevent"])),
          ref: "button"
        }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($data.options.label), 3)
      ])
    ])
  ], 2))
}
;// CONCATENATED MODULE: ./vue/components/vx-vue/alert.vue?vue&type=template&id=9bfcbb82

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/alert.vue?vue&type=script&lang=js

    /* harmony default export */ const alertvue_type_script_lang_js = ({
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

;// CONCATENATED MODULE: ./vue/components/vx-vue/alert.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/vx-vue/alert.vue




;
const alert_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(alertvue_type_script_lang_js, [['render',alertvue_type_template_id_9bfcbb82_render]])

/* harmony default export */ const vx_vue_alert = (alert_exports_);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/forms/file-edit-form.vue?vue&type=template&id=90373e08


const file_edit_formvue_type_template_id_90373e08_hoisted_1 = { class: "columns" }
const file_edit_formvue_type_template_id_90373e08_hoisted_2 = { class: "column" }
const file_edit_formvue_type_template_id_90373e08_hoisted_3 = ["src"]
const file_edit_formvue_type_template_id_90373e08_hoisted_4 = { class: "column" }
const file_edit_formvue_type_template_id_90373e08_hoisted_5 = { class: "table" }
const file_edit_formvue_type_template_id_90373e08_hoisted_6 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("td", null, "Typ", -1)
const file_edit_formvue_type_template_id_90373e08_hoisted_7 = { key: 0 }
const file_edit_formvue_type_template_id_90373e08_hoisted_8 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("td", null, "Cache", -1)
const file_edit_formvue_type_template_id_90373e08_hoisted_9 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("td", null, "Link", -1)
const file_edit_formvue_type_template_id_90373e08_hoisted_10 = ["href"]
const file_edit_formvue_type_template_id_90373e08_hoisted_11 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", {
  class: "divider",
  "data-content": "Metadaten der Datei"
}, null, -1)
const file_edit_formvue_type_template_id_90373e08_hoisted_12 = { class: "form-group" }
const file_edit_formvue_type_template_id_90373e08_hoisted_13 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("label", { for: "title_input" }, "Titel", -1)
const file_edit_formvue_type_template_id_90373e08_hoisted_14 = { class: "form-group" }
const file_edit_formvue_type_template_id_90373e08_hoisted_15 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("label", { for: "subtitle_input" }, "Untertitel", -1)
const file_edit_formvue_type_template_id_90373e08_hoisted_16 = { class: "form-group" }
const file_edit_formvue_type_template_id_90373e08_hoisted_17 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("label", { for: "description_input" }, "Beschreibung", -1)
const file_edit_formvue_type_template_id_90373e08_hoisted_18 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", {
  class: "divider",
  "data-content": "Erweiterte Einstellungen"
}, null, -1)
const file_edit_formvue_type_template_id_90373e08_hoisted_19 = { class: "form-group" }
const file_edit_formvue_type_template_id_90373e08_hoisted_20 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("label", { for: "customsort_input" }, "Sortierziffer", -1)
const file_edit_formvue_type_template_id_90373e08_hoisted_21 = { class: "form-group" }
const file_edit_formvue_type_template_id_90373e08_hoisted_22 = ["disabled"]

function file_edit_formvue_type_template_id_90373e08_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("form", {
    action: "/",
    onSubmit: _cache[5] || (_cache[5] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)(() => {}, ["prevent"]))
  }, [
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", file_edit_formvue_type_template_id_90373e08_hoisted_1, [
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", file_edit_formvue_type_template_id_90373e08_hoisted_2, [
        ($props.fileInfo.thumb)
          ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("img", {
              key: 0,
              src: $props.fileInfo.thumb,
              class: "img-responsive"
            }, null, 8, file_edit_formvue_type_template_id_90373e08_hoisted_3))
          : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true)
      ]),
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", file_edit_formvue_type_template_id_90373e08_hoisted_4, [
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("table", file_edit_formvue_type_template_id_90373e08_hoisted_5, [
          (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("tr", null, [
            file_edit_formvue_type_template_id_90373e08_hoisted_6,
            (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("td", null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($props.fileInfo.mimetype), 1)
          ]),
          ($props.fileInfo.cache)
            ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("tr", file_edit_formvue_type_template_id_90373e08_hoisted_7, [
                file_edit_formvue_type_template_id_90373e08_hoisted_8,
                (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("td", null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($props.fileInfo.cache.count) + " Files, " + (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($options.formatFilesize($props.fileInfo.cache.totalSize, ',')), 1)
              ]))
            : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true),
          (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("tr", null, [
            file_edit_formvue_type_template_id_90373e08_hoisted_9,
            (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("td", null, [
              (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("a", {
                href: '/' + $props.fileInfo.path,
                target: "_blank"
              }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($props.fileInfo.name), 9, file_edit_formvue_type_template_id_90373e08_hoisted_10)
            ])
          ])
        ])
      ])
    ]),
    file_edit_formvue_type_template_id_90373e08_hoisted_11,
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", file_edit_formvue_type_template_id_90373e08_hoisted_12, [
      file_edit_formvue_type_template_id_90373e08_hoisted_13,
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withDirectives)((0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("input", {
        id: "title_input",
        class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(["form-input", {'is-error': $options.errors.title}]),
        "onUpdate:modelValue": _cache[0] || (_cache[0] = $event => ((_ctx.form.title) = $event)),
        autocomplete: "off"
      }, null, 2), [
        [external_commonjs_vue_commonjs2_vue_root_Vue_.vModelText, _ctx.form.title]
      ])
    ]),
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", file_edit_formvue_type_template_id_90373e08_hoisted_14, [
      file_edit_formvue_type_template_id_90373e08_hoisted_15,
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withDirectives)((0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("input", {
        id: "subtitle_input",
        class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(["form-input", {'is-error': $options.errors.subtitle}]),
        "onUpdate:modelValue": _cache[1] || (_cache[1] = $event => ((_ctx.form.subtitle) = $event)),
        autocomplete: "off"
      }, null, 2), [
        [external_commonjs_vue_commonjs2_vue_root_Vue_.vModelText, _ctx.form.subtitle]
      ])
    ]),
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", file_edit_formvue_type_template_id_90373e08_hoisted_16, [
      file_edit_formvue_type_template_id_90373e08_hoisted_17,
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withDirectives)((0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("textarea", {
        rows: "2",
        id: "description_input",
        class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(["form-input", {'is-error': $options.errors.description}]),
        "onUpdate:modelValue": _cache[2] || (_cache[2] = $event => ((_ctx.form.description) = $event))
      }, null, 2), [
        [external_commonjs_vue_commonjs2_vue_root_Vue_.vModelText, _ctx.form.description]
      ])
    ]),
    file_edit_formvue_type_template_id_90373e08_hoisted_18,
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", file_edit_formvue_type_template_id_90373e08_hoisted_19, [
      file_edit_formvue_type_template_id_90373e08_hoisted_20,
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withDirectives)((0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("input", {
        id: "customsort_input",
        class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(["form-input col-4", {'is-error': $options.errors.customsort}]),
        "onUpdate:modelValue": _cache[3] || (_cache[3] = $event => ((_ctx.form.customsort) = $event)),
        autocomplete: "off"
      }, null, 2), [
        [external_commonjs_vue_commonjs2_vue_root_Vue_.vModelText, _ctx.form.customsort]
      ])
    ]),
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", file_edit_formvue_type_template_id_90373e08_hoisted_21, [
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("button", {
        type: "button",
        onClick: _cache[4] || (_cache[4] = (...args) => ($options.submit && $options.submit(...args))),
        class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(["btn btn-success col-12", {'loading': _ctx.loading}]),
        disabled: _ctx.loading
      }, "Änderungen speichern ", 10, file_edit_formvue_type_template_id_90373e08_hoisted_22)
    ])
  ], 32))
}
;// CONCATENATED MODULE: ./vue/components/forms/file-edit-form.vue?vue&type=template&id=90373e08

;// CONCATENATED MODULE: ./vue/filters.js

function formatFilesize (size, sep) {
    let i = Math.floor(Math.floor(Math.log(size) / Math.log(1000)));
    return (size / Math.pow(1000, i)).toFixed(i ? 2 : 0).toString().replace('.', sep || '.') + ['B', 'kB', 'MB', 'GB'][i];
}


;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/forms/file-edit-form.vue?vue&type=script&lang=js




/* harmony default export */ const file_edit_formvue_type_script_lang_js = ({
  name: 'file-edit-form',
  emits: ['response-received'],
  props: {
    initialData: {type: Object, default: {}},
    fileInfo: {type: Object, default: {}},
    url: {type: String, default: ""}
  },
  data: () => ({
    form: {},
    response: {},
    loading: false
  }),
  computed: {
    errors() {
      return this.response ? (this.response.errors || {}) : {};
    },
    message() {
      return this.response ? this.response.message : "";
    }
  },
  watch: {
    initialData(newValue) {
      this.form = Object.assign({}, this.form, newValue);
    }
  },
  methods: {
    async submit() {
      this.loading = true;

      /* avoid strings "null" with null values */

      let formData = {};

      Object.keys(this.form).forEach(key => {
        if (this.form[key] !== null) {
          formData[key] = this.form[key];
        }
      });

      this.response = await SimpleFetch(this.url, 'POST', {}, JSON.stringify(formData));
      this.$emit('response-received', this.response);
      this.loading = false;
    },
    formatFilesize: formatFilesize
  }
});

;// CONCATENATED MODULE: ./vue/components/forms/file-edit-form.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/forms/file-edit-form.vue




;
const file_edit_form_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(file_edit_formvue_type_script_lang_js, [['render',file_edit_formvue_type_template_id_90373e08_render]])

/* harmony default export */ const file_edit_form = (file_edit_form_exports_);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/forms/folder-edit-form.vue?vue&type=template&id=3e0187ca


const folder_edit_formvue_type_template_id_3e0187ca_hoisted_1 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", {
  class: "divider",
  "data-content": "Metadaten des Ordners"
}, null, -1)
const folder_edit_formvue_type_template_id_3e0187ca_hoisted_2 = { class: "form-group" }
const folder_edit_formvue_type_template_id_3e0187ca_hoisted_3 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("label", { for: "title_input" }, "Titel", -1)
const folder_edit_formvue_type_template_id_3e0187ca_hoisted_4 = { class: "form-group" }
const folder_edit_formvue_type_template_id_3e0187ca_hoisted_5 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("label", { for: "description_input" }, "Beschreibung", -1)
const folder_edit_formvue_type_template_id_3e0187ca_hoisted_6 = { class: "form-group" }
const folder_edit_formvue_type_template_id_3e0187ca_hoisted_7 = ["disabled"]

function folder_edit_formvue_type_template_id_3e0187ca_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("form", {
    action: "/",
    onSubmit: _cache[3] || (_cache[3] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)(() => {}, ["prevent"]))
  }, [
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($props.folderInfo.path), 1),
    folder_edit_formvue_type_template_id_3e0187ca_hoisted_1,
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", folder_edit_formvue_type_template_id_3e0187ca_hoisted_2, [
      folder_edit_formvue_type_template_id_3e0187ca_hoisted_3,
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withDirectives)((0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("input", {
        id: "title_input",
        class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(["form-input", {'is-error': $options.errors.title}]),
        "onUpdate:modelValue": _cache[0] || (_cache[0] = $event => ((_ctx.form.title) = $event)),
        autocomplete: "off"
      }, null, 2), [
        [external_commonjs_vue_commonjs2_vue_root_Vue_.vModelText, _ctx.form.title]
      ])
    ]),
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", folder_edit_formvue_type_template_id_3e0187ca_hoisted_4, [
      folder_edit_formvue_type_template_id_3e0187ca_hoisted_5,
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withDirectives)((0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("textarea", {
        rows: "2",
        id: "description_input",
        class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(["form-input", {'is-error': $options.errors.description}]),
        "onUpdate:modelValue": _cache[1] || (_cache[1] = $event => ((_ctx.form.description) = $event))
      }, null, 2), [
        [external_commonjs_vue_commonjs2_vue_root_Vue_.vModelText, _ctx.form.description]
      ])
    ]),
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", folder_edit_formvue_type_template_id_3e0187ca_hoisted_6, [
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("button", {
        type: "button",
        onClick: _cache[2] || (_cache[2] = (...args) => ($options.submit && $options.submit(...args))),
        class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(["btn btn-success col-12", {'loading': _ctx.loading}]),
        disabled: _ctx.loading
      }, "Änderungen speichern ", 10, folder_edit_formvue_type_template_id_3e0187ca_hoisted_7)
    ])
  ], 32))
}
;// CONCATENATED MODULE: ./vue/components/forms/folder-edit-form.vue?vue&type=template&id=3e0187ca

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/forms/folder-edit-form.vue?vue&type=script&lang=js



/* harmony default export */ const folder_edit_formvue_type_script_lang_js = ({
  name: 'folder-edit-form',
  emits: ['response-received'],
  props: {
    initialData: {type: Object, default: {}},
    folderInfo: {type: Object, default: {}},
    url: {type: String, default: ""}
  },
  data: () => ({
    form: {},
    response: {},
    loading: false
  }),
  computed: {
    errors() {
      return this.response ? (this.response.errors || {}) : {};
    },
    message() {
      return this.response ? this.response.message : "";
    }
  },
  watch: {
    initialData(newValue) {
      this.form = Object.assign({}, this.form, newValue);
    }
  },
  methods: {
    async submit() {
      this.loading = true;

      /* avoid strings "null" with null values */

      let formData = {};

      Object.keys(this.form).forEach(key => {
        if (this.form[key] !== null) {
          formData[key] = this.form[key];
        }
      });

      this.response = await SimpleFetch(this.url, 'POST', {}, JSON.stringify(formData));
      this.$emit('response-received', this.response);
      this.loading = false;
    }
  }
});

;// CONCATENATED MODULE: ./vue/components/forms/folder-edit-form.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/forms/folder-edit-form.vue




;
const folder_edit_form_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(folder_edit_formvue_type_script_lang_js, [['render',folder_edit_formvue_type_template_id_3e0187ca_render]])

/* harmony default export */ const folder_edit_form = (folder_edit_form_exports_);
;// CONCATENATED MODULE: ./vue/util/promised-xhr.js
function PromisedXhr(url, method = 'GET', headers = {}, payload = null, timeout = null, progressCallback = null, cancelToken = null) {

    if(!headers['X-CSRF-Token'] && document.querySelector('meta[name="csrf-token"]')) {
        headers['X-CSRF-Token'] = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
    }
    headers['Content-type'] = headers['Content-type'] || 'application/x-www-form-urlencoded';
    headers['X-Requested-With'] = 'XMLHttpRequest';

    let xhr = new XMLHttpRequest();

    let xhrPromise = new Promise((resolve, reject) => {
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
            xhr.ontimeout = () => {reject({ status: 408, statusText: 'Request timeout.' });};
        }

        xhr.send(payload);
    });

    xhrPromise.cancel = () => xhr.abort();

    return xhrPromise;
}

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/filemanager/filemanager.vue?vue&type=script&lang=js



















/* harmony default export */ const filemanagervue_type_script_lang_js = ({
  name: 'filemanager',
  components: {
    FolderTree: filemanager_folder_tree,
    'sortable': sortable,
    'circular-progress': circular_progress,
    'confirm': vx_vue_confirm,
    'alert': vx_vue_alert,
    'file-edit-form': file_edit_form,
    'folder-edit-form': folder_edit_form,
    'filemanager-add': filemanager_add,
    'filemanager-search': filemanager_search,
    'filemanager-actions': filemanager_actions,
    'filemanager-breadcrumbs': filemanager_breadcrumbs,
    'filemanager-folder-tree': filemanager_folder_tree
  },

  data() {
    return {
      currentFolder: null,
      files: [],
      folders: [],
      breadcrumbs: [],
      toRename: null,
      showAddActivities: false,
      indicateDrag: false,
      upload: { files: [], progressing: false, cancelToken: {} },
      progress: { total: null, loaded: null, file: null },
      showFileForm: false,
      showFolderForm: false,
      editFormData: {},
      editMetaData: {}
    }
  },

  computed: {
    directoryEntries() {
      let folders = this.folders;
      let files = this.files;
      folders.forEach(item => {
        item.isFolder = true;
        item.key = 'd' + item.id
      });
      files.forEach(item => item.key = item.id);
      return [...folders, ...files];
    },
    checkedFiles() {
      return this.files.filter(({checked}) => checked);
    },
    checkedFolders() {
      return this.folders.filter(({checked}) => checked);
    }
  },

  props: {
    routes: { type: Object, required: true },
    limits: { type: Object, default: () => ({}) },
    columns: { type: Array, required: true },
    folder: { type: String, default: '' },
    initSort: Object
  },

  watch: {
    folder(newValue) {
      this.currentFolder = newValue;
    }
  },

  async created() {
    let response = await SimpleFetch(url_query.create(this.routes.init, {folder: this.folder}));

    this.breadcrumbs = response.breadcrumbs || [];
    this.files = response.files || [];
    this.folders = response.folders || [];
    this.currentFolder = response.currentFolder || null;
  },
  mounted() {
    document.body.addEventListener('click', this.handleBodyClick);
  },
  beforeUnmount() {
    document.body.removeEventListener('click', this.handleBodyClick);
  },

  methods: {
    handleBodyClick() {
      this.showAddActivities = false;
    },
    async readFolder(id) {
      let response = await SimpleFetch(url_query.create(this.routes.readFolder, {folder: id}));

      if (response.success) {
        this.files = response.files || [];
        this.folders = response.folders || [];
        this.currentFolder = id;
        if (response.breadcrumbs) {
          this.breadcrumbs = response.breadcrumbs;
        }
      }
    },
    async delSelection() {
      let response = await SimpleFetch(url_query.create(this.routes.delSelection, {
        files: this.checkedFiles.map(({id}) => id).join(","),
        folders: this.checkedFolders.map(({id}) => id).join(",")
      }), 'DELETE');
      if (response.success) {
        this.files = response.files || [];
        this.folders = response.folders || [];
      } else if (response.error) {
        this.$emit('response-received', response);
        this.files = response.files || this.files;
        this.folders = response.folders || this.folders;
      }
    },
    async moveSelection() {
      let folder = await this.$refs['folder-tree'].open(this.routes.getFoldersTree, this.currentFolder);

      if (folder !== false) {
        let response = await SimpleFetch(url_query.create(this.routes.moveSelection, {destination: folder.id}), 'POST', {}, JSON.stringify({
          files: this.checkedFiles.map(({id}) => id),
          folders: this.checkedFolders.map(({id}) => id)
        }));

        if (response.success) {
          this.files = response.files || [];
          this.folders = response.folders || [];
        } else if (response.error) {
          this.$emit('response-received', response);
          this.files = response.files || this.files;
          this.folders = response.folders || this.folders;
        }
      }
    },
    async editFile(row) {
      this.showFileForm = true;
      let response = await SimpleFetch(url_query.create(this.routes.getFile, {id: row.id}));
      this.editFormData = response.form || {};
      this.editMetaData = response.fileInfo || {};
      this.editFormData.id = row.id;
    },
    async editFolder(row) {
      this.showFolderForm = true;
      let response = await SimpleFetch(url_query.create(this.routes.getFolder, {id: row.id}));
      this.editFormData = response.form || {};
      this.editMetaData = response.folderInfo || {};
      this.editFormData.id = row.id;
    },
    async delFile(row) {
      if (await this.$refs.confirm.open('Datei löschen', "'" + row.name + "' wirklich löschen?")) {
        let response = await SimpleFetch(url_query.create(this.routes.delFile, {id: row.id}), 'DELETE');
        if (response.success) {
          this.files.splice(this.files.findIndex(item => row === item), 1);
        }
      }
    },
    async renameFile(event) {
      let name = event.target.value.trim();
      if (name && this.toRename) {
        let response = await SimpleFetch(this.routes.renameFile, 'POST', {}, JSON.stringify({
          name: name,
          id: this.toRename.id
        }));
        if (response.success) {
          this.toRename.name = response.name || name;
          this.toRename = null;
        }
      }
    },
    async renameFolder(event) {
      let name = event.target.value.trim();
      if (name && this.toRename) {
        let response = await SimpleFetch(this.routes.renameFolder, 'POST', {}, JSON.stringify({
          name: name,
          folder: this.toRename.id
        }));
        if (response.success) {
          this.toRename.name = response.name || name;
          this.toRename = null;
        }
      }
    },
    async delFolder(row) {
      if (await this.$refs.confirm.open('Verzeichnis löschen', "'" + row.name + "' und enthaltene Dateien wirklich löschen?", {cancelLabel: "Abbrechen"})) {
        let response = await SimpleFetch(url_query.create(this.routes.delFolder, {folder: row.id}), 'DELETE');
        if (response.success) {
          this.folders.splice(this.folders.findIndex(item => row === item), 1);
        }
      }
    },
    async createFolder(name) {
      this.showAddActivities = false;

      let response = await SimpleFetch(this.routes.addFolder, 'POST', {}, JSON.stringify({
        name: name,
        parent: this.currentFolder
      }));
      if (response.folder) {
        this.folders.push(response.folder);
      }
    },
    async moveFile(row) {
      let folder = await this.$refs['folder-tree'].open(this.routes.getFoldersTree, this.currentFolder);

      if (folder !== false) {
        let response = await SimpleFetch(this.routes.moveFile, 'POST', {}, JSON.stringify({
          id: row.id,
          folderId: folder.id
        }));
        if (response.success) {
          this.files.splice(this.files.findIndex(item => row === item), 1);
        } else {
          this.$emit('response-received', response);
        }
      }
    },
    uploadDraggedFiles(event) {
      this.indicateDrag = false;
      this.uploadInputFiles(event.dataTransfer.files || []);
    },
    uploadInputFiles(files) {
      this.showAddActivities = false;
      this.upload.files.push(...files);
      if (!this.upload.progressing) {
        this.upload.progressing = true;
        this.progress.loaded = 0;
        this.handleUploads();
      }
    },
    async handleUploads() {
      let file = null, response = null;
      while ((file = this.upload.files.shift()) !== undefined) {

        if (this.limits.maxUploadFilesize && this.limits.maxUploadFilesize < file.size) {
          await this.$refs.alert.open('Datei zu groß', "'" + file.name + "' übersteigt die maximale Uploadgröße.");
          continue;
        }
        this.progress.file = file.name;
        try {
          response = await PromisedXhr(
              url_query.create(this.routes.uploadFile, {folder: this.currentFolder}),
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
        } catch (err) {
          this.upload.files = [];
          this.upload.progressing = false;
          return;
        }

        if (!response.success) {
          this.$emit('response-received', response);
          this.upload.files = [];
          this.upload.progressing = false;
          return;
        }
      }
      this.upload.progressing = false;
      if (response) {
        this.$emit('response-received', {success: true, message: response.message || 'File upload successful'});
      }
    },
    cancelUpload() {
      if (this.upload.cancelToken.cancel) {
        this.upload.cancelToken.cancel();
        this.upload.cancelToken = {};
      }
    },
    doSearch(term) {
      if (term.trim().length > 2) {
        return SimpleFetch(url_query.create(this.routes.search, {search: term}));
      }
      return {files: [], folders: []};
    },
    formatFilesize: formatFilesize
  },

  directives: {
    focus: Focus,
    checkIndeterminate: {
      updated(el, binding, vnode) {
        let filteredLength = binding.instance.checkedFolders.length + binding.instance.checkedFiles.length;
        if (!filteredLength) {
          el.checked = false;
        }
        el.indeterminate = filteredLength && filteredLength !== binding.instance.folders.length + binding.instance.files.length;
      }
    }
  }
});

;// CONCATENATED MODULE: ./vue/components/filemanager/filemanager.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/filemanager/filemanager.vue




;
const filemanager_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(filemanagervue_type_script_lang_js, [['render',filemanagervue_type_template_id_42f6cf2c_render]])

/* harmony default export */ const filemanager = (filemanager_exports_);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/tab.vue?vue&type=template&id=e613b9ee


const tabvue_type_template_id_e613b9ee_hoisted_1 = ["data-badge", "onClick"]
const tabvue_type_template_id_e613b9ee_hoisted_2 = {
  key: 0,
  class: "tab-item tab-action"
}

function tabvue_type_template_id_e613b9ee_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", null, [
    ($props.items.length)
      ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("ul", {
          key: 0,
          class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(["tab", { 'tab-block': $props.block }])
        }, [
          ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(true), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_.Fragment, null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.renderList)($props.items, (item, ndx) => {
            return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("li", {
              key: ndx,
              class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(["tab-item", { active: $props.activeIndex === ndx }])
            }, [
              (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("a", {
                "data-badge": item.badge,
                onClick: $event => ($options.itemOnClick(item)),
                class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)({ disabled: item.disabled })
              }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)(item.name), 11, tabvue_type_template_id_e613b9ee_hoisted_1)
            ], 2))
          }), 128)),
          ($options.hasActionSlot)
            ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("li", tabvue_type_template_id_e613b9ee_hoisted_2, [
                (0,external_commonjs_vue_commonjs2_vue_root_Vue_.renderSlot)(_ctx.$slots, "action")
              ]))
            : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true)
        ], 2))
      : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true)
  ]))
}
;// CONCATENATED MODULE: ./vue/components/vx-vue/tab.vue?vue&type=template&id=e613b9ee

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/tab.vue?vue&type=script&lang=js

/* harmony default export */ const tabvue_type_script_lang_js = ({
  name: 'tab',
  emits: ['update:active-index'],
  props: {
    items: {
      type: Array,
      default: () => ([])
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
      if (!item.disabled) {
        this.activeTab = item;
        this.$emit('update:active-index', this.items.indexOf(item));
      }
    }
  }
});

;// CONCATENATED MODULE: ./vue/components/vx-vue/tab.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/vx-vue/tab.vue




;
const tab_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(tabvue_type_script_lang_js, [['render',tabvue_type_template_id_e613b9ee_render]])

/* harmony default export */ const tab = (tab_exports_);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/formelements/password-input.vue?vue&type=template&id=0aec82d5&scoped=true


const password_inputvue_type_template_id_0aec82d5_scoped_true_withScopeId = n => (_pushScopeId("data-v-0aec82d5"),n=n(),_popScopeId(),n)
const password_inputvue_type_template_id_0aec82d5_scoped_true_hoisted_1 = { class: "form-group is-password" }
const password_inputvue_type_template_id_0aec82d5_scoped_true_hoisted_2 = ["value", "type"]

function password_inputvue_type_template_id_0aec82d5_scoped_true_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", password_inputvue_type_template_id_0aec82d5_scoped_true_hoisted_1, [
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("input", (0,external_commonjs_vue_commonjs2_vue_root_Vue_.mergeProps)(_ctx.$attrs, {
      value: $props.modelValue,
      class: "form-input",
      type: $data.show ? 'text': 'password',
      onInput: _cache[0] || (_cache[0] = $event => (_ctx.$emit('update:modelValue', $event.target.value)))
    }), null, 16, password_inputvue_type_template_id_0aec82d5_scoped_true_hoisted_2),
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("a", {
      class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)({ 'show': $data.show }),
      href: "#",
      onClick: _cache[1] || (_cache[1] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)($event => ($data.show = !$data.show), ["prevent"]))
    }, null, 2)
  ]))
}
;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/password-input.vue?vue&type=template&id=0aec82d5&scoped=true

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/formelements/password-input.vue?vue&type=script&lang=js

    /* harmony default export */ const password_inputvue_type_script_lang_js = ({
      name: 'password-input',
      inheritAttrs: false,
      props: ['modelValue'],
      emits: ['update:modelValue'],
      data () { return {
        show: false
      }}
    });

;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/password-input.vue?vue&type=script&lang=js
 
// EXTERNAL MODULE: ./node_modules/vue-style-loader/index.js??clonedRuleSet-22.use[0]!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-22.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-22.use[2]!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-22.use[3]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-22.use[4]!./node_modules/style-resources-loader/lib/index.js??clonedRuleSet-22.use[5]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/formelements/password-input.vue?vue&type=style&index=0&id=0aec82d5&scoped=true&lang=scss
var password_inputvue_type_style_index_0_id_0aec82d5_scoped_true_lang_scss = __webpack_require__(26);
;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/password-input.vue?vue&type=style&index=0&id=0aec82d5&scoped=true&lang=scss

;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/password-input.vue




;


const password_input_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(password_inputvue_type_script_lang_js, [['render',password_inputvue_type_template_id_0aec82d5_scoped_true_render],['__scopeId',"data-v-0aec82d5"]])

/* harmony default export */ const password_input = (password_input_exports_);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/cookie-consent.vue?vue&type=template&id=6fdf6898


const cookie_consentvue_type_template_id_6fdf6898_hoisted_1 = { class: "content" }
const cookie_consentvue_type_template_id_6fdf6898_hoisted_2 = { class: "buttons" }
const cookie_consentvue_type_template_id_6fdf6898_hoisted_3 = ["target", "href"]

function cookie_consentvue_type_template_id_6fdf6898_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_.Transition, {
    appear: "",
    name: $props.transitionName
  }, {
    default: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withCtx)(() => [
      ($data.isOpen)
        ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", {
            key: 0,
            class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(["cookie-consent", $options.containerPosition])
          }, [
            (0,external_commonjs_vue_commonjs2_vue_root_Vue_.renderSlot)(_ctx.$slots, "default", {
              accept: $options.accept,
              close: $options.close,
              decline: $options.decline,
              open: $options.open
            }, () => [
              (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", cookie_consentvue_type_template_id_6fdf6898_hoisted_1, [
                (0,external_commonjs_vue_commonjs2_vue_root_Vue_.renderSlot)(_ctx.$slots, "message", {}, () => [
                  (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createTextVNode)((0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($props.message), 1)
                ])
              ]),
              (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", cookie_consentvue_type_template_id_6fdf6898_hoisted_2, [
                ($props.buttonLink)
                  ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("a", {
                      key: 0,
                      target: $options.target,
                      href: $props.buttonLink,
                      class: "btn-link"
                    }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($props.buttonLinkText), 9, cookie_consentvue_type_template_id_6fdf6898_hoisted_3))
                  : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true),
                (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("button", {
                  onClick: _cache[0] || (_cache[0] = (...args) => ($options.accept && $options.accept(...args))),
                  class: "btn-accept"
                }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($props.buttonText), 1),
                ($props.buttonDecline)
                  ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("button", {
                      key: 1,
                      onClick: _cache[1] || (_cache[1] = (...args) => ($options.decline && $options.decline(...args))),
                      class: "btn-decline"
                    }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($props.buttonDeclineText), 1))
                  : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true)
              ])
            ])
          ], 2))
        : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true)
    ]),
    _: 3
  }, 8, ["name"]))
}
;// CONCATENATED MODULE: ./vue/components/cookie-consent.vue?vue&type=template&id=6fdf6898

;// CONCATENATED MODULE: ./vue/util/cookie.js
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


;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/cookie-consent.vue?vue&type=script&lang=js



/* harmony default export */ const cookie_consentvue_type_script_lang_js = ({
  name: 'cookie-consent',
  emits: ['accept', 'decline', 'revoke'],
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
      default: () => ({}),
      required: false
    }
  },

  data() {
    return {
      isOpen: false
    }
  },

  computed: {
    containerPosition() {
      return this.position;
    },
    target() {
      return this.buttonLinkNewTab ? '_blank' : '_self';
    }
  },

  created() {
    if (!this.getVisited()) {
      this.isOpen = true;
    }
  },
  mounted() {
    if (this.isAccepted()) {
      this.$emit('accept');
    }
  },
  methods: {
    setVisited() {
      set(this.storageName, true, {...this.cookieOptions, expires: '1Y'})
    },
    getVisited() {
      let visited = false;
      visited = get(this.storageName);
      if (typeof visited === 'string') {
        visited = JSON.parse(visited);
      }
      return !(visited === null || visited === undefined);
    },
    isAccepted() {
      let accepted = false;
      accepted = get(this.storageName);
      if (typeof accepted === 'string') {
        accepted = JSON.parse(accepted);
      }
      return accepted;
    },
    accept() {
      set(this.storageName, true, {...this.cookieOptions, expires: '1Y'})
      this.setVisited();
      this.isOpen = false;
      this.$emit('accept');
    },
    decline() {
      set(this.storageName, false, {...this.cookieOptions, expires: '1Y'})
      this.setVisited();
      this.isOpen = false;
      this.$emit('decline');
    },
    revoke() {
      remove(this.storageName);
      this.isOpen = true;
      this.$emit('revoke');
    },
    open() {
      if (!this.getVisited()) {
        this.isOpen = true;
      }
    },
    close() {
      this.isOpen = false;
      this.$emit('close');
    }
  }
});

;// CONCATENATED MODULE: ./vue/components/cookie-consent.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/cookie-consent.vue




;
const cookie_consent_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(cookie_consentvue_type_script_lang_js, [['render',cookie_consentvue_type_template_id_6fdf6898_render]])

/* harmony default export */ const cookie_consent = (cookie_consent_exports_);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/pagination.vue?vue&type=template&id=7c778d31


const paginationvue_type_template_id_7c778d31_hoisted_1 = { class: "pagination" }
const paginationvue_type_template_id_7c778d31_hoisted_2 = ["onClick"]
const paginationvue_type_template_id_7c778d31_hoisted_3 = { key: 1 }

function paginationvue_type_template_id_7c778d31_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", null, [
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("ul", paginationvue_type_template_id_7c778d31_hoisted_1, [
      ($props.showNavButtons)
        ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("li", {
            key: 0,
            class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(["page-item", { disabled: $data.currentPage <= 1 }])
          }, [
            (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("a", {
              tabindex: "-1",
              onClick: _cache[0] || (_cache[0] = (...args) => ($options.prevPage && $options.prevPage(...args))),
              class: "menu-item"
            }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($props.prevText), 1)
          ], 2))
        : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true),
      ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(true), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_.Fragment, null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.renderList)($options.pagesToShow, (page, idx) => {
        return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("li", {
          key: idx,
          class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(["page-item", {active: $data.currentPage === page}])
        }, [
          (page !== 'dots')
            ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("a", {
                key: 0,
                onClick: $event => ($options.pageClick(page))
              }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)(page), 9, paginationvue_type_template_id_7c778d31_hoisted_2))
            : ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("span", paginationvue_type_template_id_7c778d31_hoisted_3, "…"))
        ], 2))
      }), 128)),
      ($props.showNavButtons)
        ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("li", {
            key: 1,
            class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(["page-item", { disabled: $data.currentPage >= $data.maxPage }])
          }, [
            (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("a", {
              tabindex: "-1",
              onClick: _cache[1] || (_cache[1] = (...args) => ($options.nextPage && $options.nextPage(...args)))
            }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($props.nextText), 1)
          ], 2))
        : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true)
    ])
  ]))
}
;// CONCATENATED MODULE: ./vue/components/vx-vue/pagination.vue?vue&type=template&id=7c778d31

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/pagination.vue?vue&type=script&lang=js


/* harmony default export */ const paginationvue_type_script_lang_js = ({
  name: 'pagination',
  emits: ['update:page', 'update:total'],
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

;// CONCATENATED MODULE: ./vue/components/vx-vue/pagination.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/vx-vue/pagination.vue




;
const pagination_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(paginationvue_type_script_lang_js, [['render',paginationvue_type_template_id_7c778d31_render]])

/* harmony default export */ const pagination = (pagination_exports_);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vue-ckeditor.vue?vue&type=template&id=12f62f34


const vue_ckeditorvue_type_template_id_12f62f34_hoisted_1 = { class: "ckeditor" }
const vue_ckeditorvue_type_template_id_12f62f34_hoisted_2 = ["name", "id", "value", "types", "config", "disabled"]

function vue_ckeditorvue_type_template_id_12f62f34_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", vue_ckeditorvue_type_template_id_12f62f34_hoisted_1, [
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("textarea", {
      name: $props.name,
      id: $props.id,
      value: $props.modelValue,
      types: $props.types,
      config: $props.config,
      disabled: $props.readOnlyMode
    }, "\n    ", 8, vue_ckeditorvue_type_template_id_12f62f34_hoisted_2)
  ]))
}
;// CONCATENATED MODULE: ./vue/components/vue-ckeditor.vue?vue&type=template&id=12f62f34

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vue-ckeditor.vue?vue&type=script&lang=js

let inc = new Date().getTime();

/* harmony default export */ const vue_ckeditorvue_type_script_lang_js = ({
  name: 'vue-ckeditor',
  emits: ['blur', 'focus', 'contentDom', 'dialogDefinition', 'fileUploadRequest', 'fileUploadResponse', 'update:modelValue'],
  props: {
    modelValue: String,
    name: { type: String, default: `editor-${++inc}` },
    id: { type: String, default:`editor-${inc}` },
    types: { type: String, default: `classic` },
    config: { type: Object, default: {} },
    instanceReadyCallback: Function,
    readOnlyMode: { type: Boolean, default: false }
  },
  data: () => ({
      instanceValue: ''
  }),
  computed: {
    instance() {
      return CKEDITOR.instances[this.id];
    }
  },
  watch: {
    modelValue(val) {
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

      this.instance.setData(this.modelValue);

      this.instance.on('instanceReady', () => {
        this.instance.setData(this.modelValue);
      });

      // Ckeditor change event
      this.instance.on('change', this.onChange);

      // Ckeditor mode html or source
      this.instance.on('mode', this.onMode);

      // Ckeditor blur event
      this.instance.on('blur', evt => { this.$emit('blur', evt) });

      // Ckeditor focus event
      this.instance.on('focus', evt => { this.$emit('focus', evt) });

      // Ckeditor contentDom event
      this.instance.on('contentDom', evt => { this.$emit('contentDom', evt) });

      // Ckeditor dialog definition event
      CKEDITOR.on('dialogDefinition', evt => { this.$emit('dialogDefinition', evt) });

      // Ckeditor file upload request event
      this.instance.on('fileUploadRequest', evt => { this.$emit('fileUploadRequest', evt) });

      // Ckditor file upload response event
      this.instance.on('fileUploadResponse', evt => {
        this.$nextTick( () => { this.onChange() });
        this.$emit('fileUploadResponse', evt);
      });

      // Listen for instanceReady event
      if (typeof this.instanceReadyCallback !== 'undefined') {
        this.instance.on('instanceReady', this.instanceReadyCallback);
      }
    }
  },
  beforeUnmount() {
    try {
      let editor = window['CKEDITOR'];
      if (editor.instances && editor.instances[this.id]) {
        editor.instances[this.id].destroy();
      }
    } catch (e) {}
  },
  methods: {
    update(val) {
      if (this.instanceValue !== val) {
        this.instance.setData(val, { internal: false });
        this.instanceValue = val;
      }
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
      if (html !== this.modelValue) {
        this.$emit('update:modelValue', html);
        this.instanceValue = html;
      }
    }
  }
});

;// CONCATENATED MODULE: ./vue/components/vue-ckeditor.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/vue-ckeditor.vue




;
const vue_ckeditor_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(vue_ckeditorvue_type_script_lang_js, [['render',vue_ckeditorvue_type_template_id_12f62f34_render]])

/* harmony default export */ const vue_ckeditor = (vue_ckeditor_exports_);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/forms/profile-form.vue?vue&type=template&id=1e77d4c6


const profile_formvue_type_template_id_1e77d4c6_hoisted_1 = { class: "form-sect" }
const profile_formvue_type_template_id_1e77d4c6_hoisted_2 = { class: "form-sect" }
const profile_formvue_type_template_id_1e77d4c6_hoisted_3 = { class: "form-group" }
const profile_formvue_type_template_id_1e77d4c6_hoisted_4 = ["for"]
const profile_formvue_type_template_id_1e77d4c6_hoisted_5 = { class: "col-9" }
const profile_formvue_type_template_id_1e77d4c6_hoisted_6 = {
  key: 0,
  class: "form-input-hint vx-error-box error"
}
const profile_formvue_type_template_id_1e77d4c6_hoisted_7 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", {
  class: "divider text-center",
  "data-content": "Benachrichtigungen"
}, null, -1)
const profile_formvue_type_template_id_1e77d4c6_hoisted_8 = { class: "form-sect off-3" }
const profile_formvue_type_template_id_1e77d4c6_hoisted_9 = { class: "form-group" }
const profile_formvue_type_template_id_1e77d4c6_hoisted_10 = { class: "form-switch" }
const profile_formvue_type_template_id_1e77d4c6_hoisted_11 = ["value"]
const profile_formvue_type_template_id_1e77d4c6_hoisted_12 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("i", { class: "form-icon" }, null, -1)
const profile_formvue_type_template_id_1e77d4c6_hoisted_13 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", { class: "divider" }, null, -1)
const profile_formvue_type_template_id_1e77d4c6_hoisted_14 = { class: "form-base" }
const profile_formvue_type_template_id_1e77d4c6_hoisted_15 = { class: "form-group off-3" }
const profile_formvue_type_template_id_1e77d4c6_hoisted_16 = ["disabled"]

function profile_formvue_type_template_id_1e77d4c6_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("form", {
    action: "/",
    class: "form-horizontal",
    onSubmit: _cache[2] || (_cache[2] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)(() => {}, ["prevent"]))
  }, [
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", profile_formvue_type_template_id_1e77d4c6_hoisted_1, [
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", profile_formvue_type_template_id_1e77d4c6_hoisted_2, [
        ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(true), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_.Fragment, null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.renderList)($data.elements, (element) => {
          return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", profile_formvue_type_template_id_1e77d4c6_hoisted_3, [
            (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("label", {
              class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(["form-label col-3",  { required: element.required, 'text-error': $options.errors[element.model] }]),
              for: element.model + '_' + element.type
            }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)(element.label), 11, profile_formvue_type_template_id_1e77d4c6_hoisted_4),
            (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", profile_formvue_type_template_id_1e77d4c6_hoisted_5, [
              ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createBlock)((0,external_commonjs_vue_commonjs2_vue_root_Vue_.resolveDynamicComponent)(element.type || 'form-input'), {
                id: element.model + '_' + element.type,
                name: element.model,
                modelValue: $data.form[element.model],
                "onUpdate:modelValue": $event => (($data.form[element.model]) = $event)
              }, null, 8, ["id", "name", "modelValue", "onUpdate:modelValue"])),
              ($options.errors[element.model])
                ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("p", profile_formvue_type_template_id_1e77d4c6_hoisted_6, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($options.errors[element.model]), 1))
                : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true)
            ])
          ]))
        }), 256))
      ])
    ]),
    ($props.notifications.length)
      ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_.Fragment, { key: 0 }, [
          profile_formvue_type_template_id_1e77d4c6_hoisted_7,
          (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", profile_formvue_type_template_id_1e77d4c6_hoisted_8, [
            ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(true), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_.Fragment, null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.renderList)($props.notifications, (notification) => {
              return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", profile_formvue_type_template_id_1e77d4c6_hoisted_9, [
                (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("label", profile_formvue_type_template_id_1e77d4c6_hoisted_10, [
                  (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withDirectives)((0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("input", {
                    name: "notification[]",
                    value: notification.alias,
                    type: "checkbox",
                    "onUpdate:modelValue": _cache[0] || (_cache[0] = $event => (($data.form.notifications) = $event))
                  }, null, 8, profile_formvue_type_template_id_1e77d4c6_hoisted_11), [
                    [external_commonjs_vue_commonjs2_vue_root_Vue_.vModelCheckbox, $data.form.notifications]
                  ]),
                  profile_formvue_type_template_id_1e77d4c6_hoisted_12,
                  (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createTextVNode)((0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)(notification.label), 1)
                ])
              ]))
            }), 256))
          ])
        ], 64))
      : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true),
    profile_formvue_type_template_id_1e77d4c6_hoisted_13,
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", profile_formvue_type_template_id_1e77d4c6_hoisted_14, [
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", profile_formvue_type_template_id_1e77d4c6_hoisted_15, [
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("button", {
          name: "submit_profile",
          type: "button",
          class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(["btn btn-success", {'loading': $data.loading}]),
          disabled: $data.loading,
          onClick: _cache[1] || (_cache[1] = (...args) => ($options.submit && $options.submit(...args)))
        }, "Änderungen speichern", 10, profile_formvue_type_template_id_1e77d4c6_hoisted_16)
      ])
    ])
  ], 32))
}
;// CONCATENATED MODULE: ./vue/components/forms/profile-form.vue?vue&type=template&id=1e77d4c6

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/forms/profile-form.vue?vue&type=script&lang=js






/* harmony default export */ const profile_formvue_type_script_lang_js = ({
  components: {
    'password-input': password_input,
    'form-input': form_input
  },
  props: {
    url: { type: String, required: true },
    initialData: { type: Object, default: () => ({}) },
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

;// CONCATENATED MODULE: ./vue/components/forms/profile-form.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/forms/profile-form.vue




;
const profile_form_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(profile_formvue_type_script_lang_js, [['render',profile_formvue_type_template_id_1e77d4c6_render]])

/* harmony default export */ const profile_form = (profile_form_exports_);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/forms/user-form.vue?vue&type=template&id=7f01d0af


const user_formvue_type_template_id_7f01d0af_hoisted_1 = { class: "form-sect" }
const user_formvue_type_template_id_7f01d0af_hoisted_2 = { class: "form-group" }
const user_formvue_type_template_id_7f01d0af_hoisted_3 = ["for"]
const user_formvue_type_template_id_7f01d0af_hoisted_4 = { class: "col-9" }
const user_formvue_type_template_id_7f01d0af_hoisted_5 = {
  key: 0,
  class: "form-input-hint vx-error-box error"
}
const user_formvue_type_template_id_7f01d0af_hoisted_6 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", { class: "divider" }, null, -1)
const user_formvue_type_template_id_7f01d0af_hoisted_7 = { class: "form-base" }
const user_formvue_type_template_id_7f01d0af_hoisted_8 = { class: "form-group off-3" }
const user_formvue_type_template_id_7f01d0af_hoisted_9 = ["disabled"]

function user_formvue_type_template_id_7f01d0af_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("form", {
    action: "/",
    class: "form-horizontal",
    onSubmit: _cache[1] || (_cache[1] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)(() => {}, ["prevent"]))
  }, [
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", user_formvue_type_template_id_7f01d0af_hoisted_1, [
      ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(true), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_.Fragment, null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.renderList)(_ctx.elements, (element) => {
        return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", user_formvue_type_template_id_7f01d0af_hoisted_2, [
          (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("label", {
            class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(["form-label col-3",  { required: element.required, 'text-error': $options.errors[element.model] }]),
            for: element.model + '_' + element.type
          }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)(element.label), 11, user_formvue_type_template_id_7f01d0af_hoisted_3),
          (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", user_formvue_type_template_id_7f01d0af_hoisted_4, [
            ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createBlock)((0,external_commonjs_vue_commonjs2_vue_root_Vue_.resolveDynamicComponent)(element.type || 'form-input'), {
              id: element.model + '_' + element.type,
              name: element.model,
              modelValue: _ctx.form[element.model],
              "onUpdate:modelValue": $event => ((_ctx.form[element.model]) = $event),
              options: element.options
            }, null, 8, ["id", "name", "modelValue", "onUpdate:modelValue", "options"])),
            ($options.errors[element.model])
              ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("p", user_formvue_type_template_id_7f01d0af_hoisted_5, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($options.errors[element.model]), 1))
              : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true)
          ])
        ]))
      }), 256))
    ]),
    user_formvue_type_template_id_7f01d0af_hoisted_6,
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", user_formvue_type_template_id_7f01d0af_hoisted_7, [
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", user_formvue_type_template_id_7f01d0af_hoisted_8, [
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("button", {
          name: "submit_user",
          type: "button",
          class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(["btn btn-success", {'loading': _ctx.loading}]),
          disabled: _ctx.loading,
          onClick: _cache[0] || (_cache[0] = (...args) => ($options.submit && $options.submit(...args)))
        }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)(_ctx.form.id ? 'Daten übernehmen' : 'User anlegen'), 11, user_formvue_type_template_id_7f01d0af_hoisted_9)
      ])
    ])
  ], 32))
}
;// CONCATENATED MODULE: ./vue/components/forms/user-form.vue?vue&type=template&id=7f01d0af

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/formelements/form-select.vue?vue&type=template&id=7295af77


const form_selectvue_type_template_id_7295af77_hoisted_1 = ["value"]
const form_selectvue_type_template_id_7295af77_hoisted_2 = ["value", "selected"]

function form_selectvue_type_template_id_7295af77_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("select", (0,external_commonjs_vue_commonjs2_vue_root_Vue_.mergeProps)(_ctx.$attrs, {
    value: $props.modelValue,
    class: "form-select",
    onChange: _cache[0] || (_cache[0] = $event => (_ctx.$emit('update:modelValue', $event.target.value)))
  }), [
    ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(true), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_.Fragment, null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.renderList)($props.options, (option) => {
      return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("option", {
        value: option.key || option.label || option,
        selected: (option.key || option.label || option) == $props.modelValue
      }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)(option.label || option), 9, form_selectvue_type_template_id_7295af77_hoisted_2))
    }), 256))
  ], 16, form_selectvue_type_template_id_7295af77_hoisted_1))
}
;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-select.vue?vue&type=template&id=7295af77

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/formelements/form-select.vue?vue&type=script&lang=js

    /* harmony default export */ const form_selectvue_type_script_lang_js = ({
      name: 'form-select',
      props: { options: Array, modelValue: [String, Number] },
      emits: ['update:modelValue']
    });

;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-select.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-select.vue




;
const form_select_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(form_selectvue_type_script_lang_js, [['render',form_selectvue_type_template_id_7295af77_render]])

/* harmony default export */ const form_select = (form_select_exports_);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/forms/user-form.vue?vue&type=script&lang=js


    
    
    
    

    /* harmony default export */ const user_formvue_type_script_lang_js = ({
        components: {
            'password-input': password_input,
            'form-input': form_input,
            'form-select': form_select
        },

        props: {
            url: { type: String, required: true },
            initialData: { type: Object, default: () => ({}) },
            options: Object
        },

        data: () => ({
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
        }),

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
              this.elements[this.elements.findIndex(({model}) => model === 'admingroupsid')].options = newValue.admingroups;
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

;// CONCATENATED MODULE: ./vue/components/forms/user-form.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/forms/user-form.vue




;
const user_form_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(user_formvue_type_script_lang_js, [['render',user_formvue_type_template_id_7f01d0af_render]])

/* harmony default export */ const user_form = (user_form_exports_);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/forms/article-form.vue?vue&type=template&id=2a5a57c8


const article_formvue_type_template_id_2a5a57c8_hoisted_1 = { class: "form-group" }
const article_formvue_type_template_id_2a5a57c8_hoisted_2 = ["for"]
const article_formvue_type_template_id_2a5a57c8_hoisted_3 = { class: "col-9" }
const article_formvue_type_template_id_2a5a57c8_hoisted_4 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", { class: "divider" }, null, -1)
const article_formvue_type_template_id_2a5a57c8_hoisted_5 = { class: "form-group" }
const article_formvue_type_template_id_2a5a57c8_hoisted_6 = ["disabled"]

function article_formvue_type_template_id_2a5a57c8_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("form", {
    action: "/",
    class: "form-horizontal",
    onSubmit: _cache[1] || (_cache[1] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)(() => {}, ["prevent"]))
  }, [
    ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(true), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_.Fragment, null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.renderList)($data.elements, (element) => {
      return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("div", article_formvue_type_template_id_2a5a57c8_hoisted_1, [
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("label", {
          class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(["form-label col-3",  { required: element.required, 'text-error': $options.errors[element.model] }]),
          for: element.model + '_' + element.type
        }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)(element.label), 11, article_formvue_type_template_id_2a5a57c8_hoisted_2),
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", article_formvue_type_template_id_2a5a57c8_hoisted_3, [
          ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createBlock)((0,external_commonjs_vue_commonjs2_vue_root_Vue_.resolveDynamicComponent)(element.type || 'form-input'), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.mergeProps)({
            id: element.model + '_' + element.type,
            name: element.model,
            options: element.options,
            modelValue: $data.form[element.model],
            "onUpdate:modelValue": $event => (($data.form[element.model]) = $event)
          }, element.attrs), null, 16, ["id", "name", "options", "modelValue", "onUpdate:modelValue"]))
        ])
      ]))
    }), 256)),
    article_formvue_type_template_id_2a5a57c8_hoisted_4,
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", article_formvue_type_template_id_2a5a57c8_hoisted_5, [
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("button", {
        type: "button",
        onClick: _cache[0] || (_cache[0] = (...args) => ($options.submit && $options.submit(...args))),
        class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(["btn btn-success off-3 col-3", {'loading': $data.loading}]),
        disabled: $data.loading
      }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($data.form.id ? 'Daten übernehmen' : 'Artikel anlegen'), 11, article_formvue_type_template_id_2a5a57c8_hoisted_6)
    ])
  ], 32))
}
;// CONCATENATED MODULE: ./vue/components/forms/article-form.vue?vue&type=template&id=2a5a57c8

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/formelements/form-textarea.vue?vue&type=template&id=e3a10e74


const form_textareavue_type_template_id_e3a10e74_hoisted_1 = ["value"]

function form_textareavue_type_template_id_e3a10e74_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("textarea", {
    value: $props.modelValue,
    class: "form-input",
    onInput: _cache[0] || (_cache[0] = $event => (_ctx.$emit('update:modelValue', $event.target.value)))
  }, null, 40, form_textareavue_type_template_id_e3a10e74_hoisted_1))
}
;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-textarea.vue?vue&type=template&id=e3a10e74

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/formelements/form-textarea.vue?vue&type=script&lang=js

/* harmony default export */ const form_textareavue_type_script_lang_js = ({
  name: 'form-textarea',
  props: ['modelValue'],
  emits: ['update:modelValue']
});

;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-textarea.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-textarea.vue




;
const form_textarea_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(form_textareavue_type_script_lang_js, [['render',form_textareavue_type_template_id_e3a10e74_render]])

/* harmony default export */ const form_textarea = (form_textarea_exports_);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/formelements/form-checkbox.vue?vue&type=template&id=6b569d2a


const form_checkboxvue_type_template_id_6b569d2a_hoisted_1 = { class: "form-checkbox" }
const form_checkboxvue_type_template_id_6b569d2a_hoisted_2 = ["checked"]
const form_checkboxvue_type_template_id_6b569d2a_hoisted_3 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("i", { class: "form-icon" }, null, -1)

function form_checkboxvue_type_template_id_6b569d2a_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("label", form_checkboxvue_type_template_id_6b569d2a_hoisted_1, [
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("input", (0,external_commonjs_vue_commonjs2_vue_root_Vue_.mergeProps)({
      value: "1",
      type: "checkbox",
      onChange: _cache[0] || (_cache[0] = $event => (_ctx.$emit('update:modelValue', $event.target.checked)))
    }, _ctx.$attrs, { checked: $props.modelValue }), null, 16, form_checkboxvue_type_template_id_6b569d2a_hoisted_2),
    form_checkboxvue_type_template_id_6b569d2a_hoisted_3,
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.renderSlot)(_ctx.$slots, "default")
  ]))
}
;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-checkbox.vue?vue&type=template&id=6b569d2a

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/vx-vue/formelements/form-checkbox.vue?vue&type=script&lang=js

  /* harmony default export */ const form_checkboxvue_type_script_lang_js = ({
    name: 'form-checkbox',
    inheritAttrs: false,
    props: ['modelValue'],
    emits: ['update:modelValue']
  });

;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-checkbox.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-checkbox.vue




;
const form_checkbox_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(form_checkboxvue_type_script_lang_js, [['render',form_checkboxvue_type_template_id_6b569d2a_render]])

/* harmony default export */ const form_checkbox = (form_checkbox_exports_);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/forms/article-form.vue?vue&type=script&lang=js










/* harmony default export */ const article_formvue_type_script_lang_js = ({
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
    initialData: { type: Object, default: () => ({}) },
    options: Object,
    editorConfig: Object
  },

  data() { return {
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
  }},

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
      this.elements[this.elements.findIndex(({model}) => model === 'articlecategoriesid')].options = newValue.categories;
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
      this.form.id = this.response.id || this.form.id;
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

;// CONCATENATED MODULE: ./vue/components/forms/article-form.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/forms/article-form.vue




;
const article_form_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(article_formvue_type_script_lang_js, [['render',article_formvue_type_template_id_2a5a57c8_render]])

/* harmony default export */ const article_form = (article_form_exports_);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/forms/page-form.vue?vue&type=template&id=69e2ec24


const page_formvue_type_template_id_69e2ec24_hoisted_1 = { class: "columns" }
const page_formvue_type_template_id_69e2ec24_hoisted_2 = { class: "column col-8" }
const page_formvue_type_template_id_69e2ec24_hoisted_3 = { class: "form-group" }
const page_formvue_type_template_id_69e2ec24_hoisted_4 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("label", {
  class: "form-label",
  for: "alias_input"
}, "Eindeutiger Name", -1)
const page_formvue_type_template_id_69e2ec24_hoisted_5 = ["value", "disabled"]
const page_formvue_type_template_id_69e2ec24_hoisted_6 = {
  key: 0,
  class: "form-input-hint vx-error-box error"
}
const page_formvue_type_template_id_69e2ec24_hoisted_7 = { class: "form-group" }
const page_formvue_type_template_id_69e2ec24_hoisted_8 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("label", {
  class: "form-label",
  for: "title_input"
}, "Titel", -1)
const page_formvue_type_template_id_69e2ec24_hoisted_9 = {
  key: 0,
  class: "form-input-hint vx-error-box error"
}
const page_formvue_type_template_id_69e2ec24_hoisted_10 = { class: "form-group" }
const page_formvue_type_template_id_69e2ec24_hoisted_11 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("label", { class: "form-label" }, "Inhalt", -1)
const page_formvue_type_template_id_69e2ec24_hoisted_12 = { class: "column col-4" }
const page_formvue_type_template_id_69e2ec24_hoisted_13 = { class: "form-group" }
const page_formvue_type_template_id_69e2ec24_hoisted_14 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("label", {
  class: "form-label",
  for: "keywords_input"
}, "Schlüsselwörter", -1)
const page_formvue_type_template_id_69e2ec24_hoisted_15 = { class: "form-group" }
const page_formvue_type_template_id_69e2ec24_hoisted_16 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("label", {
  class: "form-label",
  for: "description_input"
}, "Beschreibung", -1)
const page_formvue_type_template_id_69e2ec24_hoisted_17 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", {
  class: "divider",
  "data-content": "Revisionen"
}, null, -1)
const page_formvue_type_template_id_69e2ec24_hoisted_18 = { id: "revisionsContainer" }
const page_formvue_type_template_id_69e2ec24_hoisted_19 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", { class: "divider" }, null, -1)
const page_formvue_type_template_id_69e2ec24_hoisted_20 = { class: "form-base" }
const page_formvue_type_template_id_69e2ec24_hoisted_21 = { class: "form-group" }
const page_formvue_type_template_id_69e2ec24_hoisted_22 = ["disabled"]

function page_formvue_type_template_id_69e2ec24_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_vue_ckeditor = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.resolveComponent)("vue-ckeditor")
  const _component_revision_table = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.resolveComponent)("revision-table")

  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("form", {
    action: "/",
    onSubmit: _cache[9] || (_cache[9] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withModifiers)(() => {}, ["prevent"]))
  }, [
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", page_formvue_type_template_id_69e2ec24_hoisted_1, [
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", page_formvue_type_template_id_69e2ec24_hoisted_2, [
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", page_formvue_type_template_id_69e2ec24_hoisted_3, [
          page_formvue_type_template_id_69e2ec24_hoisted_4,
          (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("input", {
            id: "alias_input",
            value: _ctx.form.alias,
            onInput: _cache[0] || (_cache[0] = $event => (_ctx.form.alias = $event.target.value.toUpperCase())),
            class: "form-input",
            disabled: $props.mode === 'edit',
            maxlength: "64"
          }, null, 40, page_formvue_type_template_id_69e2ec24_hoisted_5),
          ($options.errors.alias)
            ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("p", page_formvue_type_template_id_69e2ec24_hoisted_6, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($options.errors.alias), 1))
            : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true)
        ]),
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", page_formvue_type_template_id_69e2ec24_hoisted_7, [
          page_formvue_type_template_id_69e2ec24_hoisted_8,
          (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withDirectives)((0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("input", {
            id: "title_input",
            "onUpdate:modelValue": _cache[1] || (_cache[1] = $event => ((_ctx.form.title) = $event)),
            class: "form-input",
            maxlength: "128"
          }, null, 512), [
            [external_commonjs_vue_commonjs2_vue_root_Vue_.vModelText, _ctx.form.title]
          ]),
          ($options.errors.title)
            ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("p", page_formvue_type_template_id_69e2ec24_hoisted_9, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($options.errors.title), 1))
            : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true)
        ]),
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", page_formvue_type_template_id_69e2ec24_hoisted_10, [
          page_formvue_type_template_id_69e2ec24_hoisted_11,
          (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createVNode)(_component_vue_ckeditor, {
            modelValue: _ctx.form.markup,
            "onUpdate:modelValue": _cache[2] || (_cache[2] = $event => ((_ctx.form.markup) = $event)),
            config: _ctx.editorConfig
          }, null, 8, ["modelValue", "config"])
        ])
      ]),
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", page_formvue_type_template_id_69e2ec24_hoisted_12, [
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", page_formvue_type_template_id_69e2ec24_hoisted_13, [
          page_formvue_type_template_id_69e2ec24_hoisted_14,
          (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withDirectives)((0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("textarea", {
            id: "keywords_input",
            class: "form-input",
            rows: "4",
            "onUpdate:modelValue": _cache[3] || (_cache[3] = $event => ((_ctx.form.keywords) = $event))
          }, null, 512), [
            [external_commonjs_vue_commonjs2_vue_root_Vue_.vModelText, _ctx.form.keywords]
          ])
        ]),
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", page_formvue_type_template_id_69e2ec24_hoisted_15, [
          page_formvue_type_template_id_69e2ec24_hoisted_16,
          (0,external_commonjs_vue_commonjs2_vue_root_Vue_.withDirectives)((0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("textarea", {
            id: "description_input",
            class: "form-input",
            rows: "4",
            "onUpdate:modelValue": _cache[4] || (_cache[4] = $event => ((_ctx.form.description) = $event))
          }, null, 512), [
            [external_commonjs_vue_commonjs2_vue_root_Vue_.vModelText, _ctx.form.description]
          ])
        ]),
        page_formvue_type_template_id_69e2ec24_hoisted_17,
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", page_formvue_type_template_id_69e2ec24_hoisted_18, [
          (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createVNode)(_component_revision_table, {
            revisions: _ctx.revisions,
            onActivateRevision: _cache[5] || (_cache[5] = $event => (_ctx.$emit('activate-revision', $event))),
            onLoadRevision: _cache[6] || (_cache[6] = $event => (_ctx.$emit('load-revision', $event))),
            onDeleteRevision: _cache[7] || (_cache[7] = $event => (_ctx.$emit('delete-revision', $event)))
          }, null, 8, ["revisions"])
        ])
      ])
    ]),
    page_formvue_type_template_id_69e2ec24_hoisted_19,
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", page_formvue_type_template_id_69e2ec24_hoisted_20, [
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("div", page_formvue_type_template_id_69e2ec24_hoisted_21, [
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("button", {
          name: "submit_page",
          type: "button",
          class: (0,external_commonjs_vue_commonjs2_vue_root_Vue_.normalizeClass)(["btn btn-success", {'loading': _ctx.loading}]),
          disabled: _ctx.loading,
          onClick: _cache[8] || (_cache[8] = (...args) => ($options.submit && $options.submit(...args)))
        }, "Änderungen übernehmen und neue Revision erzeugen", 10, page_formvue_type_template_id_69e2ec24_hoisted_22)
      ])
    ])
  ], 32))
}
;// CONCATENATED MODULE: ./vue/components/forms/page-form.vue?vue&type=template&id=69e2ec24

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/forms/revision-table.vue?vue&type=template&id=473af282


const revision_tablevue_type_template_id_473af282_hoisted_1 = {
  id: "revisions",
  class: "table table-striped"
}
const revision_tablevue_type_template_id_473af282_hoisted_2 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("thead", null, [
  /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("tr", null, [
    /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("th", null, "Angelegt um"),
    /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("th", { class: "col-2" }),
    /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("th", { class: "col-2" }, "aktiv"),
    /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("th", { class: "col-2" })
  ])
], -1)
const revision_tablevue_type_template_id_473af282_hoisted_3 = ["onClick"]
const revision_tablevue_type_template_id_473af282_hoisted_4 = { class: "form-switch" }
const revision_tablevue_type_template_id_473af282_hoisted_5 = ["checked", "disabled", "onClick"]
const revision_tablevue_type_template_id_473af282_hoisted_6 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("i", { class: "form-icon" }, null, -1)
const revision_tablevue_type_template_id_473af282_hoisted_7 = { class: "text-right" }
const revision_tablevue_type_template_id_473af282_hoisted_8 = ["onClick"]

function revision_tablevue_type_template_id_473af282_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("table", revision_tablevue_type_template_id_473af282_hoisted_1, [
    revision_tablevue_type_template_id_473af282_hoisted_2,
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("tbody", null, [
      ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(true), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_.Fragment, null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.renderList)($options.sortedRevisions, (revision) => {
        return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("tr", {
          key: revision.id
        }, [
          (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("td", null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_.toDisplayString)($options.formatDateTime(revision.firstCreated, 'y-mm-dd h:i:s')), 1),
          (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("td", null, [
            (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("button", {
              class: "btn btn-link webfont-icon-only tooltip",
              type: "button",
              "data-tooltip": "Ansicht",
              onClick: $event => (_ctx.$emit('load-revision', revision))
            }, "", 8, revision_tablevue_type_template_id_473af282_hoisted_3)
          ]),
          (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("td", null, [
            (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("label", revision_tablevue_type_template_id_473af282_hoisted_4, [
              (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("input", {
                type: "checkbox",
                checked: revision.active,
                disabled: revision.active,
                onClick: $event => (_ctx.$emit('activate-revision', revision))
              }, null, 8, revision_tablevue_type_template_id_473af282_hoisted_5),
              revision_tablevue_type_template_id_473af282_hoisted_6
            ])
          ]),
          (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementVNode)("td", revision_tablevue_type_template_id_473af282_hoisted_7, [
            (!revision.active)
              ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createElementBlock)("button", {
                  key: 0,
                  class: "btn btn-primary webfont-icon-only tooltip tooltip-left",
                  type: "button",
                  "data-tooltip": "Löschen",
                  onClick: $event => (_ctx.$emit('delete-revision', revision))
                }, "", 8, revision_tablevue_type_template_id_473af282_hoisted_8))
              : (0,external_commonjs_vue_commonjs2_vue_root_Vue_.createCommentVNode)("", true)
          ])
        ]))
      }), 128))
    ])
  ]))
}
;// CONCATENATED MODULE: ./vue/components/forms/revision-table.vue?vue&type=template&id=473af282

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/forms/revision-table.vue?vue&type=script&lang=js

    
    /* harmony default export */ const revision_tablevue_type_script_lang_js = ({
        props: {
            revisions: { type: Array, default: [] }
        },
        computed: {
            sortedRevisions() {
                return this.revisions.slice().sort((a, b) => a.firstCreated < b.firstCreated ? 1 : -1);
            }
        },
        methods: {
            formatDateTime: date_functions.formatDate
        }

    });

;// CONCATENATED MODULE: ./vue/components/forms/revision-table.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/forms/revision-table.vue




;
const revision_table_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(revision_tablevue_type_script_lang_js, [['render',revision_tablevue_type_template_id_473af282_render]])

/* harmony default export */ const revision_table = (revision_table_exports_);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vue/components/forms/page-form.vue?vue&type=script&lang=js

    
    
    

    /* harmony default export */ const page_formvue_type_script_lang_js = ({
        name: 'page-form',

        components: {
            'vue-ckeditor': vue_ckeditor,
            'revision-table': revision_table
        },
        props: {
            mode: { type: String, default: "edit" },
            url: { type: String, required: true },
            formData: { type: Object, default: () => {{}} },
            revisionsData: { type: Array, default: () => {[]}}
        },

        data: () => ({
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
        }),

        computed: {
            errors () {
                return this.response ? (this.response.errors || {}) : {};
            },
            message () {
                return this.response ? this.response.message : "";
            }
        },

        watch: {
            formData (newValue) {
              this.form = Object.assign({}, this.form, newValue);
            },
            revisionsData (newValue) {
              this.revisions = newValue.slice();
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
                this.$emit("response-received", this.response);
            }
        }
    });

;// CONCATENATED MODULE: ./vue/components/forms/page-form.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/forms/page-form.vue




;
const page_form_exports_ = /*#__PURE__*/(0,exportHelper/* default */.Z)(page_formvue_type_script_lang_js, [['render',page_formvue_type_template_id_69e2ec24_render]])

/* harmony default export */ const page_form = (page_form_exports_);
// EXTERNAL MODULE: ./node_modules/vue-slicksort/dist/vue-slicksort.umd.js
var vue_slicksort_umd = __webpack_require__(247);
;// CONCATENATED MODULE: ./vue/build/vxweb.js































const Components = {
    MessageToast: message_toast,
    CircularProgress: circular_progress,
    Autocomplete: autocomplete,
    DatePicker: datepicker,
    Sortable: sortable,
    SimpleTree: simple_tree,
    Filemanager: filemanager,
    SlickList: vue_slicksort_umd.SlickList, SlickItem: vue_slicksort_umd.SlickItem,
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
    Handle: vue_slicksort_umd.HandleDirective
};

const Util = {
    SimpleFetch: SimpleFetch,
    PromisedXhr: PromisedXhr,
    UrlQuery: url_query,
    DateFunctions: date_functions
};

const Plugins = {
    Slicksort: vue_slicksort_umd.plugin
};


;// CONCATENATED MODULE: ./node_modules/@vue/cli-service/lib/commands/build/entry-lib-no-default.js



})();

module.exports = __webpack_exports__;
/******/ })()
;
//# sourceMappingURL=vxweb.common.js.map