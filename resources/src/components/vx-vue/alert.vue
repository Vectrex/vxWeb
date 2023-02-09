<template>
  <transition name="fade">
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50 backdrop-blur-sm" aria-hidden="true" v-if="show"></div>
  </transition>
  <transition name="appear">
    <div class="fixed z-50 inset-0 overflow-y-auto" v-if="show">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div v-if="show">
          <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

          <div class="inline-block align-bottom bg-white rounded text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full">
            <h3 :class="headerClass" v-if="title">
              <slot name="title" :title="title">{{ title }}</slot>
            </h3>
            <div class="mt-4 sm:mt-5 px-4 sm:px-6 pb-4 sm:pb-6">
              <div class="flex flex-row items-center">
                <div class="flex-shrink-0">
                  <slot name="icon"></slot>
                </div>
                <p class="text-center flex-grow">
                  <slot :message="message">{{ message }}</slot>
                </p>
              </div>
              <div class="mt-5 sm:mt-6 flex justify-center space-x-2" ref="buttons">
                <button type="button" :class="[buttonClass, button['class']]" @click.prevent="handleClick(button.value)" v-for="button in buttonArray">{{ button.label }}</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'alert',
  props: {
    buttons: {
      type: [Object, Array],
      default: { label: 'Ok', value: 'ok' },
      validator: p => (Array.isArray(p) && p.filter(v => v['label'] !== 'undefined' && v['value'] !== 'undefined').length === p.length) || (p.label !== undefined && p.value !== undefined)
    },
    headerClass: {
      type: String,
      default: "text-lg text-center font-medium text-vxvue-alt-900 pt-4 sm:py-6 py-4 bg-vxvue-alt-400"
    },
    buttonClass: {
      type: String,
      default: "button"
    }
  },

  data () { return {
    title: "",
    message: "",
    show: false,
    resolve: null,
    reject: null
  }},

  computed: {
    buttonArray () {
      return Array.isArray(this.buttons) ? this.buttons : [this.buttons];
    }
  },

  methods: {
    open (title, message) {
      this.title = title;
      this.message = message;
      this.show = true;
      this.$nextTick(() => this.$refs.buttons.firstElementChild.focus());
      return new Promise((resolve, reject) => {
        this.resolve = resolve;
        this.reject = reject;
      });
    },
    handleClick (value) {
      this.show = false;
      this.resolve(value);
    }
  }
}
</script>
