<script setup>
  import FormSelect from "@/components/formelements/form-select.vue";
</script>

<template>
  <div class="flex items-center space-x-2">
    <input
        :value="modelValue.title"
        @input="$emit('update:modelValue', { ...modelValue, title: $event.target.value } )"
        placeholder="Filtern nach Titel..."
        class="form-input"
    />
    <form-select
        :options="options.categories"
        :model-value="modelValue.cat"
        @update:model-value="$emit('update:modelValue', { ...modelValue, cat: parseInt($event, 10) } )"
        class="form-select"
    />
  </div>
</template>

<script>
export default {
  name: "FilterForm",
  props: {
    modelValue: Object,
    options: Object
  },
  emits: ['update:modelValue']
}
</script>
