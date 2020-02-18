<template>
  <div>
    <ul class="tab" :class="{'tab-block': block}" v-if="items.length">
      <li
        v-for="(item, ndx) in items"
        v-bind:key="ndx"
        class="tab-item"
        :class="{ active: activeIndex === ndx }"
      >
        <z-link :badge="item.badge" :name="item.name" @click="itemOnClick(item)" :class="{ 'disabled': item.disabled }"/>
      </li>
      <li v-if="hasActionSlot" class="tab-item tab-action">
          <slot name="action"/>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
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
      activeTab: {},
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
        this.$emit('update:activeIndex', this.items.indexOf(item));
      }
    }
  }
}
</script>
