<template>
  <div class="relative">
    <div class="absolute inset-0 flex items-center" aria-hidden="true">
      <div class="w-full border-t border-slate-300" />
    </div>
    <div class="relative flex justify-start">
      <span class="bg-white pr-3 text-base font-semibold leading-6 text-slate-900"><slot /></span>
    </div>
  </div>
</template>

<script>
export default {
  name: "divider"
}
</script>