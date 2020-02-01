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

export { Focus, Bubble }
