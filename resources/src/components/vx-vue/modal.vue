<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 z-20 backdrop-blur-sm" aria-hidden="true" v-if="show" />
  <transition name="appear">
    <div class="fixed inset-0 z-30 my-8" v-if="show">
      <div class="flex items-end justify-center items-center">
        <div class="relative transform overflow-hidden rounded bg-white shadow-xl transition-all w-3/4 max-w-full xl:max-w-screen-lg">
          <slot name="title" />
          <div class="h-[calc(100vh-4rem)] py-4">
            <div class="h-full overflow-y-auto"><slot /></div>
          </div>
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
