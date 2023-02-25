<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 z-20 backdrop-blur-sm" aria-hidden="true" v-if="show" />
  <transition name="appear">
    <div class="fixed inset-0 z-30 overflow-y-auto" v-if="show">
      <div class="flex min-h-full items-end justify-center p-4 text-center items-center">
        <div class="relative transform overflow-hidden rounded bg-white shadow-xl transition-all sm:w-full sm:max-w-sm">
          <slot></slot>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  name: "modal",
  props: {
    show: Boolean
  }
}
</script>
