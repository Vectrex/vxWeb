<script setup xmlns="http://www.w3.org/1999/html"></script>
<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 z-20 backdrop-blur-sm" aria-hidden="true" v-if="show" />
  <transition name="appear">
    <div class="fixed inset-0 z-30 overflow-y-auto" v-if="show">
      <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded bg-white shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm sm:p-6">
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
